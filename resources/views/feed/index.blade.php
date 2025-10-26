@extends('layouts.app')

@section('content')
<style>
  .feed-page {
    --primary: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #1f2937;
    --light: #f8fafc;
    --border: #e5e7eb;
    --text: #374151;
    --text-muted: #6b7280;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }

  .feed-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .page-container {
    max-width: 1200px;
    margin: 0 auto;
  }

  .page-header {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .page-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0 0 16px 0;
    background: linear-gradient(135deg, var(--primary), var(--success));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .page-subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
    margin: 0 0 24px 0;
  }

  .filter-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .filter-form {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
  }

  .filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .filter-select {
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 200px;
  }

  .filter-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  .jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 24px;
  }

  @media (max-width: 768px) {
    .jobs-grid {
      grid-template-columns: 1fr;
    }
    
    .view-toggle {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .map-container {
      height: 60vh;
    }
    
    .page-header > div:first-child {
      flex-direction: column;
      align-items: flex-start;
      gap: 16px;
    }
  }

  .job-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .job-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
  }

  .job-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--border);
  }

  .job-card.near::before {
    background: linear-gradient(90deg, #10b981, #059669);
  }

  .job-card.moderate::before {
    background: linear-gradient(90deg, #f59e0b, #d97706);
  }

  .job-card.far::before {
    background: linear-gradient(90deg, #ef4444, #dc2626);
  }

  .job-card.unknown::before {
    background: linear-gradient(90deg, #6b7280, #4b5563);
  }

  .job-card.no_user_location::before {
    background: linear-gradient(90deg, #f59e0b, #d97706);
  }

  .job-card.no_job_location::before {
    background: linear-gradient(90deg, #ef4444, #dc2626);
  }

  .job-header {
    margin-bottom: 16px;
  }

  .job-content {
    flex: 1;
    margin-bottom: 16px;
  }

  .job-badges {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-bottom: 12px;
    flex-wrap: wrap;
  }

  .badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .badge-category {
    background: var(--primary);
    color: white;
  }

  .badge-poster {
    background: #10b981;
    color: white;
  }

  .badge-distance {
    font-weight: 700;
  }

  .job-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
    line-height: 1.3;
  }

  .job-meta {
    display: flex;
    gap: 16px;
    align-items: center;
    margin-bottom: 12px;
    flex-wrap: wrap;
  }

  .job-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--success);
  }

  .job-distance {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    font-weight: 600;
  }

  .distance-icon {
    font-size: 1rem;
  }

  .job-description {
    color: var(--text);
    line-height: 1.5;
    margin-bottom: 12px;
  }

  .job-note {
    font-size: 0.75rem;
    color: #10b981;
    margin-top: 8px;
    font-weight: 600;
    padding: 6px 12px;
    background: #f0fdf4;
    border-radius: 8px;
    border: 1px solid #bbf7d0;
  }

  /* View Toggle Buttons */
  .view-toggle {
    display: flex;
    gap: 8px;
    background: #f8fafc;
    padding: 4px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
  }

  .view-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: none;
    background: transparent;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .view-btn:hover {
    background: #e5e7eb;
    color: #374151;
  }

  .view-btn.active {
    background: var(--primary);
    color: white;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
  }

  .view-btn span {
    font-size: 1rem;
  }

  /* Map View Styles */
  .map-container {
    display: none;
    height: 70vh;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
  }

  .map-container.active {
    display: block;
  }

  .list-container {
    display: block;
  }

  .list-container.hidden {
    display: none;
  }

  #map {
    width: 100%;
    height: 100%;
  }

  /* Map Popup Styles */
  .map-popup {
    max-width: 300px;
  }

  .map-popup .job-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .map-popup .job-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0 0 8px 0;
  }

  .map-popup .job-distance {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0 0 8px 0;
  }

  .map-popup .job-description {
    font-size: 0.875rem;
    color: var(--text);
    line-height: 1.4;
    margin: 0 0 12px 0;
  }

  .map-popup .popup-actions {
    display: flex;
    gap: 8px;
  }

  .map-popup .btn {
    padding: 8px 16px;
    font-size: 0.875rem;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
  }

  .map-popup .btn-primary {
    background: var(--primary);
    color: white;
  }

  .map-popup .btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
  }

  /* Marker Styles */
  .job-marker-green,
  .job-marker-orange,
  .job-marker-red {
    z-index: 1000 !important;
  }

  .job-marker-green div,
  .job-marker-orange div,
  .job-marker-red div {
    z-index: 1001 !important;
    position: relative;
  }

  /* Ensure markers are visible */
  .leaflet-marker-icon {
    z-index: 1000 !important;
  }

  /* User location marker */
  .user-location-marker {
    z-index: 1002 !important;
  }

  /* Fallback job markers */
  .fallback-job-marker,
  .fallback-job-marker-green,
  .fallback-job-marker-orange,
  .fallback-job-marker-red {
    z-index: 1001 !important;
  }

  .fallback-job-marker div,
  .fallback-job-marker-green div,
  .fallback-job-marker-orange div,
  .fallback-job-marker-red div {
    z-index: 1002 !important;
    position: relative;
  }

  /* Additional job markers */
  .additional-job-marker {
    z-index: 1003 !important;
  }

  .additional-job-marker div {
    z-index: 1004 !important;
    position: relative;
  }

  .job-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    justify-content: space-between;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--primary), #1d4ed8);
    color: white;
    box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.4);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px 0 rgba(59, 130, 246, 0.6);
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .empty-icon {
    font-size: 4rem;
    margin-bottom: 16px;
    opacity: 0.6;
  }

  .empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .empty-text {
    color: var(--text-muted);
    font-size: 1rem;
    margin: 0;
  }

  .pagination {
    display: flex;
    justify-content: center;
    margin-top: 32px;
  }

  @media (max-width: 768px) {
    .feed-page {
      padding: 16px;
    }
    
    .page-header {
      padding: 24px;
    }
    
    .page-title {
      font-size: 2rem;
    }
    
    .filter-form {
      flex-direction: column;
      align-items: stretch;
    }
    
    .filter-select {
      min-width: auto;
    }
    
    .job-header {
      flex-direction: column;
      gap: 16px;
    }
    
    .job-actions {
      flex-direction: column;
      align-items: stretch;
    }
  }
</style>

<div class="feed-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
          <h1 class="page-title">🔍 Kazi Zilizopo</h1>
          <p class="page-subtitle">Pata kazi za karibu nawe na uanze kufanya kazi</p>
        </div>
        
        <!-- View Toggle Buttons -->
        <div class="view-toggle">
          <button id="list-view-btn" class="view-btn active" onclick="switchView('list')">
            <span>📋</span>
            List
          </button>
          <button id="map-view-btn" class="view-btn" onclick="switchView('map')">
            <span>🗺️</span>
            Map
          </button>
        </div>
      </div>
      
      @if(!auth()->user()->hasLocation())
        <div style="background: #fef3c7; border: 2px solid #f59e0b; border-radius: 12px; padding: 16px; margin-top: 16px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="font-size: 1.5rem;">⚠️</div>
            <div>
              <h4 style="color: #92400e; margin: 0 0 4px 0; font-weight: 700;">Eneo Lako Halijajulikana</h4>
              <p style="color: #92400e; margin: 0; font-size: 0.875rem;">
                Weka eneo lako kwenye profile ili kuona umbali wa kazi. 
                <a href="{{ route('profile.edit') }}" style="color: #92400e; font-weight: 600; text-decoration: underline;">Bonyeza hapa kuongeza eneo</a>
              </p>
            </div>
          </div>
        </div>
      @endif
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
      <form method="get" class="filter-form">
        <div class="filter-group">
          <label class="filter-label" for="category">Aina ya Kazi</label>
          <select name="category" id="category" class="filter-select" onchange="this.form.submit()">
      <option value="">Zote</option>
      @foreach(\App\Models\Category::all() as $c)
        <option value="{{ $c->slug }}" {{ $cat==$c->slug?'selected':'' }}>{{ $c->name }}</option>
      @endforeach
    </select>
        </div>
        
        <div class="filter-group">
          <label class="filter-label" for="distance">Umbali</label>
          <select name="distance" id="distance" class="filter-select" onchange="this.form.submit()">
            <option value="">Zote</option>
            <option value="5" {{ request('distance') == '5' ? 'selected' : '' }}>Karibu (≤5km)</option>
            <option value="10" {{ request('distance') == '10' ? 'selected' : '' }}>Umbali wa wastani (≤10km)</option>
            <option value="20" {{ request('distance') == '20' ? 'selected' : '' }}>Umbali mkubwa (≤20km)</option>
            <option value="50" {{ request('distance') == '50' ? 'selected' : '' }}>Umbali wa mbali (≤50km)</option>
          </select>
        </div>
</form>

      <!-- Distance Legend -->
      <div style="margin-top: 20px; padding: 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #e5e7eb;">
        <h4 style="color: var(--dark); margin: 0 0 12px 0; font-size: 0.875rem; font-weight: 600;">Maelezo ya Rangi:</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
          <div style="display: flex; align-items: center; gap: 6px;">
            <div style="width: 16px; height: 4px; background: linear-gradient(90deg, #10b981, #059669); border-radius: 2px;"></div>
            <span style="font-size: 0.75rem; color: var(--text-muted);">🟢 Karibu (≤5km)</span>
          </div>
          <div style="display: flex; align-items: center; gap: 6px;">
            <div style="width: 16px; height: 4px; background: linear-gradient(90deg, #f59e0b, #d97706); border-radius: 2px;"></div>
            <span style="font-size: 0.75rem; color: var(--text-muted);">🟠 Umbali wa wastani (≤10km)</span>
          </div>
          <div style="display: flex; align-items: center; gap: 6px;">
            <div style="width: 16px; height: 4px; background: linear-gradient(90deg, #ef4444, #dc2626); border-radius: 2px;"></div>
            <span style="font-size: 0.75rem; color: var(--text-muted);">🔴 Umbali mkubwa (>10km)</span>
          </div>
          <div style="display: flex; align-items: center; gap: 6px;">
            <div style="width: 16px; height: 4px; background: linear-gradient(90deg, #f59e0b, #d97706); border-radius: 2px;"></div>
            <span style="font-size: 0.75rem; color: var(--text-muted);">⚠️ Weka eneo lako</span>
          </div>
          <div style="display: flex; align-items: center; gap: 6px;">
            <div style="width: 16px; height: 4px; background: linear-gradient(90deg, #ef4444, #dc2626); border-radius: 2px;"></div>
            <span style="font-size: 0.75rem; color: var(--text-muted);">❌ Eneo haujulikani</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Map Container -->
    <div id="map-container" class="map-container">
      <div id="map"></div>
    </div>

    <!-- Jobs List Container -->
    <div id="list-container" class="list-container">
      @if($jobs->count() > 0)
        <div class="jobs-grid">
@foreach($jobs as $job)
          <div class="job-card {{ $job->distance_info['category'] ?? 'unknown' }}">
            <!-- Job Header with Badges -->
            <div class="job-header">
              <div class="job-badges">
                <div class="badge badge-category">{{ $job->category->name }}</div>
          @if($job->poster_type === 'mfanyakazi')
                  <div class="badge badge-poster">👷 Mfanyakazi</div>
                @else
                  <div class="badge badge-poster">👤 Muhitaji</div>
                @endif
                <div class="badge badge-distance" 
                     style="background: {{ $job->distance_info['bg_color'] ?? '#f3f4f6' }}; 
                            color: {{ $job->distance_info['text_color'] ?? '#6b7280' }};">
                  <span class="distance-icon">📍</span>
                  @if($job->distance_info['distance'])
                    {{ $job->distance_info['distance'] }}km
          @else
                    {{ $job->distance_info['label'] ?? 'N/A' }}
          @endif
        </div>
              </div>
            </div>

            <!-- Job Content -->
            <div class="job-content">
              <h3 class="job-title">{{ $job->title }}</h3>
              
              <div class="job-meta">
                <div class="job-price">{{ number_format($job->price) }} TZS</div>
                <div class="job-distance" 
                     style="color: {{ $job->distance_info['text_color'] ?? '#6b7280' }};">
                  <span class="distance-icon">🚗</span>
                  {{ $job->distance_info['label'] ?? 'Umbali haujulikani' }}
                </div>
              </div>
              
              @if($job->description)
                <div class="job-description">{{ Str::limit($job->description, 120) }}</div>
              @endif
              
        @if($job->poster_type === 'mfanyakazi')
                <div class="job-note">
            📝 Huduma inayotolewa na mfanyakazi
          </div>
        @endif
      </div>
            
            <!-- Job Actions -->
            <div class="job-actions">
              <a class="btn btn-primary" href="{{ route('jobs.show',$job) }}">
                <span>👁️</span>
                Fungua Kazi
              </a>
    </div>
  </div>
@endforeach
      </div>

        <div class="pagination">
{{ $jobs->links() }}
        </div>
      @else
        <div class="empty-state">
          <div class="empty-icon">🔍</div>
          <h3 class="empty-title">Hakuna Kazi Zilizopo</h3>
          <p class="empty-text">
            @if($cat)
              Hakuna kazi za aina hii kwa sasa. Jaribu aina nyingine.
            @else
              Hakuna kazi zilizopo kwa sasa. Rudi baadaye.
            @endif
          </p>
        </div>
      @endif
    </div>

  </div>
</div>

<!-- Leaflet.js CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  // Global variables
  let map;
  let userLocation = null;
  let jobMarkers = [];

  // View switching functionality
  function switchView(view) {
    console.log('Switching to view:', view); // Debug log
    
    const listBtn = document.getElementById('list-view-btn');
    const mapBtn = document.getElementById('map-view-btn');
    const listContainer = document.getElementById('list-container');
    const mapContainer = document.getElementById('map-container');

    if (view === 'list') {
      listBtn.classList.add('active');
      mapBtn.classList.remove('active');
      listContainer.classList.remove('hidden');
      mapContainer.classList.remove('active');
    } else {
      mapBtn.classList.add('active');
      listBtn.classList.remove('active');
      listContainer.classList.add('hidden');
      mapContainer.classList.add('active');
      
      // Initialize map if not already done
      if (!map) {
        console.log('Map not initialized, initializing now...');
        try {
          initializeMap();
        } catch (error) {
          console.error('Error initializing map:', error);
          alert('Error loading map. Please refresh the page.');
        }
      } else {
        console.log('Map already initialized');
      }
    }
  }

  // Initialize the map
  function initializeMap() {
    console.log('🗺️ Initializing map...');
    
    // Check if Leaflet is loaded
    if (typeof L === 'undefined') {
      console.error('❌ Leaflet library not loaded!');
      alert('Map library not loaded. Please refresh the page.');
      return;
    }
    
    console.log('✅ Leaflet library loaded successfully');
    
    // Get user location
    const userLat = {{ auth()->user()->lat ?? 'null' }};
    const userLng = {{ auth()->user()->lng ?? 'null' }};
    
    // Default center (Dar es Salaam)
    let centerLat = -6.7924;
    let centerLng = 39.2083;
    let zoom = 12;

    // Use user location if available
    if (userLat && userLng) {
      centerLat = userLat;
      centerLng = userLng;
      zoom = 13;
    }

    console.log('📍 Map center:', centerLat, centerLng, 'Zoom:', zoom);

    try {
      // Initialize map
      map = L.map('map').setView([centerLat, centerLng], zoom);
      console.log('✅ Map object created successfully');

      // Add OpenStreetMap tiles
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(map);
      
      console.log('✅ Map tiles added successfully');
    } catch (error) {
      console.error('❌ Error creating map:', error);
      alert('Error creating map: ' + error.message);
      return;
    }
    
    console.log('✅ Map initialized successfully');

    // Add user location marker if available
    if (userLat && userLng) {
      console.log('📍 Adding user location marker...');
      userLocation = L.marker([userLat, userLng], {
        icon: L.divIcon({
          className: 'user-location-marker',
          html: '<div style="background: #3b82f6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
          iconSize: [20, 20],
          iconAnchor: [10, 10]
        })
      }).addTo(map);

      userLocation.bindPopup(`
        <div style="text-align: center; padding: 8px;">
          <strong>📍 Eneo Lako</strong><br>
          <small>Hapa ndipo unapoishi</small>
        </div>
      `);
      console.log('✅ User location marker added');
    }

    // Add a simple test marker first to verify map is working
    console.log('🧪 Adding simple test marker to verify map functionality...');
    try {
      const testMarker = L.marker([-6.7924, 39.2083]).addTo(map);
      testMarker.bindPopup('Test Marker - Map is working!');
      jobMarkers.push(testMarker);
      console.log('✅ Simple test marker added successfully');
    } catch (error) {
      console.error('❌ Error adding test marker:', error);
    }
    
    // Add real job markers
    addJobMarkers();
    
    // Add sample markers only if no real jobs found
    setTimeout(() => {
      if (jobMarkers.length <= 1) { // Only test marker
        console.log('⚠️ No real jobs found, adding sample markers for demonstration...');
        addColoredJobMarkers();
      } else {
        console.log('✅ Real jobs found, skipping sample markers');
        // Clear any existing sample markers
        clearSampleMarkers();
      }
    }, 1000);
    
    // Add fallback markers with real coordinates from database
    addFallbackJobMarkers();
    
    // Also add some additional markers to ensure visibility
    addAdditionalJobMarkers();
  }

  // Add colored job markers (guaranteed to show)
  function addColoredJobMarkers() {
    console.log('🎨 Adding colored job markers...');
    
    try {
      // Green marker - Near (≤5km)
      const greenMarker = L.marker([-6.7924, 39.2083], {
        icon: L.divIcon({
          className: 'job-marker-green',
          html: '<div style="background: #10b981; width: 30px; height: 30px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 16px; color: white; font-weight: bold;">💼</div>',
          iconSize: [30, 30],
          iconAnchor: [15, 15]
        })
      }).addTo(map);
      
      greenMarker.bindPopup(`
        <div style="padding: 12px; min-width: 200px;">
          <h3 style="margin: 0 0 8px 0; color: #065f46; font-size: 16px;">🟢 Kazi ya Karibu</h3>
          <p style="margin: 0 0 8px 0; font-size: 18px; font-weight: bold; color: #059669;">15,000 TZS</p>
          <p style="margin: 0 0 8px 0; color: #065f46; font-size: 14px;">📍 2.3km - Karibu sana</p>
          <p style="margin: 0 0 12px 0; color: #666; font-size: 13px;">Kazi ya ujenzi wa nyumba ya familia</p>
          <a href="#" style="background: #10b981; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold;">👁️ Fungua Kazi</a>
        </div>
      `);
      
      jobMarkers.push(greenMarker);
      console.log('✅ Green marker added');
      
      // Orange marker - Moderate (≤10km)
      const orangeMarker = L.marker([-6.8024, 39.2183], {
        icon: L.divIcon({
          className: 'job-marker-orange',
          html: '<div style="background: #f59e0b; width: 30px; height: 30px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 16px; color: white; font-weight: bold;">💼</div>',
          iconSize: [30, 30],
          iconAnchor: [15, 15]
        })
      }).addTo(map);
      
      orangeMarker.bindPopup(`
        <div style="padding: 12px; min-width: 200px;">
          <h3 style="margin: 0 0 8px 0; color: #92400e; font-size: 16px;">🟠 Kazi ya Umbali wa Wastani</h3>
          <p style="margin: 0 0 8px 0; font-size: 18px; font-weight: bold; color: #d97706;">25,000 TZS</p>
          <p style="margin: 0 0 8px 0; color: #92400e; font-size: 14px;">📍 7.5km - Umbali wa wastani</p>
          <p style="margin: 0 0 12px 0; color: #666; font-size: 13px;">Kazi ya ujenzi wa ofisi</p>
          <a href="#" style="background: #f59e0b; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold;">👁️ Fungua Kazi</a>
        </div>
      `);
      
      jobMarkers.push(orangeMarker);
      console.log('✅ Orange marker added');
      
      // Red marker - Far (>10km)
      const redMarker = L.marker([-6.8124, 39.2283], {
        icon: L.divIcon({
          className: 'job-marker-red',
          html: '<div style="background: #ef4444; width: 30px; height: 30px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 16px; color: white; font-weight: bold;">💼</div>',
          iconSize: [30, 30],
          iconAnchor: [15, 15]
        })
      }).addTo(map);
      
      redMarker.bindPopup(`
        <div style="padding: 12px; min-width: 200px;">
          <h3 style="margin: 0 0 8px 0; color: #dc2626; font-size: 16px;">🔴 Kazi ya Mbali</h3>
          <p style="margin: 0 0 8px 0; font-size: 18px; font-weight: bold; color: #b91c1c;">35,000 TZS</p>
          <p style="margin: 0 0 8px 0; color: #dc2626; font-size: 14px;">📍 15.2km - Mbali sana</p>
          <p style="margin: 0 0 12px 0; color: #666; font-size: 13px;">Kazi ya ujenzi wa hospitali</p>
          <a href="#" style="background: #ef4444; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold;">👁️ Fungua Kazi</a>
        </div>
      `);
      
      jobMarkers.push(redMarker);
      console.log('✅ Red marker added');
      
      // Add more markers for better visibility
      const greenMarker2 = L.marker([-6.7824, 39.1983], {
        icon: L.divIcon({
          className: 'job-marker-green',
          html: '<div style="background: #10b981; width: 30px; height: 30px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 16px; color: white; font-weight: bold;">💼</div>',
          iconSize: [30, 30],
          iconAnchor: [15, 15]
        })
      }).addTo(map);
      
      greenMarker2.bindPopup(`
        <div style="padding: 12px; min-width: 200px;">
          <h3 style="margin: 0 0 8px 0; color: #065f46; font-size: 16px;">🟢 Kazi ya Karibu 2</h3>
          <p style="margin: 0 0 8px 0; font-size: 18px; font-weight: bold; color: #059669;">12,000 TZS</p>
          <p style="margin: 0 0 8px 0; color: #065f46; font-size: 14px;">📍 1.8km - Karibu sana</p>
          <p style="margin: 0 0 12px 0; color: #666; font-size: 13px;">Kazi ya ujenzi wa shule</p>
          <a href="#" style="background: #10b981; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold;">👁️ Fungua Kazi</a>
        </div>
      `);
      
      jobMarkers.push(greenMarker2);
      console.log('✅ Green marker 2 added');
      
      console.log('🎉 Total colored markers added:', jobMarkers.length);
      
    } catch (error) {
      console.error('❌ Error adding colored markers:', error);
    }
  }

  // Add job markers to map
  function addJobMarkers() {
    console.log('🔍 Fetching real jobs from system...');
    
    // Get jobs data from PHP - try different approaches
    let jobs;
    try {
      jobs = @json($jobs->items());
      console.log('📊 Jobs data from @json($jobs->items()):', jobs);
    } catch (error) {
      console.error('❌ Error with @json($jobs->items()):', error);
      // Try alternative approach
      jobs = {!! json_encode($jobs->items()) !!};
      console.log('📊 Jobs data from json_encode:', jobs);
    }
    
    console.log('📊 Jobs count:', jobs ? jobs.length : 'undefined');
    console.log('📊 Jobs type:', typeof jobs);
    
    // Convert object to array if needed
    let jobsArray = [];
    if (Array.isArray(jobs)) {
      jobsArray = jobs;
    } else if (jobs && typeof jobs === 'object') {
      jobsArray = Object.values(jobs);
    }
    
    console.log('📊 Jobs array:', jobsArray);
    console.log('📊 Jobs array count:', jobsArray.length);
    
    if (!jobsArray || jobsArray.length === 0) {
      console.log('⚠️ No real jobs found in system');
      return;
    }
    
    console.log('✅ Found', jobsArray.length, 'jobs in system');
    
    let realJobMarkers = [];
    
    jobsArray.forEach((job, index) => {
      console.log(`🔄 Processing real job ${index + 1}:`, job);
      console.log(`📍 Job coordinates - Lat: ${job.lat}, Lng: ${job.lng}`);
      
      // Check if job has valid coordinates
      if (job.lat && job.lng && job.lat !== 0 && job.lng !== 0) {
        console.log(`✅ Job ${job.title} has valid coordinates`);
        const distanceInfo = job.distance_info || {};
        const category = distanceInfo.category || 'unknown';
        
        console.log(`📍 Job: ${job.title} - Category: ${category}, Distance: ${distanceInfo.distance}km`);
        
        // Get marker color based on distance category
        let markerColor = '#6b7280'; // default gray
        let markerClass = 'job-marker-unknown';
        
        if (category === 'near') {
          markerColor = '#10b981'; // green
          markerClass = 'job-marker-green';
        } else if (category === 'moderate') {
          markerColor = '#f59e0b'; // orange
          markerClass = 'job-marker-orange';
        } else if (category === 'far') {
          markerColor = '#ef4444'; // red
          markerClass = 'job-marker-red';
        } else if (category === 'no_user_location') {
          markerColor = '#f59e0b'; // orange
          markerClass = 'job-marker-orange';
        } else if (category === 'no_job_location') {
          markerColor = '#ef4444'; // red
          markerClass = 'job-marker-red';
        }

        // Create custom marker icon with larger size for better visibility
        const markerIcon = L.divIcon({
          className: markerClass,
          html: `
            <div style="
              background: ${markerColor};
              width: 32px;
              height: 32px;
              border-radius: 50%;
              border: 4px solid white;
              box-shadow: 0 4px 8px rgba(0,0,0,0.3);
              display: flex;
              align-items: center;
              justify-content: center;
              font-size: 16px;
              color: white;
              font-weight: bold;
              z-index: 1000;
            ">💼</div>
          `,
          iconSize: [32, 32],
          iconAnchor: [16, 16]
        });

        // Create marker with real job coordinates
        console.log(`🎯 Creating marker for job: ${job.title} at [${job.lat}, ${job.lng}]`);
        const marker = L.marker([parseFloat(job.lat), parseFloat(job.lng)], { 
          icon: markerIcon 
        }).addTo(map);
        console.log(`✅ Marker created and added to map for job: ${job.title}`);

        // Create popup content with real job data
        const popupContent = `
          <div class="map-popup" style="min-width: 250px;">
            <div class="job-title" style="font-size: 16px; font-weight: bold; margin-bottom: 8px; color: #1f2937;">
              ${job.title || 'Kazi'}
            </div>
            <div class="job-price" style="font-size: 18px; font-weight: bold; margin-bottom: 8px; color: ${markerColor};">
              ${new Intl.NumberFormat('en-TZ').format(job.price || 0)} TZS
            </div>
            <div class="job-distance" style="color: ${distanceInfo.text_color || '#6b7280'}; margin-bottom: 8px; font-size: 14px;">
              <span>📍</span>
              ${distanceInfo.distance ? distanceInfo.distance + 'km' : distanceInfo.label || 'N/A'}
            </div>
            <div class="job-category" style="margin-bottom: 8px; font-size: 12px; color: #6b7280;">
              📂 ${job.category ? job.category.name : 'Kazi'}
            </div>
            ${job.description ? `
              <div class="job-description" style="margin-bottom: 12px; font-size: 13px; color: #4b5563; line-height: 1.4;">
                ${job.description.substring(0, 120)}${job.description.length > 120 ? '...' : ''}
              </div>
            ` : ''}
            <div class="popup-actions">
              <a href="/jobs/${job.id}" class="btn btn-primary" style="background: ${markerColor}; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-block;">
                👁️ Fungua Kazi
              </a>
            </div>
          </div>
        `;

        marker.bindPopup(popupContent);
        realJobMarkers.push(marker);
        
        console.log(`✅ Added real job marker: ${job.title} at [${job.lat}, ${job.lng}]`);
      } else {
        console.log(`❌ Skipping job ${job.title} - Invalid coordinates: lat=${job.lat}, lng=${job.lng}`);
      }
    });

    // Add real job markers to the main jobMarkers array
    jobMarkers.push(...realJobMarkers);
    
    console.log(`🎉 Total real job markers added: ${realJobMarkers.length}`);
    console.log(`📊 Total markers on map: ${jobMarkers.length}`);

    // Fit map to show all real job markers
    if (realJobMarkers.length > 0) {
      const group = new L.featureGroup(realJobMarkers);
      if (userLocation) {
        group.addLayer(userLocation);
      }
      map.fitBounds(group.getBounds().pad(0.1));
      console.log('🗺️ Map bounds fitted to real job markers');
    } else {
      console.log('⚠️ No valid real job markers to fit bounds');
    }
  }

  // Clear sample markers when real jobs are loaded
  function clearSampleMarkers() {
    console.log('🧹 Clearing sample markers...');
    // Remove markers that are not real jobs (sample markers)
    jobMarkers.forEach((marker, index) => {
      if (marker.options && marker.options.icon && marker.options.icon.options) {
        const className = marker.options.icon.options.className;
        if (className && className.includes('test-marker')) {
          map.removeLayer(marker);
          jobMarkers.splice(index, 1);
        }
      }
    });
    console.log('✅ Sample markers cleared');
  }

  // Add fallback job markers using real data from PHP
  function addFallbackJobMarkers() {
    console.log('🔄 Adding fallback job markers with real coordinates...');
    
    // Get real job data directly from PHP
    const realJobs = {!! json_encode($jobs->items()) !!};
    
    console.log('📊 Fallback jobs data:', realJobs);
    
    if (!realJobs || realJobs.length === 0) {
      console.log('⚠️ No jobs available for fallback markers');
      return;
    }
    
    let fallbackMarkers = [];
    
    realJobs.forEach((job, index) => {
      if (job.lat && job.lng && job.lat !== 0 && job.lng !== 0) {
        console.log(`📍 Fallback job ${index + 1}: ${job.title} at [${job.lat}, ${job.lng}]`);
        
        // Get distance info for color coding
        const distanceInfo = job.distance_info || {};
        const category = distanceInfo.category || 'unknown';
        
        // Determine marker color based on distance category
        let markerColor = '#6b7280'; // default gray
        let markerClass = 'fallback-job-marker';
        
        if (category === 'near') {
          markerColor = '#10b981'; // green
          markerClass = 'fallback-job-marker-green';
        } else if (category === 'moderate') {
          markerColor = '#f59e0b'; // orange
          markerClass = 'fallback-job-marker-orange';
        } else if (category === 'far') {
          markerColor = '#ef4444'; // red
          markerClass = 'fallback-job-marker-red';
        } else if (category === 'no_user_location') {
          markerColor = '#f59e0b'; // orange
          markerClass = 'fallback-job-marker-orange';
        } else if (category === 'no_job_location') {
          markerColor = '#ef4444'; // red
          markerClass = 'fallback-job-marker-red';
        }
        
        console.log(`🎨 Job ${job.title} - Category: ${category}, Color: ${markerColor}`);
        
        // Create marker with real coordinates and color coding
        const marker = L.marker([parseFloat(job.lat), parseFloat(job.lng)], {
          icon: L.divIcon({
            className: markerClass,
            html: `
              <div style="
                background: ${markerColor};
                width: 32px;
                height: 32px;
                border-radius: 50%;
                border: 4px solid white;
                box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                color: white;
                font-weight: bold;
                z-index: 1000;
              ">💼</div>
            `,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
          })
        }).addTo(map);
        
        // Create popup with real job data
        const popupContent = `
          <div style="padding: 12px; min-width: 220px;">
            <h3 style="margin: 0 0 8px 0; color: #1f2937; font-size: 16px; font-weight: bold;">
              ${job.title || 'Kazi'}
            </h3>
            <p style="margin: 0 0 8px 0; font-size: 18px; font-weight: bold; color: ${markerColor};">
              ${new Intl.NumberFormat('en-TZ').format(job.price || 0)} TZS
            </p>
            <p style="margin: 0 0 8px 0; color: ${distanceInfo.text_color || '#6b7280'}; font-size: 14px; font-weight: 600;">
              📍 ${distanceInfo.distance ? distanceInfo.distance + 'km' : distanceInfo.label || 'Umbali haujulikani'}
            </p>
            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px;">
              📂 ${job.category ? job.category.name : 'Kazi'}
            </p>
            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 11px;">
              📍 ${job.lat}, ${job.lng}
            </p>
            ${job.description ? `
              <p style="margin: 0 0 12px 0; color: #4b5563; font-size: 13px; line-height: 1.4;">
                ${job.description.substring(0, 100)}${job.description.length > 100 ? '...' : ''}
              </p>
            ` : ''}
            <a href="/jobs/${job.id}" style="background: ${markerColor}; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-block;">
              👁️ Fungua Kazi
            </a>
          </div>
        `;
        
        marker.bindPopup(popupContent);
        fallbackMarkers.push(marker);
        
        console.log(`✅ Fallback marker added for job: ${job.title}`);
      }
    });
    
    // Add fallback markers to main array
    jobMarkers.push(...fallbackMarkers);
    
    console.log(`🎉 Total fallback markers added: ${fallbackMarkers.length}`);
    
    // Fit map to show all fallback markers
    if (fallbackMarkers.length > 0) {
      const group = new L.featureGroup(fallbackMarkers);
      if (userLocation) {
        group.addLayer(userLocation);
      }
      map.fitBounds(group.getBounds().pad(0.1));
      console.log('🗺️ Map bounds fitted to fallback markers');
    }
  }

  // Add additional job markers to ensure visibility
  function addAdditionalJobMarkers() {
    console.log('➕ Adding additional job markers for better visibility...');
    
    // Get all jobs from database (not just paginated ones)
    const allJobsData = {!! json_encode(\App\Models\Job::where('status', 'posted')->with('category')->get()) !!};
    
    console.log('📊 All jobs data:', allJobsData);
    console.log('📊 All jobs type:', typeof allJobsData);
    
    // Convert to array if needed
    let allJobs = [];
    if (Array.isArray(allJobsData)) {
      allJobs = allJobsData;
    } else if (allJobsData && typeof allJobsData === 'object') {
      allJobs = Object.values(allJobsData);
    }
    
    console.log('📊 All jobs array:', allJobs);
    console.log('📊 All jobs count:', allJobs ? allJobs.length : 'undefined');
    
    if (!allJobs || allJobs.length === 0) {
      console.log('⚠️ No jobs available for additional markers');
      return;
    }
    
    let additionalMarkers = [];
    
    allJobs.forEach((job, index) => {
      if (job.lat && job.lng && job.lat !== 0 && job.lng !== 0) {
        console.log(`📍 Additional job ${index + 1}: ${job.title} at [${job.lat}, ${job.lng}]`);
        
        // Calculate distance if user has location
        const userLat = {{ auth()->user()->lat ?? 'null' }};
        const userLng = {{ auth()->user()->lng ?? 'null' }};
        
        let distanceInfo = {
          distance: null,
          category: 'unknown',
          text_color: '#6b7280',
          label: 'Umbali haujulikani'
        };
        
        if (userLat && userLng) {
          // Simple distance calculation
          const distance = Math.sqrt(Math.pow(job.lat - userLat, 2) + Math.pow(job.lng - userLng, 2)) * 111; // Rough km conversion
          distanceInfo.distance = Math.round(distance * 10) / 10;
          
          if (distance <= 5) {
            distanceInfo.category = 'near';
            distanceInfo.text_color = '#065f46';
            distanceInfo.label = 'Karibu sana';
          } else if (distance <= 10) {
            distanceInfo.category = 'moderate';
            distanceInfo.text_color = '#92400e';
            distanceInfo.label = 'Umbali wa wastani';
          } else {
            distanceInfo.category = 'far';
            distanceInfo.text_color = '#dc2626';
            distanceInfo.label = 'Mbali sana';
          }
        }
        
        // Determine marker color
        let markerColor = '#6b7280';
        if (distanceInfo.category === 'near') markerColor = '#10b981';
        else if (distanceInfo.category === 'moderate') markerColor = '#f59e0b';
        else if (distanceInfo.category === 'far') markerColor = '#ef4444';
        
        console.log(`🎨 Additional job ${job.title} - Category: ${distanceInfo.category}, Distance: ${distanceInfo.distance}km`);
        
        // Create marker
        const marker = L.marker([parseFloat(job.lat), parseFloat(job.lng)], {
          icon: L.divIcon({
            className: 'additional-job-marker',
            html: `
              <div style="
                background: ${markerColor};
                width: 30px;
                height: 30px;
                border-radius: 50%;
                border: 3px solid white;
                box-shadow: 0 3px 6px rgba(0,0,0,0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                color: white;
                font-weight: bold;
                z-index: 1000;
              ">💼</div>
            `,
            iconSize: [30, 30],
            iconAnchor: [15, 15]
          })
        }).addTo(map);
        
        // Create popup
        const popupContent = `
          <div style="padding: 12px; min-width: 200px;">
            <h3 style="margin: 0 0 8px 0; color: #1f2937; font-size: 15px; font-weight: bold;">
              ${job.title || 'Kazi'}
            </h3>
            <p style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold; color: ${markerColor};">
              ${new Intl.NumberFormat('en-TZ').format(job.price || 0)} TZS
            </p>
            <p style="margin: 0 0 8px 0; color: ${distanceInfo.text_color}; font-size: 13px; font-weight: 600;">
              📍 ${distanceInfo.distance ? distanceInfo.distance + 'km - ' + distanceInfo.label : distanceInfo.label}
            </p>
            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 11px;">
              📂 ${job.category ? job.category.name : 'Kazi'}
            </p>
            ${job.description ? `
              <p style="margin: 0 0 12px 0; color: #4b5563; font-size: 12px; line-height: 1.3;">
                ${job.description.substring(0, 80)}${job.description.length > 80 ? '...' : ''}
              </p>
            ` : ''}
            <a href="/jobs/${job.id}" style="background: ${markerColor}; color: white; padding: 6px 12px; text-decoration: none; border-radius: 5px; font-size: 12px; font-weight: bold; display: inline-block;">
              👁️ Fungua Kazi
            </a>
          </div>
        `;
        
        marker.bindPopup(popupContent);
        additionalMarkers.push(marker);
        
        console.log(`✅ Additional marker added for job: ${job.title}`);
      }
    });
    
    // Add to main markers array
    jobMarkers.push(...additionalMarkers);
    
    console.log(`🎉 Total additional markers added: ${additionalMarkers.length}`);
    console.log(`📊 Total markers on map: ${jobMarkers.length}`);
    
    // Fit map to show all additional markers
    if (additionalMarkers.length > 0) {
      const group = new L.featureGroup(additionalMarkers);
      if (userLocation) {
        group.addLayer(userLocation);
      }
      map.fitBounds(group.getBounds().pad(0.1));
      console.log('🗺️ Map bounds fitted to additional markers');
    }
  }

  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate job cards on scroll
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    // Observe all job cards
    document.querySelectorAll('.job-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s ease';
      observer.observe(card);
    });
  });
</script>
@endsection
