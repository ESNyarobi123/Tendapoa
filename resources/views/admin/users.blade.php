@extends('layouts.app')
@section('title', 'Admin — User Management')

@section('content')
<style>
  /* ====== Modern Admin Users Page ====== */
  .users-page {
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

  .users-page {
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

  /* Users Table */
  .users-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .users-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  .users-table th {
    background: #f8fafc;
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: var(--dark);
    border-bottom: 2px solid var(--border);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .users-table td {
    padding: 16px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }

  .users-table tr:hover {
    background: #f8fafc;
  }

  .user-info {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--success));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.875rem;
  }

  .user-details h4 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 4px 0;
  }

  .user-details p {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
  }

  .role-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .role-badge.admin {
    background: #fef3c7;
    color: #92400e;
  }

  .role-badge.mfanyakazi {
    background: #dbeafe;
    color: #1e40af;
  }

  .role-badge.muhitaji {
    background: #d1fae5;
    color: #065f46;
  }

  .status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .status-badge.active {
    background: #d1fae5;
    color: #065f46;
  }

  .status-badge.inactive {
    background: #fecaca;
    color: #dc2626;
  }

  /* Buttons */
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.75rem;
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
    .users-page {
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
    
    .users-table {
      font-size: 0.875rem;
    }
    
    .users-table th,
    .users-table td {
      padding: 12px 8px;
    }
  }
</style>

<div class="users-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <div class="header-content">
        <div class="header-text">
          <h1>👥 User Management</h1>
          <p>Dhibiti na simamia watumiaji wote wa mfumo</p>
        </div>
        <div class="header-actions">
          <a class="btn btn-outline" href="{{ route('dashboard') }}">
            <span>↩️</span>
            Rudi Dashboard
          </a>
          <a class="btn btn-primary" href="{{ url('/admin/users/export') }}">
            <span>📊</span>
            Export Users
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value">{{ $users->total() }}</div>
        <div class="stat-label">Total Users</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">👷</div>
        <div class="stat-value">{{ $users->where('role', 'mfanyakazi')->count() }}</div>
        <div class="stat-label">Workers</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🧑‍💼</div>
        <div class="stat-value">{{ $users->where('role', 'muhitaji')->count() }}</div>
        <div class="stat-label">Clients</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🛠️</div>
        <div class="stat-value">{{ $users->where('role', 'admin')->count() }}</div>
        <div class="stat-label">Admins</div>
      </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
      <div class="filters-grid">
        <div class="filter-group">
          <label class="filter-label" for="search">Search Users</label>
          <input type="text" class="filter-input" id="search" placeholder="Search by name or email..." onkeyup="searchUsers(this.value)">
        </div>
        <div class="filter-group">
          <label class="filter-label" for="role">Filter by Role</label>
          <select class="filter-select" id="role" onchange="filterByRole(this.value)">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="mfanyakazi">Worker</option>
            <option value="muhitaji">Client</option>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label" for="status">Filter by Status</label>
          <select class="filter-select" id="status" onchange="filterByStatus(this.value)">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
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

    <!-- Users Table -->
    <div class="users-section">
      @if($users->count())
        <table class="users-table">
          <thead>
            <tr>
              <th>User</th>
              <th>Role</th>
              <th>Status</th>
              <th>Joined</th>
              <th>Last Active</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $user)
              <tr class="user-row" data-role="{{ $user->role }}" data-status="{{ $user->email_verified_at ? 'active' : 'inactive' }}">
                <td>
                  <div class="user-info">
                    <div class="user-avatar">
                      {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="user-details">
                      <h4>{{ $user->name }}</h4>
                      <p>{{ $user->email }}</p>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="role-badge {{ $user->role }}">
                    {{ ucfirst($user->role) }}
                  </span>
                </td>
                <td>
                  <span class="status-badge {{ $user->email_verified_at ? 'active' : 'inactive' }}">
                    {{ $user->email_verified_at ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td>{{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                <td>{{ $user->updated_at?->diffForHumans() ?? 'N/A' }}</td>
                <td>
                  <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <a class="btn btn-outline" href="{{ url('/admin/users/'.$user->id) }}">
                      <span>👁️</span>
                      View
                    </a>
                    @if($user->role !== 'admin')
                      <button class="btn btn-warning" onclick="toggleUserStatus({{ $user->id }})">
                        <span>🔄</span>
                        Toggle
                      </button>
                    @endif
                    @if($user->role !== 'admin')
                      <button class="btn btn-danger" onclick="deleteUser({{ $user->id }})">
                        <span>🗑️</span>
                        Delete
                      </button>
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-wrapper">
          {{ $users->links() }}
        </div>
      @else
        <div class="empty-state">
          <div class="empty-state-icon">👥</div>
          <h3>No Users Found</h3>
          <p>There are no users to display at the moment.</p>
        </div>
      @endif
    </div>

  </div>
</div>

<script>
  // Filter functions
  function searchUsers(query) {
    const rows = document.querySelectorAll('.user-row');
    rows.forEach(row => {
      const name = row.querySelector('h4').textContent.toLowerCase();
      const email = row.querySelector('p').textContent.toLowerCase();
      const searchTerm = query.toLowerCase();
      
      if (name.includes(searchTerm) || email.includes(searchTerm)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  function filterByRole(role) {
    const rows = document.querySelectorAll('.user-row');
    rows.forEach(row => {
      if (!role || row.dataset.role === role) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  function filterByStatus(status) {
    const rows = document.querySelectorAll('.user-row');
    rows.forEach(row => {
      if (!status || row.dataset.status === status) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('role').value = '';
    document.getElementById('status').value = '';
    
    const rows = document.querySelectorAll('.user-row');
    rows.forEach(row => {
      row.style.display = '';
    });
  }

  function toggleUserStatus(userId) {
    if (confirm('Are you sure you want to toggle this user\'s status?')) {
      // This would need a backend endpoint
      console.log('Toggle user status:', userId);
    }
  }

  function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
      // This would need a backend endpoint
      console.log('Delete user:', userId);
    }
  }

  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate table rows on scroll
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

    // Observe all table rows
    document.querySelectorAll('.user-row').forEach(row => {
      row.style.opacity = '0';
      row.style.transform = 'translateY(20px)';
      row.style.transition = 'all 0.6s ease';
      observer.observe(row);
    });

    // Add hover effects to table rows
    document.querySelectorAll('.user-row').forEach(row => {
      row.addEventListener('mouseenter', function() {
        this.style.backgroundColor = '#f8fafc';
        this.style.transform = 'translateX(4px)';
      });
      
      row.addEventListener('mouseleave', function() {
        this.style.backgroundColor = '';
        this.style.transform = 'translateX(0)';
      });
    });
  });
</script>
@endsection
