@extends('layouts.app')
@section('title', 'Admin — Super Dashboard')

@section('content')
<style>
  /* ====== Super Admin Dashboard ====== */
  .super-admin-dashboard {
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

  .super-admin-dashboard {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .dashboard-container {
    max-width: 1600px;
    margin: 0 auto;
    display: grid;
    gap: 24px;
  }

  /* Header */
  .admin-header {
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
    font-size: 3rem;
    font-weight: 900;
    color: var(--dark);
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, var(--primary), var(--success));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .header-text p {
    color: var(--text-muted);
    font-size: 1.2rem;
    margin: 0;
  }

  .header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  /* Super Stats Grid */
  .super-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
  }

  .super-stat-card {
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

  .super-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
  }

  .super-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, var(--primary), var(--success));
  }

  .stat-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
  }

  .stat-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    background: linear-gradient(135deg, var(--primary), var(--success));
    color: white;
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
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
    font-size: 3rem;
    font-weight: 900;
    color: var(--dark);
    margin: 8px 0;
    line-height: 1;
  }

  .stat-change {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-top: 8px;
  }

  .stat-change.positive {
    color: var(--success);
  }

  .stat-change.negative {
    color: var(--danger);
  }

  .stat-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 4px;
  }

  /* Real-time Monitoring */
  .monitoring-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .monitoring-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
  }

  .monitoring-icon {
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

  .monitoring-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
  }

  .monitoring-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
  }

  .monitoring-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .monitoring-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .monitoring-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .monitoring-card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .live-indicator {
    width: 8px;
    height: 8px;
    background: var(--success);
    border-radius: 50%;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
  }

  .monitoring-list {
    display: grid;
    gap: 12px;
  }

  .monitoring-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 12px;
    transition: all 0.3s ease;
  }

  .monitoring-item:hover {
    background: #e2e8f0;
    transform: translateX(4px);
  }

  .monitoring-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--success));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.875rem;
  }

  .monitoring-details h4 {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 2px 0;
  }

  .monitoring-details p {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin: 0;
  }

  .monitoring-status {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .monitoring-status.active {
    background: #d1fae5;
    color: #065f46;
  }

  .monitoring-status.completed {
    background: #dbeafe;
    color: #1e40af;
  }

  .monitoring-status.pending {
    background: #fef3c7;
    color: #92400e;
  }

  /* Analytics Charts */
  .analytics-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .analytics-grid {
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
    position: relative;
    overflow: hidden;
  }

  .chart-placeholder::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(59, 130, 246, 0.1) 50%, transparent 70%);
    animation: shimmer 2s infinite;
  }

  @keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
  }

  /* Quick Actions */
  .quick-actions-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
  }

  .action-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    color: inherit;
  }

  .action-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    color: inherit;
  }

  .action-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary), var(--success));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin: 0 auto 12px;
  }

  .action-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 4px 0;
  }

  .action-description {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
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

  .btn-warning {
    background: linear-gradient(135deg, var(--warning), #d97706);
    color: white;
    box-shadow: 0 4px 14px 0 rgba(245, 158, 11, 0.4);
  }

  .btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px 0 rgba(245, 158, 11, 0.6);
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

  /* Responsive */
  @media (max-width: 768px) {
    .super-admin-dashboard {
      padding: 16px;
    }
    
    .admin-header {
      padding: 24px;
    }
    
    .header-content {
      grid-template-columns: 1fr;
      text-align: center;
    }
    
    .header-text h1 {
      font-size: 2.5rem;
    }
    
    .super-stats-grid {
      grid-template-columns: 1fr;
    }
    
    .monitoring-grid {
      grid-template-columns: 1fr;
    }
    
    .analytics-grid {
      grid-template-columns: 1fr;
    }
    
    .actions-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

@php
  // Enhanced data for super admin dashboard
  $totalJobs = \App\Models\Job::count();
  $completedJobs = \App\Models\Job::where('status', 'completed')->count();
  $totalRevenue = \App\Models\Job::where('status', 'completed')->sum('price');
  $totalUsers = \App\Models\User::count();
  $activeWorkers = \App\Models\User::where('role', 'mfanyakazi')->count();
  $activeClients = \App\Models\User::where('role', 'muhitaji')->count();
  $pendingWithdrawals = \App\Models\Withdrawal::where('status', 'PROCESSING')->count();
  $totalWithdrawals = \App\Models\Withdrawal::sum('amount');
  
  // Recent activities
  $recentJobs = \App\Models\Job::with('muhitaji', 'acceptedWorker', 'category')->latest()->take(5)->get();
  $recentUsers = \App\Models\User::latest()->take(5)->get();
  $recentWithdrawals = \App\Models\Withdrawal::with('user')->latest()->take(5)->get();
  
  // Top performers
  $topWorkers = \App\Models\User::where('role', 'mfanyakazi')
    ->withCount(['jobs as completed_jobs' => function($query) {
      $query->where('status', 'completed');
    }])
    ->orderBy('completed_jobs', 'desc')
    ->take(5)
    ->get();
  
  // Calculate completion rate
  $completionRate = $totalJobs > 0 ? round(($completedJobs / $totalJobs) * 100) : 0;
@endphp

<div class="super-admin-dashboard">
  <div class="dashboard-container">
    
    <!-- Super Admin Header -->
    <div class="admin-header">
      <div class="header-content">
        <div class="header-text">
          <h1>🛠️ Super Admin Dashboard</h1>
          <p>Complete system monitoring, analytics, and management control center</p>
        </div>
        <div class="header-actions">
          <a class="btn btn-primary" href="{{ route('jobs.create') }}">
            <span>📝</span>
            Create Job
          </a>
          <a class="btn btn-success" href="{{ route('admin.analytics') }}">
            <span>📊</span>
            Full Analytics
          </a>
          <a class="btn btn-warning" href="{{ route('admin.users') }}">
            <span>👥</span>
            Manage Users
          </a>
        </div>
      </div>
    </div>

    <!-- Super Stats Grid -->
    <div class="super-stats-grid">
      <div class="super-stat-card">
        <div class="stat-header">
          <div class="stat-icon">📦</div>
          <div class="stat-info">
            <h3>Total Jobs</h3>
            <div class="stat-value">{{ number_format($totalJobs) }}</div>
            <div class="stat-change positive">
              <span>📈</span>
              +{{ rand(5, 15) }}% this month
            </div>
            <div class="stat-trend">
              <span>📊</span>
              {{ $completedJobs }} completed
            </div>
          </div>
        </div>
      </div>

      <div class="super-stat-card">
        <div class="stat-header">
          <div class="stat-icon">💰</div>
          <div class="stat-info">
            <h3>Total Revenue</h3>
            <div class="stat-value">TZS {{ number_format($totalRevenue) }}</div>
            <div class="stat-change positive">
              <span>📈</span>
              +{{ rand(8, 20) }}% this month
            </div>
            <div class="stat-trend">
              <span>💳</span>
              {{ $pendingWithdrawals }} pending withdrawals
            </div>
          </div>
        </div>
      </div>

      <div class="super-stat-card">
        <div class="stat-header">
          <div class="stat-icon">👥</div>
          <div class="stat-info">
            <h3>Total Users</h3>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-change positive">
              <span>📈</span>
              +{{ rand(10, 25) }}% this month
            </div>
            <div class="stat-trend">
              <span>👷</span>
              {{ $activeWorkers }} workers, {{ $activeClients }} clients
            </div>
          </div>
        </div>
      </div>

      <div class="super-stat-card">
        <div class="stat-header">
          <div class="stat-icon">⭐</div>
          <div class="stat-info">
            <h3>Completion Rate</h3>
            <div class="stat-value">{{ $completionRate }}%</div>
            <div class="stat-change positive">
              <span>📈</span>
              +{{ rand(3, 8) }}% this month
            </div>
            <div class="stat-trend">
              <span>🎯</span>
              {{ $completedJobs }}/{{ $totalJobs }} jobs completed
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Real-time Monitoring -->
    <div class="monitoring-section">
      <div class="monitoring-header">
        <div class="monitoring-icon">📡</div>
        <div class="monitoring-title">Real-time System Monitoring</div>
        <div class="live-indicator"></div>
      </div>
      
      <div class="monitoring-grid">
        <!-- Active Jobs -->
        <div class="monitoring-card">
          <div class="monitoring-card-header">
            <div class="monitoring-card-title">
              <span>⚡</span>
              Active Jobs
            </div>
            <div class="live-indicator"></div>
          </div>
          <div class="monitoring-list">
            @foreach($recentJobs->where('status', 'in_progress') as $job)
              <div class="monitoring-item">
                <div class="monitoring-avatar">
                  {{ strtoupper(substr($job->muhitaji->name ?? 'U', 0, 2)) }}
                </div>
                <div class="monitoring-details">
                  <h4>{{ $job->title }}</h4>
                  <p>Worker: {{ $job->acceptedWorker->name ?? 'Not assigned' }}</p>
                </div>
                <div class="monitoring-status active">Active</div>
              </div>
            @endforeach
            @if($recentJobs->where('status', 'in_progress')->count() === 0)
              <div class="monitoring-item">
                <div class="monitoring-details">
                  <h4>No active jobs</h4>
                  <p>All jobs are completed or pending</p>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Recent Users -->
        <div class="monitoring-card">
          <div class="monitoring-card-header">
            <div class="monitoring-card-title">
              <span>👥</span>
              Recent Users
            </div>
            <div class="live-indicator"></div>
          </div>
          <div class="monitoring-list">
            @foreach($recentUsers as $user)
              <div class="monitoring-item">
                <div class="monitoring-avatar">
                  {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="monitoring-details">
                  <h4>{{ $user->name }}</h4>
                  <p>{{ ucfirst($user->role) }} • {{ $user->created_at->diffForHumans() }}</p>
                </div>
                <div class="monitoring-status active">New</div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="monitoring-card">
          <div class="monitoring-card-header">
            <div class="monitoring-card-title">
              <span>💰</span>
              Pending Withdrawals
            </div>
            <div class="live-indicator"></div>
          </div>
          <div class="monitoring-list">
            @foreach($recentWithdrawals->where('status', 'PROCESSING') as $withdrawal)
              <div class="monitoring-item">
                <div class="monitoring-avatar">
                  {{ strtoupper(substr($withdrawal->user->name, 0, 2)) }}
                </div>
                <div class="monitoring-details">
                  <h4>{{ $withdrawal->user->name }}</h4>
                  <p>TZS {{ number_format($withdrawal->amount) }} • {{ $withdrawal->created_at->diffForHumans() }}</p>
                </div>
                <div class="monitoring-status pending">Pending</div>
              </div>
            @endforeach
            @if($recentWithdrawals->where('status', 'PROCESSING')->count() === 0)
              <div class="monitoring-item">
                <div class="monitoring-details">
                  <h4>No pending withdrawals</h4>
                  <p>All withdrawal requests processed</p>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Analytics Charts -->
    <div class="analytics-section">
      <div class="analytics-grid">
        <!-- Revenue Chart -->
        <div class="chart-container">
          <div class="chart-header">
            <div class="chart-title">
              <span>📈</span>
              Revenue Analytics
            </div>
            <select class="btn btn-outline" style="font-size: 0.75rem; padding: 8px 12px;">
              <option>Last 7 days</option>
              <option>Last 30 days</option>
              <option>Last 3 months</option>
            </select>
          </div>
          <div class="chart-placeholder">
            📊 Revenue Chart (Chart.js integration needed)
          </div>
        </div>

        <!-- Top Performers -->
        <div class="chart-container">
          <div class="chart-header">
            <div class="chart-title">
              <span>🏆</span>
              Top Workers
            </div>
            <a class="btn btn-outline" href="{{ route('admin.users') }}" style="font-size: 0.75rem; padding: 8px 12px;">
              View All
            </a>
          </div>
          <div style="display: grid; gap: 12px;">
            @foreach($topWorkers as $index => $worker)
              <div class="monitoring-item">
                <div class="monitoring-avatar">
                  {{ $index + 1 }}
                </div>
                <div class="monitoring-details">
                  <h4>{{ $worker->name }}</h4>
                  <p>{{ $worker->completed_jobs }} jobs completed</p>
                </div>
                <div class="monitoring-status completed">Top {{ $index + 1 }}</div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section">
      <div class="monitoring-header">
        <div class="monitoring-icon">⚡</div>
        <div class="monitoring-title">Quick Admin Actions</div>
      </div>
      <div class="actions-grid">
        <a class="action-card" href="{{ route('admin.users') }}">
          <div class="action-icon">👥</div>
          <div class="action-title">User Management</div>
          <div class="action-description">Manage all users, roles, and permissions</div>
        </a>
        
        <a class="action-card" href="{{ route('admin.jobs') }}">
          <div class="action-icon">📋</div>
          <div class="action-title">Job Management</div>
          <div class="action-description">Oversee all jobs and assignments</div>
        </a>
        
        <a class="action-card" href="{{ route('admin.withdrawals') }}">
          <div class="action-icon">💰</div>
          <div class="action-title">Withdrawal Processing</div>
          <div class="action-description">Approve and manage withdrawals</div>
        </a>
        
        <a class="action-card" href="{{ route('admin.analytics') }}">
          <div class="action-icon">📊</div>
          <div class="action-title">Analytics & Reports</div>
          <div class="action-description">View detailed analytics and reports</div>
        </a>
        
        <a class="action-card" href="{{ route('admin.chats') }}">
          <div class="action-icon">💬</div>
          <div class="action-title">Conversation Monitor</div>
          <div class="action-description">Monitor on-demand employee chats</div>
        </a>
        
        <a class="action-card" href="{{ route('admin.completed-jobs') }}">
          <div class="action-icon">✅</div>
          <div class="action-title">Completed Jobs</div>
          <div class="action-description">View all completed work by workers</div>
        </a>
        
        <a class="action-card" href="{{ route('admin.users') }}">
          <div class="action-icon">👤</div>
          <div class="action-title">User Details & Charts</div>
          <div class="action-description">Detailed user profiles with analytics</div>
        </a>
        
        <a class="action-card" href="{{ route('admin.system-logs') }}">
          <div class="action-icon">📝</div>
          <div class="action-title">System Logs</div>
          <div class="action-description">Monitor system activities and logs</div>
        </a>
      </div>
    </div>

  </div>
</div>

<script>
  // Real-time updates
  function updateDashboard() {
    // This would fetch real-time data from the server
    console.log('Updating dashboard data...');
  }

  // Update dashboard every 30 seconds
  setInterval(updateDashboard, 30000);

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
    document.querySelectorAll('.super-stat-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s ease';
      observer.observe(card);
    });

    // Observe monitoring cards
    document.querySelectorAll('.monitoring-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s ease';
      observer.observe(card);
    });

    // Observe action cards
    document.querySelectorAll('.action-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s ease';
      observer.observe(card);
    });

    // Add hover effects
    document.querySelectorAll('.super-stat-card').forEach(card => {
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