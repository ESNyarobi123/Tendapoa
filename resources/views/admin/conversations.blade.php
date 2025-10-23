@extends('layouts.app')
@section('title', 'Admin — Conversation Monitor')

@section('content')
<style>
  /* ====== Admin Conversation Monitor Page ====== */
  .conversations-page {
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

  .conversations-page {
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

  /* Conversations Grid */
  .conversations-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .conversations-grid {
    display: grid;
    gap: 20px;
  }

  .conversation-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .conversation-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .conversation-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--border);
  }

  .conversation-participants {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .participant {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .participant-avatar {
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

  .participant-info h4 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
  }

  .participant-info p {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
  }

  .conversation-status {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .conversation-status.active {
    background: #d1fae5;
    color: #065f46;
  }

  .conversation-status.ended {
    background: #fecaca;
    color: #dc2626;
  }

  .conversation-status.pending {
    background: #fef3c7;
    color: #92400e;
  }

  .conversation-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
  }

  .detail-group {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
  }

  .detail-group h5 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0 0 8px 0;
  }

  .detail-group p {
    font-size: 0.875rem;
    color: var(--dark);
    margin: 0;
  }

  .conversation-messages {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
    max-height: 300px;
    overflow-y: auto;
  }

  .message {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
    padding: 12px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
  }

  .message:last-child {
    margin-bottom: 0;
  }

  .message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--success));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.75rem;
    flex-shrink: 0;
  }

  .message-content {
    flex: 1;
  }

  .message-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
  }

  .message-sender {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--dark);
  }

  .message-time {
    font-size: 0.75rem;
    color: var(--text-muted);
  }

  .message-text {
    font-size: 0.875rem;
    color: var(--dark);
    line-height: 1.5;
  }

  .conversation-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
  }

  /* Live Monitor */
  .live-monitor {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
    margin-bottom: 24px;
  }

  .live-monitor-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
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

  .live-monitor-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
  }

  .live-conversations {
    display: grid;
    gap: 12px;
  }

  .live-conversation {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .live-conversation:hover {
    transform: translateX(4px);
    box-shadow: var(--shadow-lg);
  }

  .live-avatar {
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

  .live-details h4 {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 2px 0;
  }

  .live-details p {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin: 0;
  }

  .live-status {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: #d1fae5;
    color: #065f46;
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

  /* Responsive */
  @media (max-width: 768px) {
    .conversations-page {
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
    
    .conversation-details {
      grid-template-columns: 1fr;
    }
    
    .conversation-actions {
      flex-direction: column;
    }
  }
</style>

@php
  // Mock data for conversations - in real implementation, this would come from a conversations table
  $conversations = collect([
    (object)[
      'id' => 1,
      'muhitaji' => (object)['name' => 'John Doe', 'email' => 'john@example.com'],
      'mfanyakazi' => (object)['name' => 'Jane Smith', 'email' => 'jane@example.com'],
      'job' => (object)['title' => 'House Cleaning', 'price' => 50000],
      'status' => 'active',
      'started_at' => now()->subHours(2),
      'last_message' => now()->subMinutes(15),
      'messages' => [
        (object)['sender' => 'muhitaji', 'text' => 'Hello, I need my house cleaned', 'time' => now()->subHours(2)],
        (object)['sender' => 'mfanyakazi', 'text' => 'Hi! I can help with that. When do you need it done?', 'time' => now()->subHours(1)->subMinutes(45)],
        (object)['sender' => 'muhitaji', 'text' => 'This weekend would be perfect', 'time' => now()->subHours(1)->subMinutes(30)],
        (object)['sender' => 'mfanyakazi', 'text' => 'Great! I can do Saturday morning. Is 9 AM okay?', 'time' => now()->subMinutes(15)],
      ]
    ],
    (object)[
      'id' => 2,
      'muhitaji' => (object)['name' => 'Alice Johnson', 'email' => 'alice@example.com'],
      'mfanyakazi' => (object)['name' => 'Bob Wilson', 'email' => 'bob@example.com'],
      'job' => (object)['title' => 'Garden Maintenance', 'price' => 75000],
      'status' => 'ended',
      'started_at' => now()->subDays(1),
      'last_message' => now()->subHours(3),
      'messages' => [
        (object)['sender' => 'muhitaji', 'text' => 'My garden needs some maintenance', 'time' => now()->subDays(1)],
        (object)['sender' => 'mfanyakazi', 'text' => 'I specialize in garden work. What needs to be done?', 'time' => now()->subDays(1)->addMinutes(30)],
        (object)['sender' => 'muhitaji', 'text' => 'Trimming, weeding, and planting some flowers', 'time' => now()->subHours(5)],
        (object)['sender' => 'mfanyakazi', 'text' => 'Perfect! I can start tomorrow morning', 'time' => now()->subHours(3)],
      ]
    ]
  ]);

  $totalConversations = $conversations->count();
  $activeConversations = $conversations->where('status', 'active')->count();
  $endedConversations = $conversations->where('status', 'ended')->count();
  $totalMessages = $conversations->sum(fn($c) => count($c->messages));
@endphp

<div class="conversations-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <div class="header-content">
        <div class="header-text">
          <h1>💬 Conversation Monitor</h1>
          <p>Monitor all conversations between muhitaji and mfanyakazi in real-time</p>
        </div>
        <div class="header-actions">
          <a class="btn btn-outline" href="{{ route('dashboard') }}">
            <span>↩️</span>
            Rudi Dashboard
          </a>
          <a class="btn btn-primary" href="{{ url('/admin/analytics') }}">
            <span>📊</span>
            Analytics
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">💬</div>
        <div class="stat-value">{{ number_format($totalConversations) }}</div>
        <div class="stat-label">Total Conversations</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⚡</div>
        <div class="stat-value">{{ number_format($activeConversations) }}</div>
        <div class="stat-label">Active Now</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ number_format($endedConversations) }}</div>
        <div class="stat-label">Completed</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-value">{{ number_format($totalMessages) }}</div>
        <div class="stat-label">Total Messages</div>
      </div>
    </div>

    <!-- Live Monitor -->
    <div class="live-monitor">
      <div class="live-monitor-header">
        <div class="live-indicator"></div>
        <div class="live-monitor-title">Live Active Conversations</div>
      </div>
      <div class="live-conversations">
        @foreach($conversations->where('status', 'active') as $conversation)
          <div class="live-conversation">
            <div class="live-avatar">
              {{ strtoupper(substr($conversation->muhitaji->name, 0, 1)) }}
            </div>
            <div class="live-details">
              <h4>{{ $conversation->muhitaji->name }} ↔ {{ $conversation->mfanyakazi->name }}</h4>
              <p>{{ $conversation->job->title }} • Last message {{ $conversation->last_message->diffForHumans() }}</p>
            </div>
            <div class="live-status">Active</div>
          </div>
        @endforeach
        @if($activeConversations === 0)
          <div class="live-conversation">
            <div class="live-details">
              <h4>No active conversations</h4>
              <p>All conversations are currently ended</p>
            </div>
          </div>
        @endif
      </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
      <div class="filters-grid">
        <div class="filter-group">
          <label class="filter-label" for="search">Search Conversations</label>
          <input type="text" class="filter-input" id="search" placeholder="Search by participant names..." onkeyup="searchConversations(this.value)">
        </div>
        <div class="filter-group">
          <label class="filter-label" for="status">Filter by Status</label>
          <select class="filter-select" id="status" onchange="filterByStatus(this.value)">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="ended">Ended</option>
            <option value="pending">Pending</option>
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

    <!-- Conversations List -->
    <div class="conversations-section">
      @if($conversations->count())
        <div class="conversations-grid">
          @foreach($conversations as $conversation)
            <div class="conversation-card" data-status="{{ $conversation->status }}">
              
              <!-- Conversation Header -->
              <div class="conversation-header">
                <div class="conversation-participants">
                  <div class="participant">
                    <div class="participant-avatar">
                      {{ strtoupper(substr($conversation->muhitaji->name, 0, 1)) }}
                    </div>
                    <div class="participant-info">
                      <h4>{{ $conversation->muhitaji->name }}</h4>
                      <p>{{ $conversation->muhitaji->email }}</p>
                    </div>
                  </div>
                  
                  <div style="font-size: 1.5rem; color: var(--text-muted);">↔</div>
                  
                  <div class="participant">
                    <div class="participant-avatar">
                      {{ strtoupper(substr($conversation->mfanyakazi->name, 0, 1)) }}
                    </div>
                    <div class="participant-info">
                      <h4>{{ $conversation->mfanyakazi->name }}</h4>
                      <p>{{ $conversation->mfanyakazi->email }}</p>
                    </div>
                  </div>
                </div>
                
                <div class="conversation-status {{ $conversation->status }}">
                  {{ strtoupper($conversation->status) }}
                </div>
              </div>

              <!-- Conversation Details -->
              <div class="conversation-details">
                <div class="detail-group">
                  <h5>Job Details</h5>
                  <p><strong>Title:</strong> {{ $conversation->job->title }}</p>
                  <p><strong>Price:</strong> TZS {{ number_format($conversation->job->price) }}</p>
                </div>
                <div class="detail-group">
                  <h5>Conversation Info</h5>
                  <p><strong>Started:</strong> {{ $conversation->started_at->format('M d, Y H:i') }}</p>
                  <p><strong>Last Message:</strong> {{ $conversation->last_message->diffForHumans() }}</p>
                </div>
              </div>

              <!-- Messages -->
              <div class="conversation-messages">
                <h5 style="margin-bottom: 12px; color: var(--dark);">Messages ({{ count($conversation->messages) }}):</h5>
                @foreach($conversation->messages as $message)
                  <div class="message">
                    <div class="message-avatar">
                      {{ strtoupper(substr($message->sender === 'muhitaji' ? $conversation->muhitaji->name : $conversation->mfanyakazi->name, 0, 1)) }}
                    </div>
                    <div class="message-content">
                      <div class="message-header">
                        <span class="message-sender">{{ ucfirst($message->sender) }}</span>
                        <span class="message-time">{{ $message->time->format('H:i') }}</span>
                      </div>
                      <div class="message-text">{{ $message->text }}</div>
                    </div>
                  </div>
                @endforeach
              </div>

              <!-- Actions -->
              <div class="conversation-actions">
                <a class="btn btn-outline" href="{{ url('/admin/conversations/'.$conversation->id) }}">
                  <span>👁️</span>
                  View Full Conversation
                </a>
                <a class="btn btn-primary" href="{{ url('/admin/users/'.($conversation->muhitaji->id ?? 1)) }}">
                  <span>👤</span>
                  View Muhitaji
                </a>
                <a class="btn btn-success" href="{{ url('/admin/users/'.($conversation->mfanyakazi->id ?? 1)) }}">
                  <span>👷</span>
                  View Mfanyakazi
                </a>
                @if($conversation->status === 'active')
                  <button class="btn btn-warning" onclick="endConversation({{ $conversation->id }})">
                    <span>🔚</span>
                    End Conversation
                  </button>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="empty-state">
          <div class="empty-state-icon">💬</div>
          <h3>No Conversations Found</h3>
          <p>There are no conversations to display at the moment.</p>
        </div>
      @endif
    </div>

  </div>
</div>

<script>
  // Filter functions
  function searchConversations(query) {
    const cards = document.querySelectorAll('.conversation-card');
    cards.forEach(card => {
      const text = card.textContent.toLowerCase();
      const searchTerm = query.toLowerCase();
      
      if (text.includes(searchTerm)) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function filterByStatus(status) {
    const cards = document.querySelectorAll('.conversation-card');
    cards.forEach(card => {
      if (!status || card.dataset.status === status) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function filterByDate(dateRange) {
    // This would need server-side implementation for proper date filtering
    const cards = document.querySelectorAll('.conversation-card');
    cards.forEach(card => {
      card.style.display = 'block';
    });
  }

  function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('status').value = '';
    document.getElementById('date').value = '';
    
    const cards = document.querySelectorAll('.conversation-card');
    cards.forEach(card => {
      card.style.display = 'block';
    });
  }

  function endConversation(conversationId) {
    if (confirm('Are you sure you want to end this conversation?')) {
      // This would need a backend endpoint
      console.log('End conversation:', conversationId);
    }
  }

  // Real-time updates
  function updateLiveConversations() {
    // This would fetch real-time data from the server
    console.log('Updating live conversations...');
  }

  // Update every 10 seconds
  setInterval(updateLiveConversations, 10000);

  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate conversation cards on scroll
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

    // Observe all conversation cards
    document.querySelectorAll('.conversation-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s ease';
      observer.observe(card);
    });

    // Add hover effects
    document.querySelectorAll('.conversation-card').forEach(card => {
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
