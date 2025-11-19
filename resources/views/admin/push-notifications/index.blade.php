@extends('layouts.app')
@section('title', 'Admin — Push Notifications')

@section('content')
<style>
  .push-notification-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .notification-container {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    gap: 24px;
  }

  .notification-header {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
  }

  .header-text h1 {
    font-size: 2.5rem;
    font-weight: 900;
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, #3b82f6, #10b981);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .header-text p {
    color: #6b7280;
    font-size: 1.1rem;
    margin: 0;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #3b82f6, #10b981);
    border-radius: 20px 20px 0 0;
  }

  .stat-card {
    position: relative;
  }

  .stat-value {
    font-size: 2.5rem;
    font-weight: 900;
    color: #1f2937;
    margin: 12px 0;
  }

  .stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .form-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .form-group {
    margin-bottom: 24px;
  }

  .form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    font-size: 0.95rem;
  }

  .form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
  }

  .form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  .form-textarea {
    min-height: 120px;
    resize: vertical;
  }

  .checkbox-group {
    background: #f8fafc;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    max-height: 400px;
    overflow-y: auto;
  }

  .token-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: all 0.2s ease;
  }

  .token-item:hover {
    background: #f0f9ff;
    transform: translateX(4px);
  }

  .token-item input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
  }

  .token-info {
    flex: 1;
  }

  .token-user {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
  }

  .token-details {
    font-size: 0.875rem;
    color: #6b7280;
  }

  .select-all-btn {
    background: linear-gradient(135deg, #3b82f6, #10b981);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 16px;
    transition: all 0.3s ease;
  }

  .select-all-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
  }

  .send-btn {
    background: linear-gradient(135deg, #3b82f6, #10b981);
    color: white;
    border: none;
    padding: 16px 32px;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
  }

  .send-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
  }

  .send-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  .history-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .history-item {
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    margin-bottom: 12px;
    border-left: 4px solid #3b82f6;
    transition: all 0.2s ease;
  }

  .history-item:hover {
    background: #f0f9ff;
    transform: translateX(4px);
  }

  .history-title {
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
  }

  .history-body {
    color: #6b7280;
    margin-bottom: 12px;
  }

  .history-meta {
    display: flex;
    gap: 16px;
    font-size: 0.875rem;
    color: #9ca3af;
  }

  .status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
  }

  .status-completed {
    background: #d1fae5;
    color: #065f46;
  }

  .status-failed {
    background: #fee2e2;
    color: #991b1b;
  }

  .status-sending {
    background: #dbeafe;
    color: #1e40af;
  }

  .alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    font-weight: 500;
  }

  .alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 2px solid #10b981;
  }

  .alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #ef4444;
  }
</style>

<div class="push-notification-page">
  <div class="notification-container">
    <!-- Header -->
    <div class="notification-header">
      <div class="header-content">
        <div class="header-text">
          <h1>📱 Push Notifications</h1>
          <p>Tuma taarifa kwa watumiaji wote au chagua watumiaji maalum</p>
        </div>
        <div>
          <a href="{{ route('admin.push-notifications.history') }}" style="background: linear-gradient(135deg, #3b82f6, #10b981); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-block;">
            📜 Historia
          </a>
        </div>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-label">Jumla ya Tokens</div>
        <div class="stat-value">{{ number_format($totalTokens) }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Taarifa Zilizotumwa</div>
        <div class="stat-value">{{ $recentNotifications->count() }}</div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">
        ✅ {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-error">
        ❌ {{ $errors->first() }}
      </div>
    @endif

    <!-- Form Section -->
    <div class="form-section">
      <form action="{{ route('admin.push-notifications.send') }}" method="POST" id="notificationForm">
        @csrf

        <div class="form-group">
          <label class="form-label">Kichwa (Title) *</label>
          <input type="text" name="title" class="form-input" placeholder="Andika kichwa cha taarifa" required value="{{ old('title') }}">
        </div>

        <div class="form-group">
          <label class="form-label">Maelezo (Body) *</label>
          <textarea name="body" class="form-input form-textarea" placeholder="Andika maelezo ya taarifa" required>{{ old('body') }}</textarea>
        </div>

        <div class="form-group">
          <label style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <input type="checkbox" name="send_to_all" id="sendToAll" value="1" style="width: 20px; height: 20px;">
            <span style="font-weight: 600; color: #374151;">Tuma kwa watumiaji wote ({{ number_format($totalTokens) }} tokens)</span>
          </label>

          <div id="tokenSelection" style="display: none;">
            <button type="button" class="select-all-btn" onclick="selectAllTokens()">✓ Chagua Zote</button>
            <div class="checkbox-group" id="tokenList">
              <p style="text-align: center; color: #6b7280; padding: 20px;">Inapakia tokens...</p>
            </div>
          </div>
        </div>

        <button type="submit" class="send-btn" id="sendBtn">
          🚀 Tuma Taarifa
        </button>
      </form>
    </div>

    <!-- Recent History -->
    @if($recentNotifications->count() > 0)
      <div class="history-section">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 20px; color: #1f2937;">Historia ya Hivi Karibuni</h2>
        @foreach($recentNotifications as $notification)
          <div class="history-item">
            <div class="history-title">{{ $notification->title }}</div>
            <div class="history-body">{{ $notification->body }}</div>
            <div class="history-meta">
              <span>📅 {{ $notification->sent_at ? $notification->sent_at->format('d/m/Y H:i') : 'Hajatumwa' }}</span>
              <span>👥 {{ $notification->total_recipients }} watumiaji</span>
              <span>✅ {{ $notification->successful_sends }} imefanikiwa</span>
              <span>❌ {{ $notification->failed_sends }} imeshindwa</span>
              <span class="status-badge status-{{ $notification->status }}">{{ $notification->status }}</span>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>

<script>
  // Load tokens
  fetch('{{ route("api.admin.push-notifications.tokens") }}')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const tokenList = document.getElementById('tokenList');
        if (data.tokens.length === 0) {
          tokenList.innerHTML = '<p style="text-align: center; color: #6b7280; padding: 20px;">Hakuna tokens zilizoandikishwa bado</p>';
        } else {
          tokenList.innerHTML = data.tokens.map(token => `
            <div class="token-item">
              <input type="checkbox" name="token_ids[]" value="${token.id}" class="token-checkbox">
              <div class="token-info">
                <div class="token-user">${token.user ? token.user.name : 'User #' + token.user_id}</div>
                <div class="token-details">
                  ${token.device_type || 'Unknown'} • ${token.created_at ? new Date(token.created_at).toLocaleDateString() : 'N/A'}
                </div>
              </div>
            </div>
          `).join('');
        }
      }
    })
    .catch(error => {
      console.error('Error loading tokens:', error);
      document.getElementById('tokenList').innerHTML = '<p style="text-align: center; color: #ef4444; padding: 20px;">Hitilafu ya kupakia tokens</p>';
    });

  // Toggle token selection
  document.getElementById('sendToAll').addEventListener('change', function() {
    const tokenSelection = document.getElementById('tokenSelection');
    if (this.checked) {
      tokenSelection.style.display = 'none';
    } else {
      tokenSelection.style.display = 'block';
    }
  });

  // Select all tokens
  function selectAllTokens() {
    const checkboxes = document.querySelectorAll('.token-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
  }

  // Form submission
  document.getElementById('notificationForm').addEventListener('submit', function(e) {
    const sendBtn = document.getElementById('sendBtn');
    sendBtn.disabled = true;
    sendBtn.textContent = '⏳ Inatumwa...';
  });
</script>
@endsection

