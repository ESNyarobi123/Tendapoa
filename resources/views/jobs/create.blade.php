@extends('layouts.app')
@section('title', 'Chapisha Kazi')

@section('content')
<style>
  /* ====== Modern Job Creation Form ====== */
  .create-job-page {
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

  .create-job-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .page-container {
    max-width: 800px;
    margin: 0 auto;
  }

  /* Header */
  .page-header {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.2);
    text-align: center;
  }

  .page-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, var(--primary), var(--success));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .page-subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
    margin: 0;
  }

  /* Form */
  .form-container {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .form-group {
    margin-bottom: 24px;
  }

  .form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .form-input {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
  }

  .form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  .form-select {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .form-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  .form-textarea {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    min-height: 120px;
    resize: vertical;
    transition: all 0.3s ease;
  }

  .form-textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  /* Map Section */
  .map-section {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow);
    margin: 24px 0;
  }

  .map-container {
    height: 320px;
    position: relative;
  }

  .map-controls {
    padding: 20px;
    background: #f8fafc;
    border-top: 1px solid var(--border);
  }

  .map-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
  }

  .map-info-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary), var(--success));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
  }

  .map-info-text {
    flex: 1;
  }

  .map-info-text h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0 0 4px 0;
  }

  .map-info-text p {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
  }

  .map-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  /* Buttons */
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

  .btn-success {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
    box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.4);
  }

  .btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.6);
  }

  .btn-outline {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
  }

  .btn-outline:hover {
    background: var(--primary);
    color: white;
  }

  /* Error Messages */
  .alert-error {
    background: #fef2f2;
    border: 2px solid #fecaca;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
    color: #dc2626;
  }

  .alert-error b {
    font-weight: 700;
    margin-bottom: 8px;
    display: block;
  }

  .alert-error ul {
    margin: 0;
    padding-left: 20px;
  }

  .alert-error li {
    margin-bottom: 4px;
  }

  /* Form Actions */
  .form-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .create-job-page {
      padding: 16px;
    }
    
    .page-header {
      padding: 24px;
    }
    
    .page-title {
      font-size: 2rem;
    }
    
    .form-container {
      padding: 24px;
    }
    
    .map-actions {
      flex-direction: column;
    }
    
    .form-actions {
      flex-direction: column;
    }
  }
</style>

<div class="create-job-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <h1 class="page-title">📝 Chapisha Kazi</h1>
      <p class="page-subtitle">Pata mfanyakazi wa kuegemea kwa kazi yako ya usafi</p>
    </div>

    <!-- Form -->
    <div class="form-container">
      @if($errors->any())
        <div class="alert-error">
          <b>⚠️ Angalia makosa:</b>
          <ul>
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="post" action="{{ route('jobs.store') }}">
        @csrf

        <!-- Job Title -->
        <div class="form-group">
          <label class="form-label" for="title">📋 Kichwa cha Kazi</label>
          <input 
            type="text" 
            id="title"
            name="title" 
            class="form-input"
            maxlength="120" 
            placeholder="Mf: Usafi wa sebuleni & vyoo"
            value="{{ old('title') }}"
            required
          >
        </div>

        <!-- Category -->
        <div class="form-group">
          <label class="form-label" for="category_id">🏷️ Aina ya Huduma</label>
          <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Chagua aina ya huduma</option>
            @foreach(\App\Models\Category::all() as $c)
              <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Price -->
        <div class="form-group">
          <label class="form-label" for="price">💰 Bei (TZS)</label>
          <input 
            type="number" 
            id="price"
            name="price" 
            class="form-input"
            min="500" 
            step="100" 
            placeholder="mf. 15000"
            value="{{ old('price') }}"
            required
          >
          <small style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px; display: block;">
            Bei ya chini ni TZS 500. Bei bora hupata mfanyakazi wa kuegemea.
          </small>
        </div>

        <!-- Phone -->
        <div class="form-group">
          <label class="form-label" for="phone">📱 Namba ya Malipo</label>
          <input 
            type="tel" 
            id="phone"
            name="phone" 
            class="form-input"
            placeholder="07xxxxxxxx au 2557xxxxxxxx"
            value="{{ old('phone') }}"
            required
          >
          <small style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px; display: block;">
            Namba hii itatumika kwa malipo ya escrow.
          </small>
        </div>

        <!-- Map Section -->
        <div class="map-section">
          <div class="map-container">
            <div id="map" style="height: 100%; width: 100%;"></div>
          </div>
          <div class="map-controls">
            <div class="map-info">
              <div class="map-info-icon">📍</div>
              <div class="map-info-text">
                <h4>Eneo la Kazi (Lazima)</h4>
                <p>Weka pini eneo la kazi au tumia GPS. Hii ni lazima ili wafanyakazi waweze kuona umbali wa kazi.</p>
              </div>
            </div>
            <div class="map-actions">
              <button type="button" id="geo" class="btn btn-success">
                <span>🎯</span>
                Tumia GPS
              </button>
              <input 
                type="text" 
                name="address_text" 
                class="form-input"
                placeholder="Maelezo ya eneo (hiari)"
                value="{{ old('address_text') }}"
                style="flex: 1; margin: 0;"
              >
            </div>
            <div id="location-status" style="margin-top: 12px; padding: 12px; border-radius: 8px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; display: none;">
              <strong>⚠️ Lazima uweke eneo la kazi!</strong> Wafanyakazi wataweza kuona umbali wa kazi kutoka kwenye eneo lako.
            </div>
          </div>
        </div>

        <!-- Hidden inputs for coordinates -->
        <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
        <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">

        <!-- Form Actions -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">
            <span>🚀</span>
            Endelea → Checkout
          </button>
          <a href="{{ route('dashboard') }}" class="btn btn-outline">
            <span>↩️</span>
            Rudi Dashboard
          </a>
        </div>
      </form>
    </div>

  </div>
</div>

@push('scripts')
<script>
  // Initialize map
  const map = L.map('map').setView([-6.7924, 39.2083], 12); // DSM default
  
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
  }).addTo(map);

  let marker;
  let isLocationSet = false;

  // Set marker on map click
  function setMarker(lat, lng) {
    if (marker) {
      map.removeLayer(marker);
    }
    
    marker = L.marker([lat, lng]).addTo(map);
    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;
    isLocationSet = true;
  }

  // Map click handler
  map.on('click', function(e) {
    setMarker(e.latlng.lat, e.latlng.lng);
  });

  // GPS button handler
  document.getElementById('geo').addEventListener('click', function() {
    if (navigator.geolocation) {
      this.innerHTML = '<span>⏳</span> Inasubiri GPS...';
      this.disabled = true;
      
      navigator.geolocation.getCurrentPosition(
        function(position) {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          
          setMarker(lat, lng);
          map.setView([lat, lng], 15);
          
          document.getElementById('geo').innerHTML = '<span>✅</span> GPS Imepatikana';
          setTimeout(() => {
            document.getElementById('geo').innerHTML = '<span>🎯</span> Tumia GPS';
            document.getElementById('geo').disabled = false;
          }, 2000);
        },
        function(error) {
          alert('GPS haijapatikana. Tafadhali weka eneo kwa mkono.');
          document.getElementById('geo').innerHTML = '<span>🎯</span> Tumia GPS';
          document.getElementById('geo').disabled = false;
        }
      );
    } else {
      alert('GPS haijasaidiwa na browser yako.');
    }
  });

  // Form validation
  document.querySelector('form').addEventListener('submit', function(e) {
    if (!isLocationSet) {
      e.preventDefault();
      document.getElementById('location-status').style.display = 'block';
      document.getElementById('map').scrollIntoView({ behavior: 'smooth' });
      return false;
    }
  });

  // Show/hide location status
  function updateLocationStatus() {
    const statusDiv = document.getElementById('location-status');
    if (isLocationSet) {
      statusDiv.style.display = 'none';
    } else {
      statusDiv.style.display = 'block';
    }
  }

  // Update status on page load
  updateLocationStatus();

  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate form elements on scroll
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

    // Observe form groups
    document.querySelectorAll('.form-group').forEach(group => {
      group.style.opacity = '0';
      group.style.transform = 'translateY(20px)';
      group.style.transition = 'all 0.6s ease';
      observer.observe(group);
    });
  });
</script>
@endpush
@endsection