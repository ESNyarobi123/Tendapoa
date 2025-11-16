@extends('layouts.app')

@section('content')
<h2>Badilisha Neno Siri</h2>

@if($errors->any())
  <div class="alert-error">
    <b>Angalia makosa:</b>
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

@if(session('success'))
  <div class="alert-success" style="background:#d4edda;color:#155724;padding:12px;border-radius:4px;margin-bottom:16px">
    {{ session('success') }}
  </div>
@endif

<div class="card" style="max-width:500px;margin:0 auto">
  <p style="margin-bottom:20px">Weka nambari ya OTP uliyopokea kwenye email na neno siri jipya.</p>

  <form method="post" action="{{ route('password.reset.post') }}">
    @csrf
    <label>Barua Pepe
      <input type="email" name="email" value="{{ old('email', $email ?? '') }}" required {{ $email ? 'readonly style="background:#f5f5f5"' : '' }}>
    </label><br>

    <label>Nambari ya OTP
      <input type="text" name="otp" value="{{ old('otp') }}" required maxlength="6" pattern="[0-9]{6}" placeholder="123456" style="letter-spacing:8px;text-align:center;font-size:20px;font-weight:bold">
      <small style="color:#666;font-size:12px">Weka nambari 6 za OTP ulizopokea kwenye email</small>
    </label><br>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
      <label>Neno Siri Jipya
        <input type="password" name="password" required>
      </label>
      <label>Rudia Neno Siri
        <input type="password" name="password_confirmation" required>
      </label>
    </div><br>

    <button class="btn btn-primary" type="submit">Badilisha Neno Siri</button>
    <a class="btn" href="{{ route('login') }}" style="background:var(--blue);color:#fff">Rudi kwenye Login</a>
  </form>
</div>

@push('scripts')
<script>
// Auto-format OTP input
document.querySelector('input[name="otp"]')?.addEventListener('input', function(e) {
  this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
});
</script>
@endpush
@endsection

