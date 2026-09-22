<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>CommunityConnect | Reports</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
      :root {
        --bg: #f4f8ff;
        --surface: #ffffff;
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
      body { margin: 0; font-family: "Inter", sans-serif; background: linear-gradient(180deg, #f3f8ff 0%, #f5f7fb 100%); color: var(--text); }
      a { color: inherit; text-decoration: none; }
      button, input, select, textarea { font: inherit; }
      .container { width: min(1180px, calc(100% - 32px)); margin: 0 auto; }
      .topbar { background: rgba(244, 248, 245, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(19, 42, 33, 0.06); }
      .nav { display: flex; align-items: center; justify-content: space-between; gap: 16px; min-height: 78px; }
      .brand { display: inline-flex; align-items: center; gap: 12px; }
      .brand strong, .brand span { display: block; }
      .brand strong { font-size: 1rem; }
      .brand span { color: var(--muted); font-size: 0.73rem; }
      .brand-mark { display: grid; place-items: center; width: 42px; height: 42px; border-radius: 13px; background: linear-gradient(135deg, var(--primary) 0%, #34b76d 100%); color: white; font-weight: 800; box-shadow: var(--shadow); }
      .nav-links { display: flex; align-items: center; gap: 28px; color: var(--muted); font-weight: 500; }
      .nav-links a { position: relative; }
      .nav-links a:hover, .nav-links a.active { color: var(--text); }
      .nav-links a.active::after {
        content: ""; position: absolute; left: 0; bottom: -8px; width: 100%; height: 2px; border-radius: 999px; background: var(--primary);
      }
      .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; border: none; border-radius: 12px; padding: 0.82rem 1.3rem; font-weight: 600; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease;
      }
      .btn:hover { transform: translateY(-1px); }
      .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, #2aa561 100%); color: white; box-shadow: 0 8px 14px rgba(31, 138, 90, 0.22); }
      .btn-ghost { background: rgba(31, 138, 90, 0.07); color: var(--primary-dark); border: 1px solid rgba(31, 138, 90, 0.18); }
      .reports-layout { display: grid; grid-template-columns: 320px minmax(0, 1fr) 320px; gap: 24px; padding: 28px 0 56px; }
      .panel { background: rgba(255, 255, 255, 0.9); border: 1px solid rgba(103, 120, 128, 0.08); border-radius: 24px; box-shadow: 0 16px 32px rgba(28, 36, 48, 0.04); padding: 18px; }
      .panel-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
      .panel-header h3 { margin: 0; font-size: 1.2rem; }
      .panel-header p { margin: 4px 0 0; color: var(--muted); font-size: 0.8rem; }
      .pill, .tag { display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; font-size: 0.7rem; font-weight: 700; padding: 0.42rem 0.7rem; }
      .pill { background: rgba(228, 93, 93, 0.12); color: #b53838; }
      .tag.danger { background: rgba(228, 93, 93, 0.12); color: #b53838; }
      .tag.warning { background: rgba(245, 185, 66, 0.14); color: #9d6900; }
      .tag.success { background: rgba(31, 138, 90, 0.12); color: var(--primary-dark); }
      .filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; }
      .filter-btn {
        border: 1px solid rgba(39, 59, 52, 0.06); background: rgba(64, 93, 108, 0.03); color: var(--muted); border-radius: 999px; padding: 0.55rem 0.8rem; font-weight: 600; cursor: pointer;
      }
      .filter-btn.active { background: var(--primary-soft); color: var(--primary-dark); }
      .report-list { display: grid; gap: 12px; }
      .report-card { background: #f9fbfa; border: 1px solid rgba(112, 132, 124, 0.08); border-radius: 18px; padding: 14px 14px 16px; cursor: pointer; transition: border 0.2s ease, transform 0.2s ease; }
      .report-card:hover, .report-card.active { border-color: rgba(31, 138, 90, 0.2); transform: translateY(-1px); }
      .report-meta { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 10px; color: var(--muted); font-size: 0.75rem; }
      .report-card h4 { margin: 0 0 8px; font-size: 1.05rem; }
      .report-card p { margin: 0; color: var(--muted); line-height: 1.6; font-size: 0.9rem; }
      .map-surface {
        position: relative; height: 680px; border-radius: 22px; overflow: hidden; background: linear-gradient(135deg, rgba(59, 159, 117, 0.08), rgba(76, 138, 198, 0.08)), linear-gradient(120deg, rgba(255, 255, 255, 0.7) 0%, rgba(235, 247, 239, 0.9) 100%); border: 1px solid rgba(70, 103, 98, 0.08);
      }
      .map-surface::before { content: ""; position: absolute; inset: 0; background-image: linear-gradient(rgba(48, 71, 63, 0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(48, 71, 63, 0.05) 1px, transparent 1px); background-size: 42px 42px; }
      .map-surface::after { content: ""; position: absolute; inset: 8% 10% 9% 12%; border-radius: 40px; border: 1px solid rgba(66, 106, 87, 0.12); box-shadow: inset 0 0 0 10px rgba(255, 255, 255, 0.22); }
      #lapuMap { height: 680px; width: 100%; border-radius: 22px; overflow: hidden; border: 1px solid rgba(70, 103, 98, 0.08); }
      .map-region { position: absolute; border-radius: 50%; filter: blur(1px); opacity: 0.8; }
      .region-a { width: 260px; height: 200px; background: rgba(64, 189, 125, 0.11); top: 24%; left: 18%; }
      .region-b { width: 200px; height: 170px; background: rgba(69, 129, 185, 0.1); bottom: 15%; right: 22%; }
      .region-c { width: 240px; height: 170px; background: rgba(242, 194, 89, 0.11); bottom: 24%; left: 34%; }
      .marker { position: absolute; width: 22px; height: 22px; border: 0; border-radius: 50%; background: linear-gradient(135deg, var(--danger) 0%, #ff9d59 100%); box-shadow: 0 0 0 8px rgba(228, 93, 93, 0.12), 0 10px 20px rgba(228, 93, 93, 0.28); cursor: pointer; }
      .marker::after { content: ""; position: absolute; width: 8px; height: 8px; background: rgba(255, 255, 255, 0.8); border-radius: 50%; top: 50%; left: 50%; transform: translate(-50%, -50%); }
      .marker.active { background: linear-gradient(135deg, var(--primary) 0%, #64c084 100%); box-shadow: 0 0 0 9px rgba(31, 138, 90, 0.12), 0 14px 22px rgba(31, 138, 90, 0.28); }
      .marker-one { top: 34%; left: 38%; }
      .marker-two { top: 48%; right: 32%; }
      .marker-three { bottom: 28%; left: 52%; }
      .detail-content { display: block; }
      .detail-card { background: #f8fbf9; border: 1px solid rgba(121, 135, 129, 0.08); border-radius: 18px; padding: 18px 16px; }
      .detail-card h4 { margin: 16px 0 12px; font-size: 1.2rem; }
      .detail-card ul { list-style: none; padding: 0; margin: 0; display: grid; gap: 12px; color: var(--muted); line-height: 1.6; }
      .comment-panel { margin-top: 18px; border-top: 1px solid rgba(31, 138, 90, 0.08); padding-top: 16px; }
      .comment-panel h5 { margin: 0 0 12px; font-size: 0.8rem; letter-spacing: 0.08em; text-transform: uppercase; color: var(--primary-dark); }
      .comment-list { display: grid; gap: 10px; }
      .comment-item { background: rgba(255,255,255,0.7); border: 1px solid rgba(39, 59, 52, 0.06); border-radius: 12px; padding: 10px 12px; }
      .comment-item strong { display: block; margin-bottom: 4px; font-size: 0.74rem; color: var(--primary-dark); }
      .comment-item p { margin: 0; color: var(--muted); font-size: 0.85rem; line-height: 1.6; }
      .support-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 14px; padding-top: 12px; border-top: 1px solid rgba(31, 138, 90, 0.08); }
      .support-button { border: 1px solid rgba(31, 138, 90, 0.12); background: rgba(31, 138, 90, 0.05); color: var(--primary-dark); border-radius: 999px; padding: 0.55rem 0.9rem; font-weight: 700; cursor: pointer; }
      .support-count { color: var(--muted); font-size: 0.8rem; font-weight: 700; }
      .status-timeline { display: grid; gap: 8px; margin: 18px 0 0; padding: 0; list-style: none; }
      .status-timeline li { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: var(--muted); }
      .status-timeline li::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) 0%, #65c096 100%); box-shadow: 0 0 0 4px rgba(31, 138, 90, 0.08); }
      #reportMapPicker { height: 220px; width: 100%; border-radius: 14px; overflow: hidden; border: 1px solid rgba(39, 59, 52, 0.08); margin-bottom: 10px; }
      .modal { position: fixed; inset: 0; display: none; place-items: center; z-index: 100; }
      .modal.open { display: grid; }
      .modal-backdrop { position: absolute; inset: 0; background: rgba(22, 34, 31, 0.45); }
      .modal-dialog { position: relative; width: min(380px, calc(100% - 16px)); background: white; border-radius: 16px; box-shadow: var(--shadow); padding: 12px 12px 10px; z-index: 1; }
      .modal-header { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 8px; }
      .modal-header h3 { margin: 4px 0 0; font-size: 1.08rem; }
      .icon-btn { border: none; background: rgba(96, 112, 126, 0.08); width: 28px; height: 28px; border-radius: 9px; font-size: 1.1rem; cursor: pointer; }
      .input-group { margin-bottom: 10px; }
      .input-group label { display: inline-block; margin-bottom: 5px; font-size: 0.75rem; color: var(--text); font-weight: 600; }
      .input-group input, .input-group select, .input-group textarea {
        width: 100%; border: 1px solid rgba(75, 93, 98, 0.18); background: #f8fbfa; border-radius: 9px; padding: 0.62rem 0.7rem; color: var(--text); outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease; font-size: 0.86rem;
      }
      .input-group input:focus, .input-group select:focus, .input-group textarea:focus { border-color: rgba(31, 138, 90, 0.45); box-shadow: 0 0 0 3px rgba(31, 138, 90, 0.08); }
      .category-row { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; margin: 0 0 10px; color: var(--muted); font-weight: 600; font-size: 0.75rem; }
      .priority-btn { border: 1px solid rgba(25, 49, 29, 0.08); background: rgba(53, 72, 70, 0.04); border-radius: 999px; padding: 0.35rem 0.6rem; cursor: pointer; color: var(--muted); font-size: 0.74rem; }
      .priority-btn.active { background: var(--primary-soft); color: var(--primary-dark); border-color: rgba(31, 138, 90, 0.18); }
      .report-form .btn { padding: 0.66rem 0.85rem; font-size: 0.85rem; }
      @media (max-width: 980px) { .reports-layout { grid-template-columns: 1fr; } .map-surface { height: 420px; } }
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

        <nav class="nav-links" aria-label="Report page navigation">
          <a href="{{ route('home') }}">Home</a>
          <a href="{{ route('reports') }}" class="active">Reports</a>
          <a href="{{ Auth::check() ? route('account') : route('login') }}">Account</a>
        </nav>

        <button type="button" class="btn btn-primary js-open-form">+ Add report</button>
      </div>
    </header>

    <main class="reports-layout container">
      <aside class="sidebar panel">
        <div class="panel-header">
          <h3>Community issues</h3>
          <span class="pill">Live</span>
        </div>

        <div class="filters">
          <button class="filter-btn active" type="button">All</button>
          <button class="filter-btn" type="button">Safety</button>
          <button class="filter-btn" type="button">Infrastructure</button>
          <button class="filter-btn" type="button">Public services</button>
        </div>

        <div class="report-list" id="reportList">
          @if ($reports->isEmpty())
            <div class="detail-card" style="padding: 20px; text-align: center; color: var(--muted);">
              No reports yet. Add the first community issue.
            </div>
          @else
            @foreach ($reports as $index => $report)
              <article class="report-card {{ $index === 0 ? 'active' : '' }}" data-id="{{ $report->id }}" data-location="{{ $report->location }}">
                <div class="report-meta">
                  <span class="tag {{ $report->meta['tone'] ?? 'info' }}">{{ $report->type }}</span>
                  <span>{{ $report->created_at->diffForHumans() }}</span>
                </div>
                <h4>{{ $report->title }}</h4>
                @if ($report->image_url)
                  <img src="{{ $report->image_url }}" alt="{{ $report->title }}" style="width: 100%; height: 110px; object-fit: cover; border-radius: 12px; margin: 10px 0; border: 1px solid rgba(0,0,0,0.04);" />
                @endif
                <p data-location="{{ $report->location }}">{{ $report->description }}</p>
              </article>
            @endforeach
          @endif
        </div>
      </aside>

      <section class="map-panel panel">
        <div class="panel-header">
          <div>
            <h3>Neighborhood map</h3>
            <p>Latest updates in your area</p>
          </div>
          <button type="button" class="btn btn-ghost js-open-form">Quick add</button>
        </div>

        <div id="lapuMap" aria-label="Map of Lapu-Lapu City"></div>
      </section>

      <aside class="detail-panel panel">
        <div class="panel-header">
          <h3>Report details</h3>
          <span class="pill">Open</span>
        </div>

        <div class="detail-content" id="detailContent">
          @if ($reports->isNotEmpty())
            @php $first = $reports->first(); @endphp
            <div class="detail-card">
              <span class="tag {{ $first->meta['tone'] ?? 'info' }}">{{ $first->type }}</span>
              <h4>{{ $first->title }}</h4>
              @if ($first->image_url)
                <img src="{{ $first->image_url }}" alt="{{ $first->title }}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 14px; margin-bottom: 14px; border: 1px solid rgba(0,0,0,0.04);" />
              @endif
              <ul>
                <li><strong>Location:</strong> {{ $first->location }}</li>
                <li><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $first->status)) }}</li>
                <li><strong>Residents:</strong> {{ $first->meta['residents'] ?? 0 }} nearby reports</li>
              </ul>

              <div class="support-bar">
                @auth
                  <form method="POST" action="{{ route('reports.support', $first) }}">
                    @csrf
                    <button class="support-button" type="submit">Support</button>
                  </form>
                @else
                  <a href="{{ route('login') }}" class="support-button" style="display:inline-flex;">Support</a>
                @endauth
                <span class="support-count">{{ (int) ($first->support_count ?? 0) }} supporters</span>
              </div>

              <ul class="status-timeline">
                <li>Reported and shared with the community</li>
                <li>Reviewing the issue with local teams</li>
                <li>{{ ucfirst(str_replace('_', ' ', $first->status)) }} status updated</li>
              </ul>

              @if ($first->comments->isNotEmpty())
                <div class="comment-panel">
                  <h5>Community updates</h5>
                  <div class="comment-list">
                    @foreach ($first->comments as $comment)
                      <div class="comment-item">
                        <strong>{{ $comment->user?->name ?? 'Admin' }}</strong>
                        <p>{{ $comment->body }}</p>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            </div>
          @endif
        </div>
      </aside>
    </main>

    <div class="modal" id="reportModal" aria-hidden="true">
      <div class="modal-backdrop js-close-form"></div>
      <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="reportTitle">
        <div class="modal-header">
          <div>
            <span class="eyebrow" style="display: inline-block; color: var(--primary-dark); font-size: 0.74rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 14px;">New report</span>
            <h3 id="reportTitle">Add a community issue</h3>
          </div>
          <button class="icon-btn js-close-form" type="button" aria-label="Close form">×</button>
        </div>

        <form class="report-form" id="reportForm" method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="input-group">
            <label for="report-title">Title</label>
            <input id="report-title" name="title" type="text" placeholder="Enter a short title" required />
          </div>

          <div class="input-group">
            <label for="report-type">Issue type</label>
            <select id="report-type" name="type">
              <option>Pothole</option>
              <option>Streetlight</option>
              <option>Graffiti</option>
              <option>Flooding</option>
              <option>Trash</option>
            </select>
          </div>

          <div class="input-group">
            <label for="report-location">Location</label>
            <div id="reportMapPicker" aria-label="Map picker for report location"></div>
            <input id="report-location" name="location" type="text" placeholder="Enter a street or landmark" required />
            <input type="hidden" name="latitude" id="reportLatitude" />
            <input type="hidden" name="longitude" id="reportLongitude" />
          </div>

          <div class="input-group">
            <label for="report-desc">Description</label>
            <textarea id="report-desc" name="description" rows="4" placeholder="Briefly describe what is happening..." required></textarea>
          </div>

          <div class="input-group">
            <label for="report-image">Attach image</label>
            <input id="report-image" name="image" type="file" accept="image/*" />
          </div>

          <input type="hidden" name="priority" id="reportPriority" value="low" />

          <div class="category-row">
            <span>Priority</span>
            <button class="priority-btn active" type="button" data-priority="low">Low</button>
            <button class="priority-btn" type="button" data-priority="medium">Medium</button>
            <button class="priority-btn" type="button" data-priority="high">High</button>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">Submit report</button>
        </form>
      </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const lapuCenter = [10.3103, 123.9499];
        const mapContainer = document.getElementById('lapuMap');

        if (mapContainer) {
          const map = L.map('lapuMap', { zoomControl: true }).setView(lapuCenter, 12);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19,
          }).addTo(map);

          L.circleMarker(lapuCenter, {
            radius: 10,
            color: '#1d4ed8',
            weight: 3,
            fillColor: '#60a5fa',
            fillOpacity: 0.8,
          }).addTo(map).bindPopup('Lapu-Lapu City, Cebu');

          const reverseFallback = [
            [10.3168, 123.9366],
            [10.2961, 123.9550],
            [10.3244, 123.9731],
            [10.2865, 123.9618],
            [10.3342, 123.9411],
          ];

          const geocodeLocation = async (location) => {
            const query = `${location}, Lapu-Lapu City, Cebu, Philippines`;
            const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}`;

            try {
              const response = await fetch(url, {
                headers: {
                  'Accept': 'application/json',
                  'User-Agent': 'CommunityConnect/1.0',
                },
              });

              if (!response.ok) {
                return null;
              }

              const data = await response.json();
              if (!Array.isArray(data) || data.length === 0) {
                return null;
              }

              return [Number(data[0].lat), Number(data[0].lon)];
            } catch (error) {
              console.warn('Unable to geocode report location:', error);
              return null;
            }
          };

          const mapReportCards = Array.from(document.querySelectorAll('.report-card'));
          mapReportCards.forEach(async (card, index) => {
            const location = card.dataset.location || card.querySelector('p')?.dataset?.location || 'Lapu-Lapu City';
            const fallback = reverseFallback[index % reverseFallback.length];
            const coords = await geocodeLocation(location);
            const markerCoords = coords || fallback;

            const marker = L.marker(markerCoords).addTo(map);
            marker.bindPopup(`
              <strong>${card.querySelector('h4')?.textContent || 'Report'}</strong><br>
              ${location}
            `);

            card.addEventListener('click', () => {
              map.flyTo(markerCoords, 14, { duration: 0.8 });
              marker.openPopup();
            });
          });
        }

        const reportCards = document.querySelectorAll('.report-card');
        const detailContent = document.getElementById('detailContent');
        const reportList = document.getElementById('reportList');
        const form = document.getElementById('reportForm');
        const locationInput = document.getElementById('report-location');
        const latitudeInput = document.getElementById('reportLatitude');
        const longitudeInput = document.getElementById('reportLongitude');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        const mapPicker = document.getElementById('reportMapPicker');
        if (mapPicker && typeof L !== 'undefined') {
          const pickerMap = L.map('reportMapPicker', { zoomControl: true }).setView([10.3103, 123.9499], 12);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19,
          }).addTo(pickerMap);

          let pickerMarker = null;
          const setPickerLocation = (lat, lng) => {
            if (!pickerMarker) {
              pickerMarker = L.marker([lat, lng]).addTo(pickerMap);
            } else {
              pickerMarker.setLatLng([lat, lng]);
            }

            pickerMap.setView([lat, lng], 14);
            if (latitudeInput) latitudeInput.value = lat;
            if (longitudeInput) longitudeInput.value = lng;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
              .then((response) => response.json())
              .then((data) => {
                if (locationInput && data && data.display_name) {
                  locationInput.value = data.display_name;
                }
              })
              .catch(() => {
                if (locationInput && !locationInput.value) {
                  locationInput.value = 'Lapu-Lapu City, Cebu';
                }
              });
          };

          pickerMap.on('click', (event) => {
            setPickerLocation(event.latlng.lat, event.latlng.lng);
          });

          setPickerLocation(10.3103, 123.9499);
        }
        const detailMap = {
          @foreach ($reports as $report)
            {{ $report->id }}: {
              title: @json($report->title),
              type: @json($report->type),
              location: @json($report->location),
              status: @json(ucfirst(str_replace('_', ' ', $report->status))),
              residents: @json(($report->meta['residents'] ?? 0) . ' nearby reports'),
              tone: @json($report->meta['tone'] ?? 'info'),
              image: @json($report->image_url),
              support_count: @json((int) ($report->support_count ?? 0)),
              comments: @json($report->comments->map(fn($comment) => [
                'name' => $comment->user?->name ?? 'Admin',
                'body' => $comment->body,
              ])->values()->all())
            },
          @endforeach
        };

        function renderDetail(id) {
          const item = detailMap[id];
          if (!item || !detailContent) return;

          const commentsHtml = (item.comments || []).map((comment) => `
            <div class="comment-item">
              <strong>${comment.name}</strong>
              <p>${comment.body}</p>
            </div>
          `).join('');

          const supportForm = `
            <div class="support-bar">
              <form method="POST" action="/reports/${id}/support">
                <input type="hidden" name="_token" value="${csrfToken}" />
                <button class="support-button" type="submit">Support</button>
              </form>
              <span class="support-count">${item.support_count || 0} supporters</span>
            </div>
          `;

          detailContent.innerHTML = `
            <div class="detail-card">
              <span class="tag ${item.tone}">${item.type}</span>
              <h4>${item.title}</h4>
              ${item.image ? `<img src="${item.image}" alt="${item.title}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 14px; margin-bottom: 14px; border: 1px solid rgba(0,0,0,0.04);" />` : ''}
              <ul>
                <li><strong>Location:</strong> ${item.location}</li>
                <li><strong>Status:</strong> ${item.status}</li>
                <li><strong>Residents:</strong> ${item.residents}</li>
              </ul>

              ${supportForm}

              <ul class="status-timeline">
                <li>Reported and shared with the community</li>
                <li>Reviewing the issue with local teams</li>
                <li>${item.status} status updated</li>
              </ul>

              ${commentsHtml ? `
                <div class="comment-panel">
                  <h5>Community updates</h5>
                  <div class="comment-list">${commentsHtml}</div>
                </div>
              ` : ''}
            </div>
          `;
        }

        function activateCard(id) {
          reportCards.forEach((card) => card.classList.toggle('active', card.dataset.id === String(id)));
          renderDetail(id);
        }

        reportCards.forEach((card) => {
          card.addEventListener('click', () => {
            activateCard(card.dataset.id);
          });
        });

        const modal = document.getElementById('reportModal');
        const openButtons = document.querySelectorAll('.js-open-form');
        const closeButtons = document.querySelectorAll('.js-close-form');
        function toggleModal(show) {
          if (!modal) return;
          modal.classList.toggle('open', show);
          modal.setAttribute('aria-hidden', String(!show));
        }
        openButtons.forEach((button) => button.addEventListener('click', () => toggleModal(true)));
        closeButtons.forEach((button) => button.addEventListener('click', () => toggleModal(false)));
        modal?.addEventListener('click', (event) => { if (event.target === modal) toggleModal(false); });

        const reportPriorityInput = document.getElementById('reportPriority');

        document.querySelectorAll('.priority-btn').forEach((button) => {
          button.addEventListener('click', () => {
            const selectedPriority = button.dataset.priority;
            if (reportPriorityInput) {
              reportPriorityInput.value = selectedPriority;
            }
            document.querySelectorAll('.priority-btn').forEach((item) => item.classList.toggle('active', item === button));
          });
        });

        if (form) {
          form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
              submitButton.disabled = true;
              submitButton.textContent = 'Saving...';
            }

            try {
              const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                },
                body: new FormData(form),
              });

              const data = await response.json();
              if (!response.ok) {
                throw new Error(data.message || 'Unable to save report.');
              }

              const report = data.report;
              if (reportList && reportList.querySelector('.detail-card')) {
                const emptyState = reportList.querySelector('.detail-card');
                if (emptyState && emptyState.textContent.includes('No reports yet')) {
                  emptyState.remove();
                }
              }

              const article = document.createElement('article');
              article.className = 'report-card active';
              article.dataset.id = String(report.id);
              article.dataset.location = report.location;
              article.innerHTML = `
                <div class="report-meta">
                  <span class="tag ${report.tone}">${report.type}</span>
                  <span>${report.created_at}</span>
                </div>
                <h4>${report.title}</h4>
                ${report.image_path ? `<img src="${report.image_path}" alt="${report.title}" style="width: 100%; height: 110px; object-fit: cover; border-radius: 12px; margin: 10px 0; border: 1px solid rgba(0,0,0,0.04);" />` : ''}
                <p data-location="${report.location}">${report.description}</p>
              `;
              article.addEventListener('click', () => activateCard(report.id));
              reportList.prepend(article);

              const marker = document.createElement('button');
              marker.type = 'button';
              marker.className = 'marker active';
              marker.dataset.id = String(report.id);
              marker.setAttribute('aria-label', `Report ${report.id}`);
              marker.style.left = '50%';
              marker.style.top = '52%';
              marker.addEventListener('click', () => activateCard(report.id));

              const mapSurface = document.querySelector('.map-surface');
              if (mapSurface) {
                mapSurface.appendChild(marker);
              }

              detailMap[report.id] = {
                title: report.title,
                type: report.type,
                location: report.location,
                status: report.status.charAt(0).toUpperCase() + report.status.slice(1).replace('_', ' '),
                residents: `${report.residents} nearby reports`,
                tone: report.tone,
                image: report.image_path || null,
                support_count: report.support_count || 0,
                comments: []
              };

              renderDetail(report.id);
              activateCard(report.id);
              toggleModal(false);
              form.reset();
              if (reportPriorityInput) {
                reportPriorityInput.value = 'low';
              }
              document.querySelectorAll('.priority-btn').forEach((button) => button.classList.toggle('active', button.dataset.priority === 'low'));
            } catch (error) {
              console.error(error);
            } finally {
              if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Submit report';
              }
            }
          });
        }
      });
    </script>
  </body>
</html>
