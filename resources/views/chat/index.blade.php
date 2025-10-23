@extends('layouts.app')
@section('title', 'Mazungumzo Yangu')

@section('content')
<style>
  /* ====== AMAZING CHAT LIST DESIGN ====== */
  .chat-list-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
  }

  .chat-list-container {
    max-width: 900px;
    margin: 0 auto;
  }

  .page-header {
    background: rgba(255,255,255,0.98);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  }

  .page-header h1 {
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #3b82f6, #10b981);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0 0 8px 0;
  }

  .page-header p {
    color: #6b7280;
    font-size: 1.1rem;
    margin: 0;
  }

  .conversations-list {
    display: grid;
    gap: 16px;
  }

  .conversation-card {
    background: rgba(255,255,255,0.98);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    position: relative;
    overflow: hidden;
  }

  .conversation-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, #3b82f6, #10b981);
    transform: scaleY(0);
    transition: transform 0.3s;
  }

  .conversation-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  }

  .conversation-card:hover::before {
    transform: scaleY(1);
  }

  .conversation-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #10b981);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 800;
    font-size: 1.5rem;
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    flex-shrink: 0;
  }

  .conversation-info {
    flex: 1;
  }

  .conversation-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
  }

  .conversation-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
  }

  .unread-badge {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
  }

  .conversation-job-title {
    color: #6b7280;
    font-size: 0.95rem;
    margin: 0 0 6px 0;
  }

  .conversation-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.85rem;
    color: #9ca3af;
  }

  .conversation-side {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
  }

  .category-badge {
    padding: 6px 12px;
    border-radius: 20px;
    background: #f3f4f6;
    color: #6b7280;
    font-size: 0.75rem;
    font-weight: 600;
  }

  .status-badge-conv {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
  }

  .status-completed { background: #d1fae5; color: #065f46; }
  .status-progress { background: #fef3c7; color: #92400e; }
  .status-default { background: #dbeafe; color: #1e40af; }

  .empty-conversations {
    background: rgba(255,255,255,0.98);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 60px 32px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  }

  .empty-icon {
    font-size: 5rem;
    margin-bottom: 20px;
  }

  .empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 12px 0;
  }

  .empty-text {
    color: #6b7280;
    font-size: 1rem;
    margin: 0;
  }

  @media (max-width: 768px) {
    .chat-list-page {
      padding: 10px;
    }

    .page-header {
      padding: 24px 20px;
    }

    .page-header h1 {
      font-size: 2rem;
    }

    .conversation-card {
      padding: 16px;
      gap: 12px;
    }

    .conversation-avatar {
      width: 50px;
      height: 50px;
      font-size: 1.2rem;
    }

    .conversation-name {
      font-size: 1rem;
    }

    .conversation-side {
      display: none;
    }
  }
</style>

<div class="chat-list-page">
  <div class="chat-list-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <h1>💬 Mazungumzo Yangu</h1>
      <p>Angalia mazungumzo yako yote na wafanyakazi/wahitaji</p>
    </div>

    <!-- Conversations List -->
    @if($conversations->isEmpty())
      <div class="empty-conversations">
        <div class="empty-icon">💬</div>
        <h3 class="empty-title">Hakuna mazungumzo</h3>
        <p class="empty-text">Haujawahi kuongea na mtu yeyote bado. Tuma comment kwenye kazi kuanza mazungumzo!</p>
      </div>
    @else
      <div class="conversations-list">
        @foreach($conversations as $conv)
          @if($conv && $conv->job && $conv->other_user)
            <a href="{{ route('chat.show', $conv->job) }}" class="conversation-card">
              
              <!-- Avatar -->
              <div class="conversation-avatar">
                {{ substr($conv->other_user->name, 0, 1) }}
              </div>

              <!-- Info -->
              <div class="conversation-info">
                <div class="conversation-header">
                  <h3 class="conversation-name">{{ $conv->other_user->name }}</h3>
                  @if($conv->unread_count > 0)
                    <span class="unread-badge">{{ $conv->unread_count }}</span>
                  @endif
                </div>
                <p class="conversation-job-title">{{ $conv->job->title }}</p>
                <div class="conversation-meta">
                  <span>📦 {{ $conv->job->category->name ?? 'N/A' }}</span>
                  <span>⏰ {{ \Carbon\Carbon::parse($conv->last_message_at)->diffForHumans() }}</span>
                </div>
              </div>

              <!-- Side Info -->
              <div class="conversation-side">
                <span class="category-badge">{{ $conv->job->category->name ?? 'N/A' }}</span>
                <span class="status-badge-conv 
                  @if($conv->job->status === 'completed') status-completed
                  @elseif($conv->job->status === 'in_progress') status-progress
                  @else status-default
                  @endif">
                  {{ ucfirst($conv->job->status) }}
                </span>
              </div>

            </a>
          @endif
        @endforeach
      </div>
    @endif

  </div>
</div>

<script>
  // Add smooth animations on scroll
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.conversation-card');
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }, index * 100);
        }
      });
    }, { threshold: 0.1 });

    cards.forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.5s ease';
      observer.observe(card);
    });
  });
</script>
@endsection
