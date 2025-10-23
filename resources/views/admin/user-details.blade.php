@extends('layouts.app')
@section('title', 'Admin — User Details & Analytics')

@section('content')


<style>
  /* ====== Admin User Details Page ====== */
  .user-details-page {
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

  .user-details-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .page-container {
    max-width: 1600px;
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

  /* User Profile Card */
  .user-profile-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.2);
    margin-bottom: 24px;
  }

  .profile-header {
    display: flex;
    align-items: center;
    gap: 24px;
    margin-bottom: 24px;
  }

  .profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--success));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 900;
    font-size: 3rem;
    box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
  }

  .profile-info h2 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .profile-info p {
    color: var(--text-muted);
    font-size: 1.1rem;
    margin: 0 0 16px 0;
  }

  .profile-badges {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  .profile-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .profile-badge.admin {
    background: #fef3c7;
    color: #92400e;
  }

  .profile-badge.mfanyakazi {
    background: #dbeafe;
    color: #1e40af;
  }

  .profile-badge.muhitaji {
    background: #d1fae5;
    color: #065f46;
  }

  .profile-badge.verified {
    background: #d1fae5;
    color: #065f46;
  }

  .profile-badge.unverified {
    background: #fecaca;
    color: #dc2626;
  }

  .profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    margin-top: 24px;
  }

  .profile-stat {
    text-align: center;
    padding: 20px;
    background: #f8fafc;
    border-radius: 16px;
    border: 1px solid var(--border);
  }

  .profile-stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 4px;
  }

  .profile-stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  /* Analytics Grid */
  .analytics-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
  }

  .analytics-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .analytics-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
  }

  .analytics-icon {
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

  .analytics-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
  }

  .chart-container {
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
    font-size: 1.1rem;
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
    font-size: 1rem;
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

  /* User Activity */
  .activity-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .activity-list {
    display: grid;
    gap: 16px;
  }

  .activity-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .activity-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--success));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
  }

  .activity-content h4 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 4px 0;
  }

  .activity-content p {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
  }

  .activity-time {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-align: right;
  }

  /* User Jobs */
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

  .job-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .job-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .job-info h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 4px 0;
  }

  .job-info p {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
  }

  .job-status {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .job-status.completed {
    background: #d1fae5;
    color: #065f46;
  }

  .job-status.in-progress {
    background: #dbeafe;
    color: #1e40af;
  }

  .job-status.posted {
    background: #fef3c7;
    color: #92400e;
  }

  .job-price {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--success);
    text-align: right;
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

  /* Responsive */
  @media (max-width: 768px) {
    .user-details-page {
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
    
    .profile-header {
      flex-direction: column;
      text-align: center;
    }
    
    .analytics-grid {
      grid-template-columns: 1fr;
    }
    
    .profile-stats {
      grid-template-columns: 1fr;
    }
  }
</style>

@php
  // IMPORTANT: Do NOT override $user coming from controller.
  // Remove legacy demo fallback; use passed-in $user only.
  $userJobs = isset($user) && isset($user->jobs) ? $user->jobs : collect();
  $completedJobs = $userJobs->where('status', 'completed');
  $totalEarnings = $completedJobs->sum('price');
  $activeJobs = $userJobs->where('status', 'in_progress');
@endphp

<div class="user-details-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <div class="header-content">
        <div class="header-text">
          <h1>👤 User Details & Analytics</h1>
          <p>Comprehensive user profile with detailed analytics and activity monitoring</p>
        </div>
        <div class="header-actions">
          <div class="flex space-x-3 flex-wrap">
            <!-- Admin Impersonation -->
            <a href="{{ route('admin.impersonate', $user) }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105"
               onclick="return confirm('Are you sure you want to login as this user?')">
              🎭 Login as User
            </a>
            
            <!-- Edit User -->
            <a href="{{ route('admin.user.edit', $user) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
              ✏️ Edit User
            </a>
            
            <!-- Toggle Status -->
            <form action="{{ route('admin.user.toggle-status', $user) }}" method="POST" class="inline">
              @csrf
              <button type="submit" 
                      class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105"
                      onclick="return confirm('Are you sure you want to {{ $user->is_active ? 'suspend' : 'activate' }} this user?')">
                {{ $user->is_active ? '🚫 Suspend' : '✅ Activate' }}
              </button>
            </form>
            
            <!-- Delete User -->
            @if($user->id !== auth()->id())
            <form action="{{ route('admin.user.delete', $user) }}" method="POST" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" 
                      class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105"
                      onclick="return confirm('Are you sure you want to DELETE this user? This action cannot be undone!')">
                🗑️ Delete
              </button>
            </form>
            @endif
            
            <!-- Back Buttons -->
            <a class="btn btn-outline" href="{{ route('dashboard') }}">
              <span>↩️</span>
              Rudi Dashboard
            </a>
            <a class="btn btn-primary" href="{{ url('/admin/users') }}">
              <span>👥</span>
              All Users
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- User Profile Card -->
    <div class="user-profile-card">
      <div class="profile-header">
        <div class="profile-avatar">
          {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div class="profile-info">
          <h2>{{ $user->name }}</h2>
          <p>{{ $user->email }} • {{ $user->phone ?? 'No phone' }}</p>
          <p style="font-size: 0.8rem; color: #666;">User ID: {{ $user->id }}</p>
          <p style="font-size: 0.8rem; color: #666;">Request ID: {{ request()->route('user') }}</p>
          <div class="profile-badges">
            <span class="profile-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
            <span class="profile-badge {{ $user->email_verified_at ? 'verified' : 'unverified' }}">
              {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
            </span>
            <span class="profile-badge verified">Active</span>
          </div>
        </div>
      </div>

        <div class="profile-stats">
          <div class="profile-stat">
            <div class="profile-stat-value">{{ isset($stats) ? ($stats['jobs_posted'] + $stats['jobs_assigned']) : 0 }}</div>
            <div class="profile-stat-label">Total Jobs</div>
          </div>
          <div class="profile-stat">
            <div class="profile-stat-value">{{ isset($stats) ? $stats['jobs_completed'] : 0 }}</div>
            <div class="profile-stat-label">Completed</div>
          </div>
          <div class="profile-stat">
            <div class="profile-stat-value">{{ isset($stats) ? $stats['jobs_in_progress'] : 0 }}</div>
            <div class="profile-stat-label">In Progress</div>
          </div>
          <div class="profile-stat">
            <div class="profile-stat-value">TZS {{ isset($stats) ? number_format($stats['total_earned']) : '0' }}</div>
            <div class="profile-stat-label">Total Earned</div>
          </div>
          <div class="profile-stat">
            <div class="profile-stat-value">TZS {{ isset($stats) ? number_format($stats['total_spent']) : '0' }}</div>
            <div class="profile-stat-label">Total Spent</div>
          </div>
          <div class="profile-stat">
            <div class="profile-stat-value">TZS {{ isset($stats) ? number_format($stats['wallet_balance']) : '0' }}</div>
            <div class="profile-stat-label">Wallet Balance</div>
          </div>
          <div class="profile-stat">
            <div class="profile-stat-value">{{ isset($stats) ? ($stats['messages_sent'] + $stats['messages_received']) : 0 }}</div>
            <div class="profile-stat-label">Total Messages</div>
          </div>
          <div class="profile-stat">
            <div class="profile-stat-value">{{ isset($stats) ? $stats['total_conversations'] : 0 }}</div>
            <div class="profile-stat-label">Conversations</div>
          </div>
          <div class="profile-stat">
            <div class="profile-stat-value">{{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</div>
            <div class="profile-stat-label">Joined</div>
          </div>
          <div class="profile-stat">
            <div class="profile-stat-value">{{ $user->created_at ? $user->created_at->diffInDays() : 0 }}</div>
            <div class="profile-stat-label">Days Active</div>
          </div>
        </div>
    </div>

    <!-- Analytics Grid -->
    <div class="analytics-grid">
      <!-- Performance Analytics -->
      <div class="analytics-section">
        <div class="analytics-header">
          <div class="analytics-icon">📊</div>
          <div class="analytics-title">Performance Analytics</div>
        </div>
        
        <div class="chart-container">
          <div class="chart-header">
            <div class="chart-title">
              <span>📈</span>
              Earnings Over Time
            </div>
          </div>
          <div class="chart-placeholder">
            📊 Earnings Chart (Chart.js integration needed)
          </div>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <div class="chart-title">
              <span>📋</span>
              Job Completion Rate
            </div>
          </div>
          <div class="chart-placeholder">
            📊 Completion Rate Chart (Chart.js integration needed)
          </div>
        </div>
      </div>

      <!-- ALL USER ACTIVITIES - REAL DATA -->
      <div class="activity-section">
        <div class="analytics-header">
          <div class="analytics-icon">⚡</div>
          <div class="analytics-title">All User Activities ({{ isset($activities) ? $activities->count() : 0 }})</div>
        </div>
        
        @if(isset($activities) && $activities->count())
        <div class="activity-list">
          @foreach($activities->take(50) as $activity)
          <div class="activity-item" style="border-left: 4px solid 
            @if($activity['color'] == 'blue') #3b82f6
            @elseif($activity['color'] == 'green') #10b981
            @elseif($activity['color'] == 'purple') #8b5cf6
            @elseif($activity['color'] == 'indigo') #6366f1
            @elseif($activity['color'] == 'orange') #f59e0b
            @else #6b7280
            @endif">
            <div class="activity-icon">{{ $activity['icon'] }}</div>
            <div class="activity-content">
              <h4>{{ $activity['title'] }}</h4>
              <p><strong>{{ $activity['description'] }}</strong></p>
              <p style="font-size: 0.875rem; color: var(--text-muted); margin-top: 4px;">
                {{ $activity['details'] }}
              </p>
              @if($activity['link'])
              <a href="{{ $activity['link'] }}" style="font-size: 0.875rem; color: #3b82f6; text-decoration: none; margin-top: 4px; display: inline-block;">
                View Details →
              </a>
              @endif
            </div>
            <div class="activity-time">{{ $activity['timestamp']->diffForHumans() }}</div>
          </div>
          @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 40px; color: var(--text-muted);">
          <div style="font-size: 3rem; margin-bottom: 16px; opacity: 0.6;">⚡</div>
          <h3>No Activities Yet</h3>
          <p>This user hasn't performed any activities yet.</p>
        </div>
        @endif
      </div>
    </div>

    <!-- User Jobs - All Jobs Posted and Assigned -->
    <div class="jobs-section">
      <div class="analytics-header">
        <div class="analytics-icon">📋</div>
        <div class="analytics-title">User Jobs ({{ (isset($user->jobs) ? $user->jobs->count() : 0) + (isset($user->assignedJobs) ? $user->assignedJobs->count() : 0) }})</div>
      </div>
      
      @if(isset($user->jobs) && $user->jobs->count() > 0 || isset($user->assignedJobs) && $user->assignedJobs->count() > 0)
        <div class="jobs-list">
          <!-- Jobs Posted by User (as Muhitaji) -->
          @if(isset($user->jobs))
          @foreach($user->jobs as $job)
            <div class="job-item" style="border-left: 4px solid #3b82f6;">
              <div class="job-info">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                  <span style="background: #3b82f6; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">POSTED</span>
                  <h4 style="margin: 0;">{{ $job->title }}</h4>
                </div>
                <p style="color: var(--text-muted); margin: 0;">
                  Posted {{ $job->created_at->diffForHumans() }} • 
                  Budget: TZS {{ number_format($job->budget ?? $job->amount) }} •
                  Category: {{ $job->category->name ?? 'N/A' }}
                </p>
                @if($job->acceptedWorker)
                <p style="color: var(--text-muted); margin: 4px 0 0 0; font-size: 0.875rem;">
                  <strong>Worker:</strong> <a href="{{ route('admin.user.details', $job->acceptedWorker) }}" style="color: #3b82f6; text-decoration: none;">{{ $job->acceptedWorker->name }}</a>
                </p>
                @endif
              </div>
              <div style="text-align: right;">
                <div class="job-status {{ str_replace('_', '-', $job->status) }}" style="margin-bottom: 8px;">
                  {{ strtoupper($job->status) }}
                </div>
                <div class="job-price" style="font-size: 1.25rem; font-weight: 700; color: var(--dark);">
                  TZS {{ number_format($job->budget ?? $job->amount) }}
                </div>
                <a href="{{ route('admin.job.details', $job) }}" style="display: inline-block; margin-top: 8px; color: #3b82f6; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                  View Details →
                </a>
              </div>
            </div>
          @endforeach
          @endif

          <!-- Jobs Assigned to User (as Mfanyakazi) -->
          @if(isset($user->assignedJobs))
          @foreach($user->assignedJobs as $job)
            <div class="job-item" style="border-left: 4px solid #10b981;">
              <div class="job-info">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                  <span style="background: #10b981; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">ASSIGNED</span>
                  <h4 style="margin: 0;">{{ $job->title }}</h4>
                </div>
                <p style="color: var(--text-muted); margin: 0;">
                  Assigned {{ $job->accepted_at ? $job->accepted_at->diffForHumans() : $job->updated_at->diffForHumans() }} • 
                  Amount: TZS {{ number_format($job->amount) }} •
                  Category: {{ $job->category->name ?? 'N/A' }}
                </p>
                <p style="color: var(--text-muted); margin: 4px 0 0 0; font-size: 0.875rem;">
                  <strong>Posted by:</strong> <a href="{{ route('admin.user.details', $job->muhitaji) }}" style="color: #10b981; text-decoration: none;">{{ $job->muhitaji->name }}</a>
                </p>
              </div>
              <div style="text-align: right;">
                <div class="job-status {{ str_replace('_', '-', $job->status) }}" style="margin-bottom: 8px;">
                  {{ strtoupper($job->status) }}
                </div>
                <div class="job-price" style="font-size: 1.25rem; font-weight: 700; color: var(--dark);">
                  TZS {{ number_format($job->amount) }}
                </div>
                <a href="{{ route('admin.job.details', $job) }}" style="display: inline-block; margin-top: 8px; color: #10b981; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                  View Details →
                </a>
              </div>
            </div>
          @endforeach
          @endif
        </div>
      @else
        <div style="text-align: center; padding: 40px; color: var(--text-muted);">
          <div style="font-size: 3rem; margin-bottom: 16px; opacity: 0.6;">📋</div>
          <h3>No Jobs Found</h3>
          <p>This user hasn't created or been assigned any jobs yet.</p>
        </div>
      @endif
    </div>

    <!-- User Conversations -->
    <div class="analytics-section">
      <div class="analytics-header">
        <div class="analytics-icon">💬</div>
        <div class="analytics-title">All Conversations ({{ isset($conversations) ? $conversations->count() : 0 }})</div>
      </div>
      
      @if(isset($conversations) && $conversations->count())
        <div style="display: grid; gap: 16px;">
          @foreach($conversations as $conversation)
            @if($conversation->job)
            <div style="background: rgba(255,255,255,0.95); border-radius: 16px; padding: 20px; box-shadow: var(--shadow); border-left: 4px solid #8b5cf6;">
              <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                  <h4 style="color: var(--dark); margin: 0 0 8px 0;">{{ $conversation->job->title }}</h4>
                  <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">
                    <strong>Muhitaji:</strong> {{ $conversation->job->muhitaji->name }} | 
                    <strong>Worker:</strong> {{ $conversation->job->acceptedWorker ? $conversation->job->acceptedWorker->name : 'Not assigned' }}
                  </p>
                  <p style="color: var(--text-muted); margin: 8px 0 0 0; font-size: 0.875rem;">
                    Job Status: <span style="color: 
                      @if($conversation->job->status == 'completed') #10b981
                      @elseif($conversation->job->status == 'in_progress') #f59e0b
                      @else #3b82f6
                      @endif; font-weight: 600;">{{ ucfirst($conversation->job->status) }}</span>
                  </p>
                </div>
                <a href="{{ route('admin.chat.view', $conversation->job) }}" 
                   style="background: #8b5cf6; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: all 0.3s;">
                  View Chat →
                </a>
              </div>
            </div>
            @endif
          @endforeach
        </div>
      @else
        <div style="text-align: center; padding: 40px; color: var(--text-muted);">
          <div style="font-size: 3rem; margin-bottom: 16px; opacity: 0.6;">💬</div>
          <h3>No Conversations</h3>
          <p>This user hasn't started any conversations yet.</p>
        </div>
      @endif
    </div>

    <!-- User Actions -->
    <div class="analytics-section">
      <div class="analytics-header">
        <div class="analytics-icon">⚙️</div>
        <div class="analytics-title">User Management Actions</div>
      </div>
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
        <a class="btn btn-primary" href="{{ url('/admin/users/'.$user->id.'/edit') }}">
          <span>✏️</span>
          Edit Profile
        </a>
        
        <a class="btn btn-success" href="{{ url('/admin/users/'.$user->id.'/jobs') }}">
          <span>📋</span>
          View All Jobs
        </a>
        
        <a class="btn btn-warning" href="{{ url('/admin/users/'.$user->id.'/wallet') }}">
          <span>💰</span>
          Manage Wallet
        </a>
        
        <a class="btn btn-outline" href="{{ url('/admin/users/'.$user->id.'/conversations') }}">
          <span>💬</span>
          View Conversations
        </a>
        
        <a class="btn btn-danger" href="{{ url('/admin/users/'.$user->id.'/suspend') }}">
          <span>🚫</span>
          Suspend User
        </a>
        
        <a class="btn btn-outline" href="{{ url('/admin/users/'.$user->id.'/analytics') }}">
          <span>📊</span>
          Detailed Analytics
        </a>
      </div>
    </div>

  </div>
</div>

<script>
  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate profile card on scroll
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

    // Observe all sections
    document.querySelectorAll('.analytics-section, .activity-section, .jobs-section').forEach(section => {
      section.style.opacity = '0';
      section.style.transform = 'translateY(20px)';
      section.style.transition = 'all 0.6s ease';
      observer.observe(section);
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

    // Add hover effects to activity items
    document.querySelectorAll('.activity-item').forEach(item => {
      item.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px) translateX(4px)';
      });
      
      item.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) translateX(0)';
      });
    });
  });
</script>
@endsection
