<?php

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin');
        }

        return redirect()->intended(route('reports'));
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->name('login.submit');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['nullable', 'string', 'max:255'],
        'first_name' => ['nullable', 'string', 'max:255'],
        'last_name' => ['nullable', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'neighborhood' => ['nullable', 'string', 'max:255'],
    ]);

    $name = trim((string) ($validated['name'] ?? ''));
    if ($name === '') {
        $name = trim(((string) ($validated['first_name'] ?? '')) . ' ' . ((string) ($validated['last_name'] ?? '')));
    }

    User::create([
        'name' => $name,
        'email' => strtolower($validated['email']),
        'password' => Hash::make($validated['password']),
    ]);

    return redirect()->route('login')->with('status', 'Account created successfully.');
})->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/account', function () {
        return view('account');
    })->name('account');

    Route::post('/account', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ]);

        $user = $request->user();
        $user->name = $validated['name'];

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $user->avatar_path = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return redirect()->route('account')->with('status', 'Profile updated successfully.');
    })->name('account.update');

    Route::get('/admin', function () {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'This area is restricted to the site administrator.');
        }

        $reports = Report::with('comments.user')->orderByDesc('created_at')->get();
        $openCount = $reports->where('status', 'open')->count();
        $resolvedCount = $reports->where('status', 'resolved')->count();

        return view('admin', [
            'reports' => $reports,
            'openCount' => $openCount,
            'resolvedCount' => $resolvedCount,
        ]);
    })->name('admin');

    Route::post('/admin/reports/{report}/resolve', function (Report $report) {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'This area is restricted to the site administrator.');
        }

        $report->status = 'resolved';
        $report->save();

        return redirect()->route('admin')->with('status', 'Report marked as resolved.');
    })->name('admin.reports.resolve');

    Route::post('/admin/reports/{report}/delete', function (Report $report) {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'This area is restricted to the site administrator.');
        }

        if ($report->image_path && Storage::disk('public')->exists($report->image_path)) {
            Storage::disk('public')->delete($report->image_path);
        }

        $report->delete();

        return redirect()->route('admin')->with('status', 'Report removed successfully.');
    })->name('admin.reports.delete');

    Route::post('/admin/reports/{report}/comments', function (Request $request, Report $report) {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'This area is restricted to the site administrator.');
        }

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $report->comments()->create([
            'user_id' => Auth::id(),
            'body' => $validated['comment'],
        ]);

        return redirect()->route('admin')->with('status', 'Comment added to the report.');
    })->name('admin.reports.comments');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});

Route::middleware('auth')->post('/reports/{report}/support', function (Report $report) {
    $report->increment('support_count');

    return redirect()->route('reports')->with('status', 'Support added to this report.');
})->name('reports.support');

Route::get('/reports', function () {
    $reports = Report::with('comments.user')->orderByDesc('created_at')->get();
    return view('reports', ['reports' => $reports]);
})->name('reports');

Route::post('/reports', function (Request $request) {
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string'],
        'type' => ['required', 'string', 'max:100'],
        'location' => ['required', 'string', 'max:255'],
        'latitude' => ['nullable', 'numeric', 'between:-90,90'],
        'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        'priority' => ['required', 'string', 'in:low,medium,high'],
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
    ]);

    $toneMap = [
        'low' => 'success',
        'medium' => 'warning',
        'high' => 'danger',
    ];

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('reports', 'public');
    }

    $report = Report::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'type' => $validated['type'],
        'location' => $validated['location'],
        'latitude' => $validated['latitude'] ?? null,
        'longitude' => $validated['longitude'] ?? null,
        'priority' => $validated['priority'],
        'status' => 'open',
        'support_count' => 0,
        'image_path' => $imagePath,
        'meta' => [
            'residents' => 1,
            'tone' => $toneMap[$validated['priority']],
        ],
    ]);

    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Report submitted successfully.',
            'report' => [
                'id' => $report->id,
                'title' => $report->title,
                'description' => $report->description,
                'type' => $report->type,
                'location' => $report->location,
                'status' => $report->status,
                'priority' => $report->priority,
                'tone' => $report->meta['tone'] ?? 'info',
                'residents' => $report->meta['residents'] ?? 1,
                'support_count' => (int) ($report->support_count ?? 0),
                'image_path' => $report->image_url,
                'created_at' => $report->created_at->diffForHumans(),
            ],
        ], 201);
    }

    return redirect()->route('reports')->with('status', 'Report submitted successfully.');
})->name('reports.store');
