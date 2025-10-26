@extends('layouts.app')
@section('title', 'Kazi Zangu')

@section('content')
<style>
  /* ====== Modern My Jobs Page ====== */
  .my-jobs-page {
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

  .my-jobs-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .page-container {
    max-width: 1200px;
    margin: 0 auto;
  }

  /* Header */
  .page-header {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .page-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, var(--primary), var(--success));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .page-subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
    margin: 0 0 24px 0;
  }

  .header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  /* Job Cards */
  .jobs-grid {
    display: grid;
    gap: 24px;
  }

  .job-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
  }

  .job-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
  }

  .job-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .job-info h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .job-meta {
    display: flex;
    gap: 16px;
    align-items: center;
    color: var(--text-muted);
    font-size: 0.875rem;
    margin-bottom: 12px;
  }

  .job-status {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .job-status.draft {
    background: #f3f4f6;
    color: #374151;
  }

  .job-status.published {
    background: #dbeafe;
    color: #1e40af;
  }

  .job-status.assigned {
    background: #fef3c7;
    color: #92400e;
  }

  .job-status.in-progress {
    background: #fce7f3;
    color: #be185d;
  }

  .job-status.ready-for-confirmation {
    background: #fef3c7;
    color: #92400e;
  }

  .job-status.completed {
    background: #d1fae5;
    color: #065f46;
  }

  .job-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--success);
    text-align: right;
  }

  /* Completion Code Section */
  .completion-section {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #0ea5e9;
    border-radius: 16px;
    padding: 20px;
    margin: 16px 0;
    position: relative;
    overflow: hidden;
  }

  .completion-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #0ea5e9, #3b82f6, #8b5cf6);
  }

  .completion-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
  }

  .completion-header h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
  }

  .completion-code {
    background: white;
    border: 2px dashed #0ea5e9;
    border-radius: 12px;
    padding: 16px 20px;
    font-family: 'Courier New', monospace;
    font-size: 1.5rem;
    font-weight: 800;
    color: #0ea5e9;
    text-align: center;
    letter-spacing: 2px;
    margin: 12px 0;
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
    transition: all 0.3s ease;
  }

  .completion-code:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.3);
  }

  .completion-instructions {
    background: rgba(14, 165, 233, 0.1);
    border-radius: 12px;
    padding: 16px;
    margin-top: 12px;
  }

  .completion-instructions p {
    margin: 0;
    color: #0c4a6e;
    font-weight: 600;
    font-size: 0.9rem;
    line-height: 1.5;
  }

  /* Success State */
  .success-section {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border: 2px solid #10b981;
    border-radius: 16px;
    padding: 20px;
    margin: 16px 0;
    position: relative;
    overflow: hidden;
  }

  .success-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #10b981, #059669);
  }

  .success-content {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .success-icon {
    font-size: 2rem;
    color: #10b981;
  }

  .success-text h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #065f46;
    margin: 0 0 4px 0;
  }

  .success-text p {
    color: #047857;
    font-size: 0.875rem;
    margin: 0;
  }

  /* Worker Info */
  .worker-info {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
    margin-top: 12px;
    border-left: 4px solid var(--primary);
  }

  .worker-info h5 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    margin: 0 0 4px 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .worker-info p {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
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

  .btn-outline {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
  }

  .btn-outline:hover {
    background: var(--primary);
    color: white;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 80px 20px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
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
    margin: 0 0 24px 0;
  }

  /* Pagination */
  .pagination {
    display: flex;
    justify-content: center;
    margin-top: 32px;
  }

  .pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(255,255,255,0.9);
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    margin: 0 4px;
    transition: all 0.3s ease;
  }

  .pagination .page-link:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
  }

  .pagination .page-link.active {
    background: var(--primary);
    color: white;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .my-jobs-page {
      padding: 16px;
    }
    
    .page-header {
      padding: 24px;
    }
    
    .page-title {
      font-size: 2rem;
    }
    
    .job-header {
      flex-direction: column;
      gap: 16px;
    }
    
    .job-meta {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }
    
    .completion-code {
      font-size: 1.25rem;
    }
  }
</style>

<div class="my-jobs-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <h1 class="page-title">📋 Kazi Zangu</h1>
      <p class="page-subtitle">Fuatilia kazi zako, malipo, na mfanyakazi wako.</p>
      <div class="header-actions">
        <a class="btn btn-primary" href="{{ route('jobs.create') }}">
          <span>➕</span>
          Chapisha Kazi Mpya
        </a>
        <a class="btn btn-outline" href="{{ route('dashboard') }}">
          <span>🏠</span>
          Dashboard
        </a>
      </div>
    </div>

    <!-- Jobs List -->
    @if($jobs->count() > 0)
      <div class="jobs-grid">
        @foreach($jobs as $job)
          <div class="job-card">
            <div class="job-header">
              <div class="job-info">
                <h3>{{ $job->title }}</h3>
                <div class="job-meta">
                  <span>💰 {{ number_format($job->price) }} TZS</span>
                  <span>💬 {{ $job->comments_count }} maoni</span>
                  <span>📅 {{ $job->created_at->format('M d, Y') }}</span>
                </div>
              </div>
              <div style="text-align: right;">
                <div class="job-status {{ str_replace('_', '-', $job->status) }}">
                  {{ strtoupper($job->status) }}
                </div>
                <div class="job-price">{{ number_format($job->price) }}</div>
              </div>
            </div>

            <!-- Completion Code Section -->
            @if($job->status === 'in_progress' && $job->completion_code)
              <div class="completion-section">
                <div class="completion-header">
                  <span>🔐</span>
                  <h4>Code ya Mfanyakazi</h4>
                </div>
                <div class="completion-code" onclick="copyToClipboard('{{ $job->completion_code }}')">
                  {{ $job->completion_code }}
                </div>
                <div class="completion-instructions">
                  <p>
                    <strong>📋 Maagizo:</strong><br>
                    Mpe mfanyakazi code hii akimaliza kazi! Mfanyakazi atakupa code hii akimaliza kazi, 
                    kisha atathibitisha kwenye mfumo na utapokea malipo yako.
                  </p>
                </div>
              </div>
            @endif

            <!-- Success State -->
            @if($job->status === 'completed')
              <div class="success-section">
                <div class="success-content">
                  <div class="success-icon">✅</div>
                  <div class="success-text">
                    <h4>Kazi Imekamilika</h4>
                    <p>Kazi imethibitishwa na mfanyakazi. Malipo yamefanyika kwa mfanyakazi.</p>
                  </div>
                </div>
              </div>
            @endif

            <!-- Worker Info -->
            @if($job->status === 'in_progress' && $job->acceptedWorker)
              <div class="worker-info">
                <h5>Mfanyakazi Aliyekubali</h5>
                <p>{{ $job->acceptedWorker->name ?? 'Unknown' }}</p>
              </div>
            @endif

            <!-- Action Buttons -->
            <div style="margin-top: 16px; display: flex; gap: 12px; justify-content: flex-end; flex-wrap: wrap;">
              @if(in_array($job->status, ['posted', 'assigned']))
                <a class="btn btn-outline" href="{{ route('jobs.edit', $job) }}">
                  <span>✏️</span>
                  Badilisha
                </a>
              @endif
              <a class="btn btn-primary" href="{{ route('jobs.show', $job) }}">
                <span>👁️</span>
                Fungua Kazi
              </a>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="pagination">
        {{ $jobs->links() }}
      </div>
    @else
      <!-- Empty State -->
      <div class="empty-state">
        <div class="empty-state-icon">📝</div>
        <h3>Hakuna Kazi Bado</h3>
        <p>Hujachapisha kazi yoyote. Chapisha kazi yako ya kwanza na uanze safari ya usafi salama.</p>
        <a class="btn btn-primary" href="{{ route('jobs.create') }}">
          <span>🚀</span>
          Chapisha Kazi Sasa
        </a>
      </div>
    @endif

  </div>
</div>

<script>
  // Copy to clipboard function
  function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
      // Show success notification
      showNotification('Code imenakiliwa!', 'success');
    }, function(err) {
      console.error('Could not copy text: ', err);
      showNotification('Imeshindwa kunakili code', 'error');
    });
  }

  // Show notification
  function showNotification(message, type) {
    // Create notification element
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
      z-index: 1000;
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

  // Add interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate job cards on scroll
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

    // Observe all job cards
    document.querySelectorAll('.job-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s ease';
      observer.observe(card);
    });

    // Add hover effects to completion codes
    document.querySelectorAll('.completion-code').forEach(code => {
      code.addEventListener('mouseenter', function() {
        this.style.cursor = 'pointer';
        this.style.transform = 'scale(1.05)';
      });
      
      code.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
      });
    });
  });
</script>
@endsection