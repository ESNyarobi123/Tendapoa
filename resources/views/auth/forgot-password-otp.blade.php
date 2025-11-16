@extends('layouts.app')

@section('content')
<h2>Umesahau Neno Siri?</h2>

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
  <p style="margin-bottom:20px">Weka barua pepe yako ili tutumie nambari ya OTP kwenye email yako.</p>

  <form method="post" action="{{ route('password.forgot.post') }}">
    @csrf
    <label>Barua Pepe
      <input type="email" name="email" value="{{ old('email') }}" required autofocus>
    </label><br>

    <button class="btn btn-primary" type="submit">Tuma OTP</button>
    <a class="btn" href="{{ route('login') }}" style="background:var(--blue);color:#fff">Rudi kwenye Login</a>
  </form>
</div>
@endsection

