@extends('layouts.app')
@section('title', $job->title)

@section('content')
<style>
  /* ====== Modern Job Show Page ====== */
  .job-show-page {
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

  .job-show-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .page-container {
    max-width: 1000px;
    margin: 0 auto;
    display: grid;
    gap: 24px;
  }

  /* Job Header */
  .job-header {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .job-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0 0 16px 0;
    background: linear-gradient(135deg, var(--primary), var(--success));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .job-meta {
    display: flex;
    gap: 24px;
    align-items: center;
    flex-wrap: wrap;
  }

  .job-category {
    background: linear-gradient(135deg, var(--primary), #1d4ed8);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .job-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--success);
  }

  .job-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .job-status.posted {
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

  .job-status.completed {
    background: #d1fae5;
    color: #065f46;
  }

  /* Map Section */
  .map-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .map-container {
    height: 280px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow);
  }

  .map-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 16px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
  }

  .map-info-icon {
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

  .map-info-text h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0 0 4px 0;
  }

  .map-info-text p {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
  }

  /* Job Details */
  .job-details {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .job-details h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .job-details p {
    color: var(--text);
    line-height: 1.6;
    margin: 0 0 16px 0;
  }

  .job-details .price-info {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #0ea5e9;
    border-radius: 12px;
    padding: 16px;
    margin: 16px 0;
  }

  .price-info h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px 0;
  }

  .price-info .price-amount {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--success);
  }

  /* Comments Section */
  .comments-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .comments-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
  }

  .comments-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .comment-form {
    background: #f8fafc;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
  }

  .form-group {
    margin-bottom: 16px;
  }

  .form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
  }

  .form-textarea {
    width: 100%;
    padding: 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    min-height: 100px;
    resize: vertical;
    transition: all 0.3s ease;
  }

  .form-textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  .form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
  }

  .form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  .form-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 16px 0;
  }

  .form-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--primary);
  }

  .form-checkbox label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
    cursor: pointer;
  }

  /* Comments List */
  .comments-list {
    display: grid;
    gap: 16px;
  }

  .comment-item {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
  }

  .comment-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .comment-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
  }

  .comment-author {
    font-size: 1rem;
    font-weight: 700;
    color: var(--dark);
  }

  .comment-badge {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .comment-message {
    color: var(--text);
    line-height: 1.6;
    margin-bottom: 12px;
  }

  .comment-bid {
    background: #f0f9ff;
    border: 1px solid #0ea5e9;
    border-radius: 8px;
    padding: 12px;
    margin-top: 12px;
  }

  .comment-bid-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #0c4a6e;
    margin-bottom: 4px;
  }

  .comment-bid-amount {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--success);
  }

  .comment-actions {
    margin-top: 16px;
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

  /* Responsive */
  @media (max-width: 768px) {
    .job-show-page {
      padding: 16px;
    }
    
    .job-header {
      padding: 24px;
    }
    
    .job-title {
      font-size: 2rem;
    }
    
    .job-meta {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }
    
    .comments-header {
      flex-direction: column;
      gap: 16px;
    }
  }
</style>

<div class="job-show-page">
  <div class="page-container">
    
    <!-- Job Header -->
    <div class="job-header">
      <h1 class="job-title">{{ $job->title }}</h1>
      <div class="job-meta">
        <div class="job-category">{{ $job->category->name }}</div>
        <div class="job-price">{{ number_format($job->price) }} TZS</div>
        <div class="job-status {{ $job->status }}">{{ strtoupper($job->status) }}</div>
      </div>
      <div style="margin-top: 16px; padding: 12px; background: #f8fafc; border-radius: 12px; border: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
          @if($job->poster_type === 'mfanyakazi')
            <div style="background: #10b981; color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
              👷 Mfanyakazi
            </div>
            <span style="color: #10b981; font-weight: 600; font-size: 0.875rem;">Huduma inayotolewa na mfanyakazi</span>
          @else
            <div style="background: #3b82f6; color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
              👤 Muhitaji
            </div>
            <span style="color: #3b82f6; font-weight: 600; font-size: 0.875rem;">Kazi inayotafutwa na muhitaji</span>
          @endif
        </div>
        <div style="font-size: 0.875rem; color: #6b7280;">
          @if($job->poster_type === 'mfanyakazi')
            Mfanyakazi: <strong>{{ $job->muhitaji->name }}</strong> • 
            <span style="color: #10b981;">Huduma: {{ $job->description }}</span>
          @else
            Muhitaji: <strong>{{ $job->muhitaji->name }}</strong> • 
            <span style="color: #3b82f6;">Kazi: {{ $job->description }}</span>
          @endif
        </div>
      </div>
    </div>

    <!-- Map Section -->
    <div class="map-section">
      <div class="map-container">
        <div id="map" style="height: 100%; width: 100%;"></div>
      </div>
      <div class="map-info">
        <div class="map-info-icon">📍</div>
        <div class="map-info-text">
          <h4>Eneo la Kazi</h4>
          <p>{{ $job->address_text ?? 'Eneo limewekwa kwenye ramani' }}</p>
        </div>
      </div>
    </div>

    <!-- Job Details -->
    <div class="job-details">
      <h3>📋 Maelezo ya Kazi</h3>
      <p>{{ $job->description ?? 'Hakuna maelezo ya ziada.' }}</p>
      
      <div class="price-info">
        <h4>💰 Bei ya Kazi</h4>
        <div class="price-amount">{{ number_format($job->price) }} TZS</div>
        <p style="margin: 8px 0 0 0; color: var(--text-muted); font-size: 0.875rem;">
          Bei hii ni ya escrow - utalipa tu mfanyakazi akimaliza kazi kwa usahihi.
        </p>
      </div>

      @auth
        @php
          $userHasCommented = auth()->check() && $job->comments()->where('user_id', auth()->id())->exists();
          $isAcceptedWorker = auth()->id() === $job->accepted_worker_id;
          $isMuhitaji = auth()->id() === $job->user_id;
        @endphp

        {{-- Muhitaji can see chat button if job has comments from mfanyakazi --}}
        @if($isMuhitaji && $job->comments()->whereHas('user', fn($q) => $q->where('role', 'mfanyakazi'))->exists())
          <div style="background: #dcfce7; border: 2px solid #10b981; border-radius: 12px; padding: 16px; margin-top: 16px;">
            <h4 style="color: #065f46; margin: 0 0 12px 0;">💬 Mazungumzo na Wafanyakazi</h4>
            <p style="color: #065f46; margin: 0 0 16px 0; font-size: 0.875rem;">
              Unaweza kuzungumza na mfanyakazi yeyote aliye comment
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
              @foreach($job->comments()->with('user')->get()->unique('user_id') as $comment)
                @if($comment->user && $comment->user->role === 'mfanyakazi')
                  <a href="{{ route('chat.show', ['job' => $job, 'worker_id' => $comment->user_id]) }}" 
                     class="btn btn-success" style="margin: 0;">
                    <span>💬</span>
                    {{ $comment->user->name }}
                  </a>
                @endif
              @endforeach
            </div>
          </div>
        @endif

        {{-- Mfanyakazi who has commented can see chat button --}}
        @if(($userHasCommented || $isAcceptedWorker) && !$isMuhitaji)
          <div style="background: #dcfce7; border: 2px solid #10b981; border-radius: 12px; padding: 16px; margin-top: 16px;">
            <h4 style="color: #065f46; margin: 0 0 12px 0;">💬 Mazungumzo</h4>
            <p style="color: #065f46; margin: 0 0 16px 0; font-size: 0.875rem;">
              Muhitaji: <strong>{{ $job->muhitaji->name }}</strong>
            </p>
            <a href="{{ route('chat.show', $job) }}" class="btn btn-success" style="margin: 0;">
              <span>💬</span>
              Fungua Mazungumzo
            </a>
          </div>
        @endif
        @if(auth()->user()->role === 'muhitaji' && auth()->id() === $job->user_id && in_array($job->status, ['posted', 'offered']))
          <div style="background: #fef3c7; border: 2px solid #f59e0b; border-radius: 12px; padding: 16px; margin-top: 16px;">
            <h4 style="color: #92400e; margin: 0 0 8px 0;">💡 Kidokezo</h4>
            <p style="color: #92400e; margin: 0; font-size: 0.875rem;">
              Chagua mfanyakazi kupitia maoni (comments) hapa chini. Angalia profile na uwezo wa mfanyakazi kabla ya kumchagua.
            </p>
          </div>
        @endif

        {{-- Show declined workers history to Muhitaji --}}
        @if($isMuhitaji && $job->declined_workers_ids && count($job->declined_workers_ids) > 0)
          @php
            $declinedWorkers = $job->getDeclinedWorkers();
          @endphp
          @if($declinedWorkers->isNotEmpty())
            <div style="background: #fee2e2; border: 2px solid #dc2626; border-radius: 12px; padding: 16px; margin-top: 16px;">
              <h4 style="color: #991b1b; margin: 0 0 12px 0;">❌ Wafanyakazi Waliokataa</h4>
              <p style="color: #991b1b; margin: 0 0 12px 0; font-size: 0.875rem;">
                Wafanyakazi wafuatayo waliwahi kuchaguliwa lakini walikataa kazi hii:
              </p>
              <div style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($declinedWorkers as $worker)
                  <div style="background: white; padding: 8px 12px; border-radius: 8px; display: flex; align-items: center; gap: 8px;">
                    <span style="color: #dc2626;">❌</span>
                    <span style="color: #374151; font-weight: 500;">{{ $worker->name }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        @endif
      @endauth
    </div>

    <!-- Comments Section -->
    <div class="comments-section">
      <div class="comments-header">
        <div class="comments-title">
          <span>💬</span>
          Maoni na Maombi
        </div>
      </div>

      <!-- Comment Form -->
      <form method="post" action="{{ route('jobs.comment', $job) }}" class="comment-form">
        @csrf
        <div class="form-group">
          <label class="form-label" for="message">Andika Maoni au Omba Kazi</label>
          <textarea 
            name="message" 
            id="message"
            class="form-textarea"
            placeholder="Andika maoni yako au omba kufanya kazi hii..."
            required
          ></textarea>
        </div>
        
        <div class="form-checkbox">
          <input type="checkbox" name="is_application" value="1" id="is_application">
          <label for="is_application">Hii ni ombi la kufanya kazi</label>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="bid_amount">Pendekeza Bei (hiari)</label>
          <input 
            type="number" 
            name="bid_amount" 
            id="bid_amount"
            class="form-input"
            placeholder="Pendekeza bei yako (TZS)"
            min="0"
            step="100"
          >
        </div>
        
        <button type="submit" class="btn btn-primary">
          <span>📤</span>
          Tuma Maoni
        </button>
      </form>

      <!-- Comments List -->
      <div class="comments-list">
        @foreach($job->comments as $comment)
          <div class="comment-item">
            <div class="comment-header">
              <div class="comment-author">{{ $comment->user->name }}</div>
              @if($comment->is_application)
                <div class="comment-badge">Ameomba Kazi</div>
              @endif
            </div>
            <div class="comment-message">{{ $comment->message }}</div>
            @if($comment->bid_amount)
              <div class="comment-bid">
                <div class="comment-bid-label">Pendekezo la Bei:</div>
                <div class="comment-bid-amount">{{ number_format($comment->bid_amount) }} TZS</div>
              </div>
            @endif

            @auth
              @if(auth()->user()->id === $job->user_id && in_array($job->status, ['posted', 'offered']) && $comment->user->role === 'mfanyakazi')
                <div class="comment-actions">
                  <form method="post" action="{{ route('jobs.accept', [$job, $comment]) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">
                      <span>✅</span>
                      Mchague Huyu
                    </button>
                  </form>
                </div>
              @endif
            @endauth
          </div>
        @endforeach
      </div>
    </div>

  </div>
</div>

@push('scripts')
<script>
  // Initialize map
  const map = L.map('map').setView([{{ $job->lat }}, {{ $job->lng }}], 13);
  
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
  }).addTo(map);

  // Add marker for job location
  L.marker([{{ $job->lat }}, {{ $job->lng }}])
    .addTo(map)
    .bindPopup('<b>{{ $job->title }}</b><br>{{ $job->address_text ?? "Eneo la kazi" }}')
    .openPopup();

  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate comment items on scroll
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

    // Observe all comment items
    document.querySelectorAll('.comment-item').forEach(item => {
      item.style.opacity = '0';
      item.style.transform = 'translateY(20px)';
      item.style.transition = 'all 0.6s ease';
      observer.observe(item);
    });

    // Add hover effects to comment items
    document.querySelectorAll('.comment-item').forEach(item => {
      item.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px) scale(1.02)';
      });
      
      item.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });
  });
</script>
@endpush
@endsection