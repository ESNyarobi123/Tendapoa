@extends('layouts.app')

@section('content')
<h2>Fungua Akaunti</h2>

@if($errors->any())
  <div class="alert-error">
    <b>Angalia makosa:</b>
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form method="post" action="{{ route('register.post') }}" class="card">
  @csrf
  <label>Jina Kamili
    <input name="name" value="{{ old('name') }}" required>
  </label><br>

  <label>Barua Pepe
    <input type="email" name="email" value="{{ old('email') }}" required>
  </label><br>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
    <label>Neno Siri
      <input type="password" name="password" required>
    </label>
    <label>Rudia Neno Siri
      <input type="password" name="password_confirmation" required>
    </label>
  </div><br>

  <label>Nafasi (Role)
    <select name="role" required>
      <option value="muhitaji" {{ old('role')==='muhitaji'?'selected':'' }}>Muhitaji (Mteja)</option>
      <option value="mfanyakazi" {{ old('role')==='mfanyakazi'?'selected':'' }}>Mfanyakazi</option>
    </select>
  </label><br>

  <label>Namba ya Simu (hiari)
    <input name="phone" value="{{ old('phone') }}" placeholder="07xxxxxxxx au 2557xxxxxxxx">
  </label><br>

  <div class="card" style="padding:12px">
    <b>Mahali (hiari)</b>
    <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:8px;align-items:center">
      <input name="lat" id="lat" value="{{ old('lat') }}" placeholder="Lat">
      <input name="lng" id="lng" value="{{ old('lng') }}" placeholder="Lng">
      <button type="button" id="gps" class="btn" style="background:var(--blue);color:#fff">GPS</button>
    </div>
    <small>Hii husaidia kuonyesha umbali wa kazi (hasa kwa wafanyakazi).</small>
  </div>

  <button class="btn btn-primary" type="submit">Sajili</button>
  <a class="btn" href="{{ route('login') }}" style="background:var(--blue);color:#fff">Nina akaunti → Login</a>
</form>

@push('scripts')
<script>
document.getElementById('gps')?.addEventListener('click', ()=>{
  if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(pos=>{
      document.getElementById('lat').value = pos.coords.latitude.toFixed(6);
      document.getElementById('lng').value = pos.coords.longitude.toFixed(6);
    });
  }
});
</script>
@endpush
@endsection
