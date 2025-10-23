@extends('layouts.app')
@section('title', 'Admin — Analytics & Reports')

@section('content')
<style>
  /* ====== Modern Admin Analytics Page ====== */
  .analytics-page {
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

  .analytics-page {
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

  /* Date Range Picker */
  .date-range-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .date-range-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
  }

  .date-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .date-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .date-input {
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 0.875rem;
    background: white;
    transition: all 0.3s ease;
  }

  .date-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  /* Key Metrics */
  .metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
  }

  .metric-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
  }

  .metric-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
  }

  .metric-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
  }

  .metric-icon {
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

  .metric-info h3 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0;
  }

  .metric-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin: 8px 0;
  }

  .metric-change {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.875rem;
    font-weight: 600;
  }

  .metric-change.positive {
    color: var(--success);
  }

  .metric-change.negative {
    color: var(--danger);
  }

  /* Charts Section */
  .charts-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
  }

  .chart-container {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
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
    height: 300px;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    font-size: 1.1rem;
    font-weight: 600;
    border: 2px dashed var(--border);
  }

  /* Top Performers */
  .top-performers {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .performers-list {
    display: grid;
    gap: 16px;
  }

  .performer-item {
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .performer-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .performer-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
  }

  .performer-info {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .performer-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--success));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.875rem;
  }

  .performer-details h4 {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
  }

  .performer-details p {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin: 0;
  }

  .performer-stats {
    text-align: right;
  }

  .performer-value {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--success);
  }

  .performer-label {
    font-size: 0.75rem;
    color: var(--text-muted);
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

  /* Responsive */
  @media (max-width: 768px) {
    .analytics-page {
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
    
    .date-range-grid {
      grid-template-columns: 1fr;
    }
    
    .metrics-grid {
      grid-template-columns: 1fr;
    }
    
    .charts-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="analytics-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <div class="header-content">
        <div class="header-text">
          <h1>📊 Analytics & Reports</h1>
          <p>Chambua data na ufuatilie utendaji wa mfumo</p>
        </div>
        <div class="header-actions">
          <a class="btn btn-outline" href="{{ route('dashboard') }}">
            <span>↩️</span>
            Rudi Dashboard
          </a>
          <a class="btn btn-primary" href="{{ url('/admin/analytics/export') }}">
            <span>📈</span>
            Export Report
          </a>
        </div>
      </div>
    </div>

    <!-- Date Range Picker -->
    <div class="date-range-section">
      <div class="date-range-grid">
        <div class="date-group">
          <label class="date-label" for="startDate">Start Date</label>
          <input type="date" class="date-input" id="startDate" value="{{ date('Y-m-01') }}">
        </div>
        <div class="date-group">
          <label class="date-label" for="endDate">End Date</label>
          <input type="date" class="date-input" id="endDate" value="{{ date('Y-m-d') }}">
        </div>
        <div class="date-group">
          <button class="btn btn-primary" onclick="updateAnalytics()">
            <span>🔄</span>
            Update Analytics
          </button>
        </div>
        <div class="date-group">
          <button class="btn btn-outline" onclick="resetDateRange()">
            <span>📅</span>
            Reset to Current Month
          </button>
        </div>
      </div>
    </div>

    <!-- Key Metrics -->
    <div class="metrics-grid">
      <div class="metric-card">
        <div class="metric-header">
          <div class="metric-icon">📦</div>
          <div class="metric-info">
            <h3>Total Jobs</h3>
            <div class="metric-value">{{ number_format($totalJobs ?? 0) }}</div>
            <div class="metric-change positive">
              <span>📈</span>
              +12% from last month
            </div>
          </div>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-header">
          <div class="metric-icon">💰</div>
          <div class="metric-info">
            <h3>Total Revenue</h3>
            <div class="metric-value">TZS {{ number_format($totalRevenue ?? 0) }}</div>
            <div class="metric-change positive">
              <span>📈</span>
              +8% from last month
            </div>
          </div>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-header">
          <div class="metric-icon">👥</div>
          <div class="metric-info">
            <h3>Active Users</h3>
            <div class="metric-value">{{ number_format($activeUsers ?? 0) }}</div>
            <div class="metric-change positive">
              <span>📈</span>
              +15% from last month
            </div>
          </div>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-header">
          <div class="metric-icon">⭐</div>
          <div class="metric-info">
            <h3>Completion Rate</h3>
            <div class="metric-value">{{ number_format($completionRate ?? 0) }}%</div>
            <div class="metric-change positive">
              <span>📈</span>
              +3% from last month
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
      <div class="charts-grid">
        <!-- Revenue Chart -->
        <div class="chart-container">
          <div class="chart-header">
            <div class="chart-title">
              <span>💰</span>
              Revenue Trend
            </div>
            <select class="btn btn-outline" style="font-size: 0.75rem; padding: 8px 12px;">
              <option>Last 7 days</option>
              <option>Last 30 days</option>
              <option>Last 3 months</option>
            </select>
          </div>
          <div class="chart-placeholder">
            📈 Revenue Chart (Chart.js integration needed)
          </div>
        </div>

        <!-- Job Categories -->
        <div class="chart-container">
          <div class="chart-header">
            <div class="chart-title">
              <span>🏷️</span>
              Job Categories
            </div>
          </div>
          <div class="chart-placeholder">
            🥧 Category Distribution (Chart.js integration needed)
          </div>
        </div>
      </div>
    </div>

    <!-- Top Performers -->
    <div class="top-performers">
      <div class="chart-header">
        <div class="chart-title">
          <span>🏆</span>
          Top Performing Workers
        </div>
        <a class="btn btn-outline" href="{{ url('/admin/users') }}" style="font-size: 0.75rem; padding: 8px 12px;">
          View All
        </a>
      </div>
      <div class="performers-list">
        @for($i = 1; $i <= 5; $i++)
          <div class="performer-item">
            <div class="performer-header">
              <div class="performer-info">
                <div class="performer-avatar">
                  {{ chr(64 + $i) }}
                </div>
                <div class="performer-details">
                  <h4>Worker {{ $i }}</h4>
                  <p>Completed {{ rand(10, 50) }} jobs</p>
                </div>
              </div>
              <div class="performer-stats">
                <div class="performer-value">TZS {{ number_format(rand(100000, 500000)) }}</div>
                <div class="performer-label">Total Earnings</div>
              </div>
            </div>
          </div>
        @endfor
      </div>
    </div>

    <!-- Additional Analytics -->
    <div class="charts-section">
      <div class="charts-grid">
        <!-- User Growth -->
        <div class="chart-container">
          <div class="chart-header">
            <div class="chart-title">
              <span>👥</span>
              User Growth
            </div>
          </div>
          <div class="chart-placeholder">
            📊 User Growth Chart (Chart.js integration needed)
          </div>
        </div>

        <!-- Job Status Distribution -->
        <div class="chart-container">
          <div class="chart-header">
            <div class="chart-title">
              <span>📋</span>
              Job Status Distribution
            </div>
          </div>
          <div class="chart-placeholder">
            🥧 Job Status Pie Chart (Chart.js integration needed)
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  // Analytics functions
  function updateAnalytics() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (!startDate || !endDate) {
      alert('Please select both start and end dates');
      return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
      alert('Start date cannot be after end date');
      return;
    }
    
    // Show loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span>⏳</span> Loading...';
    btn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
      btn.innerHTML = originalText;
      btn.disabled = false;
      
      // Update metrics (this would be real data from API)
      updateMetrics();
      
      showNotification('Analytics updated successfully!', 'success');
    }, 2000);
  }

  function resetDateRange() {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    
    document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
    document.getElementById('endDate').value = today.toISOString().split('T')[0];
  }

  function updateMetrics() {
    // This would update the metrics with real data
    console.log('Updating metrics with new data...');
  }

  function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: ${type === 'success' ? '#10b981' : '#ef4444'};
      color: white;
      padding: 16px 24px;
      border-radius: 12px;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      z-index: 1001;
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

  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate metric cards on scroll
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

    // Observe all metric cards
    document.querySelectorAll('.metric-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s ease';
      observer.observe(card);
    });

    // Observe performer items
    document.querySelectorAll('.performer-item').forEach(item => {
      item.style.opacity = '0';
      item.style.transform = 'translateY(20px)';
      item.style.transition = 'all 0.6s ease';
      observer.observe(item);
    });

    // Add hover effects
    document.querySelectorAll('.metric-card').forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px) scale(1.02)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });
  });
</script>
@endsection
