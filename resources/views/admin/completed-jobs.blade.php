@extends('layouts.app')
@section('title', 'Admin — Completed Jobs by Workers')

@section('content')
<style>
  /* ====== Admin Completed Jobs Page ====== */
  .completed-jobs-page {
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

  .completed-jobs-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .page-container {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    gap: 24px;
  }

  /* Header */
  .page-header {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .header-content {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 24px;
    align-items: center;
  }

  .header-text h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, var(--primary), var(--success));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .header-text p {
    color: var(--text-muted);
    font-size: 1.1rem;
    margin: 0;
  }

  .header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
    text-align: center;
    transition: all 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .stat-icon {
    font-size: 2rem;
    margin-bottom: 8px;
  }

  .stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  /* Filters */
  .filters-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
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

  .filter-input, .filter-select {
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 0.875rem;
    background: white;
    transition: all 0.3s ease;
  }

  .filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  /* Worker Performance Grid */
  .workers-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .workers-grid {
    display: grid;
    gap: 20px;
  }

  .worker-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .worker-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .worker-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
  }

  .worker-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--success));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.5rem;
  }

  .worker-info h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 4px 0;
  }

  .worker-info p {
    color: var(--text-muted);
    font-size: 0.875rem;
    margin: 0;
  }

  .worker-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
  }

  .worker-stat {
    text-align: center;
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
  }

  .worker-stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--success);
    margin-bottom: 4px;
  }

  .worker-stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .completed-jobs-list {
    display: grid;
    gap: 12px;
  }

  .job-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .job-item:hover {
    background: #e2e8f0;
    transform: translateX(4px);
  }

  .job-info h4 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 4px 0;
  }

  .job-info p {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
  }

  .job-price {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--success);
    text-align: right;
  }

  .job-date {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-align: right;
  }

  /* Performance Chart */
  .performance-chart {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    margin-bottom: 20px;
  }

  .chart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  .chart-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .chart-placeholder {
    height: 200px;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    font-size: 1rem;
    font-weight: 600;
    border: 2px dashed var(--border);
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

  .btn-outline {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
  }

  .btn-outline:hover {
    background: var(--primary);
    color: white;
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

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 80px 20px;
    background: #f8fafc;
    border-radius: 16px;
    border: 2px dashed var(--border);
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
    margin: 0;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .completed-jobs-page {
      padding: 16px;
    }
    
    .page-header {
      padding: 24px;
    }
    
    .header-content {
      grid-template-columns: 1fr;
      text-align: center;
    }
    
    .header-text h1 {
      font-size: 2rem;
    }
    
    .filters-grid {
      grid-template-columns: 1fr;
    }
    
    .worker-header {
      flex-direction: column;
      text-align: center;
    }
    
    .worker-stats {
      grid-template-columns: 1fr;
    }
    
    .job-item {
      flex-direction: column;
      gap: 12px;
      text-align: center;
    }
  }
</style>

@php
  // Get workers with their completed jobs
  $workers = \App\Models\User::where('role', 'mfanyakazi')
    ->with(['jobs' => function($query) {
      $query->where('status', 'completed');
    }])
    ->withCount(['jobs as completed_jobs' => function($query) {
      $query->where('status', 'completed');
    }])
    ->orderBy('completed_jobs', 'desc')
    ->get();

  $totalCompletedJobs = \App\Models\Job::where('status', 'completed')->count();
  $totalEarnings = \App\Models\Job::where('status', 'completed')->sum('price');
  $topWorker = $workers->first();
  $averageJobsPerWorker = $workers->count() > 0 ? round($totalCompletedJobs / $workers->count(), 1) : 0;
@endphp

<div class="completed-jobs-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <div class="header-content">
        <div class="header-text">
          <h1>✅ Completed Jobs by Workers</h1>
          <p>Monitor and analyze completed work by all workers in the system</p>
        </div>
        <div class="header-actions">
          <a class="btn btn-outline" href="{{ route('dashboard') }}">
            <span>↩️</span>
            Rudi Dashboard
          </a>
          <a class="btn btn-primary" href="{{ url('/admin/analytics') }}">
            <span>📊</span>
            Full Analytics
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ number_format($totalCompletedJobs) }}</div>
        <div class="stat-label">Total Completed Jobs</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-value">TZS {{ number_format($totalEarnings) }}</div>
        <div class="stat-label">Total Earnings</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">👷</div>
        <div class="stat-value">{{ $workers->count() }}</div>
        <div class="stat-label">Active Workers</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-value">{{ $averageJobsPerWorker }}</div>
        <div class="stat-label">Avg Jobs/Worker</div>
      </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
      <div class="filters-grid">
        <div class="filter-group">
          <label class="filter-label" for="search">Search Workers</label>
          <input type="text" class="filter-input" id="search" placeholder="Search by worker name..." onkeyup="searchWorkers(this.value)">
        </div>
        <div class="filter-group">
          <label class="filter-label" for="sort">Sort By</label>
          <select class="filter-select" id="sort" onchange="sortWorkers(this.value)">
            <option value="jobs">Most Jobs</option>
            <option value="earnings">Highest Earnings</option>
            <option value="recent">Most Recent</option>
            <option value="name">Name A-Z</option>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label" for="date">Date Range</label>
          <select class="filter-select" id="date" onchange="filterByDate(this.value)">
            <option value="">All Time</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
          </select>
        </div>
        <div class="filter-group">
          <button class="btn btn-outline" onclick="clearFilters()">
            <span>🔄</span>
            Clear Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Workers Performance -->
    <div class="workers-section">
      @if($workers->count())
        <div class="workers-grid">
          @foreach($workers as $worker)
            <div class="worker-card" data-name="{{ strtolower($worker->name) }}" data-jobs="{{ $worker->completed_jobs }}" data-earnings="{{ $worker->jobs->sum('price') }}">
              
              <!-- Worker Header -->
              <div class="worker-header">
                <div class="worker-avatar">
                  {{ strtoupper(substr($worker->name, 0, 2)) }}
                </div>
                <div class="worker-info">
                  <h3>{{ $worker->name }}</h3>
                  <p>{{ $worker->email }} • Joined {{ $worker->created_at->format('M Y') }}</p>
                </div>
              </div>

              <!-- Worker Stats -->
              <div class="worker-stats">
                <div class="worker-stat">
                  <div class="worker-stat-value">{{ $worker->completed_jobs }}</div>
                  <div class="worker-stat-label">Jobs Completed</div>
                </div>
                <div class="worker-stat">
                  <div class="worker-stat-value">TZS {{ number_format($worker->jobs->sum('price')) }}</div>
                  <div class="worker-stat-label">Total Earnings</div>
                </div>
                <div class="worker-stat">
                  <div class="worker-stat-value">{{ $worker->completed_jobs > 0 ? round($worker->jobs->sum('price') / $worker->completed_jobs) : 0 }}</div>
                  <div class="worker-stat-label">Avg per Job</div>
                </div>
                <div class="worker-stat">
                  <div class="worker-stat-value">{{ $worker->jobs->where('completed_at', '>=', now()->subDays(30))->count() }}</div>
                  <div class="worker-stat-label">Last 30 Days</div>
                </div>
              </div>

              <!-- Performance Chart -->
              <div class="performance-chart">
                <div class="chart-header">
                  <div class="chart-title">
                    <span>📈</span>
                    Performance Trend
                  </div>
                </div>
                <div class="chart-placeholder">
                  📊 Performance Chart for {{ $worker->name }} (Chart.js integration needed)
                </div>
              </div>

              <!-- Completed Jobs List -->
              <div class="completed-jobs-list">
                <h4 style="margin-bottom: 12px; color: var(--dark);">Recent Completed Jobs:</h4>
                @if($worker->jobs->count())
                  @foreach($worker->jobs->take(5) as $job)
                    <div class="job-item">
                      <div class="job-info">
                        <h4>{{ $job->title }}</h4>
                        <p>Client: {{ $job->muhitaji->name ?? 'Unknown' }} • {{ $job->category->name ?? 'Uncategorized' }}</p>
                      </div>
                      <div>
                        <div class="job-price">TZS {{ number_format($job->price) }}</div>
                        <div class="job-date">{{ $job->completed_at?->format('M d, Y') ?? 'N/A' }}</div>
                      </div>
                    </div>
                  @endforeach
                  @if($worker->jobs->count() > 5)
                    <div style="text-align: center; margin-top: 12px;">
                      <a class="btn btn-outline" href="{{ url('/admin/workers/'.$worker->id.'/jobs') }}">
                        <span>👁️</span>
                        View All {{ $worker->jobs->count() }} Jobs
                      </a>
                    </div>
                  @endif
                @else
                  <div class="job-item">
                    <div class="job-info">
                      <h4>No completed jobs yet</h4>
                      <p>This worker hasn't completed any jobs</p>
                    </div>
                  </div>
                @endif
              </div>

              <!-- Worker Actions -->
              <div style="display: flex; gap: 12px; margin-top: 20px; justify-content: center;">
                <a class="btn btn-outline" href="{{ url('/admin/users/'.$worker->id) }}">
                  <span>👤</span>
                  View Profile
                </a>
                <a class="btn btn-success" href="{{ url('/admin/workers/'.$worker->id.'/analytics') }}">
                  <span>📊</span>
                  Analytics
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="empty-state">
          <div class="empty-state-icon">👷</div>
          <h3>No Workers Found</h3>
          <p>There are no workers with completed jobs to display.</p>
        </div>
      @endif
    </div>

  </div>
</div>

<script>
  // Filter functions
  function searchWorkers(query) {
    const cards = document.querySelectorAll('.worker-card');
    cards.forEach(card => {
      const name = card.dataset.name;
      const searchTerm = query.toLowerCase();
      
      if (name.includes(searchTerm)) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function sortWorkers(sortBy) {
    const container = document.querySelector('.workers-grid');
    const cards = Array.from(container.querySelectorAll('.worker-card'));
    
    cards.sort((a, b) => {
      switch(sortBy) {
        case 'jobs':
          return parseInt(b.dataset.jobs) - parseInt(a.dataset.jobs);
        case 'earnings':
          return parseInt(b.dataset.earnings) - parseInt(a.dataset.earnings);
        case 'name':
          return a.dataset.name.localeCompare(b.dataset.name);
        case 'recent':
          // This would need actual date data
          return 0;
        default:
          return 0;
      }
    });
    
    cards.forEach(card => container.appendChild(card));
  }

  function filterByDate(dateRange) {
    // This would need server-side implementation for proper date filtering
    const cards = document.querySelectorAll('.worker-card');
    cards.forEach(card => {
      card.style.display = 'block';
    });
  }

  function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('sort').value = 'jobs';
    document.getElementById('date').value = '';
    
    const cards = document.querySelectorAll('.worker-card');
    cards.forEach(card => {
      card.style.display = 'block';
    });
    
    // Reset sort
    sortWorkers('jobs');
  }

  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate worker cards on scroll
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

    // Observe all worker cards
    document.querySelectorAll('.worker-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s ease';
      observer.observe(card);
    });

    // Add hover effects
    document.querySelectorAll('.worker-card').forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px) scale(1.02)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });
  });
</script>
@endsection
