@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <h2 class="h4 mb-3">Thibitisha Malipo</h2>
                    <p class="text-muted mb-4">Ombi la malipo limetumwa kwenye simu yako. Tafadhali thibitisha muamala.</p>
                    
                    <div class="bg-light p-3 rounded mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Order ID:</span>
                            <span class="fw-bold">{{ $job->payment->order_id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Kiasi:</span>
                            <span class="fw-bold text-primary">{{ number_format($job->payment->amount) }} TZS</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status:</span>
                            <span id="st" class="badge bg-warning text-dark">{{ $job->payment->status }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="small text-muted mb-1">Muda uliobaki:</p>
                        <h3 id="timer" class="fw-bold text-danger">01:00</h3>
                    </div>

                    <form id="cancelForm" action="{{ route('jobs.pay.cancel', $job) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-2"></i>Katisha Malipo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let timeLeft = 60;
const timerElement = document.getElementById('timer');
const statusElement = document.getElementById('st');
const cancelForm = document.getElementById('cancelForm');

// Format time as MM:SS
function formatTime(seconds) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
}

// Countdown timer
const timerInterval = setInterval(() => {
    timeLeft--;
    timerElement.textContent = formatTime(timeLeft);
    
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        clearInterval(pollInterval);
        statusElement.className = 'badge bg-danger';
        statusElement.textContent = 'FAILED';
        
        // Auto-submit cancel form after timeout
        // alert('Muda wa malipo umeisha. Tafadhali jaribu tena.');
        cancelForm.submit();
    }
}, 1000);

// Poll for status
const pollInterval = setInterval(async () => {
    try {
        const r = await fetch('{{ route('jobs.pay.poll', $job) }}');
        const j = await r.json();
        
        if (j.done) {
            clearInterval(timerInterval);
            clearInterval(pollInterval);
            statusElement.className = 'badge bg-success';
            statusElement.textContent = 'COMPLETED';
            timerElement.parentElement.style.display = 'none'; // Hide timer
            cancelForm.style.display = 'none'; // Hide cancel button
            
            setTimeout(() => {
                location.href = '{{ route('jobs.show', $job) }}';
            }, 1000);
        }
    } catch (e) {
        console.error('Polling error:', e);
    }
}, 3000);
</script>
@endsection
