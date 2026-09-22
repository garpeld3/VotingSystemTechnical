<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CommunityConnect | Account</title>
    <style>
      :root {
        --bg: #f5f7fb;
        --panel: #ffffff;
        --panel-soft: #eef6ff;
        --primary: #2563eb;
        --primary-dark: #1e4ec7;
        --accent: #1abf7a;
        --text: #172033;
        --muted: #627089;
        --line: rgba(35, 52, 70, 0.09);
        --shadow: 0 18px 40px rgba(18, 28, 45, 0.12);
      }

      * { box-sizing: border-box; }
      body {
        margin: 0;
        font-family: Inter, Arial, sans-serif;
        background: linear-gradient(180deg, #eef5ff 0%, #f7f9fc 100%);
        color: var(--text);
      }
      a { color: inherit; text-decoration: none; }
      button, input { font: inherit; }
      .container { width: min(1120px, calc(100% - 32px)); margin: 0 auto; }
      header {
        background: rgba(255,255,255,0.72);
        border-bottom: 1px solid var(--line);
        backdrop-filter: blur(10px);
      }
      .topbar {
        display: flex; align-items: center; justify-content: space-between;
        min-height: 74px;
      }
      .brand { display: inline-flex; align-items: center; gap: 12px; }
      .brand-mark {
        width: 42px; height: 42px; border-radius: 12px;
        display: grid; place-items: center; font-weight: 800;
        color: white; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
      }
      .brand strong { display: block; }
      .brand small { color: var(--muted); display: block; }
      .nav-links { display: flex; align-items: center; gap: 22px; color: var(--muted); }
      .nav-links a.active { color: var(--primary-dark); font-weight: 700; }
      .account-shell {
        min-height: calc(100vh - 74px);
        display: grid;
        place-items: center;
        padding: 48px 0;
      }
      .account-card {
        width: min(760px, 100%);
        background: var(--panel);
        border: 1px solid var(--line);
        border-radius: 28px;
        box-shadow: var(--shadow);
        padding: 32px;
      }
      .profile-header {
        display: flex; align-items: center; gap: 20px; margin-bottom: 28px;
      }
      .avatar {
        width: 96px; height: 96px; border-radius: 50%;
        background: linear-gradient(135deg, var(--panel-soft) 0%, #dbeafe 100%);
        display: grid; place-items: center; border: 1px solid rgba(37,99,235,0.12);
        overflow: hidden;
        flex-shrink: 0;
      }
      .avatar img { width: 100%; height: 100%; object-fit: cover; }
      .avatar-fallback {
        font-size: 2rem; font-weight: 800; color: var(--primary-dark);
      }
      .eyebrow {
        margin: 0 0 8px; color: var(--primary-dark); text-transform: uppercase; letter-spacing: 0.12em; font-size: 0.76rem; font-weight: 700;
      }
      h1 { margin: 0; font-size: clamp(2rem, 3vw, 2.6rem); }
      .muted { margin-top: 8px; color: var(--muted); }
      .status {
        margin-bottom: 24px; display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 12px; border-radius: 999px; background: rgba(26, 191, 122, 0.12); color: #127a4b; font-weight: 600;
      }
      .status-dot { background: var(--accent); width: 10px; height: 10px; border-radius: 50%; }
      form { display: grid; gap: 18px; }
      .input-group { display: grid; gap: 8px; }
      .input-group label {
        font-weight: 600; color: var(--text);
      }
      .input-group input[type="text"],
      .input-group input[type="email"],
      .input-group input[type="file"] {
        width: 100%; border: 1px solid rgba(69, 81, 94, 0.17); border-radius: 14px;
        background: #f8fafc; padding: 0.9rem 1rem; color: var(--text);
      }
      .input-group input[type="file"] { padding: 0.8rem 0.9rem; }
      .actions {
        display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-top: 12px;
      }
      .btn {
        display: inline-flex; align-items: center; justify-content: center; border: none; cursor: pointer;
        border-radius: 12px; padding: 0.85rem 1.35rem; font-weight: 700;
      }
      .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); color: white; }
      .btn-ghost { background: rgba(37,99,235,0.08); color: var(--primary-dark); }
      .hidden-link { color: var(--muted); }
      @media (max-width: 680px) {
        .topbar { padding: 14px 0; flex-direction: column; align-items: flex-start; gap: 12px; }
        .nav-links { gap: 14px; flex-wrap: wrap; }
        .account-card { padding: 22px; }
        .profile-header { align-items: flex-start; }
        .actions { flex-direction: column; align-items: stretch; }
      }
    </style>
  </head>
  <body>
    <header>
      <div class="container topbar">
        <a href="{{ route('home') }}" class="brand">
          <div class="brand-mark">C</div>
          <div>
            <strong>CommunityConnect</strong>
            <small>Neighborhood network</small>
          </div>
        </a>

        <nav class="nav-links" aria-label="Account navigation">
          <a href="{{ route('home') }}">Home</a>
          <a href="{{ route('reports') }}">Reports</a>
          <a href="{{ route('account') }}" class="active">Account</a>
        </nav>
      </div>
    </header>

    <main class="account-shell">
      <section class="account-card">
        <div class="profile-header">
          <div class="avatar">
            @if (Auth::user()->avatar_url)
              <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }} avatar" />
            @else
              <span class="avatar-fallback">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
            @endif
          </div>

          <div>
            <p class="eyebrow">Profile</p>
            <h1>{{ Auth::user()->name }}</h1>
            <p class="muted">{{ Auth::user()->email }}</p>
          </div>
        </div>

        @if (session('status'))
          <div class="status"><span class="status-dot"></span>{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('account.update') }}" enctype="multipart/form-data">
          @csrf

          <div class="input-group">
            <label for="profile-name">Full name</label>
            <input id="profile-name" name="name" type="text" value="{{ old('name', Auth::user()->name) }}" required />
          </div>

          <div class="input-group">
            <label for="profile-avatar">Profile picture</label>
            <input id="profile-avatar" name="avatar" type="file" accept="image/*" />
          </div>

          <div class="actions">
            <a class="hidden-link" href="{{ route('reports') }}">Back to reports</a>
            <div style="display: flex; gap: 12px;">
              <button class="btn btn-ghost" type="submit" formaction="{{ route('logout') }}" formmethod="POST">Log out</button>
              <button class="btn btn-primary" type="submit">Save profile</button>
            </div>
          </div>
        </form>
      </section>
    </main>
  </body>
</html>
