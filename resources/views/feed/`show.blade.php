@extends('layouts.app')

@section('content')
<h2>
  {{ $job->title }}
  <span class="badge">{{ $job->category->name }}</span>
</h2>

<div id="map" style="height:280px;border-radius:18px;margin:8px 0"></div>

<div class="card">
  <p><b>Bei:</b> {{ number_format($job->price) }} TZS</p>
  <p>{{ $job->description }}</p>
  @auth
    @if(auth()->user()->role==='muhitaji' && auth()->id()===$job->user_id && $job->status==='posted')
      <small>Chagua mfanyakazi kupitia maoni (comments) hapa chini.</small>
    @endif
  @endauth
</div>

<div class="card">
  <form method="post" action="{{ route('jobs.comment',$job) }}">
    @csrf
    <textarea name="message" placeholder="Andika maoni au omba kazi..." style="width:100%;height:90px"></textarea>
    <label><input type="checkbox" name="is_application" value="1"> Hii ni ombi la kufanya kazi</label>
    <input type="number" name="bid_amount" placeholder="Pendekeza bei (hiari)">
    <button class="btn btn-primary">Tuma</button>
  </form>
  <hr>
  @foreach($job->comments as $c)
    <div style="margin:8px 0">
      <b>{{ $c->user->name }}</b>
      @if($c->is_application)<span class="badge">Ameomba kazi</span>@endif
      <div>{{ $c->message }}</div>
      @if($c->bid_amount)<small>Pendekezo: {{ number_format($c->bid_amount) }} TZS</small>@endif

      @auth
        @if(auth()->user()->id===$job->user_id && $job->status==='posted' && $c->user->role==='mfanyakazi')
          <form method="post" action="{{ route('jobs.accept',[$job,$c]) }}">
            @csrf
            <button class="btn" style="background:var(--green);color:#fff">Mchague huyu</button>
          </form>
        @endif
      @endauth
    </div>
  @endforeach
</div>

<script>
const map = L.map('map').setView([{{ $job->lat }}, {{ $job->lng }}], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map);
const target = L.marker([{{ $job->lat }}, {{ $job->lng }}]).addTo(map);

@if(auth()->check() && auth()->user()->lat && auth()->user()->lng)
  const me = L.marker([{{ auth()->user()->lat }}, {{ auth()->user()->lng }}]).addTo(map);

  function hav(a,b){
    const toR=x=>x*Math.PI/180;
    const [lat1,lon1]=a,[lat2,lon2]=b; const R=6371e3;
    const dLat=toR(lat2-lat1), dLon=toR(lon2-lon1);
    const s1=Math.sin(dLat/2), s2=Math.sin(dLon/2);
    const c=2*Math.atan2(Math.sqrt(s1*s1+Math.cos(toR(lat1))*Math.cos(toR(lat2))*s2*s2), Math.sqrt(1-(s1*s1+Math.cos(toR(lat1))*Math.cos(toR(lat2))*s2*s2)));
    return R*c;
  }
  const d = hav([{{ auth()->user()->lat }}, {{ auth()->user()->lng }}],[{{ $job->lat }}, {{ $job->lng }}]);
  const km = (d/1000).toFixed(1);
  const b = document.createElement('span'); b.className='badge'; b.textContent=`Umbali ~ ${km} km`;
  document.querySelector('h2').append(b);
@endif
</script>
@endsection
