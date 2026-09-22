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
            <p>
              CommunityConnect helps residents report issues, track progress,
              and organize local solutions in one shared digital space.
            </p>

            <div class="hero-actions">
              <a href="{{ route('reports') }}" class="btn btn-primary">Report an issue</a>
              <a href="{{ route('login') }}" class="btn btn-secondary">Join your community</a>
            </div>

            <div class="mini-stats">
              <div>
                <strong>12.4k</strong>
                <span>Reports created</span>
              </div>
              <div>
                <strong>94%</strong>
                <span>Resolution rate</span>
              </div>
              <div>
                <strong>62</strong>
                <span>Neighborhoods active</span>
              </div>
            </div>
          </div>

          <div class="hero-visual">
            <img src="{{ asset('images/communityconnect-hero.svg') }}" alt="CommunityConnect neighborhood activity" style="max-width: 100%; width: 620px; height: auto; border-radius: 28px; box-shadow: 0 24px 60px rgba(17, 40, 31, 0.12);" />

            <div class="dashboard-card" style="display:none;">
              <div class="card-top">
                <span>Live community pulse</span>
                <span class="status-dot">● Active</span>
              </div>

              <div class="map-box">
                <div class="map-pin pin-one"></div>
                <div class="map-pin pin-two"></div>
                <div class="map-pin pin-three"></div>
                <div class="map-pin pin-four"></div>
              </div>

              <div class="issue-list">
                <div class="issue-item">
                  <div class="issue-icon warning">!</div>
                  <div>
                    <strong>Pothole cluster</strong>
                    <small>8 reports • 3 days</small>
                  </div>
                  <span class="trend up">+18%</span>
                </div>
                <div class="issue-item">
                  <div class="issue-icon success">✓</div>
                  <div>
                    <strong>Street light repaired</strong>
                    <small>Completed by city team</small>
                  </div>
                  <span class="trend">Solved</span>
                </div>
              </div>
            </div>
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
              <p>
                Pin problems to exact locations, add details, and attach photos
                for faster action.
              </p>
            </article>
            <article class="feature-card">
              <div class="feature-icon">🗺️</div>
              <h3>Neighborhood visibility</h3>
              <p>
                View local hot spots, trends, and open requests across your area.
              </p>
            </article>
            <article class="feature-card">
              <div class="feature-icon">🤝</div>
              <h3>Community coordination</h3>
              <p>
                Organize volunteers, update residents, and keep everyone in the loop.
              </p>
            </article>
          </div>
        </div>
      </section>

      <section class="impact section" id="impact">
        <div class="container impact-grid">
          <div class="section-copy">
            <span class="eyebrow">Built for action</span>
            <h2>Turn local concerns into visible change.</h2>
            <p>
              Whether it is a blocked drain, broken lighting, or maintenance request,
              residents can document issues transparently and help city teams prioritize work.
            </p>
            <ul class="check-list">
              <li>Track real-time status updates</li>
              <li>Collaborate with local groups and agencies</li>
              <li>Prioritize underserved areas</li>
            </ul>
          </div>

          <div class="impact-cards">
            <div class="stat-card accent">
              <span class="label">Open reports</span>
              <strong>421</strong>
              <small>Across 14 districts</small>
            </div>
            <div class="stat-card">
              <span class="label">Average response</span>
              <strong>2.4 days</strong>
              <small>Down from 6.1 days</small>
            </div>
            <div class="stat-card">
              <span class="label">Volunteer work</span>
              <strong>1,280 hrs</strong>
              <small>Last 30 days</small>
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
