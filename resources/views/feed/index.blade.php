@extends('layouts.app')

@section('content')
<h2>Kazi Zilizopo</h2>

<form method="get" class="card" style="display:flex;gap:8px;align-items:center">
  <label>Aina
    <select name="category" onchange="this.form.submit()">
      <option value="">Zote</option>
      @foreach(\App\Models\Category::all() as $c)
        <option value="{{ $c->slug }}" {{ $cat==$c->slug?'selected':'' }}>{{ $c->name }}</option>
      @endforeach
    </select>
  </label>
</form>

@foreach($jobs as $job)
  <div class="card">
    <div style="display:flex;justify-content:space-between;gap:12px;align-items:center">
      <div>
        <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
          <div class="badge">{{ $job->category->name }}</div>
          @if($job->poster_type === 'mfanyakazi')
            <div class="badge" style="background:#10b981;color:white">👷 Mfanyakazi</div>
          @else
            <div class="badge" style="background:#3b82f6;color:white">👤 Muhitaji</div>
          @endif
        </div>
        <h3 style="margin:6px 0">{{ $job->title }}</h3>
        <small>{{ number_format($job->price) }} TZS</small>
        @if($job->poster_type === 'mfanyakazi')
          <div style="font-size:0.75rem;color:#10b981;margin-top:4px">
            📝 Huduma inayotolewa na mfanyakazi
          </div>
        @endif
      </div>
      <a class="btn" style="background:var(--blue);color:#fff" href="{{ route('jobs.show',$job) }}">Fungua</a>
    </div>
  </div>
@endforeach

{{ $jobs->links() }}
@endsection
