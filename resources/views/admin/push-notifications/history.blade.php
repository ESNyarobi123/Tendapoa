@extends('layouts.app')
@section('title', 'Admin — Push Notification History')

@section('content')
<style>
  .history-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .history-container {
    max-width: 1400px;
    margin: 0 auto;
  }

  .history-header {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255,255,255,0.2);
    margin-bottom: 24px;
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

  .history-list {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .history-item {
    padding: 24px;
    background: #f8fafc;
    border-radius: 16px;
    margin-bottom: 16px;
    border-left: 4px solid #3b82f6;
    transition: all 0.3s ease;
  }

  .history-item:hover {
    background: #f0f9ff;
    transform: translateX(8px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .history-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
  }

  .history-body {
    color: #6b7280;
    margin-bottom: 16px;
    line-height: 1.6;
  }

  .history-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    font-size: 0.875rem;
    color: #9ca3af;
    margin-bottom: 12px;
  }

  .status-badge {
    display: inline-block;
    padding: 6px 14px;
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

  .status-pending {
    background: #fef3c7;
    color: #92400e;
  }

  .pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 24px;
  }

  .pagination a, .pagination span {
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    background: white;
    color: #3b82f6;
    font-weight: 600;
  }

  .pagination .active {
    background: #3b82f6;
    color: white;
  }
</style>

<div class="history-page">
  <div class="history-container">
    <div class="history-header">
      <div class="header-content">
        <div class="header-text">
          <h1>📜 Historia ya Push Notifications</h1>
          <p>Orodha ya taarifa zote zilizotumwa</p>
        </div>
        <div>
          <a href="{{ route('admin.push-notifications.index') }}" style="background: linear-gradient(135deg, #3b82f6, #10b981); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-block;">
            ← Rudi Nyuma
          </a>
        </div>
      </div>
    </div>

    <div class="history-list">
      @if($notifications->count() > 0)
        @foreach($notifications as $notification)
          <div class="history-item">
            <div class="history-title">{{ $notification->title }}</div>
            <div class="history-body">{{ $notification->body }}</div>
            <div class="history-meta">
              <span>📅 {{ $notification->sent_at ? $notification->sent_at->format('d/m/Y H:i') : 'Hajatumwa' }}</span>
              <span>👤 {{ $notification->sender ? $notification->sender->name : 'System' }}</span>
              <span>👥 {{ $notification->total_recipients }} watumiaji</span>
              <span>✅ {{ $notification->successful_sends }} imefanikiwa</span>
              <span>❌ {{ $notification->failed_sends }} imeshindwa</span>
              <span class="status-badge status-{{ $notification->status }}">{{ $notification->status }}</span>
            </div>
            @if($notification->errors && count($notification->errors) > 0)
              <details style="margin-top: 12px;">
                <summary style="cursor: pointer; color: #ef4444; font-weight: 600;">Tazama makosa ({{ count($notification->errors) }})</summary>
                <div style="margin-top: 8px; padding: 12px; background: #fee2e2; border-radius: 8px; font-size: 0.875rem;">
                  @foreach($notification->errors as $error)
                    <div style="margin-bottom: 4px;">• {{ $error }}</div>
                  @endforeach
                </div>
              </details>
            @endif
          </div>
        @endforeach

        <div class="pagination">
          {{ $notifications->links() }}
        </div>
      @else
        <div style="text-align: center; padding: 60px 20px; color: #6b7280;">
          <div style="font-size: 4rem; margin-bottom: 16px;">📭</div>
          <div style="font-size: 1.25rem; font-weight: 600; margin-bottom: 8px;">Hakuna taarifa zilizotumwa bado</div>
          <div>Tuma taarifa ya kwanza kwa kutumia fomu ya push notifications</div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

