@extends('layouts.app')
@section('title', 'Chat - ' . $job->title)

@section('content')
<style>
  /* ====== AMAZING CHAT DESIGN ====== */
  .amazing-chat {
    --chat-primary: #3b82f6;
    --chat-secondary: #10b981;
    --gradient-start: #667eea;
    --gradient-end: #764ba2;
    --shadow-lg: 0 10px 30px rgba(0,0,0,0.15);
    min-height: 100vh;
    background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
    padding: 20px;
  }

  .chat-container {
    max-width: 900px;
    margin: 0 auto;
  }

  /* Chat Header */
  .chat-header {
    background: rgba(255,255,255,0.98);
    backdrop-filter: blur(20px);
    border-radius: 20px 20px 0 0;
    padding: 20px 24px;
    box-shadow: var(--shadow-lg);
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid #e5e7eb;
  }

  .chat-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .back-btn {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s;
  }

  .back-btn:hover {
    background: #e5e7eb;
    transform: translateX(-3px);
  }

  .user-avatar-large {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--chat-primary), var(--chat-secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 20px;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
  }

  .chat-header-info h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
  }

  .chat-header-info p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 4px 0 0 0;
  }

  .view-job-btn {
    padding: 10px 20px;
    background: linear-gradient(135deg, var(--chat-primary), #1d4ed8);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
  }

  .view-job-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
  }

  /* Job Info Banner */
  .job-banner {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .job-banner-info h3 {
    font-weight: 600;
    color: #1e40af;
    margin: 0 0 4px 0;
  }

  .job-banner-info p {
    color: #3b82f6;
    font-size: 0.875rem;
    margin: 0;
  }

  .status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .status-completed { background: #d1fae5; color: #065f46; }
  .status-progress { background: #fef3c7; color: #92400e; }
  .status-default { background: #dbeafe; color: #1e40af; }

  /* Messages Container */
  .messages-box {
    background: rgba(255,255,255,0.95);
    height: 520px;
    overflow-y: auto;
    padding: 24px;
    position: relative;
  }

  .messages-box::-webkit-scrollbar {
    width: 8px;
  }

  .messages-box::-webkit-scrollbar-track {
    background: #f3f4f6;
  }

  .messages-box::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 10px;
  }

  .messages-box::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
  }

  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #9ca3af;
  }

  .empty-state-icon {
    font-size: 4rem;
    margin-bottom: 16px;
  }

  /* Message Bubble */
  .message-wrapper {
    display: flex;
    margin-bottom: 16px;
    animation: slideIn 0.3s ease;
  }

  @keyframes slideIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .message-wrapper.sent {
    justify-content: flex-end;
  }

  .message-wrapper.received {
    justify-content: flex-start;
  }

  .message-content {
    max-width: 70%;
  }

  .message-bubble-wrapper {
    display: flex;
    align-items: end;
    gap: 10px;
  }

  .sent .message-bubble-wrapper {
    flex-direction: row-reverse;
  }

  .user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.875rem;
    flex-shrink: 0;
  }

  .sent .user-avatar {
    background: linear-gradient(135deg, var(--chat-primary), #1d4ed8);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
  }

  .received .user-avatar {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
  }

  .message-bubble {
    border-radius: 18px;
    padding: 12px 16px;
    word-wrap: break-word;
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }

  .sent .message-bubble {
    background: linear-gradient(135deg, var(--chat-primary), #2563eb);
    color: white;
    border-bottom-right-radius: 4px;
  }

  .received .message-bubble {
    background: #f3f4f6;
    color: #1f2937;
    border-bottom-left-radius: 4px;
  }

  .message-text {
    font-size: 0.95rem;
    line-height: 1.5;
    margin: 0;
  }

  .message-meta {
    font-size: 0.75rem;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .sent .message-meta {
    color: rgba(255,255,255,0.8);
    justify-content: flex-end;
  }

  .received .message-meta {
    color: #9ca3af;
  }

  .read-status {
    color: #10b981;
    font-weight: 600;
  }

  /* Message Input */
  .message-input-box {
    background: rgba(255,255,255,0.98);
    backdrop-filter: blur(20px);
    border-radius: 0 0 20px 20px;
    padding: 20px 24px;
    box-shadow: var(--shadow-lg);
    border-top: 2px solid #e5e7eb;
  }

  .input-form {
    display: flex;
    gap: 12px;
    align-items: flex-end;
  }

  .input-textarea {
    flex: 1;
    padding: 14px 18px;
    border: 2px solid #e5e7eb;
    border-radius: 14px;
    font-size: 0.95rem;
    resize: none;
    transition: all 0.3s;
    font-family: inherit;
  }

  .input-textarea:focus {
    outline: none;
    border-color: var(--chat-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  .send-btn {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--chat-secondary), #059669);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
  }

  .send-btn:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6);
  }

  .send-btn svg {
    width: 24px;
    height: 24px;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .amazing-chat {
      padding: 0;
    }
    
    .chat-container {
      border-radius: 0;
    }
    
    .chat-header {
      border-radius: 0;
      padding: 16px;
    }
    
    .messages-box {
      height: calc(100vh - 260px);
    }
    
    .message-content {
      max-width: 85%;
    }

    .chat-header-info h2 {
      font-size: 1.2rem;
    }

    .message-input-box {
      border-radius: 0;
    }
  }
</style>

<div class="amazing-chat">
  <div class="chat-container">
    
    <!-- Chat Header -->
    <div class="chat-header">
      <div class="chat-header-left">
        <a href="{{ route('chat.index') }}" class="back-btn">
          <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </a>
        
        <div class="user-avatar-large">
          {{ substr($otherUser->name, 0, 1) }}
        </div>
        
        <div class="chat-header-info">
          <h2>{{ $otherUser->name }}</h2>
          <p>{{ $job->title }}</p>
        </div>
      </div>
      
      <a href="{{ route('jobs.show', $job) }}" class="view-job-btn">
        Angalia Kazi
      </a>
    </div>

    <!-- Job Info Banner -->
    <div class="job-banner">
      <div class="job-banner-info">
        <h3>{{ $job->title }}</h3>
        <p>Bei: Tsh {{ number_format($job->amount) }}</p>
      </div>
      <span class="status-badge 
        @if($job->status === 'completed') status-completed
        @elseif($job->status === 'in_progress') status-progress
        @else status-default
        @endif">
        {{ ucfirst($job->status) }}
      </span>
    </div>

    <!-- Messages Container -->
    <div class="messages-box" id="messages-container">
      @forelse($messages as $message)
        <div class="message-wrapper {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}">
          <div class="message-content">
            <div class="message-bubble-wrapper">
              <div class="user-avatar">
                {{ substr($message->sender->name, 0, 1) }}
              </div>
              <div>
                <div class="message-bubble">
                  <p class="message-text">{{ $message->message }}</p>
                </div>
                <div class="message-meta">
                  <span>{{ $message->created_at->format('H:i') }}</span>
                  @if($message->sender_id === auth()->id() && $message->is_read)
                    <span class="read-status">✓✓</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="empty-state">
          <div class="empty-state-icon">💬</div>
          <h3 style="font-size:1.2rem;font-weight:600;margin:0 0 8px 0">Hakuna ujumbe bado</h3>
          <p style="margin:0">Anza mazungumzo yako!</p>
        </div>
      @endforelse
    </div>

    <!-- Message Input -->
    <div class="message-input-box">
      <form action="{{ route('chat.send', $job) }}" method="POST" id="message-form" class="input-form">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
        <textarea 
          name="message" 
          id="message-input"
          rows="2" 
          class="input-textarea"
          placeholder="Andika ujumbe wako..."
          required
        ></textarea>
        <button type="submit" class="send-btn">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
          </svg>
        </button>
      </form>
    </div>

  </div>
</div>

<script>
  // Auto-scroll to bottom
  const container = document.getElementById('messages-container');
  if (container) {
    container.scrollTop = container.scrollHeight;
  }

  // Handle form submission
  const form = document.getElementById('message-form');
  const input = document.getElementById('message-input');

  if (form && input) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(form);
      
      fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success && data.message) {
          // Add message to UI - always sent (since current user sent it)
          const messageHtml = `
            <div class="message-wrapper sent">
              <div class="message-content">
                <div class="message-bubble-wrapper">
                  <div class="user-avatar">${data.message.sender.name.charAt(0)}</div>
                  <div>
                    <div class="message-bubble">
                      <p class="message-text">${data.message.message}</p>
                    </div>
                    <div class="message-meta">
                      <span>Sasa</span>
                      <span class="read-status">✓</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;
          
          container.insertAdjacentHTML('beforeend', messageHtml);
          container.scrollTop = container.scrollHeight;
          input.value = '';
          
          // Update lastMessageId to prevent duplicates
          lastMessageId = data.message.id;
        }
      })
      .catch(error => console.error('Error:', error));
    });
  }

  // Poll for new messages every 3 seconds
  let lastMessageId = {{ $messages->last()->id ?? 0 }};
  const otherUserId = {{ $otherUser->id }};
  const currentUserId = {{ auth()->id() }};
  
  setInterval(function() {
    fetch('{{ route("chat.poll", $job) }}?last_id=' + lastMessageId + '&other_user_id=' + otherUserId)
      .then(response => response.json())
      .then(data => {
        if (data.count > 0) {
          data.messages.forEach(message => {
            // Check if message is from current user (sent) or from other user (received)
            const isFromCurrentUser = message.sender_id == currentUserId;
            const messageClass = isFromCurrentUser ? 'sent' : 'received';
            
            // Only add if it's from other user (not current user to avoid duplicates)
            if (!isFromCurrentUser) {
              const messageHtml = `
                <div class="message-wrapper ${messageClass}">
                  <div class="message-content">
                    <div class="message-bubble-wrapper">
                      <div class="user-avatar">${message.sender.name.charAt(0)}</div>
                      <div>
                        <div class="message-bubble">
                          <p class="message-text">${message.message}</p>
                        </div>
                        <div class="message-meta">
                          <span>Sasa</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              `;
              
              container.insertAdjacentHTML('beforeend', messageHtml);
            }
            lastMessageId = message.id;
          });
          
          container.scrollTop = container.scrollHeight;
        }
      })
      .catch(error => console.error('Polling error:', error));
  }, 3000);
</script>
@endsection
