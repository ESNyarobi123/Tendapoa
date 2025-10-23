@extends('layouts.app')
@section('title', 'Admin — Job Management')

@section('content')
<style>
  /* ====== Modern Admin Jobs Page ====== */
  .jobs-page {
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

  .jobs-page {
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

  /* Jobs List */
  .jobs-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .jobs-list {
    display: grid;
    gap: 16px;
  }

  .job-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
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
    margin-bottom: 16px;
  }

  .job-info h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .job-meta {
    display: flex;
    gap: 16px;
    align-items: center;
    color: var(--text-muted);
    font-size: 0.875rem;
    flex-wrap: wrap;
  }

  .job-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--success);
    text-align: right;
  }

  .job-status {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
    display: inline-block;
  }

  .job-status.posted {
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

  .job-status.cancelled {
    background: #fecaca;
    color: #dc2626;
  }

  .job-details {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
  }

  .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e5e7eb;
  }

  .detail-row:last-child {
    border-bottom: none;
  }

  .detail-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
  }

  .detail-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
  }

  .job-actions {
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

  .btn-danger {
    background: linear-gradient(135deg, var(--danger), #dc2626);
    color: white;
    box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.4);
  }

  .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px 0 rgba(239, 68, 68, 0.6);
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

  .btn-warning {
    background: linear-gradient(135deg, var(--warning), #d97706);
    color: white;
    box-shadow: 0 4px 14px 0 rgba(245, 158, 11, 0.4);
  }

  .btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px 0 rgba(245, 158, 11, 0.6);
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

  /* Pagination */
  .pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 32px;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .jobs-page {
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
    
    .job-header {
      flex-direction: column;
      gap: 16px;
    }
    
    .job-meta {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }
    
    .job-actions {
      flex-direction: column;
    }
  }
</style>

<div class="jobs-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <div class="header-content">
        <div class="header-text">
          <h1>📋 Job Management</h1>
          <p>Dhibiti na simamia kazi zote za mfumo</p>
        </div>
        <div class="header-actions">
          <a class="btn btn-outline" href="{{ route('dashboard') }}">
            <span>↩️</span>
            Rudi Dashboard
          </a>
          <a class="btn btn-primary" href="{{ route('jobs.create') }}">
            <span>➕</span>
            Chapisha Kazi
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-value">{{ $jobs->total() }}</div>
        <div class="stat-label">Total Jobs</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-value">{{ $jobs->where('status', 'posted')->count() }}</div>
        <div class="stat-label">Posted</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-value">{{ $jobs->where('status', 'in_progress')->count() }}</div>
        <div class="stat-label">In Progress</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ $jobs->where('status', 'completed')->count() }}</div>
        <div class="stat-label">Completed</div>
      </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
      <div class="filters-grid">
        <div class="filter-group">
          <label class="filter-label" for="search">Search Jobs</label>
          <input type="text" class="filter-input" id="search" placeholder="Search by title or description..." onkeyup="searchJobs(this.value)">
        </div>
        <div class="filter-group">
          <label class="filter-label" for="status">Filter by Status</label>
          <select class="filter-select" id="status" onchange="filterByStatus(this.value)">
            <option value="">All Status</option>
            <option value="posted">Posted</option>
            <option value="assigned">Assigned</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label" for="category">Filter by Category</label>
          <select class="filter-select" id="category" onchange="filterByCategory(this.value)">
            <option value="">All Categories</option>
            <option value="cleaning">Cleaning</option>
            <option value="delivery">Delivery</option>
            <option value="maintenance">Maintenance</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label" for="price">Price Range</label>
          <select class="filter-select" id="price" onchange="filterByPrice(this.value)">
            <option value="">All Prices</option>
            <option value="0-10000">TZS 0 - 10,000</option>
            <option value="10000-50000">TZS 10,000 - 50,000</option>
            <option value="50000+">TZS 50,000+</option>
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

    <!-- Jobs List -->
    <div class="jobs-section">
      @if($jobs->count())
        <div class="jobs-list">
          @foreach($jobs as $job)
            <div class="job-card" data-status="{{ $job->status }}" data-category="{{ $job->category->slug ?? '' }}" data-price="{{ $job->price ?? 0 }}">
              <div class="job-header">
                <div class="job-info">
                  <div class="job-status {{ str_replace('_', '-', $job->status) }}">
                    {{ strtoupper($job->status) }}
                  </div>
                  <h3>{{ $job->title }}</h3>
                  <div class="job-meta">
                    <span>👤 {{ $job->muhitaji->name ?? 'Unknown' }}</span>
                    <span>🏷️ {{ $job->category->name ?? 'Uncategorized' }}</span>
                    <span>📍 {{ $job->location ?? 'N/A' }}</span>
                    <span>⏱️ {{ $job->created_at?->diffForHumans() ?? '' }}</span>
                  </div>
                </div>
                <div class="job-price">TZS {{ number_format($job->price ?? 0) }}</div>
              </div>

              <div class="job-details">
                <div class="detail-row">
                  <span class="detail-label">Description:</span>
                  <span class="detail-value">{{ Str::limit($job->description ?? 'No description', 50) }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Worker:</span>
                  <span class="detail-value">{{ $job->acceptedWorker->name ?? 'Not assigned' }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Created:</span>
                  <span class="detail-value">{{ $job->created_at?->format('M d, Y H:i') ?? 'N/A' }}</span>
                </div>
                @if($job->completed_at)
                  <div class="detail-row">
                    <span class="detail-label">Completed:</span>
                    <span class="detail-value">{{ $job->completed_at->format('M d, Y H:i') }}</span>
                  </div>
                @endif
              </div>

              <div class="job-actions">
                <a class="btn btn-outline" href="{{ route('jobs.show', $job) }}">
                  <span>👁️</span>
                  View Details
                </a>
                @if($job->status === 'posted')
                  <button class="btn btn-warning" onclick="assignJob({{ $job->id }})">
                    <span>👷</span>
                    Assign Worker
                  </button>
                @endif
                @if(in_array($job->status, ['posted', 'assigned']))
                  <button class="btn btn-danger" onclick="cancelJob({{ $job->id }})">
                    <span>❌</span>
                    Cancel Job
                  </button>
                @endif
                @if($job->status === 'completed')
                  <button class="btn btn-success" onclick="markAsPaid({{ $job->id }})">
                    <span>💰</span>
                    Mark as Paid
                  </button>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
          {{ $jobs->links() }}
        </div>
      @else
        <div class="empty-state">
          <div class="empty-state-icon">📋</div>
          <h3>No Jobs Found</h3>
          <p>There are no jobs to display at the moment.</p>
        </div>
      @endif
    </div>

  </div>
</div>

<script>
  // Filter functions
  function searchJobs(query) {
    const cards = document.querySelectorAll('.job-card');
    cards.forEach(card => {
      const title = card.querySelector('h3').textContent.toLowerCase();
      const description = card.querySelector('.detail-value').textContent.toLowerCase();
      const searchTerm = query.toLowerCase();
      
      if (title.includes(searchTerm) || description.includes(searchTerm)) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function filterByStatus(status) {
    const cards = document.querySelectorAll('.job-card');
    cards.forEach(card => {
      if (!status || card.dataset.status === status) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function filterByCategory(category) {
    const cards = document.querySelectorAll('.job-card');
    cards.forEach(card => {
      if (!category || card.dataset.category === category) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function filterByPrice(range) {
    const cards = document.querySelectorAll('.job-card');
    cards.forEach(card => {
      const price = parseInt(card.dataset.price);
      let show = true;
      
      if (range) {
        switch(range) {
          case '0-10000':
            show = price >= 0 && price <= 10000;
            break;
          case '10000-50000':
            show = price > 10000 && price <= 50000;
            break;
          case '50000+':
            show = price > 50000;
            break;
        }
      }
      
      card.style.display = show ? 'block' : 'none';
    });
  }

  function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('status').value = '';
    document.getElementById('category').value = '';
    document.getElementById('price').value = '';
    
    const cards = document.querySelectorAll('.job-card');
    cards.forEach(card => {
      card.style.display = 'block';
    });
  }

  function assignJob(jobId) {
    if (confirm('Are you sure you want to assign a worker to this job?')) {
      // This would need a backend endpoint
      console.log('Assign job:', jobId);
    }
  }

  function cancelJob(jobId) {
    if (confirm('Are you sure you want to cancel this job? This action cannot be undone.')) {
      // This would need a backend endpoint
      console.log('Cancel job:', jobId);
    }
  }

  function markAsPaid(jobId) {
    if (confirm('Are you sure you want to mark this job as paid?')) {
      // This would need a backend endpoint
      console.log('Mark as paid:', jobId);
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

    // Add hover effects
    document.querySelectorAll('.job-card').forEach(card => {
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
