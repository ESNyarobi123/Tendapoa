@extends('layouts.app')
@section('title', 'Admin — Withdrawal Management')

@section('content')
<style>
  /* ====== Modern Admin Withdrawals Page ====== */
  .withdrawals-page {
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

  .withdrawals-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .page-container {
    max-width: 1200px;
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

  .filter-select {
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 0.875rem;
    background: white;
    transition: all 0.3s ease;
  }

  .filter-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  /* Withdrawals List */
  .withdrawals-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .withdrawals-list {
    display: grid;
    gap: 16px;
  }

  .withdrawal-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .withdrawal-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .withdrawal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .withdrawal-info h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .withdrawal-meta {
    display: flex;
    gap: 16px;
    align-items: center;
    color: var(--text-muted);
    font-size: 0.875rem;
    flex-wrap: wrap;
  }

  .withdrawal-amount {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--success);
    text-align: right;
  }

  .withdrawal-status {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
    display: inline-block;
  }

  .withdrawal-status.pending {
    background: #fef3c7;
    color: #92400e;
  }

  .withdrawal-status.processing {
    background: #dbeafe;
    color: #1e40af;
  }

  .withdrawal-status.paid {
    background: #d1fae5;
    color: #065f46;
  }

  .withdrawal-status.rejected {
    background: #fecaca;
    color: #dc2626;
  }

  .withdrawal-details {
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

  .withdrawal-actions {
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

  .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
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

  /* Stats Cards */
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

  /* Responsive */
  @media (max-width: 768px) {
    .withdrawals-page {
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
    
    .withdrawal-header {
      flex-direction: column;
      gap: 16px;
    }
    
    .withdrawal-meta {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }
    
    .withdrawal-actions {
      flex-direction: column;
    }
  }
</style>

<div class="withdrawals-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <div class="header-content">
        <div class="header-text">
          <h1>💰 Withdrawal Management</h1>
          <p>Dhibiti na simamia maombi ya withdrawal ya wafanyakazi</p>
        </div>
        <div class="header-actions">
          <a class="btn btn-outline" href="{{ route('dashboard') }}">
            <span>↩️</span>
            Rudi Dashboard
          </a>
          <a class="btn btn-primary" href="{{ url('/admin/withdrawals/export') }}">
            <span>📊</span>
            Export Data
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-value">{{ $items->total() }}</div>
        <div class="stat-label">Total Withdrawals</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-value">{{ $items->where('status', 'PROCESSING')->count() }}</div>
        <div class="stat-label">Pending</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ $items->where('status', 'PAID')->count() }}</div>
        <div class="stat-label">Approved</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">❌</div>
        <div class="stat-value">{{ $items->where('status', 'REJECTED')->count() }}</div>
        <div class="stat-label">Rejected</div>
      </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
      <div class="filters-grid">
        <div class="filter-group">
          <label class="filter-label" for="status">Status</label>
          <select class="filter-select" id="status" onchange="filterByStatus(this.value)">
            <option value="">All Status</option>
            <option value="PROCESSING">Processing</option>
            <option value="PAID">Paid</option>
            <option value="REJECTED">Rejected</option>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label" for="amount">Amount Range</label>
          <select class="filter-select" id="amount" onchange="filterByAmount(this.value)">
            <option value="">All Amounts</option>
            <option value="0-10000">TZS 0 - 10,000</option>
            <option value="10000-50000">TZS 10,000 - 50,000</option>
            <option value="50000+">TZS 50,000+</option>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label" for="date">Date Range</label>
          <select class="filter-select" id="date" onchange="filterByDate(this.value)">
            <option value="">All Dates</option>
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

    <!-- Withdrawals List -->
    <div class="withdrawals-section">
      @if($items->count())
        <div class="withdrawals-list">
          @foreach($items as $w)
            <div class="withdrawal-card" data-status="{{ strtolower($w->status) }}" data-amount="{{ $w->amount }}">
              <div class="withdrawal-header">
                <div class="withdrawal-info">
                  <div class="withdrawal-status {{ strtolower($w->status) }}">
                    {{ strtoupper($w->status) }}
                  </div>
                  <h3>{{ $w->user->name ?? 'Unknown User' }}</h3>
                  <div class="withdrawal-meta">
                    <span>📱 {{ $w->account ?? 'N/A' }}</span>
                    <span>📧 {{ $w->user->email ?? 'N/A' }}</span>
                    <span>⏱️ {{ $w->created_at?->diffForHumans() ?? '' }}</span>
                    <span>🆔 #{{ $w->id }}</span>
                  </div>
                </div>
                <div class="withdrawal-amount">TZS {{ number_format($w->amount) }}</div>
              </div>

              <div class="withdrawal-details">
                <div class="detail-row">
                  <span class="detail-label">User ID:</span>
                  <span class="detail-value">#{{ $w->user_id }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Phone Number:</span>
                  <span class="detail-value">{{ $w->account ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Registered Name:</span>
                  <span class="detail-value">{{ $w->registered_name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Network Type:</span>
                  <span class="detail-value">{{ ucfirst($w->network_type ?? 'N/A') }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Method:</span>
                  <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $w->method ?? 'mobile_money')) }}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Requested At:</span>
                  <span class="detail-value">{{ $w->created_at?->format('M d, Y H:i') ?? 'N/A' }}</span>
                </div>
                @if($w->updated_at && $w->updated_at != $w->created_at)
                  <div class="detail-row">
                    <span class="detail-label">Last Updated:</span>
                    <span class="detail-value">{{ $w->updated_at->format('M d, Y H:i') }}</span>
                  </div>
                @endif
              </div>

              <div class="withdrawal-actions">
                @if($w->status !== 'PAID')
                  <form method="POST" action="{{ url('/admin/withdrawals/'.$w->id.'/paid') }}" style="display: inline;">
        @csrf
                    <button class="btn btn-success" type="submit" onclick="return confirm('Are you sure you want to mark this withdrawal as PAID?')">
                      <span>✅</span>
                      Mark as Paid
                    </button>
      </form>
                  <form method="POST" action="{{ url('/admin/withdrawals/'.$w->id.'/reject') }}" style="display: inline;">
        @csrf
                    <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure you want to REJECT this withdrawal? This will refund the amount to the user.')">
                      <span>❌</span>
                      Reject
                    </button>
      </form>
                @else
                  <span class="btn btn-outline" style="cursor: default;">
                    <span>✅</span>
                    Already Paid
                  </span>
                @endif
                <a class="btn btn-outline" href="{{ url('/admin/users/'.$w->user_id) }}">
                  <span>👤</span>
                  View User
                </a>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
          {{ $items->links() }}
        </div>
      @else
        <div class="empty-state">
          <div class="empty-state-icon">💰</div>
          <h3>No Withdrawals Found</h3>
          <p>There are no withdrawal requests to display at the moment.</p>
        </div>
      @endif
    </div>

  </div>
</div>

<script>
  // Filter functions
  function filterByStatus(status) {
    const cards = document.querySelectorAll('.withdrawal-card');
    cards.forEach(card => {
      if (!status || card.dataset.status === status.toLowerCase()) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function filterByAmount(range) {
    const cards = document.querySelectorAll('.withdrawal-card');
    cards.forEach(card => {
      const amount = parseInt(card.dataset.amount);
      let show = true;
      
      if (range) {
        switch(range) {
          case '0-10000':
            show = amount >= 0 && amount <= 10000;
            break;
          case '10000-50000':
            show = amount > 10000 && amount <= 50000;
            break;
          case '50000+':
            show = amount > 50000;
            break;
        }
      }
      
      card.style.display = show ? 'block' : 'none';
    });
  }

  function filterByDate(range) {
    // This would need server-side implementation for proper date filtering
    // For now, just show all cards
    const cards = document.querySelectorAll('.withdrawal-card');
    cards.forEach(card => {
      card.style.display = 'block';
    });
  }

  function clearFilters() {
    document.getElementById('status').value = '';
    document.getElementById('amount').value = '';
    document.getElementById('date').value = '';
    
    const cards = document.querySelectorAll('.withdrawal-card');
    cards.forEach(card => {
      card.style.display = 'block';
    });
  }

  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate withdrawal cards on scroll
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

    // Observe all withdrawal cards
    document.querySelectorAll('.withdrawal-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s ease';
      observer.observe(card);
    });

    // Add hover effects
    document.querySelectorAll('.withdrawal-card').forEach(card => {
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