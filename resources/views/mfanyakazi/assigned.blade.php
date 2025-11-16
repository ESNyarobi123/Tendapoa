@extends('layouts.app')
@section('title','Mfanyakazi — Kazi Ulizopewa')

@section('content')
<style>
  /* ====== Modern Mfanyakazi Assigned Jobs Page ====== */
  .assigned-page {
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

  .assigned-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .page-container {
    max-width: 1200px;
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

  /* Jobs Grid */
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

  .job-category {
    background: linear-gradient(135deg, var(--primary), #1d4ed8);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .job-status {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
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

  /* Code Input Modal */
  .code-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
  }

  .code-modal.show {
    opacity: 1;
    visibility: visible;
  }

  .code-modal-content {
    background: white;
    border-radius: 20px;
    padding: 32px;
    max-width: 500px;
    width: 90%;
    box-shadow: var(--shadow-lg);
    transform: scale(0.9);
    transition: transform 0.3s ease;
  }

  .code-modal.show .code-modal-content {
    transform: scale(1);
  }

  .code-modal-header {
    text-align: center;
    margin-bottom: 24px;
  }

  .code-modal-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .code-modal-header p {
    color: var(--text-muted);
    font-size: 0.875rem;
    margin: 0;
  }

  .code-input-group {
    margin-bottom: 24px;
  }

  .code-input-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
  }

  .code-input {
    width: 100%;
    padding: 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1.25rem;
    font-weight: 700;
    text-align: center;
    letter-spacing: 2px;
    font-family: 'Courier New', monospace;
    transition: all 0.3s ease;
  }

  .code-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  .code-instructions {
    background: #f0f9ff;
    border: 2px solid #0ea5e9;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
  }

  .code-instructions h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #0c4a6e;
    margin: 0 0 8px 0;
  }

  .code-instructions p {
    color: #0c4a6e;
    font-size: 0.875rem;
    margin: 0;
    line-height: 1.5;
  }

  .code-modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
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

  /* Status Badges */
  .status-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .status-badge.waiting {
    background: #fef3c7;
    color: #92400e;
  }

  .status-badge.completed {
    background: #d1fae5;
    color: #065f46;
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

  /* Responsive */
  @media (max-width: 768px) {
    .assigned-page {
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
    
    .job-header {
      flex-direction: column;
      gap: 16px;
    }
    
    .job-meta {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }
  }
</style>

@php
  use Illuminate\Support\Facades\Route;

  // Safe links & actions
  $feedUrl     = Route::has('feed') ? route('feed') : url('/feed');
  $assignedUrl = Route::has('mfanyakazi.assigned') ? route('mfanyakazi.assigned') : url('/mfanyakazi/assigned');
  $dashboardUrl = Route::has('dashboard') ? route('dashboard') : url('/dashboard');

  $acceptUrl   = fn($id) => Route::has('mfanyakazi.jobs.accept')   ? route('mfanyakazi.jobs.accept',  $id) : url('/mfanyakazi/jobs/'.$id.'/accept');
  $declineUrl  = fn($id) => Route::has('mfanyakazi.jobs.decline')  ? route('mfanyakazi.jobs.decline', $id) : url('/mfanyakazi/jobs/'.$id.'/decline');
  $completeUrl = fn($id) => Route::has('mfanyakazi.jobs.complete') ? route('mfanyakazi.jobs.complete',$id) : url('/mfanyakazi/jobs/'.$id.'/complete');
@endphp

<div class="assigned-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <div class="header-content">
        <div class="header-text">
          <h1>🧾 Kazi Ulizopewa</h1>
          <p>Simamia ofa, kazi zinazoendelea, na kamilisha kwa kutumia code ya muhitaji.</p>
        </div>
        <div class="header-actions">
          <a class="btn btn-outline" href="{{ $feedUrl }}">
            <span>🔍</span>
            Tafuta Kazi
          </a>
          <a class="btn btn-primary" href="{{ $assignedUrl }}">
            <span>🔄</span>
            Refresh
          </a>
        </div>
      </div>
    </div>

    <!-- Jobs List -->
    @if(isset($jobs) && (method_exists($jobs, 'count') ? $jobs->count() : (is_countable($jobs ?? []) ? count($jobs) : 0)))
      <div class="jobs-grid">
        @foreach($jobs as $job)
          @php
            $cat        = $job->category->name ?? ($job->category_name ?? '—');
            $title      = $job->title ?? 'Kazi';
            $status     = strtolower((string)($job->status ?? 'pending'));
            $mfResp     = strtolower((string)($job->mfanyakazi_response ?? $job->assignee_response ?? $job->worker_response ?? ''));
            $amount     = (int)($job->payout ?? $job->price ?? $job->budget ?? 0);
          @endphp

          <div class="job-card">
            <div class="job-header">
              <div class="job-info">
                <div class="job-category">{{ $cat }}</div>
                <h3>{{ $title }}</h3>
                <div class="job-meta">
                  <span class="job-status {{ str_replace('_', '-', $status) }}">
                    {{ strtoupper($status) }}
                  </span>
                  @if(!empty($job->location))
                    <span>📍 {{ $job->location }}</span>
                  @endif
                  @if(!empty($job->created_at))
                    <span>⏱️ {{ \Illuminate\Support\Carbon::parse($job->created_at)->diffForHumans() }}</span>
                  @endif
                </div>
              </div>
              <div class="job-price">TZS {{ number_format($amount) }}</div>
            </div>

            <!-- Job Actions -->
            <div style="display: flex; gap: 12px; justify-content: flex-end; flex-wrap: wrap;">
              {{-- Chat button - available after being selected (accepted_worker_id is set) --}}
              @if(isset($job->accepted_worker_id) && $job->accepted_worker_id && auth()->id() === $job->accepted_worker_id)
                <a href="{{ route('chat.show', $job) }}" class="btn btn-success" style="text-decoration: none;">
                  <span>💬</span>
                  Mazungumzo na Muhitaji
                </a>
              @endif

              @if(($status === 'assigned' && ($mfResp === '' || $mfResp === 'pending')) || $status === 'offered')
                <form method="POST" action="{{ $acceptUrl($job->id) }}" style="display: inline;">
                  @csrf
                  <button class="btn btn-success" type="submit">
                    <span>✅</span>
                    Kubali Kazi
                  </button>
                </form>
                <form method="POST" action="{{ $declineUrl($job->id) }}" style="display: inline;">
                  @csrf
                  <button class="btn btn-danger" type="submit">
                    <span>❌</span>
                    Kataa
                  </button>
                </form>

              @elseif($status === 'in_progress')
                <button class="btn btn-primary" onclick="showCodeInputModal({{ $job->id }}, '{{ addslashes($title) }}')">
                  <span>🏁</span>
                  Maliza Kazi
                </button>

              @elseif($status === 'ready_for_confirmation')
                <div class="status-badge waiting">
                  ⏳ Inasubiri Uthibitisho wa Mteja
                </div>

              @elseif($status === 'completed')
                <div class="status-badge completed">
                  ✅ Imekamilika
                </div>

              @else
                <div class="status-badge">
                  Status: {{ strtoupper($status) }}
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div style="display: flex; justify-content: center; margin-top: 32px;">
        {{ method_exists($jobs,'links') ? $jobs->links() : '' }}
      </div>
    @else
      <!-- Empty State -->
      <div class="empty-state">
        <div class="empty-state-icon">📋</div>
        <h3>Hakuna Kazi Ulizopewa</h3>
        <p>Hakuna kazi ulizopewa kwa sasa. Tafuta kazi mpya na uanze kufanya kazi.</p>
        <a class="btn btn-primary" href="{{ $feedUrl }}">
          <span>🔍</span>
          Tafuta Kazi
        </a>
      </div>
    @endif

  </div>
</div>

<!-- Code Input Modal -->
<div id="codeInputModal" class="code-modal">
  <div class="code-modal-content">
    <div class="code-modal-header">
      <h3>🏁 Maliza Kazi</h3>
      <p>Ingiza code uliyopewa na mteja ili kumaliza kazi</p>
    </div>
    
    <div class="code-instructions">
      <h4>📋 Maagizo:</h4>
      <p>
        <strong>1.</strong> Omba code kutoka kwa mteja<br>
        <strong>2.</strong> Ingiza code hapa chini<br>
        <strong>3.</strong> Bofya "Thibitisha" ili kumaliza kazi
      </p>
    </div>

    <form id="codeInputForm">
      <div class="code-input-group">
        <label class="code-input-label" for="muhitajiCode">Code ya Mteja:</label>
        <input 
          type="text" 
          id="muhitajiCode"
          name="muhitajiCode" 
          class="code-input"
          placeholder="123456"
          maxlength="6"
          pattern="[0-9]{6}"
          required
        >
      </div>
      
      <div class="code-modal-actions">
        <button type="button" class="btn btn-outline" onclick="closeCodeInputModal()">
          <span>❌</span>
          Fungua
        </button>
        <button type="submit" class="btn btn-primary">
          <span>✅</span>
          Thibitisha
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  let currentJobId = null;

  function showCodeInputModal(jobId, jobTitle) {
    currentJobId = jobId;
    document.getElementById('codeInputModal').classList.add('show');
    document.getElementById('muhitajiCode').focus();
  }

  function closeCodeInputModal() {
    document.getElementById('codeInputModal').classList.remove('show');
    document.getElementById('muhitajiCode').value = '';
    currentJobId = null;
  }

  // Handle form submission
  document.getElementById('codeInputForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const code = document.getElementById('muhitajiCode').value;
    if (!code || code.length !== 6) {
      alert('Tafadhali ingiza code ya tarakimu 6');
      return;
    }

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span>⏳</span> Inasubiri...';
    submitBtn.disabled = true;

    // Submit the form
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('code', code);

    fetch(`/mfanyakazi/jobs/${currentJobId}/complete`, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showNotification(data.message, 'success');
        closeCodeInputModal();
        // Reload page after a short delay
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      } else {
        showNotification(data.message, 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showNotification('Kuna tatizo la mtandao. Jaribu tena.', 'error');
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    });
  });

  // Show notification
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

  // Close modal on escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeCodeInputModal();
    }
  });

  // Close modal on backdrop click
  document.getElementById('codeInputModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeCodeInputModal();
    }
  });

  // Add some interactive animations
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
  });
</script>
@endsection