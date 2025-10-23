@extends('layouts.app')
@section('title', 'Mfanyakazi — Dashibodi')

@section('content')
<style>
  /* ====== Modern Mfanyakazi Dashboard ====== */
  .mfanyakazi-dash {
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

  .mfanyakazi-dash {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    gap: 24px;
  }

  /* Hero Section */
  .hero-section {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .hero-content {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 24px;
    align-items: center;
  }

  .hero-text h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, var(--primary), var(--success));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-text p {
    color: var(--text-muted);
    font-size: 1.1rem;
    margin: 0;
  }

  .hero-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
  }

  .stat-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
  }

  .stat-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
  }

  .stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    background: linear-gradient(135deg, var(--primary), var(--success));
    color: white;
  }

  .stat-info h3 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0;
  }

  .stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin: 8px 0;
  }

  .stat-change {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.875rem;
    font-weight: 600;
  }

  .stat-change.positive {
    color: var(--success);
  }

  .stat-change.negative {
    color: var(--danger);
  }

  /* Map Section */
  .map-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .map-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  .map-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .map-container {
    height: 400px;
    border-radius: 16px;
    overflow: hidden;
    background: #f3f4f6;
    position: relative;
  }

  .map-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
  }

  /* Jobs Section */
  .jobs-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .jobs-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  .jobs-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .job-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .job-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .job-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 12px;
  }

  .job-info h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 4px 0;
  }

  .job-meta {
    display: flex;
    gap: 12px;
    align-items: center;
    color: var(--text-muted);
    font-size: 0.875rem;
  }

  .job-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
  }

  .job-status.assigned {
    background: #dbeafe;
    color: #1e40af;
  }

  .job-status.in-progress {
    background: #fef3c7;
    color: #92400e;
  }

  .job-status.ready-for-confirmation {
    background: #fce7f3;
    color: #be185d;
  }

  .job-status.completed {
    background: #d1fae5;
    color: #065f46;
  }

  .job-actions {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .job-price {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--success);
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

  /* Completion Code Modal */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
  }

  .modal-overlay.active {
    opacity: 1;
    visibility: visible;
  }

  .modal-content {
    background: white;
    border-radius: 20px;
    padding: 32px;
    max-width: 500px;
    width: 90%;
    box-shadow: var(--shadow-lg);
    transform: scale(0.9);
    transition: all 0.3s ease;
  }

  .modal-overlay.active .modal-content {
    transform: scale(1);
  }

  .modal-header {
    text-align: center;
    margin-bottom: 24px;
  }

  .modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .modal-subtitle {
    color: var(--text-muted);
    font-size: 1rem;
  }

  .completion-code {
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border: 2px dashed var(--primary);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    margin: 20px 0;
  }

  .completion-code-number {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary);
    font-family: 'Courier New', monospace;
    letter-spacing: 4px;
  }

  .completion-instructions {
    background: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: 12px;
    padding: 16px;
    margin: 16px 0;
  }

  .completion-instructions h4 {
    color: #92400e;
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 8px 0;
  }

  .completion-instructions p {
    color: #92400e;
    font-size: 0.875rem;
    margin: 0;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .mfanyakazi-dash {
      padding: 16px;
    }
    
    .hero-content {
      grid-template-columns: 1fr;
      text-align: center;
    }
    
    .hero-text h1 {
      font-size: 2rem;
    }
    
    .stats-grid {
      grid-template-columns: 1fr;
    }
    
    .job-header {
      flex-direction: column;
      gap: 12px;
    }
    
    .job-actions {
      width: 100%;
      justify-content: center;
    }
  }

  /* History Sections */
  .history-sections {
    display: grid;
    gap: 24px;
    margin-top: 32px;
  }

  .history-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .history-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
  }

  .history-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
  }

  .history-count {
    background: var(--primary);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
  }

  .history-list {
    display: grid;
    gap: 12px;
  }

  .history-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
  }

  .history-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
    background: white;
  }

  .history-icon {
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .history-details {
    flex: 1;
  }

  .history-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 4px;
  }

  .history-description {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-bottom: 8px;
  }

  .history-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.75rem;
  }

  .history-date {
    color: var(--text-muted);
  }

  .history-status {
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.7rem;
  }

  .status-paid {
    background: #d1fae5;
    color: #065f46;
  }

  .status-processing {
    background: #fef3c7;
    color: #92400e;
  }

  .status-rejected {
    background: #fecaca;
    color: #dc2626;
  }

  .history-amount {
    font-size: 1.1rem;
    font-weight: 700;
    text-align: right;
  }

  .history-amount.positive {
    color: var(--success);
  }

  .history-amount.negative {
    color: var(--danger);
  }

  .empty-history {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-muted);
  }

  .empty-icon {
    font-size: 3rem;
    margin-bottom: 16px;
    opacity: 0.5;
  }

  .empty-text {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--dark);
  }

  .empty-subtitle {
    font-size: 0.875rem;
    opacity: 0.7;
  }

  /* History Responsive */
  @media (max-width: 768px) {
    .history-item {
      flex-direction: column;
      text-align: center;
      gap: 12px;
    }

    .history-details {
      order: 2;
    }

    .history-amount {
      order: 3;
      text-align: center;
    }
  }
</style>

@php
  use Illuminate\Support\Facades\Route;
  use Illuminate\Support\Facades\Auth;

  // Numbers
  $done      = (int)($done ?? 0);
  $earned    = (int)($earnTotal ?? 0);
  $withdrawn = (int)($withdrawn ?? 0);
  $available = (int)($available ?? 0);

  // Safe URLs
  $feedUrl       = Route::has('feed') ? route('feed') : url('/feed');
  $assignedUrl   = Route::has('mfanyakazi.assigned') ? route('mfanyakazi.assigned') : url('/mfanyakazi/assigned');
  $withdrawUrl   = Route::has('withdraw.form') ? route('withdraw.form') : url('/withdraw');

  // Get user's current jobs
  $currentJobs = \App\Models\Job::with('muhitaji', 'category')
    ->where('accepted_worker_id', Auth::id())
    ->whereIn('status', ['assigned', 'in_progress', 'ready_for_confirmation'])
    ->latest()
    ->limit(5)
    ->get();

  // Calculate earnings this month
  $thisMonthEarnings = \App\Models\Job::where('accepted_worker_id', Auth::id())
    ->where('status', 'completed')
    ->where('completed_at', '>=', now()->startOfMonth())
    ->sum('price');

  // Calculate average job completion time (in days)
  $avgCompletionTime = \App\Models\Job::where('accepted_worker_id', Auth::id())
    ->where('status', 'completed')
    ->whereNotNull('completed_at')
    ->whereNotNull('created_at')
    ->selectRaw('AVG(DATEDIFF(completed_at, created_at)) as avg_days')
    ->value('avg_days') ?? 0;
@endphp

<div class="mfanyakazi-dash">
  <div class="dashboard-container">
    
    <!-- Hero Section -->
    <div class="hero-section">
      <div class="hero-content">
        <div class="hero-text">
          <h1>👋 Karibu, {{ Auth::user()->name ?? 'Mfanyakazi' }}!</h1>
          <p>Ona mapato yako, kazi zilizokamilika, na fursa mpya za kazi kwenye mfumo wa Tendapoa.</p>
        </div>
        <div class="hero-actions">
          <a href="{{ $feedUrl }}" class="btn btn-primary">
            🔍 Tafuta Kazi
          </a>
          <a href="{{ $assignedUrl }}" class="btn btn-outline">
            📋 Kazi Zangu
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon">✅</div>
          <div class="stat-info">
            <h3>Kazi Zilizokamilika</h3>
            <div class="stat-value">{{ number_format($done) }}</div>
            <div class="stat-change positive">
              <span>+{{ $thisMonthEarnings > 0 ? '1' : '0' }} mwezi huu</span>
            </div>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon">💰</div>
          <div class="stat-info">
            <h3>Mapato Yote</h3>
            <div class="stat-value">TZS {{ number_format($earned) }}</div>
            <div class="stat-change positive">
              <span>TZS {{ number_format($thisMonthEarnings) }} mwezi huu</span>
            </div>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon">💳</div>
          <div class="stat-info">
            <h3>Inapatikana</h3>
            <div class="stat-value">TZS {{ number_format($available) }}</div>
            <div class="stat-change {{ $available > 0 ? 'positive' : 'negative' }}">
              <span>{{ $available > 0 ? 'Unaweza kutoa sasa' : 'Hakuna salio' }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon">⏱️</div>
          <div class="stat-info">
            <h3>Muda wa Wastani</h3>
            <div class="stat-value">{{ number_format($avgCompletionTime, 1) }} siku</div>
            <div class="stat-change positive">
              <span>Kazi za kukamilisha</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Map and Jobs Section -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
      
      <!-- Map Section -->
      <div class="map-section">
        <div class="map-header">
          <div class="map-title">
            🗺️ Eneo la Kazi
          </div>
          <button class="btn btn-outline" onclick="getCurrentLocationAndShowJobs()">
            📍 Ona Eneo la Kazi
          </button>
        </div>
        <div class="map-container" id="mapContainer">
          <div class="map-placeholder" id="mapPlaceholder">
            <div>
              <div>🗺️</div>
              <div>Bonyeza "Ona Eneo la Kazi" ili kuona mahali pa kazi</div>
            </div>
          </div>
          <div id="map" style="height: 100%; width: 100%; display: none;"></div>
        </div>
        <div id="jobLocationInfo" style="margin-top: 12px; padding: 12px; background: #f3f4f6; border-radius: 8px; display: none;">
          <div style="font-weight: 600; color: var(--dark);">Eneo la Kazi:</div>
          <div id="jobLocationText" style="color: var(--text-muted); font-size: 0.875rem;"></div>
          <div id="distanceInfo" style="color: var(--success); font-weight: 600; margin-top: 4px;"></div>
          <div id="directionsInfo" style="margin-top: 8px;">
            <button class="btn btn-sm" onclick="getDirections()" style="background: var(--primary); color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.875rem;">
              🧭 Pata Mwelekeo
            </button>
          </div>
        </div>
      </div>

      <!-- Current Jobs -->
      <div class="jobs-section">
        <div class="jobs-header">
          <div class="jobs-title">
            📋 Kazi Zinazoendelea
          </div>
          <a href="{{ $assignedUrl }}" class="btn btn-outline">Ona Zote</a>
        </div>

        @if($currentJobs->count() > 0)
          @foreach($currentJobs as $job)
            <div class="job-card" 
                 data-job-id="{{ $job->id }}"
                 data-lat="{{ $job->lat ?? 0 }}"
                 data-lng="{{ $job->lng ?? 0 }}"
                 data-address="{{ $job->address_text ?? $job->location ?? 'Eneo haijasajiliwa' }}">
              <div class="job-header">
                <div class="job-info">
                  <h4>{{ $job->title ?? 'Kazi' }}</h4>
                  <div class="job-meta">
                    <span>📍 {{ $job->location ?? 'Eneo haijasajiliwa' }}</span>
                    <span>⏱️ {{ $job->created_at?->diffForHumans() ?? '' }}</span>
                  </div>
                </div>
                <div class="job-status {{ str_replace('_', '-', $job->status ?? 'assigned') }}">
                  {{ ucfirst(str_replace('_', ' ', $job->status ?? 'assigned')) }}
                </div>
              </div>
              
              <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 16px;">
                <div class="job-price">TZS {{ number_format($job->amount) }}</div>
                <div class="job-actions">
                  @if($job->status === 'assigned')
                    <form method="POST" action="{{ route('mfanyakazi.jobs.accept', $job->id) }}" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn btn-success">Kubali Kazi</button>
                    </form>
                  @elseif($job->status === 'in_progress')
                    <button onclick="showCodeInputModal({{ $job->id }}, '{{ $job->title }}')" class="btn btn-primary">
                      Maliza Kazi
                    </button>
                  @elseif($job->status === 'ready_for_confirmation')
                    <span style="color: var(--warning); font-weight: 600;">⏳ Inasubiri Uthibitisho</span>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div style="text-align: center; padding: 40px; color: var(--text-muted);">
            <div style="font-size: 3rem; margin-bottom: 16px;">📭</div>
            <h3 style="margin: 0 0 8px 0; color: var(--dark);">Hakuna Kazi</h3>
            <p style="margin: 0 0 16px 0;">Huna kazi zinazoendelea kwa sasa.</p>
            <a href="{{ $feedUrl }}" class="btn btn-primary">Tafuta Kazi Mpya</a>
          </div>
        @endif
      </div>
    </div>

    <!-- Quick Actions -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
      <a href="{{ $feedUrl }}" class="btn btn-primary" style="justify-content: center; padding: 16px;">
        🔍 Tafuta Kazi Mpya
      </a>
      <a href="{{ route('jobs.create-mfanyakazi') }}" class="btn btn-success" style="justify-content: center; padding: 16px;">
        📝 Chapisha Huduma
      </a>
      <a href="{{ $assignedUrl }}" class="btn btn-outline" style="justify-content: center; padding: 16px;">
        📋 Kazi Zangu
      </a>
      <a href="{{ $withdrawUrl }}" class="btn btn-warning" style="justify-content: center; padding: 16px;" {{ $available <= 0 ? 'disabled' : '' }}>
        💳 Omba Withdraw
      </a>
      <a href="{{ route('home') }}" class="btn btn-outline" style="justify-content: center; padding: 16px;">
        🏠 Nyumbani
      </a>
    </div>

  </div>
</div>

<!-- Code Input Modal -->
<div class="modal-overlay" id="codeInputModal">
  <div class="modal-content">
    <div class="modal-header">
      <div class="modal-title">✅ Kazi Imekamilika!</div>
      <div class="modal-subtitle">Omba code kutoka kwa mteja ili uthibitishe kazi</div>
    </div>
    
    <div class="completion-instructions" style="background: #dbeafe; border-color: #3b82f6;">
      <h4 style="color: #1e40af;">📋 Maagizo:</h4>
      <p style="color: #1e40af;">1. Mpe mteja code yake ya kuthibitisha kazi<br>
         2. Ingiza code hapa chini<br>
         3. Kazi itathibitishwa na utapokea malipo</p>
    </div>
    
    <form id="codeInputForm" style="margin: 20px 0;">
      <div style="margin-bottom: 16px;">
        <label style="display: block; font-weight: 600; color: var(--dark); margin-bottom: 8px;">
          Ingiza Code ya Mteja:
        </label>
        <input type="text" id="muhitajiCode" placeholder="Ingiza code ya mteja" 
               style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; 
                      font-size: 1.1rem; text-align: center; letter-spacing: 2px; font-family: monospace;"
               maxlength="6" pattern="[0-9]{6}" required>
      </div>
      
      <div style="display: flex; gap: 12px; justify-content: center;">
        <button type="button" onclick="closeCodeInputModal()" class="btn btn-outline">Funga</button>
        <button type="submit" class="btn btn-primary">Thibitisha Kazi</button>
      </div>
    </form>
  </div>
</div>

<!-- History Sections -->
<div class="history-sections" style="margin-top: 32px;">
  
  <!-- Earnings History -->
  <div class="history-section">
    <div class="history-header">
      <h3>💰 Historia ya Mapato</h3>
      <span class="history-count">{{ $earningsHistory->count() }} malipo</span>
    </div>
    
    @if($earningsHistory->count() > 0)
      <div class="history-list">
        @foreach($earningsHistory as $earning)
          <div class="history-item earnings-item">
            <div class="history-icon">💰</div>
            <div class="history-details">
              <div class="history-title">Malipo ya Kazi</div>
              <div class="history-description">{{ $earning->description ?? 'Job completion payment' }}</div>
              <div class="history-meta">
                <span class="history-date">{{ $earning->created_at->diffForHumans() }}</span>
                <span class="history-status">Imeingia</span>
              </div>
            </div>
            <div class="history-amount positive">+{{ number_format($earning->amount) }} TZS</div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-history">
        <div class="empty-icon">📊</div>
        <div class="empty-text">Hujapata mapato bado</div>
        <div class="empty-subtitle">Maliza kazi za kwanza ili uone mapato yako</div>
      </div>
    @endif
  </div>

  <!-- Withdrawals History -->
  <div class="history-section">
    <div class="history-header">
      <h3>💸 Historia ya Withdrawals</h3>
      <span class="history-count">{{ $withdrawalsHistory->count() }} maombi</span>
    </div>
    
    @if($withdrawalsHistory->count() > 0)
      <div class="history-list">
        @foreach($withdrawalsHistory as $withdrawal)
          <div class="history-item withdrawals-item" data-status="{{ strtolower($withdrawal->status) }}">
            <div class="history-icon">
              @if($withdrawal->status === 'PAID')
                ✅
              @elseif($withdrawal->status === 'PROCESSING')
                ⏳
              @elseif($withdrawal->status === 'REJECTED')
                ❌
              @else
                💸
              @endif
            </div>
            <div class="history-details">
              <div class="history-title">Withdrawal Request</div>
              <div class="history-description">
                {{ $withdrawal->network_type ? ucfirst($withdrawal->network_type) : 'Mobile Money' }} - {{ $withdrawal->account }}
              </div>
              <div class="history-meta">
                <span class="history-date">{{ $withdrawal->created_at->diffForHumans() }}</span>
                <span class="history-status status-{{ strtolower($withdrawal->status) }}">
                  {{ ucfirst($withdrawal->status) }}
                </span>
              </div>
            </div>
            <div class="history-amount negative">-{{ number_format($withdrawal->amount) }} TZS</div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-history">
        <div class="empty-icon">💸</div>
        <div class="empty-text">Hujaomba withdrawal bado</div>
        <div class="empty-subtitle">Omba withdrawal kwa kutumia pesa zako</div>
      </div>
    @endif
  </div>

  <!-- Completed Jobs History -->
  <div class="history-section">
    <div class="history-header">
      <h3>✅ Kazi Zilizokamilika</h3>
      <span class="history-count">{{ $completedJobs->count() }} kazi</span>
    </div>
    
    @if($completedJobs->count() > 0)
      <div class="history-list">
        @foreach($completedJobs as $job)
          <div class="history-item completed-jobs-item">
            <div class="history-icon">✅</div>
            <div class="history-details">
              <div class="history-title">{{ $job->title ?? 'Kazi' }}</div>
              <div class="history-description">
                Mteja: {{ $job->muhitaji->name ?? 'Unknown' }} | 
                Kategoria: {{ $job->category->name ?? 'N/A' }}
              </div>
              <div class="history-meta">
                <span class="history-date">{{ $job->completed_at->diffForHumans() }}</span>
                <span class="history-status">Imekamilika</span>
              </div>
            </div>
            <div class="history-amount positive">+{{ number_format($job->amount) }} TZS</div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-history">
        <div class="empty-icon">✅</div>
        <div class="empty-text">Hujakamilisha kazi bado</div>
        <div class="empty-subtitle">Kubali na kumaliza kazi za kwanza</div>
      </div>
    @endif
  </div>

</div>

<!-- Include Leaflet CSS and JS for OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Map functionality with OpenStreetMap
let map;
let userLocation = null;
let currentJob = null;
let userMarker = null;
let jobMarker = null;
let routeControl = null;

function showJobLocations() {
  // Get current job location from the first job in the list
  const jobCards = document.querySelectorAll('.job-card');
  if (jobCards.length === 0) {
    showNotification('Huna kazi zinazoendelea kwa sasa.', 'info');
    return;
  }
  
  // Get job data from the first job card
  const firstJobCard = jobCards[0];
  const jobId = firstJobCard.getAttribute('data-job-id');
  
  // Get job location from data attributes
  const jobLat = parseFloat(firstJobCard.getAttribute('data-lat'));
  const jobLng = parseFloat(firstJobCard.getAttribute('data-lng'));
  const jobAddress = firstJobCard.getAttribute('data-address');
  
  // Check if we have valid coordinates
  if (isNaN(jobLat) || isNaN(jobLng) || jobLat === 0 || jobLng === 0) {
    showNotification('Eneo la kazi halijasajiliwa. Tafadhali wasiliana na mteja.', 'error');
    return;
  }
  
  const jobLocation = {
    lat: jobLat,
    lng: jobLng,
    address: jobAddress || 'Eneo la kazi'
  };
  
  showJobLocationOnMap(jobLocation);
  updateJobLocationInfo(jobLocation);
}

function getCurrentLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        userLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        showLocationOnMap();
        updateLocationInfo();
      },
      function(error) {
        alert('Hauwezi kupata eneo lako. Hakikisha umeruhusu ruhusa ya eneo.');
      }
    );
  } else {
    alert('Browser yako haitumii GPS.');
  }
}

function getCurrentLocationAndShowJobs() {
  // Show loading indicator
  const button = document.querySelector('button[onclick="getCurrentLocationAndShowJobs()"]');
  const originalText = button.textContent;
  button.textContent = '⏳ Inapata eneo...';
  button.disabled = true;
  
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        userLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        showJobLocations(); // This will show both user and job locations
        button.textContent = originalText;
        button.disabled = false;
      },
      function(error) {
        console.error('Geolocation error:', error);
        showNotification('Hauwezi kupata eneo lako. Hakikisha umeruhusu ruhusa ya eneo.', 'error');
        // Still show job locations even if user location fails
        showJobLocations();
        button.textContent = originalText;
        button.disabled = false;
      }
    );
  } else {
    showNotification('Browser yako haitumii GPS.', 'error');
    // Still show job locations even if geolocation is not supported
    showJobLocations();
    button.textContent = originalText;
    button.disabled = false;
  }
}

function showLocationOnMap() {
  const mapContainer = document.getElementById('mapContainer');
  const mapPlaceholder = document.getElementById('mapPlaceholder');
  const mapDiv = document.getElementById('map');
  
  // Hide placeholder and show map
  mapPlaceholder.style.display = 'none';
  mapDiv.style.display = 'block';
  
  // Initialize OpenStreetMap
  if (!map) {
    map = L.map('map').setView([userLocation.lat, userLocation.lng], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);
  }
  
  // Add user location marker
  if (userMarker) {
    map.removeLayer(userMarker);
  }
  
  userMarker = L.marker([userLocation.lat, userLocation.lng])
    .addTo(map)
    .bindPopup('<b>📍 Eneo Lako</b><br>Hapa ulipo sasa')
    .openPopup();
  
  // Center map on user location
  map.setView([userLocation.lat, userLocation.lng], 13);
}

function showJobLocationOnMap(jobLocation) {
  const mapContainer = document.getElementById('mapContainer');
  const mapPlaceholder = document.getElementById('mapPlaceholder');
  const mapDiv = document.getElementById('map');
  
  // Hide placeholder and show map
  mapPlaceholder.style.display = 'none';
  mapDiv.style.display = 'block';
  
  // Initialize OpenStreetMap if not already done
  if (!map) {
    map = L.map('map').setView([jobLocation.lat, jobLocation.lng], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);
  }
  
  // Add job location marker
  if (jobMarker) {
    map.removeLayer(jobMarker);
  }
  
  jobMarker = L.marker([jobLocation.lat, jobLocation.lng])
    .addTo(map)
    .bindPopup('<b>🎯 Eneo la Kazi</b><br>' + jobLocation.address)
    .openPopup();
  
  // Center map on job location
  map.setView([jobLocation.lat, jobLocation.lng], 13);
  
  // If we have user location, show both markers and calculate distance
  if (userLocation) {
    if (userMarker) {
      map.removeLayer(userMarker);
    }
    
    userMarker = L.marker([userLocation.lat, userLocation.lng])
      .addTo(map)
      .bindPopup('<b>📍 Eneo Lako</b><br>Hapa ulipo sasa');
    
    // Calculate and display distance
    const distance = calculateDistance(userLocation.lat, userLocation.lng, jobLocation.lat, jobLocation.lng);
    updateDistanceInfo(distance);
    
    // Add route line between user and job location
    if (routeControl) {
      map.removeControl(routeControl);
    }
    
    const routeLine = L.polyline([
      [userLocation.lat, userLocation.lng],
      [jobLocation.lat, jobLocation.lng]
    ], {
      color: '#3b82f6',
      weight: 3,
      opacity: 0.7,
      dashArray: '10, 10'
    }).addTo(map);
    
    // Show both markers on map
    const group = new L.featureGroup([userMarker, jobMarker, routeLine]);
    map.fitBounds(group.getBounds().pad(0.1));
  }
}

function updateLocationInfo() {
  const locationInfo = document.getElementById('locationInfo');
  const locationText = document.getElementById('locationText');
  
  if (locationInfo && locationText) {
    locationInfo.style.display = 'block';
    locationText.textContent = `Latitude: ${userLocation.lat.toFixed(4)}, Longitude: ${userLocation.lng.toFixed(4)}`;
  }
}

function updateJobLocationInfo(jobLocation) {
  const jobLocationInfo = document.getElementById('jobLocationInfo');
  const jobLocationText = document.getElementById('jobLocationText');
  
  if (jobLocationInfo && jobLocationText) {
    jobLocationInfo.style.display = 'block';
    jobLocationText.textContent = jobLocation.address;
  }
}

function updateDistanceInfo(distance) {
  const distanceInfo = document.getElementById('distanceInfo');
  if (distanceInfo) {
    distanceInfo.textContent = `Umbali: ${distance.toFixed(2)} km`;
  }
}

function getDirections() {
  if (!userLocation || !jobMarker) {
    showNotification('Hakikisha umepata eneo lako na eneo la kazi.', 'error');
    return;
  }
  
  const jobLat = jobMarker.getLatLng().lat;
  const jobLng = jobMarker.getLatLng().lng;
  
  // Open directions in new tab using OpenStreetMap routing
  const directionsUrl = `https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=${userLocation.lat},${userLocation.lng};${jobLat},${jobLng}`;
  window.open(directionsUrl, '_blank');
}

// Completion modal functions
function showCompletionModal(jobId, jobTitle, completionCode) {
  document.getElementById('completionCodeNumber').textContent = completionCode;
  document.getElementById('completionModal').classList.add('active');
}

function closeCompletionModal() {
  document.getElementById('completionModal').classList.remove('active');
}

function copyCompletionCode() {
  const code = document.getElementById('completionCodeNumber').textContent;
  navigator.clipboard.writeText(code).then(function() {
    alert('Code imenakiliwa! Mpe mteja code hii.');
  });
}

// Real-time notifications
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
    color: white;
    padding: 16px 20px;
    border-radius: 12px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    z-index: 1001;
    font-weight: 600;
    max-width: 300px;
    transform: translateX(100%);
    transition: transform 0.3s ease;
  `;
  notification.textContent = message;
  document.body.appendChild(notification);
  
  // Animate in
  setTimeout(() => {
    notification.style.transform = 'translateX(0)';
  }, 100);
  
  // Auto remove after 5 seconds
  setTimeout(() => {
    notification.style.transform = 'translateX(100%)';
    setTimeout(() => {
      document.body.removeChild(notification);
    }, 300);
  }, 5000);
}

// Distance calculation function
function calculateDistance(lat1, lon1, lat2, lon2) {
  const R = 6371; // Radius of the Earth in kilometers
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon/2) * Math.sin(dLon/2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  const distance = R * c;
  return distance;
}

// Enhanced map functionality with distance calculation
function showLocationOnMap() {
  const mapContainer = document.getElementById('mapContainer');
  
  // Create a more realistic map representation
  mapContainer.innerHTML = `
    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #3b82f6, #1d4ed8); 
                display: flex; align-items: center; justify-content: center; color: white; 
                border-radius: 16px; position: relative; overflow: hidden;">
      
      <!-- Map grid pattern -->
      <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; 
                  background-image: 
                    linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
                  background-size: 20px 20px;"></div>
      
      <!-- User location marker -->
      <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                  width: 20px; height: 20px; background: #ef4444; border-radius: 50%;
                  border: 3px solid white; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3);
                  animation: pulse 2s infinite;"></div>
      
      <!-- Location info overlay -->
      <div style="position: absolute; bottom: 16px; left: 16px; right: 16px;
                  background: rgba(0,0,0,0.7); padding: 12px; border-radius: 8px;
                  backdrop-filter: blur(10px);">
        <div style="font-weight: 600; margin-bottom: 4px;">📍 Eneo Lako Limepatikana</div>
        <div style="font-size: 0.75rem; opacity: 0.8;">
          Lat: ${userLocation.lat.toFixed(4)} | Lng: ${userLocation.lng.toFixed(4)}
        </div>
      </div>
      
      <style>
        @keyframes pulse {
          0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
          70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
          100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
      </style>
    </div>
  `;
  
  showNotification('Eneo lako limepatikana! Unaweza sasa kuona umbali wa kazi.', 'success');
}

// Show job location on map
function showJobLocationOnMap(jobLocation) {
  const mapContainer = document.getElementById('mapContainer');
  
  mapContainer.innerHTML = `
    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #3b82f6, #1d4ed8); 
                display: flex; align-items: center; justify-content: center; color: white; 
                border-radius: 16px; position: relative; overflow: hidden;">
      
      <!-- Map grid pattern -->
      <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; 
                  background-image: 
                    linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
                  background-size: 20px 20px;"></div>
      
      <!-- Job location marker -->
      <div style="position: absolute; top: 30%; left: 60%; transform: translate(-50%, -50%);
                  width: 20px; height: 20px; background: #10b981; border-radius: 50%;
                  border: 3px solid white; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
                  animation: pulse 2s infinite;"></div>
      
      <!-- Your location marker -->
      <div style="position: absolute; top: 70%; left: 40%; transform: translate(-50%, -50%);
                  width: 16px; height: 16px; background: #ef4444; border-radius: 50%;
                  border: 2px solid white; box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.3);"></div>
      
      <!-- Location info overlay -->
      <div style="position: absolute; bottom: 16px; left: 16px; right: 16px;
                  background: rgba(0,0,0,0.7); padding: 12px; border-radius: 8px;
                  backdrop-filter: blur(10px);">
        <div style="font-weight: 600; margin-bottom: 4px;">📍 Eneo la Kazi</div>
        <div style="font-size: 0.75rem; opacity: 0.8;">
          ${jobLocation.address}
        </div>
      </div>
      
      <style>
        @keyframes pulse {
          0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
          70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
          100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
      </style>
    </div>
  `;
  
  showNotification('Eneo la kazi limeonyeshwa! Unaweza sasa kuenda huko.', 'success');
}

function updateJobLocationInfo(jobLocation) {
  const locationInfo = document.getElementById('jobLocationInfo');
  const locationText = document.getElementById('jobLocationText');
  const distanceInfo = document.getElementById('distanceInfo');
  
  locationInfo.style.display = 'block';
  locationText.textContent = jobLocation.address;
  
  // Calculate distance if user location is available
  if (userLocation) {
    const distance = calculateDistance(
      userLocation.lat, userLocation.lng,
      jobLocation.lat, jobLocation.lng
    );
    distanceInfo.textContent = `Umbali: ${distance.toFixed(1)} km`;
  } else {
    distanceInfo.textContent = 'Bonyeza "Pata Eneo" ili kuona umbali';
  }
}

// Code input modal functions
function showCodeInputModal(jobId, jobTitle) {
  currentJob = { id: jobId, title: jobTitle };
  document.getElementById('codeInputModal').classList.add('active');
  
  // Clear previous input
  document.getElementById('muhitajiCode').value = '';
  
  showNotification(`Kazi "${jobTitle}" imekamilika! Omba code kutoka kwa mteja.`, 'info');
}

function closeCodeInputModal() {
  document.getElementById('codeInputModal').classList.remove('active');
  currentJob = null;
}

// Auto-refresh dashboard every 30 seconds with real-time updates
setInterval(function() {
  // Check for new jobs or status updates
  fetch('/api/dashboard-updates', {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.newJobs && data.newJobs.length > 0) {
      showNotification(`Una kazi mpya ${data.newJobs.length}!`, 'info');
    }
    if (data.completedJobs && data.completedJobs.length > 0) {
      showNotification(`Kazi ${data.completedJobs.length} zimekamilika!`, 'success');
    }
  })
  .catch(error => {
    console.log('Auto-refresh error:', error);
  });
}, 30000);

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
  if (e.ctrlKey || e.metaKey) {
    switch(e.key) {
      case 'j':
        e.preventDefault();
        window.location.href = '{{ $feedUrl }}';
        break;
      case 'a':
        e.preventDefault();
        window.location.href = '{{ $assignedUrl }}';
        break;
      case 'w':
        e.preventDefault();
        window.location.href = '{{ $withdrawUrl }}';
        break;
    }
  }
});

// Add loading states for buttons
document.addEventListener('click', function(e) {
  if (e.target.matches('.btn')) {
    const btn = e.target;
    const originalText = btn.textContent;
    btn.textContent = '⏳ Inasubiri...';
    btn.disabled = true;
    
    // Re-enable after 3 seconds (in case of errors)
    setTimeout(() => {
      btn.textContent = originalText;
      btn.disabled = false;
    }, 3000);
  }
});

// Handle code input form submission
document.getElementById('codeInputForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const code = document.getElementById('muhitajiCode').value;
  if (!code || code.length !== 6) {
    showNotification('Tafadhali ingiza code ya tarakimu 6!', 'error');
    return;
  }
  
  if (!currentJob) {
    showNotification('Hitilafu: Kazi haijapatikana!', 'error');
    return;
  }
  
  // Submit the code to complete the job
  fetch(`/mfanyakazi/jobs/${currentJob.id}/complete`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ code: code })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showNotification('Kazi imethibitishwa! Utapokea malipo yako.', 'success');
      closeCodeInputModal();
      // Refresh the page to show updated status
      setTimeout(() => {
        window.location.reload();
      }, 2000);
    } else {
      showNotification(data.message || 'Code si sahihi! Angalia code uliyopewa na mteja.', 'error');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showNotification('Hitilafu imetokea! Jaribu tena.', 'error');
  });
});

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
  // Show welcome notification
  setTimeout(() => {
    showNotification('Karibu kwenye dashibodi yako! Tumia Ctrl+J kwa kazi mpya.', 'info');
  }, 1000);
  
  // Add smooth scrolling
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });
});
</script>
@endsection
