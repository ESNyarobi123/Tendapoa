@extends('layouts.app')
@section('title', 'Muhitaji — Dashibodi')

@section('content')
<style>
  /* ====== Modern Muhitaji Dashboard ====== */
  .muhitaji-dash {
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

  .muhitaji-dash {
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
    background: rgba(255,255,255,0.95);
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

  /* Progress Ring */
  .progress-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .progress-content {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 24px;
    align-items: center;
  }

  .progress-ring {
    width: 120px;
    height: 120px;
    position: relative;
  }

  .progress-ring svg {
    transform: rotate(-90deg);
    width: 100%;
    height: 100%;
  }

  .progress-ring .num {
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
    font-weight: 800;
    color: var(--dark);
    font-size: 1.5rem;
  }

  .progress-info h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .progress-info p {
    color: var(--text-muted);
    font-size: 0.875rem;
    margin: 0 0 16px 0;
  }

  .progress-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  /* Recent Jobs */
  .recent-jobs {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .recent-jobs-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  .recent-jobs-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .job-item {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .job-item:hover {
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

  .job-status.draft {
    background: #f3f4f6;
    color: #374151;
  }

  .job-status.published {
    background: #dbeafe;
    color: #1e40af;
  }

  .job-status.assigned {
    background: #fef3c7;
    color: #92400e;
  }

  .job-status.in-progress {
    background: #fce7f3;
    color: #be185d;
  }

  .job-status.completed {
    background: #d1fae5;
    color: #065f46;
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

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 60px 20px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .empty-state-icon {
    font-size: 4rem;
    margin-bottom: 16px;
    opacity: 0.6;
  }

  .empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .empty-state p {
    color: var(--text-muted);
    font-size: 1rem;
    margin: 0 0 24px 0;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .muhitaji-dash {
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
    
    .progress-content {
      grid-template-columns: 1fr;
      text-align: center;
    }
    
    .job-header {
      flex-direction: column;
      gap: 12px;
    }
  }

  /* Payment History Sections */
  .payment-history-section {
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

  .status-completed {
    background: #d1fae5;
    color: #065f46;
  }

  .status-pending {
    background: #fef3c7;
    color: #92400e;
  }

  .status-failed {
    background: #fecaca;
    color: #dc2626;
  }

  .status-in-progress {
    background: #dbeafe;
    color: #1e40af;
  }

  .status-assigned {
    background: #e0e7ff;
    color: #3730a3;
  }

  .history-amount {
    font-size: 1.1rem;
    font-weight: 700;
    text-align: right;
  }

  .history-amount.negative {
    color: var(--danger);
  }

  .amount-label {
    font-size: 0.75rem;
    color: var(--text-muted);
    display: block;
  }

  .amount-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
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

  /* Payment History Responsive */
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
  $posted = (int)($posted ?? 0);
  $completed = (int)($completed ?? 0);
  $totalPaid = (int)($totalPaid ?? 0);
  $rate = $posted > 0 ? (int)round(($completed / max($posted,1)) * 100) : 0;
  $circ = 2 * 3.14159265 * 36; // r=36 for the ring
  $dash = $circ * ($rate/100);
@endphp

<div class="muhitaji-dash">
  <div class="dashboard-container">
    
    <!-- Hero Section -->
    <div class="hero-section">
      <div class="hero-content">
        <div class="hero-text">
          <h1>🏠 Muhitaji Dashboard</h1>
          <p>Usafi salama, haraka, na wa kuegemea. Fuatilia kazi zako na upate matokeo bora.</p>
    </div>
        <div class="hero-actions">
          <a class="btn btn-primary" href="{{ route('jobs.create') }}">
            <span>📝</span>
            Chapisha Kazi
          </a>
          <a class="btn btn-outline" href="{{ route('my.jobs') }}">
            <span>📋</span>
            Kazi Zangu
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon">📌</div>
          <div class="stat-info">
            <h3>Kazi Ulizochapisha</h3>
            <div class="stat-value">{{ number_format($posted) }}</div>
            <div class="stat-change {{ $posted > 0 ? 'positive' : '' }}">
              @if($posted === 0)
                <span>Anza kwa kuchapisha kazi ya kwanza</span>
              @else
                <span>📊 {{ $posted - $completed }} inasubiri</span>
          @endif
            </div>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon">✅</div>
          <div class="stat-info">
            <h3>Zilizokamilika</h3>
            <div class="stat-value">{{ number_format($completed) }}</div>
            <div class="stat-change {{ $rate >= 70 ? 'positive' : 'negative' }}">
              <span>📈 {{ $rate }}% completion rate</span>
            </div>
        </div>
      </div>
    </div>

      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon">💳</div>
          <div class="stat-info">
            <h3>Uliyo Lipa (TZS)</h3>
            <div class="stat-value">{{ number_format($totalPaid) }}</div>
            <div class="stat-change {{ $totalPaid > 0 ? 'positive' : '' }}">
          @if($totalPaid > 0)
                <span>🔒 Escrow salama imefanya kazi</span>
          @else
                <span>Hakuna malipo bado</span>
          @endif
            </div>
        </div>
      </div>
    </div>
  </div>

    <!-- Progress Section -->
    <div class="progress-section">
      <div class="progress-content">
        <div class="progress-ring" aria-label="Completion rate">
          <svg width="120" height="120" viewBox="0 0 120 120" role="img">
            <circle cx="60" cy="60" r="45" fill="none" stroke="#e5e7eb" stroke-width="8"></circle>
            <circle cx="60" cy="60" r="45" fill="none"
              stroke="url(#gradient)" stroke-width="8" stroke-linecap="round"
              stroke-dasharray="{{ $dash }},{{ $circ }}"></circle>
            <defs>
              <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:1" />
              </linearGradient>
            </defs>
          </svg>
          <div class="num">{{ $rate }}%</div>
        </div>
        <div class="progress-info">
          <h3>Ufanisi wa Utekelezaji</h3>
          <p>
            @if($rate === 100 && $posted > 0)
              🎉 Hongera! Kila kazi imekamilika. Je, uchapishe nyingine sasa?
            @elseif($posted === 0)
              🚀 Hakuna kazi bado. Chapisha kazi ili kuanza safari ya usafi salama.
            @else
              📊 Una {{ $posted - $completed }} bado. Fungua <a href="{{ route('my.jobs') }}" style="color:var(--primary); font-weight:600;">Kazi Zangu</a> kufuatilia.
            @endif
          </p>
          <div class="progress-actions">
            <a class="btn btn-primary" href="{{ route('jobs.create') }}">
              <span>➕</span>
              Chapisha Kazi Mpya
            </a>
            <a class="btn btn-outline" href="{{ route('my.jobs') }}">
              <span>👁️</span>
              Fuatilia Zilizopo
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Jobs -->
    @isset($recentJobs)
      <div class="recent-jobs">
        <div class="recent-jobs-header">
          <div class="recent-jobs-title">
            <span>📋</span>
            Kazi za Karibuni
          </div>
          <a class="btn btn-outline" href="{{ route('my.jobs') }}">Ona Zote</a>
        </div>
        @if(count($recentJobs))
            @foreach($recentJobs as $job)
            <div class="job-item">
              <div class="job-header">
                <div class="job-info">
                  <h4>{{ $job->title ?? 'Kazi' }}</h4>
                  <div class="job-meta">
                    <span>📍 {{ $job->location ?? '—' }}</span>
                    <span>⏱️ {{ $job->created_at?->diffForHumans() ?? '' }}</span>
                  </div>
                </div>
                <div style="text-align: right;">
                  <div class="job-status {{ str_replace('_', '-', $job->status ?? 'draft') }}">
                    {{ ucfirst($job->status ?? 'pending') }}
                  </div>
                  <div class="job-price">TZS {{ number_format((int)($job->budget ?? 0)) }}</div>
                </div>
                </div>
              </div>
            @endforeach
        @else
          <div class="empty-state">
            <div class="empty-state-icon">📝</div>
            <h3>Hakuna Kazi za Karibuni</h3>
            <p>Hakuna orodha za karibuni. Chapisha kazi uanze safari ya usafi salama.</p>
            <a class="btn btn-primary" href="{{ route('jobs.create') }}">
              <span>➕</span>
              Chapisha Kazi
            </a>
          </div>
        @endif
      </div>
    @endisset

    <!-- Empty State if totally new -->
    @if($posted === 0)
      <div class="empty-state">
        <div class="empty-state-icon">🏠</div>
        <h3>Karibu kwa Tendapoa!</h3>
        <p>Unataka usafi haraka na salama? Chapisha kazi yako ya kwanza na uanze safari ya usafi wa kuegemea.</p>
        <a class="btn btn-primary" href="{{ route('jobs.create') }}">
          <span>🚀</span>
          Chapisha Kazi Sasa
        </a>
      </div>
    @endif

  </div>
</div>

<!-- Payment History Section -->
<div class="payment-history-section" style="margin-top: 32px;">
  
  <!-- Payment History -->
  <div class="history-section">
    <div class="history-header">
      <h3>💳 Historia ya Malipo</h3>
      <span class="history-count">{{ $paymentHistory->count() }} malipo</span>
    </div>
    
    @if($paymentHistory->count() > 0)
      <div class="history-list">
        @foreach($paymentHistory as $payment)
          <div class="history-item payment-item" data-status="{{ strtolower($payment->status) }}">
            <div class="history-icon">
              @if($payment->status === 'COMPLETED')
                ✅
              @elseif($payment->status === 'PENDING')
                ⏳
              @elseif($payment->status === 'FAILED')
                ❌
              @else
                💳
              @endif
            </div>
            <div class="history-details">
              <div class="history-title">{{ $payment->job->title ?? 'Job Payment' }}</div>
              <div class="history-description">
                Order ID: {{ $payment->order_id ?? 'N/A' }} | 
                Status: {{ ucfirst($payment->status) }}
              </div>
              <div class="history-meta">
                <span class="history-date">{{ $payment->created_at->diffForHumans() }}</span>
                <span class="history-status status-{{ strtolower($payment->status) }}">
                  {{ ucfirst($payment->status) }}
                </span>
              </div>
            </div>
            <div class="history-amount negative">-{{ number_format($payment->amount) }} TZS</div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-history">
        <div class="empty-icon">💳</div>
        <div class="empty-text">Hujafanya malipo bado</div>
        <div class="empty-subtitle">Chapisha kazi za kwanza ili uone malipo yako</div>
      </div>
    @endif
  </div>

  <!-- All Jobs History -->
  <div class="history-section">
    <div class="history-header">
      <h3>📋 Historia ya Kazi Zote</h3>
      <span class="history-count">{{ $allJobs->count() }} kazi</span>
    </div>
    
    @if($allJobs->count() > 0)
      <div class="history-list">
        @foreach($allJobs as $job)
          <div class="history-item jobs-item" data-status="{{ strtolower($job->status) }}">
            <div class="history-icon">
              @if($job->status === 'completed')
                ✅
              @elseif($job->status === 'in_progress')
                🔄
              @elseif($job->status === 'assigned')
                📋
              @else
                📝
              @endif
            </div>
            <div class="history-details">
              <div class="history-title">{{ $job->title ?? 'Kazi' }}</div>
              <div class="history-description">
                @if($job->acceptedWorker)
                  Mfanyakazi: {{ $job->acceptedWorker->name }} | 
                @endif
                Kategoria: {{ $job->category->name ?? 'N/A' }}
              </div>
              <div class="history-meta">
                <span class="history-date">{{ $job->created_at->diffForHumans() }}</span>
                <span class="history-status status-{{ strtolower($job->status) }}">
                  {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                </span>
              </div>
            </div>
            <div class="history-amount">
              <span class="amount-label">Bei:</span>
              <span class="amount-value">{{ number_format($job->amount) }} TZS</span>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-history">
        <div class="empty-icon">📋</div>
        <div class="empty-text">Hujachapisha kazi bado</div>
        <div class="empty-subtitle">Chapisha kazi za kwanza ili uone historia yako</div>
    </div>
  @endif
  </div>

</div>

<script>
  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate stat cards on scroll
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

    // Observe all stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s ease';
      observer.observe(card);
    });

    // Add hover effects to job items
    document.querySelectorAll('.job-item').forEach(item => {
      item.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px) scale(1.02)';
      });
      
      item.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });
  });
</script>
@endsection
