@extends('layouts.app')
@section('title', 'Omba Withdrawal')

@section('content')
<style>
  /* ====== Modern Withdrawal Page ====== */
  .withdraw-page {
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

  .withdraw-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .page-container {
    max-width: 600px;
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
    text-align: center;
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
    margin: 0;
  }

  /* Wallet Balance */
  .wallet-balance {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
    text-align: center;
  }

  .balance-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 8px;
  }

  .balance-amount {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--success);
    margin-bottom: 16px;
  }

  .balance-info {
    font-size: 0.875rem;
    color: var(--text-muted);
  }

  /* Withdrawal Form */
  .withdrawal-form {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 32px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.2);
  }

  .form-group {
    margin-bottom: 24px;
  }

  .form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .form-input {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
  }

  .form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  .form-help {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-top: 4px;
  }

  /* Error Messages */
  .alert-error {
    background: #fef2f2;
    border: 2px solid #fecaca;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
    color: #dc2626;
  }

  .alert-error ul {
    margin: 0;
    padding-left: 20px;
  }

  .alert-error li {
    margin-bottom: 4px;
  }

  /* Payment Methods */
  .payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
    margin-bottom: 24px;
  }

  .payment-method {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
  }

  .payment-method:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
  }

  .payment-method.selected {
    border-color: var(--primary);
    background: #f0f9ff;
  }

  .payment-method-icon {
    font-size: 2rem;
    margin-bottom: 8px;
  }

  .payment-method-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
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

  .btn-outline {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
  }

  .btn-outline:hover {
    background: var(--primary);
    color: white;
  }

  .form-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .withdraw-page {
      padding: 16px;
    }
    
    .page-header {
      padding: 24px;
    }
    
    .page-title {
      font-size: 2rem;
    }
    
    .withdrawal-form {
      padding: 24px;
    }
    
    .form-actions {
      flex-direction: column;
    }
  }
</style>

<div class="withdraw-page">
  <div class="page-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <h1 class="page-title">💰 Omba Withdrawal</h1>
      <p class="page-subtitle">Toa pesa kutoka kwenye wallet yako kwa urahisi na salama</p>
    </div>

    <!-- Wallet Balance -->
    <div class="wallet-balance">
      <div class="balance-label">Salio Lako</div>
      <div class="balance-amount">{{ number_format($wallet->balance) }} TZS</div>
      <div class="balance-info">
        @if($wallet->balance >= 5000)
          <span style="color: var(--success);">✅ Unaweza kutoa pesa</span>
        @else
          <span style="color: var(--danger);">❌ Salio ni chini ya TZS 5,000</span>
        @endif
      </div>
    </div>

    <!-- Withdrawal Form -->
    <div class="withdrawal-form">
  @if($errors->any())
    <div class="alert-error">
          <b>⚠️ Angalia makosa:</b>
          <ul>
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
    </div>
  @endif

  <form method="post" action="{{ url('/withdraw/submit') }}">
    @csrf

        <!-- Amount -->
        <div class="form-group">
          <label class="form-label" for="amount">Kiasi cha Kutolea</label>
          <input 
            type="number" 
            id="amount"
            name="amount" 
            class="form-input"
            placeholder="mf. 10000"
            min="5000" 
            max="{{ $wallet->balance }}"
            value="{{ old('amount') }}"
            required
          >
          <div class="form-help">
            Kiasi cha chini ni TZS 5,000. Kiasi cha juu ni {{ number_format($wallet->balance) }} TZS
          </div>
        </div>

        <!-- Payment Method -->
        <div class="form-group">
          <label class="form-label">Chagua Mfumo wa Malipo</label>
          <div class="payment-methods">
            <div class="payment-method" data-method="mpesa">
              <div class="payment-method-icon">📱</div>
              <div class="payment-method-name">M-Pesa</div>
            </div>
            <div class="payment-method" data-method="tigopesa">
              <div class="payment-method-icon">🟠</div>
              <div class="payment-method-name">TigoPesa</div>
            </div>
            <div class="payment-method" data-method="airtel">
              <div class="payment-method-icon">🔵</div>
              <div class="payment-method-name">Airtel Money</div>
            </div>
          </div>
          <input type="hidden" name="method" id="selectedMethod" value="mpesa">
        </div>

        <!-- Phone Number -->
        <div class="form-group">
          <label class="form-label" for="phone_number">Namba ya Simu</label>
          <input 
            type="tel" 
            id="phone_number"
            name="phone_number" 
            class="form-input"
            placeholder="07xxxxxxxx au 2557xxxxxxxx"
            value="{{ old('phone_number') }}"
            required
          >
          <div class="form-help">
            Ingiza namba ya simu yako ya M-Pesa, TigoPesa, au Airtel Money
          </div>
        </div>

        <!-- Registered Name -->
        <div class="form-group">
          <label class="form-label" for="registered_name">Majina yaliyosajiliwa</label>
          <input 
            type="text" 
            id="registered_name"
            name="registered_name" 
            class="form-input"
            placeholder="Majina yaliyosajiliwa kwenye simu"
            value="{{ old('registered_name') }}"
            required
          >
          <div class="form-help">
            Ingiza majina yaliyosajiliwa kwenye namba ya simu yako
          </div>
        </div>

        <!-- Network Type -->
        <div class="form-group">
          <label class="form-label" for="network_type">Aina ya Mtandao</label>
          <select 
            id="network_type"
            name="network_type" 
            class="form-input"
            required
          >
            <option value="">Chagua aina ya mtandao</option>
            <option value="vodacom" {{ old('network_type') == 'vodacom' ? 'selected' : '' }}>Vodacom (M-Pesa)</option>
            <option value="tigo" {{ old('network_type') == 'tigo' ? 'selected' : '' }}>Tigo (TigoPesa)</option>
            <option value="airtel" {{ old('network_type') == 'airtel' ? 'selected' : '' }}>Airtel (Airtel Money)</option>
            <option value="halotel" {{ old('network_type') == 'halotel' ? 'selected' : '' }}>Halotel</option>
            <option value="ttcl" {{ old('network_type') == 'ttcl' ? 'selected' : '' }}>TTCL</option>
          </select>
          <div class="form-help">
            Chagua aina ya mtandao wa simu yako
          </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary" {{ $wallet->balance < 5000 ? 'disabled' : '' }}>
            <span>💰</span>
            Tuma Ombi
          </button>
          <a href="{{ route('dashboard') }}" class="btn btn-outline">
            <span>↩️</span>
            Rudi Dashboard
          </a>
        </div>
  </form>
    </div>

  </div>
</div>

<script>
  // Payment method selection
  document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function() {
      // Remove selected class from all methods
      document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
      
      // Add selected class to clicked method
      this.classList.add('selected');
      
      // Update hidden input
      document.getElementById('selectedMethod').value = this.dataset.method;
    });
  });

  // Set default selection
  document.querySelector('.payment-method[data-method="mpesa"]').classList.add('selected');

  // Amount validation
  document.getElementById('amount').addEventListener('input', function() {
    const amount = parseInt(this.value);
    const maxAmount = {{ $wallet->balance }};
    
    if (amount > maxAmount) {
      this.value = maxAmount;
    }
    
    if (amount < 5000 && amount > 0) {
      this.setCustomValidity('Kiasi cha chini ni TZS 5,000');
    } else {
      this.setCustomValidity('');
    }
  });

  // Add some interactive animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate form elements on scroll
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

    // Observe form elements
    document.querySelectorAll('.form-group').forEach(group => {
      group.style.opacity = '0';
      group.style.transform = 'translateY(20px)';
      group.style.transition = 'all 0.6s ease';
      observer.observe(group);
    });
  });
</script>
@endsection