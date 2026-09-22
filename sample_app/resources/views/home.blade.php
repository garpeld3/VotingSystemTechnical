<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CommunityConnect | Civic Engagement Platform</title>
    <meta name="description" content="CommunityConnect helps neighborhoods report issues, share updates, and improve local services together." />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
      :root {
        --bg: #f4f8ff;
        --surface: #ffffff;
        --surface-alt: #edf4ff;
        --surface-strong: #163864;
        --primary: #2563eb;
        --primary-dark: #1d4ed8;
        --primary-soft: #dbeafe;
        --warning: #f5b942;
        --danger: #e45d5d;
        --text: #1b2a41;
        --muted: #5f728b;
        --shadow: 0 18px 45px rgba(37, 99, 235, 0.14);
      }
      * { box-sizing: border-box; }
      html { scroll-behavior: smooth; }
      body {
        margin: 0;
        font-family: "Inter", sans-serif;
        background: linear-gradient(180deg, #f7fbff 0%, #edf4ff 100%);
        color: var(--text);
      }
      a { color: inherit; text-decoration: none; }
      .container { width: min(1180px, calc(100% - 32px)); margin: 0 auto; }
      .topbar {
        position: sticky; top: 0; z-index: 50;
        background: rgba(244, 248, 245, 0.8); backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(19, 42, 33, 0.06);
      }
      .nav {
        display: flex; align-items: center; justify-content: space-between; gap: 16px; min-height: 78px;
      }
      .brand { display: inline-flex; align-items: center; gap: 12px; }
      .brand strong, .brand span { display: block; }
      .brand strong { font-size: 1rem; }
      .brand span { color: var(--muted); font-size: 0.73rem; }
      .brand-mark {
        display: grid; place-items: center; width: 42px; height: 42px; border-radius: 13px;
        background: linear-gradient(135deg, var(--primary) 0%, #34b76d 100%); color: white; font-weight: 800; box-shadow: var(--shadow);
      }
      .nav-links { display: flex; align-items: center; gap: 28px; color: var(--muted); font-weight: 500; }
      .nav-links a { position: relative; transition: color 0.2s ease; }
      .nav-links a:hover, .nav-links a.active { color: var(--text); }
      .nav-links a.active::after {
        content: ""; position: absolute; left: 0; bottom: -8px; width: 100%; height: 2px; border-radius: 999px; background: var(--primary);
      }
      .nav-actions { display: flex; align-items: center; gap: 12px; }
      .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; border: none; border-radius: 12px; padding: 0.82rem 1.3rem; font-weight: 600; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; text-decoration: none;
      }
      .btn:hover { transform: translateY(-1px); }
      .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, #2aa561 100%); color: white; box-shadow: 0 8px 14px rgba(31, 138, 90, 0.22); }
      .btn-secondary, .btn-ghost { background: rgba(31, 138, 90, 0.07); color: var(--primary-dark); border: 1px solid rgba(31, 138, 90, 0.18); }
      .hero { padding: 72px 0 56px; }
      .hero-grid { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 48px; align-items: center; }
      .badge {
        display: inline-flex; align-items: center; gap: 8px; border-radius: 999px; padding: 0.5rem 0.8rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;
      }
      .badge-soft { background: var(--primary-soft); color: var(--primary-dark); }
      .hero-copy h1 {
        font-size: clamp(2.8rem, 5vw, 5rem); line-height: 1.02; letter-spacing: -0.06em; margin: 18px 0 18px; max-width: 620px;
      }
      .hero-copy p { max-width: 560px; font-size: 1.08rem; line-height: 1.7; color: var(--muted); margin: 0 0 28px; }
      .hero-actions { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
      .mini-stats { margin-top: 28px; display: grid; grid-template-columns: repeat(3, minmax(120px, 1fr)); gap: 18px; }
      .mini-stats div { padding: 14px 10px; border-radius: 16px; background: rgba(255, 255, 255, 0.5); border: 1px solid rgba(111, 137, 124, 0.12); }
      .mini-stats strong { display: block; font-size: 1.2rem; margin-bottom: 4px; }
      .mini-stats span { color: var(--muted); font-size: 0.82rem; }
      .hero-visual { display: flex; justify-content: center; }
      .dashboard-card {
        width: min(520px, 100%); background: rgba(255, 255, 255, 0.9); border: 1px solid rgba(89, 116, 101, 0.12); border-radius: 28px; padding: 24px; box-shadow: var(--shadow);
      }
      .card-top { display: flex; align-items: center; justify-content: space-between; color: var(--muted); font-size: 0.8rem; margin-bottom: 18px; }
      .status-dot { color: var(--primary); font-weight: 700; }
      .map-box {
        position: relative; height: 250px; border-radius: 22px; overflow: hidden;
        background: linear-gradient(135deg, rgba(34, 181, 114, 0.15), rgba(67, 126, 182, 0.08)), linear-gradient(120deg, rgba(255, 255, 255, 0.7), rgba(109, 158, 152, 0.14)); border: 1px solid rgba(74, 117, 100, 0.12);
      }
      .map-box::before, .map-box::after { content: ""; position: absolute; inset: 10% 14%; border: 1px solid rgba(26, 61, 45, 0.1); border-radius: 28px; }
      .map-box::after { inset: 22% 22% 12% 16%; border-style: dashed; }
      .map-pin { position: absolute; width: 18px; height: 18px; border-radius: 50%; background: linear-gradient(135deg, var(--danger) 0%, #f5a45f 100%); box-shadow: 0 10px 20px rgba(228, 93, 93, 0.35); }
      .map-pin::after { content: ""; position: absolute; inset: 5px; border-radius: 50%; background: rgba(255, 255, 255, 0.7); }
      .pin-one { top: 28%; left: 22%; } .pin-two { top: 46%; right: 26%; } .pin-three { bottom: 28%; left: 42%; } .pin-four { top: 30%; right: 42%; }
      .issue-list { display: grid; gap: 12px; margin-top: 18px; }
      .issue-item { display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 14px; padding: 12px 14px; border-radius: 14px; background: #f8faf8; border: 1px solid rgba(93, 118, 109, 0.08); }
      .issue-icon { display: grid; place-items: center; width: 30px; height: 30px; border-radius: 10px; font-weight: 800; }
      .issue-icon.warning { background: rgba(245, 185, 66, 0.18); color: #a16600; }
      .issue-icon.success { background: rgba(31, 138, 90, 0.12); color: var(--primary-dark); }
      .issue-item strong, .issue-item small { display: block; }
      .issue-item small { color: var(--muted); }
      .trend { font-size: 0.78rem; color: var(--muted); font-weight: 700; }
      .trend.up { color: var(--primary-dark); }
      .section { padding: 72px 0; }
      .section-heading { margin-bottom: 32px; }
      .center { text-align: center; }
      .eyebrow { display: inline-block; font-size: 0.74rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--primary-dark); margin-bottom: 14px; }
      .section-heading h2, .section-copy h2 { margin: 0 0 18px; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -0.05em; line-height: 1.1; }
      .feature-grid, .community-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 22px; }
      .feature-card, .community-card, .stat-card {
        background: rgba(255, 255, 255, 0.88); border: 1px solid rgba(96, 112, 126, 0.08); border-radius: 24px; padding: 26px 20px; box-shadow: 0 10px 32px rgba(33, 47, 51, 0.04);
      }
      .feature-icon { display: grid; place-items: center; width: 50px; height: 50px; border-radius: 16px; background: linear-gradient(135deg, var(--primary-soft) 0%, rgba(120, 211, 170, 0.18) 100%); font-size: 1.5rem; margin-bottom: 18px; }
      .feature-card h3, .community-card h3 { margin: 0 0 12px; font-size: 1.35rem; }
      .feature-card p, .community-card p, .section-copy p { color: var(--muted); line-height: 1.7; }
      .impact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 26px; align-items: center; }
      .check-list { list-style: none; padding: 0; margin: 24px 0 0; display: grid; gap: 12px; }
      .check-list li { display: flex; align-items: center; gap: 10px; color: var(--text); font-weight: 500; }
      .check-list li::before {
        content: "✓"; display: inline-grid; place-items: center; width: 22px; height: 22px; border-radius: 50%; background: var(--primary-soft); color: var(--primary-dark); font-size: 0.85rem; font-weight: 800;
      }
      .impact-cards { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
      .stat-card { display: flex; flex-direction: column; justify-content: center; min-height: 170px; }
      .stat-card.accent { grid-column: span 2; background: linear-gradient(135deg, #17392d, #1f8a5a 100%); color: white; }
      .stat-card .label { display: block; color: inherit; opacity: 0.8; margin-bottom: 18px; }
      .stat-card strong { font-size: clamp(1.8rem, 4vw, 2.7rem); letter-spacing: -0.05em; margin-bottom: 8px; }
      .stat-card small { opacity: 0.8; }
      .tag { display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; font-size: 0.7rem; font-weight: 700; padding: 0.42rem 0.7rem; }
      .tag.success { background: rgba(31, 138, 90, 0.12); color: var(--primary-dark); }
      .tag.warning { background: rgba(245, 185, 66, 0.14); color: #9d6900; }
      .tag.info { background: rgba(48, 120, 173, 0.11); color: #1d5a87; }
      .site-footer { padding: 28px 0 36px; border-top: 1px solid rgba(19, 42, 33, 0.06); background: rgba(255, 255, 255, 0.44); }
      .footer-inner { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
      .footer-inner p { margin: 10px 0 0; color: var(--muted); }
      .footer-links { display: flex; align-items: center; gap: 24px; color: var(--muted); }
      @media (max-width: 980px) { .hero-grid, .impact-grid { grid-template-columns: 1fr; } }
      @media (max-width: 760px) {
        .nav { flex-wrap: wrap; justify-content: center; padding: 12px 0 16px; }
        .nav-links { order: 3; width: 100%; justify-content: center; gap: 16px; flex-wrap: wrap; }
        .feature-grid, .community-grid, .impact-cards, .mini-stats { grid-template-columns: 1fr; }
      }
    </style>
  </head>
  <body>
    <header class="topbar">
      <div class="container nav">
        <div class="brand">
          <div class="brand-mark">C</div>
          <div>
            <strong>CommunityConnect</strong>
            <span>Neighborhood network</span>
          </div>
        </div>
        <nav class="nav-links" aria-label="Main navigation">
          <a href="{{ route('home') }}" class="active">Home</a>
          <a href="#features">Features</a>
          <a href="#impact">Impact</a>
          <a href="#community">Community</a>
          <a href="{{ route('reports') }}">Reports</a>
        </nav>
        <div class="nav-actions">
          <a href="{{ route('login') }}" class="btn btn-ghost">Log in</a>
          <a href="{{ route('login') }}" class="btn btn-primary">Sign up</a>
        </div>
      </div>
    </header>

    <main>
      <section class="hero">
        <div class="container hero-grid">
          <div class="hero-copy">
            <span class="badge badge-soft">Civic action made simple</span>
            <h1>Build safer, stronger neighborhoods together.</h1>
            <p>CommunityConnect helps residents report issues, track progress, and organize local solutions in one shared digital space.</p>
            <div class="hero-actions">
              <a href="{{ route('reports') }}" class="btn btn-primary">Report an issue</a>
              <a href="{{ route('login') }}" class="btn btn-secondary">Join your community</a>
            </div>
            <div class="mini-stats">
              <div><strong>Local</strong><span>Issue reporting</span></div>
              <div><strong>Faster</strong><span>Updates</span></div>
              <div><strong>Shared</strong><span>Community care</span></div>
            </div>
          </div>

          <div class="hero-visual">
            <img src="{{ asset('images/communityconnect-hero.svg') }}" alt="CommunityConnect neighborhood activity" style="max-width: 100%; width: 620px; height: auto; border-radius: 28px; box-shadow: 0 24px 60px rgba(17, 40, 31, 0.12);" />
          </div>
        </div>
      </section>

      <section class="feature-band" id="features">
        <div class="container">
          <div class="section-heading">
            <span class="eyebrow">Why neighbors use it</span>
            <h2>Everything your community needs to respond faster.</h2>
          </div>
          <div class="feature-grid">
            <article class="feature-card">
              <div class="feature-icon">📍</div>
              <h3>Smart issue reporting</h3>
              <p>Pin problems to exact locations, add details, and attach photos for faster action.</p>
            </article>
            <article class="feature-card">
              <div class="feature-icon">🗺️</div>
              <h3>Neighborhood visibility</h3>
              <p>View local hot spots, trends, and open requests across your area.</p>
            </article>
            <article class="feature-card">
              <div class="feature-icon">🤝</div>
              <h3>Community coordination</h3>
              <p>Organize volunteers, update residents, and keep everyone in the loop.</p>
            </article>
          </div>
        </div>
      </section>

      <section class="impact section" id="impact">
        <div class="container impact-grid">
          <div class="section-copy">
            <span class="eyebrow">Built for action</span>
            <h2>Turn local concerns into visible change.</h2>
            <p>Whether it is a blocked drain, broken lighting, or maintenance request, residents can document issues transparently and help city teams prioritize work.</p>
            <ul class="check-list">
              <li>Track real-time status updates</li>
              <li>Collaborate with local groups and agencies</li>
              <li>Prioritize underserved areas</li>
            </ul>
          </div>
          <div class="impact-cards">
            <div class="stat-card accent">
              <span class="label">Tracking</span>
              <strong>Active</strong>
              <small>Community requests</small>
            </div>
            <div class="stat-card">
              <span class="label">Response</span>
              <strong>Visible</strong>
              <small>Progress updates</small>
            </div>
            <div class="stat-card">
              <span class="label">Support</span>
              <strong>Shared</strong>
              <small>Local action</small>
            </div>
          </div>
        </div>
      </section>

      <section class="community section" id="community">
        <div class="container">
          <div class="section-heading center">
            <span class="eyebrow">Community snapshots</span>
            <h2>See what’s happening nearby.</h2>
          </div>
          <div class="community-grid">
            <div class="community-card">
              <div class="tag success">Healthy</div>
              <h3>Northside</h3>
              <p>Street lighting and park beautification are trending upward this month.</p>
            </div>
            <div class="community-card">
              <div class="tag warning">Watchlist</div>
              <h3>Riverside</h3>
              <p>Flood drainage and pedestrian safety follow-ups remain a priority.</p>
            </div>
            <div class="community-card">
              <div class="tag info">High activity</div>
              <h3>Oak Park</h3>
              <p>Residents are actively reporting graffiti and abandoned bins.</p>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="site-footer">
      <div class="container footer-inner">
        <div>
          <strong>CommunityConnect</strong>
          <p>Shared local care, stronger communities.</p>
        </div>
        <div class="footer-links">
          <a href="{{ route('reports') }}">Reports</a>
          <a href="{{ route('login') }}">Account</a>
          <a href="#features">Features</a>
        </div>
      </div>
    </footer>
  </body>
</html>
