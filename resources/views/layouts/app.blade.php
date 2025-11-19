<!doctype html>
<html lang="sw">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>TendaPoa</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<style>
:root{ --green:#10b981; --green-50:#ecfdf5; --red:#ef4444; --red-50:#fef2f2; --blue:#2563eb; --ink:#111; --bg:#fff; }
*{box-sizing:border-box}
body{font-family:system-ui,Segoe UI,Roboto,Ubuntu,Arial;background:var(--bg);color:var(--ink);margin:0}
.header{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid #eee}
.brand{color:var(--green);font-weight:800;font-size:20px}
nav a, nav button{color:var(--blue);text-decoration:none;margin-left:12px;background:none;border:none;cursor:pointer;font:inherit}
.btn{display:inline-block;padding:10px 14px;border-radius:12px;text-decoration:none}
.btn-primary{background:var(--green);color:#fff}
.btn-danger{background:var(--red);color:#fff}
.card{border:1px solid #eee;border-radius:18px;padding:16px;margin:10px 0;background:#fff}
.badge{display:inline-block;background:var(--green-50);color:var(--green);padding:4px 8px;border-radius:999px;font-size:12px;margin-left:8px}
.alert-error{background:var(--red-50);color:var(--red);padding:12px;border-radius:14px;margin:10px 0}
input,select,textarea{width:100%;padding:10px;border:1px solid #ddd;border-radius:12px}
</style>
</head>
<body>
<header class="header">
  <div class="brand">TendaPoa</div>
  <nav>
    <a href="{{ route('home') }}">Nyumbani</a>
    <a href="/feed">Kazi Zote</a>
    <a href="/jobs/create">Chapisha Kazi</a>

    @auth
      <a href="{{ route('dashboard') }}">Dashboard</a>
      <a href="{{ route('chat.index') }}">💬 Mazungumzo</a>
      @if(auth()->user()->role === 'admin')
        <div class="relative group">
          <a href="{{ route('admin.dashboard') }}" style="color:#ef4444;font-weight:600">🛠️ Admin</a>
          <!-- Admin Dropdown -->
          <div class="absolute top-full left-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
            <div class="py-2">
              <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                📊 Dashboard
              </a>
              <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                👥 Users
              </a>
              <a href="{{ route('admin.jobs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                💼 Jobs
              </a>
              <a href="{{ route('admin.chats') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                💬 Chats
              </a>
              <a href="{{ route('admin.system-logs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                📋 System Logs
              </a>
              <a href="{{ route('admin.system-settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                ⚙️ Settings
              </a>
              <a href="{{ route('admin.analytics') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                📈 Analytics
              </a>
              <a href="{{ route('admin.push-notifications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                📱 Push Notifications
              </a>
            </div>
          </div>
        </div>
      @endif
      <form method="post" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" title="Toka">Logout</button>
      </form>
    @else
      <a href="{{ route('login') }}">Login</a>
      <a href="{{ route('register') }}">Register</a>
    @endauth
  </nav>
</header>

<main style="max-width:980px;margin:0 auto;padding:18px">
  @yield('content')
</main>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@stack('scripts')
</body>
</html>
