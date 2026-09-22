<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CommunityConnect | Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
      :root {
        --bg: #f4f8ff;
        --surface: #ffffff;
        --primary: #2563eb;
        --primary-dark: #1d4ed8;
        --primary-soft: #dbeafe;
        --text: #1b2a41;
        --muted: #5f728b;
        --shadow: 0 18px 45px rgba(37, 99, 235, 0.14);
      }
      * { box-sizing: border-box; }
      body {
        margin: 0; font-family: "Inter", sans-serif; background: linear-gradient(120deg, #edf5ff 0%, #f7fbff 45%, #eef7ff 100%); color: var(--text);
      }
      a { color: inherit; text-decoration: none; }
      button, input, select { font: inherit; }
      .container { width: min(1160px, calc(100% - 32px)); margin: 0 auto; }
      .topbar {
        background: transparent; border-bottom: none; padding-top: 8px;
      }
      .nav { display: flex; align-items: center; justify-content: space-between; min-height: 72px; }
      .brand { display: inline-flex; align-items: center; gap: 12px; }
      .brand strong, .brand span { display: block; }
      .brand strong { font-size: 1rem; }
      .brand span { color: var(--muted); font-size: 0.73rem; }
      .brand-mark {
        display: grid; place-items: center; width: 42px; height: 42px; border-radius: 13px;
        background: linear-gradient(135deg, var(--primary) 0%, #34b76d 100%); color: white; font-weight: 800; box-shadow: var(--shadow);
      }
      .btn {
        display: inline-flex; align-items: center; justify-content: center; border: none; cursor: pointer; border-radius: 12px; padding: 0.82rem 1.3rem; font-weight: 600; transition: transform 0.2s ease, box-shadow 0.2s ease; text-decoration: none;
      }
      .btn:hover { transform: translateY(-1px); }
      .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, #2aa561 100%); color: white; box-shadow: 0 8px 14px rgba(31, 138, 90, 0.22); }
      .btn-ghost { background: rgba(31, 138, 90, 0.07); color: var(--primary-dark); border: 1px solid rgba(31, 138, 90, 0.18); }
      .btn-full { width: 100%; }
      .auth-shell {
        width: min(1160px, calc(100% - 32px)); margin: 0 auto; min-height: calc(100vh - 72px); display: grid; grid-template-columns: 1.1fr 0.9fr; align-items: center; gap: 28px; padding: 32px 0 52px;
      }
      .auth-content h1 { margin: 18px 0 14px; font-size: clamp(2.5rem, 4vw, 4.3rem); line-height: 1.02; letter-spacing: -0.06em; }
      .auth-content p { font-size: 1.08rem; color: var(--muted); line-height: 1.7; }
      .badge {
        display: inline-flex; align-items: center; gap: 8px; border-radius: 999px; padding: 0.5rem 0.8rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;
      }
      .badge-soft { background: var(--primary-soft); color: var(--primary-dark); }
      .auth-points { display: flex; flex-wrap: wrap; gap: 24px; margin-top: 30px; }
      .auth-points div { min-width: 130px; }
      .auth-points strong { display: block; font-size: clamp(1.5rem, 2vw, 2rem); margin-bottom: 6px; }
      .auth-points span { color: var(--muted); }
      .auth-card {
        width: min(100%, 500px); background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(35, 60, 48, 0.06); border-radius: 28px; box-shadow: var(--shadow); padding: 26px 24px 24px;
      }
      .auth-tabs { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; margin-bottom: 20px; background: #eef4f1; padding: 8px; border-radius: 14px; }
      .tab-btn {
        border: none; background: transparent; border-radius: 10px; padding: 12px 10px; font-weight: 700; color: var(--muted); cursor: pointer;
      }
      .tab-btn.active { background: white; color: var(--text); box-shadow: 0 6px 16px rgba(21, 40, 49, 0.08); }
      .auth-form { display: none; }
      .auth-form.active { display: block; }
      .input-group { margin-bottom: 18px; }
      .input-group label { display: inline-block; margin-bottom: 8px; font-size: 0.89rem; color: var(--text); font-weight: 600; }
      .input-group input, .input-group select {
        width: 100%; border: 1px solid rgba(75, 93, 98, 0.18); background: #f8fbfa; border-radius: 12px; padding: 0.9rem 1rem; color: var(--text); outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;
      }
      .input-group input:focus, .input-group select:focus { border-color: rgba(31, 138, 90, 0.45); box-shadow: 0 0 0 4px rgba(31, 138, 90, 0.08); }
      .form-row { display: flex; justify-content: space-between; gap: 12px; margin: 8px 0 20px; color: var(--muted); }
      .checkbox-row { display: inline-flex; align-items: center; gap: 8px; }
      .two-col-inputs { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
      @media (max-width: 980px) { .auth-shell { grid-template-columns: 1fr; } }
      @media (max-width: 760px) { .two-col-inputs { grid-template-columns: 1fr; } }
    </style>
  </head>
  <body>
    <header class="topbar">
      <div class="container nav">
        <a href="{{ route('home') }}" class="brand">
          <div class="brand-mark">C</div>
          <div>
            <strong>CommunityConnect</strong>
            <span>Neighborhood network</span>
          </div>
        </a>
        <a href="{{ route('home') }}" class="btn btn-ghost">Back home</a>
      </div>
    </header>

    <main class="auth-shell">
      <section class="auth-content">
        <span class="badge badge-soft">Join the movement</span>
        <h1>Stay connected to what matters in your neighborhood.</h1>
        <p>Track local issues, sign up for updates, and work alongside residents and city teams.</p>
        <div class="auth-points">
          <div><strong>Open</strong><span>Local updates</span></div>
          <div><strong>Clear</strong><span>Issue tracking</span></div>
          <div><strong>Connected</strong><span>Community care</span></div>
        </div>
      </section>

      <section class="auth-card-wrap">
        <div class="auth-card">
          <div class="auth-tabs" role="tablist" aria-label="Authentication tabs">
            <button class="tab-btn active" type="button" data-tab="login" aria-selected="true">Log in</button>
            <button class="tab-btn" type="button" data-tab="signup" aria-selected="false">Sign up</button>
          </div>

          <form class="auth-form active" data-form="login" method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="input-group">
              <label for="login-email">Email address</label>
              <input id="login-email" name="email" type="email" placeholder="you@example.com" value="{{ old('email') }}" required />
            </div>
            <div class="input-group">
              <label for="login-password">Password</label>
              <input id="login-password" name="password" type="password" placeholder="••••••••" required />
            </div>
            <div class="form-row">
              <label class="checkbox-row"><input type="checkbox" /><span>Remember me</span></label>
              <a href="#">Forgot password?</a>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Log in</button>
          </form>

          <form class="auth-form" data-form="signup" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="two-col-inputs">
              <div class="input-group">
                <label for="signup-first">First name</label>
                <input id="signup-first" name="first_name" type="text" placeholder="Alex" />
              </div>
              <div class="input-group">
                <label for="signup-last">Last name</label>
                <input id="signup-last" name="last_name" type="text" placeholder="Nguyen" />
              </div>
            </div>
            <div class="input-group">
              <label for="signup-email">Email address</label>
              <input id="signup-email" name="email" type="email" placeholder="you@example.com" required />
            </div>
            <div class="input-group">
              <label for="signup-password">Password</label>
              <input id="signup-password" name="password" type="password" placeholder="Create a password" required />
            </div>
            <div class="input-group">
              <label for="signup-password_confirmation">Confirm password</label>
              <input id="signup-password_confirmation" name="password_confirmation" type="password" placeholder="Confirm password" required />
            </div>
            <div class="input-group">
              <label for="signup-area">Neighborhood</label>
              <select id="signup-area" name="neighborhood">
                <option>Downtown</option>
                <option>Northside</option>
                <option>Riverside</option>
                <option>Oak Park</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Create account</button>
          </form>
        </div>
      </section>
    </main>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.tab-btn');
        const forms = document.querySelectorAll('.auth-form');

        tabs.forEach((tab) => {
          tab.addEventListener('click', () => {
            const target = tab.dataset.tab;
            tabs.forEach((btn) => {
              const isActive = btn === tab;
              btn.classList.toggle('active', isActive);
              btn.setAttribute('aria-selected', String(isActive));
            });
            forms.forEach((form) => {
              form.classList.toggle('active', form.dataset.form === target);
            });
          });
        });
      });
    </script>
  </body>
</html>
