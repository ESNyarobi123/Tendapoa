<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Tendapoa - Usafi wa Kuegemea, Kazi za Kuegemea</title>
    <meta name="description" content="Pata usafi wa kuegemea na kazi za kuegemea kwa urahisi na salama. Tendapoa inaunganisha wafanyakazi na wateja kwa njia ya kisasa na salama.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    
    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /* ====== Modern Tendapoa Homepage ====== */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                line-height: 1.6;
                color: #1a1a1a;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                overflow-x: hidden;
            }
            
            /* Header */
            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                padding: 1rem 0;
                transition: all 0.3s ease;
            }
            
            .header.scrolled {
                background: rgba(255, 255, 255, 0.98);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
            
            .nav-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .logo {
                font-size: 1.5rem;
                font-weight: 800;
                background: linear-gradient(135deg, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .nav-links {
                display: flex;
                gap: 2rem;
                align-items: center;
            }
            
            .nav-links a {
                text-decoration: none;
                color: #4a5568;
                font-weight: 500;
                transition: color 0.3s ease;
            }
            
            .nav-links a:hover {
                color: #667eea;
            }
            
            .btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1.5rem;
                border-radius: 12px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                font-size: 0.875rem;
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
            
            .btn-outline {
                background: transparent;
                color: #667eea;
                border: 2px solid #667eea;
            }
            
            .btn-outline:hover {
                background: #667eea;
                color: white;
            }
            
            /* Hero Section */
            .hero {
                padding: 8rem 0 4rem;
                text-align: center;
                position: relative;
                overflow: hidden;
            }
            
            .hero::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="120" fill="url(%23a)"/></svg>');
                opacity: 0.3;
                animation: float 20s ease-in-out infinite;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
            }
            
            .hero-content {
                max-width: 800px;
                margin: 0 auto;
                padding: 0 2rem;
                position: relative;
                z-index: 2;
            }
            
            .hero h1 {
                font-size: clamp(2.5rem, 5vw, 4rem);
                font-weight: 800;
                line-height: 1.2;
                margin-bottom: 1.5rem;
                background: linear-gradient(135deg, #ffffff, #f0f9ff);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
            
            .hero p {
                font-size: 1.25rem;
                color: rgba(255, 255, 255, 0.9);
                margin-bottom: 2.5rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .hero-buttons {
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
                margin-bottom: 3rem;
            }
            
            .hero-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 2rem;
                max-width: 600px;
                margin: 0 auto;
            }
            
            .stat-item {
                text-align: center;
                padding: 1.5rem;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border-radius: 16px;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .stat-number {
                font-size: 2rem;
                font-weight: 800;
                color: white;
                margin-bottom: 0.5rem;
            }
            
            .stat-label {
                font-size: 0.875rem;
                color: rgba(255, 255, 255, 0.8);
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            
            /* Features Section */
            .features {
                padding: 6rem 0;
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
            }
            
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
            }
            
            .section-title {
                text-align: center;
                font-size: 2.5rem;
                font-weight: 800;
                color: white;
                margin-bottom: 1rem;
            }
            
            .section-subtitle {
                text-align: center;
                font-size: 1.125rem;
                color: rgba(255, 255, 255, 0.8);
                margin-bottom: 4rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
            }
            
            .feature-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(20px);
                border-radius: 20px;
                padding: 2rem;
                border: 1px solid rgba(255, 255, 255, 0.2);
                transition: all 0.3s ease;
                text-align: center;
            }
            
            .feature-card:hover {
                transform: translateY(-10px);
                background: rgba(255, 255, 255, 0.15);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }
            
            .feature-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
                display: block;
            }
            
            .feature-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: white;
                margin-bottom: 1rem;
            }
            
            .feature-description {
                color: rgba(255, 255, 255, 0.8);
                line-height: 1.6;
            }
            
            /* How It Works Section */
            .how-it-works {
                padding: 6rem 0;
            }
            
            .steps-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2rem;
                margin-top: 3rem;
            }
            
            .step-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(20px);
                border-radius: 20px;
                padding: 2rem;
                border: 1px solid rgba(255, 255, 255, 0.2);
                text-align: center;
                position: relative;
                transition: all 0.3s ease;
            }
            
            .step-card:hover {
                transform: translateY(-5px);
                background: rgba(255, 255, 255, 0.15);
            }
            
            .step-number {
                position: absolute;
                top: -1rem;
                left: 50%;
                transform: translateX(-50%);
                width: 3rem;
                height: 3rem;
                background: linear-gradient(135deg, #667eea, #764ba2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                color: white;
                font-size: 1.25rem;
            }
            
            .step-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: white;
                margin: 1rem 0;
            }
            
            .step-description {
                color: rgba(255, 255, 255, 0.8);
                line-height: 1.6;
            }
            
            /* CTA Section */
            .cta {
                padding: 6rem 0;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(20px);
                text-align: center;
            }
            
            .cta-content {
                max-width: 600px;
                margin: 0 auto;
            }
            
            .cta h2 {
                font-size: 2.5rem;
                font-weight: 800;
                color: white;
                margin-bottom: 1rem;
            }
            
            .cta p {
                font-size: 1.125rem;
                color: rgba(255, 255, 255, 0.8);
                margin-bottom: 2rem;
            }
            
            /* Footer */
            .footer {
                background: rgba(0, 0, 0, 0.2);
                backdrop-filter: blur(20px);
                padding: 3rem 0 2rem;
                text-align: center;
            }
            
            .footer-content {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
            }
            
            .footer-links {
                display: flex;
                justify-content: center;
                gap: 2rem;
                margin-bottom: 2rem;
                flex-wrap: wrap;
            }
            
            .footer-links a {
                color: rgba(255, 255, 255, 0.8);
                text-decoration: none;
                transition: color 0.3s ease;
            }
            
            .footer-links a:hover {
                color: white;
            }
            
            .footer-bottom {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                padding-top: 2rem;
                color: rgba(255, 255, 255, 0.6);
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .nav-container {
                    padding: 0 1rem;
                }
                
                .nav-links {
                    gap: 1rem;
                }
                
                .hero {
                    padding: 6rem 0 3rem;
                }
                
                .hero-buttons {
                    flex-direction: column;
                    align-items: center;
                }
                
                .hero-stats {
                    grid-template-columns: repeat(2, 1fr);
                }
                
                .features-grid,
                .steps-grid {
                    grid-template-columns: 1fr;
                }
                
                .footer-links {
                    flex-direction: column;
                    gap: 1rem;
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
            
            /* Mobile Menu */
            .mobile-menu-btn {
                display: none;
                background: none;
                border: none;
                color: #4a5568;
                font-size: 1.5rem;
                cursor: pointer;
            }
            
            @media (max-width: 768px) {
                .mobile-menu-btn {
                    display: block;
                }
                
                .nav-links {
                    display: none;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: rgba(255, 255, 255, 0.98);
                    backdrop-filter: blur(20px);
                    flex-direction: column;
                    padding: 1rem;
                    border-radius: 0 0 16px 16px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                }
                
                .nav-links.active {
                    display: flex;
                }
            }
        </style>
    @endif
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="nav-container">
            <div class="logo">🧹 Tendapoa</div>
            <nav class="nav-links" id="navLinks">
                <a href="#home">Nyumbani</a>
                <a href="#features">Vipengele</a>
                <a href="#how-it-works">Jinsi Inavyofanya Kazi</a>
                <a href="#contact">Mawasiliano</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline">Ingia</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Jisajili</a>
                        @endif
                    @endauth
                @endif
            </nav>
            <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content fade-in-up">
            <h1>Usafi wa Kuegemea, Kazi za Kuegemea</h1>
            <p>Pata usafi wa kuegemea na kazi za kuegemea kwa urahisi na salama. Tendapoa inaunganisha wafanyakazi na wateja kwa njia ya kisasa na salama.</p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary">
                    🚀 Anza Sasa
                </a>
                <a href="#features" class="btn btn-outline">
                    📖 Jifunze Zaidi
                </a>
            </div>
            <div class="hero-stats">
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
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">Kwa Nini Chagua Tendapoa?</h2>
            <p class="section-subtitle">Tunatoa huduma bora za usafi na kazi za kuegemea kwa wateja wetu</p>
            <div class="features-grid">
                <div class="feature-card">
                    <span class="feature-icon">🛡️</span>
                    <h3 class="feature-title">Salama na Kuegemea</h3>
                    <p class="feature-description">Wafanyakazi wetu wamepitia uchunguzi wa kina na wana sifa za kuegemea. Huduma zetu ni salama kabisa.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">⚡</span>
                    <h3 class="feature-title">Haraka na Rahisi</h3>
                    <p class="feature-description">Pata wafanyakazi haraka kwa kubofya tu. Mfumo wetu ni rahisi na wa kisasa.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">💰</span>
                    <h3 class="feature-title">Bei Nzuri</h3>
                    <p class="feature-description">Huduma zetu zina bei nzuri na thamani ya pesa. Hakuna malipo ya ziada au gharama za siri.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">📱</span>
                    <h3 class="feature-title">Teknolojia ya Kisasa</h3>
                    <p class="feature-description">Tumia teknolojia ya kisasa kufuatilia kazi na malipo. Kila kitu ni wazi na rahisi.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">🎯</span>
                    <h3 class="feature-title">Kazi za Kuegemea</h3>
                    <p class="feature-description">Wafanyakazi wetu wana ujuzi na uzoefu wa kutosha. Kazi zote zinakamilika kwa kiwango cha juu.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">🔄</span>
                    <h3 class="feature-title">Msaada wa 24/7</h3>
                    <p class="feature-description">Timu yetu ya msaada inapatikana masaa 24 kusaidia wateja na wafanyakazi wakati wowote.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <h2 class="section-title">Jinsi Inavyofanya Kazi</h2>
            <p class="section-subtitle">Ni rahisi sana! Fuata hatua hizi za kufanya kazi na Tendapoa</p>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Chapisha Kazi</h3>
                    <p class="step-description">Chapisha kazi yako kwa kuelezea unahitaji nini na lini unahitaji kufanywa.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Chagua Mfanyakazi</h3>
                    <p class="step-description">Wafanyakazi wataomba kazi yako na wewe utachagua yule anayekufaa zaidi.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Fuatilia Kazi</h3>
                    <p class="step-description">Fuatilia kazi yako kwa wakati halisi na ujiridhishe kazi inafanywa vizuri.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h3 class="step-title">Maliza na Malipa</h3>
                    <p class="step-description">Kazi ikikamilika, maliza na malipa kwa urahisi na salama.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="contact">
        <div class="container">
            <div class="cta-content">
                <h2>Anza Sasa!</h2>
                <p>Jiunge na maelfu ya wateja na wafanyakazi ambao tayari wanatumia Tendapoa kwa huduma za usafi na kazi za kuegemea.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        🚀 Jisajili Sasa
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline">
                        🔑 Ingia
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="#home">Nyumbani</a>
                <a href="#features">Vipengele</a>
                <a href="#how-it-works">Jinsi Inavyofanya Kazi</a>
                <a href="#contact">Mawasiliano</a>
                <a href="#">Masharti</a>
                <a href="#">Sera ya Faragha</a>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Tendapoa. Haki zote zimehifadhiwa.</p>
            </div>
        </div>
    </footer>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

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
        document.querySelectorAll('.feature-card, .step-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });

        // Add some interactive effects
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>