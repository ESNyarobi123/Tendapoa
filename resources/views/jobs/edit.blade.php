@extends('layouts.app')
@section('title', 'Badilisha Kazi')

@section('content')
<style>
  /* ====== Job Edit Page ====== */
  .job-edit-page {
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

  .job-edit-page {
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
    margin: 0 0 24px 0;
  }

  /* Form */
  .form-container {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .form-group {
    margin-bottom: 24px;
  }

  .form-label {
    display: block;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
    font-size: 0.875rem;
  }

  .form-input {
    width: 100%;
    padding: 12px 16px;
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

  .form-textarea {
    min-height: 120px;
    resize: vertical;
  }

  .form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    background-size: 16px;
    padding-right: 40px;
  }

  /* Price Section */
  .price-section {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #0ea5e9;
    border-radius: 16px;
    padding: 24px;
    margin: 24px 0;
    position: relative;
    overflow: hidden;
  }

  .price-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #0ea5e9, #3b82f6, #8b5cf6);
  }

  .price-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
  }

  .price-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
  }

  .current-price {
    background: white;
    border: 2px dashed #0ea5e9;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    margin-bottom: 16px;
  }

  .current-price-label {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-bottom: 4px;
  }

  .current-price-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #0ea5e9;
  }

  .price-warning {
    background: rgba(14, 165, 233, 0.1);
    border-radius: 12px;
    padding: 16px;
    margin-top: 16px;
  }

  .price-warning p {
    margin: 0;
    color: #0c4a6e;
    font-weight: 600;
    font-size: 0.9rem;
    line-height: 1.5;
  }

  /* Location Section */
  .location-section {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border: 2px solid #10b981;
    border-radius: 16px;
    padding: 24px;
    margin: 24px 0;
    position: relative;
    overflow: hidden;
  }

  .location-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #10b981, #059669);
  }

  .location-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
  }

  .location-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
  }

  .location-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 12px;
    align-items: end;
  }

  .location-warning {
    background: rgba(16, 185, 129, 0.1);
    border-radius: 12px;
    padding: 16px;
    margin-top: 16px;
  }

  .location-warning p {
    margin: 0;
    color: #065f46;
    font-weight: 600;
    font-size: 0.9rem;
    line-height: 1.5;
  }

  /* Buttons */
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
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

  .btn-outline {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
  }

  .btn-outline:hover {
    background: var(--primary);
    color: white;
  }

  .btn-danger {
    background: linear-gradient(135deg, var(--danger), #dc2626);
    color: white;
    box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.4);
  }

  .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px 0 rgba(239, 68, 68, 0.6);
  }

  .form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
  }

  /* Error Messages */
  .error-message {
    color: var(--danger);
    font-size: 0.875rem;
    margin-top: 4px;
    font-weight: 500;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .job-edit-page {
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
    
    .location-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
    
    .form-actions {
      flex-direction: column;
    }
  }
</style>

<div class="job-edit-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <h1 class="page-title">✏️ Badilisha Kazi</h1>
      <p class="page-subtitle">Badilisha maelezo ya kazi yako. Bei inaweza kuongezwa tu, sio kupunguzwa.</p>
    </div>

    <!-- Form -->
    <div class="form-container">
      <form method="POST" action="{{ route('jobs.update', $job) }}" id="editJobForm">
        @csrf
        @method('PUT')

        <!-- Job Title -->
        <div class="form-group">
          <label for="title" class="form-label">Jina la Kazi</label>
          <input type="text" id="title" name="title" class="form-input" 
                 value="{{ old('title', $job->title) }}" required maxlength="120">
          @error('title')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <!-- Category -->
        <div class="form-group">
          <label for="category_id" class="form-label">Aina ya Kazi</label>
          <select id="category_id" name="category_id" class="form-input form-select" required>
            <option value="">Chagua aina ya kazi</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" 
                      {{ old('category_id', $job->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
          @error('category_id')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <!-- Description -->
        <div class="form-group">
          <label for="description" class="form-label">Maelezo ya Kazi</label>
          <textarea id="description" name="description" class="form-input form-textarea" 
                    placeholder="Eleza kazi unayotaka kufanywa...">{{ old('description', $job->description) }}</textarea>
          @error('description')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <!-- Price Section -->
        <div class="price-section">
          <div class="price-header">
            <span>💰</span>
            <h3>Bei ya Kazi</h3>
          </div>
          
          <div class="current-price">
            <div class="current-price-label">Bei ya Sasa</div>
            <div class="current-price-value">{{ number_format($job->price) }} TZS</div>
          </div>

          <div class="form-group">
            <label for="price" class="form-label">Bei Mpya (TZS)</label>
            <input type="number" id="price" name="price" class="form-input" 
                   value="{{ old('price', $job->price) }}" required min="{{ $job->price }}" 
                   step="100" placeholder="Weka bei mpya">
            @error('price')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>

          <div class="price-warning">
            <p>
              <strong>⚠️ Kumbuka:</strong><br>
              Unaweza kuongeza bei tu, sio kupunguza. Ikiwa utaongeza bei, utalipia tofauti tu.
            </p>
          </div>
        </div>

        <!-- Location Section -->
        <div class="location-section">
          <div class="location-header">
            <span>📍</span>
            <h3>Eneo la Kazi</h3>
          </div>

          <div class="location-grid">
            <div class="form-group">
              <label for="lat" class="form-label">Latitude</label>
              <input type="number" id="lat" name="lat" class="form-input" 
                     value="{{ old('lat', $job->lat) }}" required step="any" 
                     min="-90" max="90" placeholder="Latitude">
              @error('lat')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group">
              <label for="lng" class="form-label">Longitude</label>
              <input type="number" id="lng" name="lng" class="form-input" 
                     value="{{ old('lng', $job->lng) }}" required step="any" 
                     min="-180" max="180" placeholder="Longitude">
              @error('lng')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>

            <button type="button" id="gps" class="btn btn-outline">
              📍 GPS
            </button>
          </div>

          <div class="form-group">
            <label for="address_text" class="form-label">Eneo (Maandishi)</label>
            <input type="text" id="address_text" name="address_text" class="form-input" 
                   value="{{ old('address_text', $job->address_text) }}" 
                   placeholder="Mfano: Mwenge, Dar es Salaam">
            @error('address_text')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>

          <div class="location-warning">
            <p>
              <strong>📍 Kumbuka:</strong><br>
              Eneo ni muhimu kwa wafanyakazi kujua umbali wa kazi. Tumia GPS au weka coordinates sahihi.
            </p>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <a href="{{ route('my.jobs') }}" class="btn btn-outline">
            <span>❌</span>
            Ghairi
          </a>
          <button type="submit" class="btn btn-primary">
            <span>💾</span>
            Hifadhi Mabadiliko
          </button>
        </div>

      </form>
    </div>

  </div>
</div>

<script>
  // GPS Location
  document.getElementById('gps').addEventListener('click', function() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        document.getElementById('lat').value = position.coords.latitude.toFixed(7);
        document.getElementById('lng').value = position.coords.longitude.toFixed(7);
        
        // Show success notification
        showNotification('Eneo limepatikana!', 'success');
      }, function(error) {
        console.error('Error getting location:', error);
        showNotification('Imeshindwa kupata eneo. Tafadhali jaribu tena.', 'error');
      });
    } else {
      showNotification('GPS haijafungwa kwenye kivinjari chako.', 'error');
    }
  });

  // Price validation
  document.getElementById('price').addEventListener('input', function() {
    const currentPrice = {{ $job->price }};
    const newPrice = parseInt(this.value);
    
    if (newPrice < currentPrice) {
      this.style.borderColor = '#ef4444';
      showNotification('Huwezi kupunguza bei. Unaweza kuongeza tu.', 'error');
    } else if (newPrice > currentPrice) {
      this.style.borderColor = '#10b981';
      const difference = newPrice - currentPrice;
      showNotification(`Utalipia ziada: ${difference.toLocaleString()} TZS`, 'info');
    } else {
      this.style.borderColor = '#e5e7eb';
    }
  });

  // Form validation
  document.getElementById('editJobForm').addEventListener('submit', function(e) {
    const currentPrice = {{ $job->price }};
    const newPrice = parseInt(document.getElementById('price').value);
    
    if (newPrice < currentPrice) {
      e.preventDefault();
      showNotification('Huwezi kupunguza bei ya kazi!', 'error');
      return false;
    }
  });

  // Show notification
  function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
      color: white;
      padding: 16px 24px;
      border-radius: 12px;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      z-index: 1000;
      font-weight: 600;
      transform: translateX(100%);
      transition: transform 0.3s ease;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
      notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
      notification.style.transform = 'translateX(100%)';
      setTimeout(() => {
        document.body.removeChild(notification);
      }, 300);
    }, 3000);
  }
</script>
@endsection
