@extends('layouts.app')

@section('title', 'System Logs - Admin')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
  
  * {
    font-family: 'Inter', sans-serif;
  }
  
  .glass-morphism {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
  }
  
  .gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  }
  
  .activity-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
  }
  
  .activity-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  }
  
  .pulse-animation {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }
  
  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .5; }
  }
  
  .fade-in {
    animation: fadeIn 0.5s ease-in-out;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  .slide-in {
    animation: slideIn 0.6s ease-out;
  }
  
  @keyframes slideIn {
    from { transform: translateX(-100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }
  
  .type-badge {
    position: relative;
    overflow: hidden;
  }
  
  .type-badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
  }
  
  .type-badge:hover::before {
    left: 100%;
  }
  
  .search-input {
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
  }
  
  .search-input:focus {
    background: rgba(255, 255, 255, 1);
    transform: scale(1.02);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }
  
  .filter-btn {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }
  
  .filter-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }
  
  .filter-btn:hover::before {
    width: 300px;
    height: 300px;
  }
  
  .stats-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
  }
  
  .stats-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
  }
  
  .timeline-line {
    position: absolute;
    left: 24px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #3b82f6, #8b5cf6, #f59e0b);
  }
  
  .activity-item {
    position: relative;
    padding-left: 60px;
  }
  
  .activity-item::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 20px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
  }
  
  .floating-action {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    animation: float 3s ease-in-out infinite;
  }
  
  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
  }
</style>

<div class="min-h-screen gradient-bg">
  <!-- Animated Background Elements -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-40 left-1/2 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse" style="animation-delay: 4s;"></div>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="glass-morphism rounded-3xl p-8 mb-8 fade-in">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
        <div class="mb-6 lg:mb-0">
          <h1 class="text-4xl font-bold text-white mb-3 slide-in">
            📊 System Activity Monitor
          </h1>
          <p class="text-white/80 text-lg">Real-time monitoring of all platform activities and user interactions</p>
          <div class="flex items-center mt-4">
            <div class="w-3 h-3 bg-green-400 rounded-full mr-2 pulse-animation"></div>
            <span class="text-white/90 font-medium">Live monitoring active</span>
          </div>
        </div>
        <div class="flex flex-wrap gap-4">
          <a href="{{ route('admin.dashboard') }}" 
             class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 backdrop-blur-sm border border-white/30">
            ← Dashboard
          </a>
          <button onclick="refreshData()" 
                  class="bg-blue-500/80 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
            🔄 Refresh
          </button>
        </div>
      </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="stats-card rounded-2xl p-6 text-white fade-in" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/80 text-sm font-medium">Total Activities</p>
            <p class="text-3xl font-bold">{{ $activities->count() }}</p>
            <p class="text-white/60 text-xs mt-1">All time</p>
          </div>
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="stats-card rounded-2xl p-6 text-white fade-in" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/80 text-sm font-medium">Jobs Created</p>
            <p class="text-3xl font-bold">{{ $activities->where('type', 'job_created')->count() }}</p>
            <p class="text-white/60 text-xs mt-1">This session</p>
          </div>
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
              <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="stats-card rounded-2xl p-6 text-white fade-in" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/80 text-sm font-medium">Messages Sent</p>
            <p class="text-3xl font-bold">{{ $activities->where('type', 'message_sent')->count() }}</p>
            <p class="text-white/60 text-xs mt-1">Private chats</p>
          </div>
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="stats-card rounded-2xl p-6 text-white fade-in" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/80 text-sm font-medium">Payments Made</p>
            <p class="text-3xl font-bold">{{ $activities->where('type', 'payment_made')->count() }}</p>
            <p class="text-white/60 text-xs mt-1">Transactions</p>
          </div>
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
              <path d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="glass-morphism rounded-2xl p-6 mb-8 fade-in">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex-1">
          <div class="relative">
            <input type="text" 
                   id="searchInput"
                   placeholder="Search activities, users, or descriptions..." 
                   class="search-input w-full px-4 py-3 pl-12 rounded-xl border-0 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-0">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap gap-3">
          <button onclick="filterByType('all')" 
                  class="filter-btn bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
            All
          </button>
          <button onclick="filterByType('job_created')" 
                  class="filter-btn bg-green-500/80 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
            Jobs
          </button>
          <button onclick="filterByType('message_sent')" 
                  class="filter-btn bg-blue-500/80 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
            Messages
          </button>
          <button onclick="filterByType('payment_made')" 
                  class="filter-btn bg-orange-500/80 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
            Payments
          </button>
        </div>
      </div>
    </div>

    <!-- Activity Timeline -->
    <div class="glass-morphism rounded-2xl overflow-hidden fade-in">
      <div class="px-8 py-6 border-b border-white/20">
        <h2 class="text-2xl font-bold text-white">Activity Timeline</h2>
        <p class="text-white/80">Real-time feed of all platform activities</p>
      </div>

      <div class="relative">
        <div class="timeline-line"></div>
        <div class="p-6 space-y-6" id="activityContainer">
          @forelse($activities as $index => $activity)
          <div class="activity-item activity-card" 
               data-type="{{ $activity['type'] }}" 
               data-user="{{ strtolower($activity['user']->name ?? '') }}"
               data-description="{{ strtolower($activity['description'] ?? '') }}"
               style="animation-delay: {{ $index * 0.1 }}s;">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
              <div class="flex items-start space-x-4">
                <!-- Activity Icon -->
                <div class="flex-shrink-0">
                  @if($activity['type'] == 'job_created')
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center border border-green-500/30">
                      <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                  @elseif($activity['type'] == 'message_sent')
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center border border-blue-500/30">
                      <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                      </svg>
                    </div>
                  @elseif($activity['type'] == 'payment_made')
                    <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center border border-orange-500/30">
                      <svg class="w-6 h-6 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                        <path d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                      </svg>
                    </div>
                  @else
                    <div class="w-12 h-12 bg-gray-500/20 rounded-xl flex items-center justify-center border border-gray-500/30">
                      <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                  @endif
                </div>

                <!-- Activity Content -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-3">
                      <h3 class="text-lg font-semibold text-white">
                        {{ $activity['user']->name ?? 'Unknown User' }}
                      </h3>
                      <span class="type-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white/90">
                        {{ ucfirst($activity['user']->role ?? 'user') }}
                      </span>
                    </div>
                    <div class="text-right">
                      <p class="text-sm text-white/80 font-medium">
                        {{ $activity['timestamp']->diffForHumans() }}
                      </p>
                      <p class="text-xs text-white/60">
                        {{ $activity['timestamp']->format('M j, Y g:i A') }}
                      </p>
                    </div>
                  </div>
                  
                  <p class="text-white/90 mb-4">{{ $activity['description'] }}</p>

                  <!-- Additional Details -->
                  @if(isset($activity['data']))
                  <div class="flex flex-wrap gap-2">
                    @if($activity['type'] == 'job_created')
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-300 border border-green-500/30">
                        Job ID: {{ $activity['data']->id }}
                      </span>
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-300 border border-green-500/30">
                        Budget: Tsh {{ number_format($activity['data']->budget ?? 0) }}
                      </span>
                    @elseif($activity['type'] == 'message_sent')
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300 border border-blue-500/30">
                        Message ID: {{ $activity['data']->id }}
                      </span>
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300 border border-blue-500/30">
                        To: {{ $activity['data']->receiver->name ?? 'Unknown' }}
                      </span>
                    @elseif($activity['type'] == 'payment_made')
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-500/20 text-orange-300 border border-orange-500/30">
                        Payment ID: {{ $activity['data']->id }}
                      </span>
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-500/20 text-orange-300 border border-orange-500/30">
                        Amount: Tsh {{ number_format($activity['data']->amount ?? 0) }}
                      </span>
                    @endif
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          @empty
          <div class="text-center py-16">
            <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-12 h-12 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">No activities found</h3>
            <p class="text-white/80">Activities will appear here as users interact with the platform.</p>
          </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Floating Action Button -->
    <div class="floating-action">
      <button onclick="scrollToTop()" 
              class="w-14 h-14 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 border border-white/30">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
      </button>
    </div>
  </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
  const searchTerm = e.target.value.toLowerCase();
  const activities = document.querySelectorAll('.activity-item');
  
  activities.forEach(activity => {
    const user = activity.dataset.user;
    const description = activity.dataset.description;
    
    if (user.includes(searchTerm) || description.includes(searchTerm)) {
      activity.style.display = 'block';
      activity.classList.add('fade-in');
    } else {
      activity.style.display = 'none';
    }
  });
});

// Filter by type
function filterByType(type) {
  const activities = document.querySelectorAll('.activity-item');
  const buttons = document.querySelectorAll('.filter-btn');
  
  // Update button states
  buttons.forEach(btn => {
    btn.classList.remove('bg-white/30');
    btn.classList.add('bg-white/20');
  });
  event.target.classList.remove('bg-white/20');
  event.target.classList.add('bg-white/30');
  
  // Filter activities
  activities.forEach(activity => {
    if (type === 'all' || activity.dataset.type === type) {
      activity.style.display = 'block';
      activity.classList.add('fade-in');
    } else {
      activity.style.display = 'none';
    }
  });
}

// Refresh data
function refreshData() {
  const btn = event.target;
  const originalText = btn.innerHTML;
  
  btn.innerHTML = '🔄 Refreshing...';
  btn.disabled = true;
  
  setTimeout(() => {
    location.reload();
  }, 1000);
}

// Scroll to top
function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

// Auto-refresh every 30 seconds
setTimeout(function() {
  location.reload();
}, 30000);

// Add entrance animations
document.addEventListener('DOMContentLoaded', function() {
  const elements = document.querySelectorAll('.fade-in, .slide-in');
  elements.forEach((el, index) => {
    el.style.animationDelay = `${index * 0.1}s`;
  });
});
</script>
@endsection