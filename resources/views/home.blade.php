@extends('layouts.app')

@section('content')
<style>
  /* ====== Modern Tendapoa Home Page ====== */
  .home-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem 0;
  }

  .home-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    display: grid;
    gap: 2rem;
  }

  /* Hero Section */
  .hero-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
  }

  .hero-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
  }

  @keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  .hero-content {
    position: relative;
    z-index: 2;
  }

  .hero-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-subtitle {
    font-size: 1.25rem;
    color: #4a5568;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
  }

  .hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 2rem;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    position: relative;
    overflow: hidden;
  }

  .btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
  }

  .btn:hover::before {
    left: 100%;
  }

  .btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 14px rgba(102, 126, 234, 0.4);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
  }

  .btn-secondary {
    background: linear-gradient(135deg, #4299e1, #3182ce);
    color: white;
    box-shadow: 0 4px 14px rgba(66, 153, 225, 0.4);
  }

  .btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(66, 153, 225, 0.6);
  }

  .btn-danger {
    background: linear-gradient(135deg, #f56565, #e53e3e);
    color: white;
    box-shadow: 0 4px 14px rgba(245, 101, 101, 0.4);
  }

  .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 101, 101, 0.6);
  }

  /* Services Section */
  .services-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
  }

  .section-title {
    font-size: 2rem;
    font-weight: 800;
    color: #2d3748;
    margin-bottom: 1rem;
    text-align: center;
  }

  .services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
  }

  .service-card {
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #667eea, #764ba2);
  }

  .service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, #ffffff, #f7fafc);
  }

  .service-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 1rem;
  }

  .service-description {
    color: #4a5568;
    font-size: 0.875rem;
    line-height: 1.5;
  }

  /* How It Works Section */
  .how-it-works-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
  }

  .steps-list {
    list-style: none;
    padding: 0;
    margin: 2rem 0 0;
  }

  .step-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem 0;
    border-bottom: 1px solid #e2e8f0;
    position: relative;
  }

  .step-item:last-child {
    border-bottom: none;
  }

  .step-number {
    flex-shrink: 0;
    width: 2.5rem;
    height: 2.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.125rem;
  }

  .step-content {
    flex: 1;
  }

  .step-text {
    color: #4a5568;
    line-height: 1.6;
    font-size: 1rem;
  }

  /* Stats Section */
  .stats-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
  }

  .stat-item {
    text-align: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-radius: 16px;
    border: 1px solid #e2e8f0;
  }

  .stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
  }

  .stat-label {
    color: #4a5568;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-size: 0.875rem;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .home-content {
      padding: 0 0.5rem;
    }

    .hero-section {
      padding: 2rem 1rem;
    }

    .hero-buttons {
      flex-direction: column;
      align-items: center;
    }

    .btn {
      width: 100%;
      max-width: 300px;
      justify-content: center;
    }

    .services-grid {
      grid-template-columns: 1fr;
    }

    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }

    .step-item {
      flex-direction: column;
      text-align: center;
    }
  }

  /* Animations */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .fade-in-up {
    animation: fadeInUp 0.6s ease-out;
  }

  /* Interactive Effects */
  .service-card:hover .service-badge {
    transform: scale(1.05);
  }

  .btn:hover {
    transform: translateY(-2px);
  }

  .stat-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  }
</style>

<div class="home-container">
  <div class="home-content">
    
    <!-- Hero Section -->
    <section class="hero-section fade-in-up">
      <div class="hero-content">
        <h1 class="hero-title">🧹 TendaPoa — Usafi wa Nyumba & Ofisini</h1>
        <p class="hero-subtitle">Lipa salama (escrow) kupitia ZenoPay. Pata mfanyakazi wa karibu kwa haraka na urahisi.</p>
        
        <div class="hero-buttons">
          <a class="btn btn-primary" href="{{ auth()->check() ? route('jobs.create') : '#' }}" 
             @if(!auth()->check()) onclick="alert('Tafadhali login/register kwanza'); return false;" @endif>
            🚀 Natafuta Mfanyakazi (Chapisha Kazi)
          </a>
          <a class="btn btn-secondary" href="{{ auth()->check() ? route('feed') : '#' }}" 
             @if(!auth()->check()) onclick="alert('Tafadhali login/register kwanza'); return false;" @endif>
            💼 Tafuta Kazi (Mfanyakazi)
          </a>
          <a class="btn btn-danger" href="/dashboard">
            📊 Dashboard
          </a>
        </div>
  </div>
</section>

    <!-- Services Section -->
    <section class="services-section fade-in-up">
      <h2 class="section-title">🛠️ Aina za Huduma</h2>
      <div class="services-grid">
    @foreach($cats as $c)
          <div class="service-card">
            <div class="service-badge">{{ $c->name }}</div>
            <div class="service-description">
              Bonyeza "Chapisha Kazi" kisha uchague aina hii ya huduma. Tunatoa huduma bora za usafi kwa bei nzuri.
            </div>
      </div>
    @endforeach
  </div>
</section>

    <!-- How It Works Section -->
    <section class="how-it-works-section fade-in-up">
      <h2 class="section-title">⚙️ Jinsi Inavyofanya Kazi Kwa Tendapoa</h2>
      <ol class="steps-list">
        <li class="step-item">
          <div class="step-number">1</div>
          <div class="step-content">
            <div class="step-text">
              <strong>Muhitaji</strong> → Chapisha kazi, weka eneo kwenye ramani, weka bei, anza malipo (ZenoPay).
            </div>
          </div>
        </li>
        <li class="step-item">
          <div class="step-number">2</div>
          <div class="step-content">
            <div class="step-text">
              <strong>Mfanyakazi</strong> → Anaona kazi kwenye "Tafuta Kazi", anatuma maoni/ombi la kufanya.
            </div>
          </div>
        </li>
        <li class="step-item">
          <div class="step-number">3</div>
          <div class="step-content">
            <div class="step-text">
              <strong>Muhitaji</strong> → Anam-"accept" mfanyakazi mmoja. Wanafanya kazi. Fedha ziko salama (escrow).
            </div>
          </div>
        </li>
        <li class="step-item">
          <div class="step-number">4</div>
          <div class="step-content">
            <div class="step-text">
              <strong>Baada ya kukamilika</strong> → Malipo yanathibitishwa, mfanyakazi anaweza kuomba "withdraw".
            </div>
          </div>
        </li>
  </ol>
</section>

    <!-- Stats Section -->
    <section class="stats-section fade-in-up">
      <h2 class="section-title">📈 Takwimu za Tendapoa</h2>
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-number">500+</div>
          <div class="stat-label">Wafanyakazi</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">1000+</div>
          <div class="stat-label">Kazi Zilizokamilika</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">98%</div>
          <div class="stat-label">Kuridhika</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">24/7</div>
          <div class="stat-label">Msaada</div>
        </div>
      </div>
    </section>

  </div>
</div>

<script>
  // Add interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer for animations
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

    // Observe elements for animation
    document.querySelectorAll('.service-card, .step-item, .stat-item').forEach(item => {
      item.style.opacity = '0';
      item.style.transform = 'translateY(30px)';
      item.style.transition = 'all 0.6s ease';
      observer.observe(item);
    });

    // Add hover effects
    document.querySelectorAll('.service-card').forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px) scale(1.02)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });

    // Button click animations
    document.querySelectorAll('.btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        // Create ripple effect
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        this.appendChild(ripple);
        
        setTimeout(() => {
          ripple.remove();
        }, 600);
      });
    });
  });
</script>

<style>
  .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
  }

  @keyframes ripple-animation {
    to {
      transform: scale(4);
      opacity: 0;
    }
  }
</style>
@endsection