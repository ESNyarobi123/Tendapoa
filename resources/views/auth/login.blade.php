@extends('layouts.app')

@section('content')
<h2>Ingia</h2>

@if($errors->any())
  <div class="alert-error">
    <b>Angalia makosa:</b>
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form method="post" action="{{ route('login.post') }}" class="card">
  @csrf
  <label>Barua Pepe
    <input type="email" name="email" value="{{ old('email') }}" required>
  </label><br>

  <label>Neno Siri
    <input type="password" name="password" required>
  </label><br>

  <label style="display:flex;align-items:center;gap:8px">
    <input type="checkbox" name="remember" value="1"> Kumbuka mimi
  </label><br>

  <button class="btn btn-primary" type="submit">Ingia</button>
  <a class="btn" href="{{ route('register') }}" style="background:var(--blue);color:#fff">Sina akaunti → Sajili</a>
</form>
@endsection
