<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CommunityConnect | Admin</title>
    <style>
      :root {
        --bg: #09131f;
        --bg-soft: #101d2f;
        --panel: #132235;
        --panel-alt: #0d1828;
        --panel-soft: #1a2a3d;
        --primary: #7cc7ff;
        --primary-strong: #4da3ff;
        --danger: #ff6d77;
        --success: #53d59f;
        --warning: #ffcd5c;
        --text: #ebf5ff;
        --muted: #9bb2ca;
        --line: rgba(155, 178, 202, 0.16);
        --shadow: 0 26px 60px rgba(4, 9, 15, 0.5);
      }
      * { box-sizing: border-box; }
      body {
        margin: 0; font-family: Inter, Arial, sans-serif; background: radial-gradient(circle at top, rgba(77,163,255,0.18), transparent 30%), linear-gradient(180deg, var(--bg) 0%, #0d1726 100%); color: var(--text);
      }
      a { color: inherit; text-decoration: none; }
      button, textarea { font: inherit; }
      .container { width: min(1200px, calc(100% - 28px)); margin: 0 auto; }
      header { background: rgba(9,19,31,0.88); border-bottom: 1px solid var(--line); backdrop-filter: blur(10px); }
      .topbar {
        min-height: 76px; display: flex; align-items: center; justify-content: space-between; gap: 18px;
      }
      .brand { display: inline-flex; align-items: center; gap: 12px; }
      .brand-mark {
        width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center; font-weight: 800; color: #09131f;
        background: linear-gradient(135deg, var(--primary) 0%, var(--success) 100%);
      }
      .brand small { color: var(--muted); display: block; }
      .topbar-actions { display: flex; align-items: center; gap: 12px; }
      .status-pill {
        display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px; background: rgba(83, 213, 159, 0.12); color: var(--success); font-weight: 700; margin-bottom: 18px;
      }
      .dot { width: 8px; height: 8px; background: var(--success); border-radius: 50%; }
      .page-shell { padding: 32px 0 60px; }
      h1 { margin: 0 0 10px; font-size: clamp(2.3rem, 4vw, 3.2rem); }
      .subtitle { color: var(--muted); font-size: 1.02rem; }
      .admin-grid { display: grid; gap: 18px; margin-top: 30px; }
      .report-card {
        background: linear-gradient(180deg, var(--panel) 0%, var(--panel-alt) 100%); border: 1px solid var(--line); border-radius: 22px; box-shadow: var(--shadow); padding: 22px;
      }
      .report-head {
        display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 18px;
      }
      .report-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
      .pill {
        padding: 7px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase;
      }
      .pill-open { background: rgba(29,78,216,0.1); color: var(--primary); }
      .pill-resolved { background: rgba(28,140,92,0.12); color: var(--success); }
      .pill-low { background: rgba(28,140,92,0.12); color: var(--success); }
      .pill-medium { background: rgba(250,204,21,0.18); color: #7a5600; }
      .pill-high { background: rgba(217,45,32,0.14); color: var(--danger); }
      .report-body {
        display: grid; grid-template-columns: 260px 1fr; gap: 22px;
      }
      .report-image {
        width: 100%; min-height: 180px; border-radius: 18px; object-fit: cover; background: #ebefff; border: 1px solid rgba(22, 41, 75, 0.08);
      }
      .report-content h2 {
        margin: 0 0 8px; font-size: 1.7rem;
      }
      .report-content p {
        color: var(--muted); line-height: 1.7; margin: 0 0 18px;
      }
      .report-stats { display: flex; flex-wrap: wrap; gap: 14px; color: var(--muted); font-weight: 600; }
      .actions-row {
        display: flex; flex-wrap: wrap; gap: 12px; margin: 18px 0 12px;
      }
      .btn {
        border: none; border-radius: 12px; padding: 0.8rem 1.1rem; font-weight: 700; cursor: pointer;
      }
      .btn-success { background: rgba(83,213,159,0.14); color: var(--success); }
      .btn-danger { background: rgba(255,109,119,0.12); color: var(--danger); }
      .comment-box {
        width: 100%; min-height: 86px; border-radius: 12px; border: 1px solid var(--line); background: #f9fafc; padding: 0.9rem 1rem; resize: vertical;
      }
      .comment-list {
        margin-top: 16px; display: grid; gap: 12px;
      }
      .comment-item {
        background: #f9fbff; border: 1px solid var(--line); border-radius: 12px; padding: 12px 14px;
      }
      .comment-item strong { display: block; margin-bottom: 4px; }
      .comment-item p { margin: 0; color: var(--muted); }
      .empty-state {
        border: 1px dashed rgba(27,35,51,0.18); border-radius: 18px; padding: 26px; text-align: center; color: var(--muted); background: rgba(255,255,255,0.55);
      }
      @media (max-width: 840px) {
        .report-body { grid-template-columns: 1fr; }
        .report-head { align-items:flex-start; flex-direction:column; }
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
            <small>Private admin console</small>
          </div>
        </a>

        <div class="topbar-actions">
          <span class="pill pill-open">Admin</span>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger" type="submit" style="background: rgba(217,45,32,0.12); color: var(--danger);">Log out</button>
          </form>
        </div>
      </div>
    </header>

    <main class="container page-shell">
      @if (session('status'))
        <div class="status-pill"><span class="dot"></span>{{ session('status') }}</div>
      @endif

      <h1>Private moderation dashboard</h1>
      <p class="subtitle">Review submitted public reports, resolve issues, and add notes for residents.</p>

      <div style="display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-top: 28px; margin-bottom: 8px;">
        <div class="report-card" style="padding: 18px 20px;">
          <div style="color: var(--muted); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700;">Total reports</div>
          <div style="font-size: 2rem; font-weight: 800; margin-top: 8px;">{{ $reports->count() }}</div>
        </div>
        <div class="report-card" style="padding: 18px 20px;">
          <div style="color: var(--muted); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700;">Open</div>
          <div style="font-size: 2rem; font-weight: 800; margin-top: 8px; color: var(--primary);">{{ $openCount }}</div>
        </div>
        <div class="report-card" style="padding: 18px 20px;">
          <div style="color: var(--muted); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700;">Resolved</div>
          <div style="font-size: 2rem; font-weight: 800; margin-top: 8px; color: var(--success);">{{ $resolvedCount }}</div>
        </div>
      </div>

      @if ($reports->isEmpty())
        <div class="empty-state" style="margin-top: 22px;">
          No public reports have been submitted yet.
        </div>
      @else
        <div class="admin-grid">
          @foreach ($reports as $report)
            <article class="report-card">
              <div class="report-head">
                <div class="report-meta">
                  <span class="pill {{ $report->priority === 'high' ? 'pill-high' : ($report->priority === 'medium' ? 'pill-medium' : 'pill-low') }}">{{ ucfirst($report->priority) }}</span>
                  <span class="pill {{ $report->status === 'resolved' ? 'pill-resolved' : 'pill-open' }}">{{ $report->status === 'resolved' ? 'Resolved' : 'Open' }}</span>
                  <span style="color: var(--muted); font-weight: 600;">{{ $report->type }}</span>
                </div>
                <div style="color: var(--muted); font-weight: 600;">{{ $report->created_at->format('M d, Y') }}</div>
              </div>

              <div class="report-body">
                <div>
                  @if ($report->image_url)
                    <img src="{{ $report->image_url }}" alt="{{ $report->title }}" class="report-image" />
                  @else
                    <div class="report-image" style="display:flex;align-items:center;justify-content:center;color:var(--muted);font-weight:700;">No image</div>
                  @endif
                </div>

                <div class="report-content">
                  <h2>{{ $report->title }}</h2>
                  <p>{{ $report->description }}</p>

                  <div class="report-stats">
                    <span>Location: {{ $report->location }}</span>
                    <span>Status: {{ ucfirst(str_replace('_', ' ', $report->status)) }}</span>
                  </div>

                  <div class="actions-row">
                    <form method="POST" action="{{ route('admin.reports.resolve', $report) }}">
                      @csrf
                      <button class="btn btn-success" type="submit">{{ $report->status === 'resolved' ? 'Already resolved' : 'Mark resolved' }}</button>
                    </form>

                    <form method="POST" action="{{ route('admin.reports.delete', $report) }}">
                      @csrf
                      <button class="btn btn-danger" type="submit">Remove report</button>
                    </form>
                  </div>

                  <div class="comment-list">
                    @forelse ($report->comments as $comment)
                      <div class="comment-item">
                        <strong>{{ $comment->user?->name ?? 'Moderator' }}</strong>
                        <p>{{ $comment->body }}</p>
                      </div>
                    @empty
                      <div class="comment-item">
                        <p>No comments yet.</p>
                      </div>
                    @endforelse
                  </div>

                  <div style="margin-top: 16px;">
                    <form method="POST" action="{{ route('admin.reports.comments', $report) }}">
                      @csrf
                      <textarea class="comment-box" name="comment" placeholder="Add an internal or public-facing update..." required></textarea>
                      <div class="actions-row" style="margin-bottom: 0;">
                        <button class="btn btn-success" type="submit" style="margin-top: 12px;">Add comment</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      @endif
    </main>
  </body>
</html>
