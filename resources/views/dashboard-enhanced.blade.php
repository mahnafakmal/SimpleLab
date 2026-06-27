@extends('layouts.app-enhanced')

@section('title', 'Dashboard - SimpleLab')

@section('css')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .overview-section {
        background: white;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--unimus-primary);
        margin-bottom: 1.5rem;
        border-bottom: 3px solid var(--unimus-secondary);
        padding-bottom: 0.5rem;
    }

    .loan-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 0.75rem;
        border-left: 4px solid var(--unimus-primary);
    }

    .loan-item.overdue {
        border-left-color: #dc3545;
        background-color: #ffe6e6;
    }

    .loan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .loan-equipment {
        font-weight: 600;
        color: var(--unimus-primary);
    }

    .loan-date {
        font-size: 0.85rem;
        color: #666;
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i> <strong>Selamat datang!</strong> 
            Anda memiliki {{ $activeLoans->count() }} peminjaman aktif.
            @if($overdueLoans->count() > 0)
            <span class="text-danger"><strong>⚠️ {{ $overdueLoans->count() }} barang terlambat dikembalikan!</strong></span>
            @endif
        </div>
    </div>
</div>

<!-- Dashboard Statistics Grid -->
<div class="dashboard-grid">
    <div class="stat-card available">
        <div class="stat-value" id="available-count">-</div>
        <div class="stat-label">Alat Tersedia</div>
    </div>
    <div class="stat-card borrowed">
        <div class="stat-value" id="borrowed-count">-</div>
        <div class="stat-label">Sedang Dipinjam</div>
    </div>
    <div class="stat-card damaged">
        <div class="stat-value" id="damaged-count">-</div>
        <div class="stat-label">Rusak/Perbaikan</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="total-count">-</div>
        <div class="stat-label">Total Alat</div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row">
    <!-- Left Column: Charts -->
    <div class="col-lg-8">
        <!-- Equipment Status Chart -->
        <div class="overview-section">
            <h3 class="section-title">Status Alat Laboratorium</h3>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Borrowing Frequency Chart -->
        <div class="overview-section">
            <h3 class="section-title">Frekuensi Peminjaman Alat (Top 10)</h3>
            <div class="chart-container" style="height: 400px;">
                <canvas id="frequencyChart"></canvas>
            </div>
        </div>

        <!-- Borrowing Trends Chart -->
        <div class="overview-section">
            <h3 class="section-title">Tren Peminjaman (30 Hari Terakhir)</h3>
            <div class="chart-container">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Right Column: Information & Alerts -->
    <div class="col-lg-4">
        <!-- Active Loans -->
        <div class="overview-section">
            <h3 class="section-title">Peminjaman Aktif</h3>
            <div id="active-loans-container">
                <div class="text-center">
                    <div class="loader" style="margin: 1rem auto;"></div>
                </div>
            </div>
        </div>

        <!-- Lab Schedule Today -->
        <div class="overview-section">
            <h3 class="section-title">Jadwal Hari Ini</h3>
            <div id="today-schedule" class="text-muted small">
                <p>Memuat jadwal...</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="overview-section">
            <h3 class="section-title">Aksi Cepat</h3>
            <div class="d-grid gap-2">
                <a href="{{ route('equipment.return') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Kembalikan Alat
                </a>
                <a href="{{ route('barang.tersedia') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-box"></i> Lihat Alat Tersedia
                </a>
                <a href="{{ route('schedule.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-calendar"></i> Jadwal Lab
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Top Borrowed Items -->
<div class="overview-section">
    <h3 class="section-title">Alat yang Paling Sering Dipinjam</h3>
    <div class="row" id="top-items-container">
        <div class="text-center w-100">
            <div class="loader" style="margin: 1rem auto;"></div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// Chart instances
let statusChart, frequencyChart, trendsChart;

document.addEventListener('DOMContentLoaded', function() {
    // Load equipment status
    loadEquipmentStatus();
    
    // Load borrowing frequency
    loadBorrowingFrequency();
    
    // Load trends
    loadBorrowingTrends();
    
    // Load active loans
    loadActiveLoans();
    
    // Load today's schedule
    loadTodaySchedule();
    
    // Load top items
    loadTopItems();
    
    // Refresh data every 5 minutes
    setInterval(function() {
        loadEquipmentStatus();
        loadBorrowingFrequency();
        loadBorrowingTrends();
        loadActiveLoans();
    }, 5 * 60 * 1000);
});

function loadEquipmentStatus() {
    $.ajax({
        url: "{{ route('statistics.status') }}",
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response;
                document.getElementById('total-count').textContent = data.total;
                document.getElementById('available-count').textContent = data.available;
                document.getElementById('borrowed-count').textContent = data.borrowed;
                document.getElementById('damaged-count').textContent = data.damaged;
                
                // Create status chart
                if (statusChart) statusChart.destroy();
                const ctx = document.getElementById('statusChart').getContext('2d');
                statusChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Tersedia', 'Dipinjam', 'Rusak'],
                        datasets: [{
                            data: [data.available, data.borrowed, data.damaged],
                            backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }
    });
}

function loadBorrowingFrequency() {
    $.ajax({
        url: "{{ route('statistics.frequency') }}",
        method: 'GET',
        success: function(response) {
            if (response.success && response.data.length > 0) {
                const labels = response.data.slice(0, 10).map(item => item.name);
                const data = response.data.slice(0, 10).map(item => item.frequency);
                
                if (frequencyChart) frequencyChart.destroy();
                const ctx = document.getElementById('frequencyChart').getContext('2d');
                frequencyChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Peminjaman',
                            data: data,
                            backgroundColor: 'rgba(0, 51, 102, 0.8)',
                            borderColor: '#003366',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }
    });
}

function loadBorrowingTrends() {
    $.ajax({
        url: "{{ route('statistics.trends') }}",
        method: 'GET',
        success: function(response) {
            if (response.success && response.data.length > 0) {
                const labels = response.data.map(item => item.date);
                const data = response.data.map(item => item.count);
                
                if (trendsChart) trendsChart.destroy();
                const ctx = document.getElementById('trendsChart').getContext('2d');
                trendsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Peminjaman per Hari',
                            data: data,
                            borderColor: '#ff6b35',
                            backgroundColor: 'rgba(255, 107, 53, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#ff6b35',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }
    });
}

function loadActiveLoans() {
    $.ajax({
        url: "{{ route('equipment.active-loans') }}",
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let html = '';
                if (response.loans.length === 0) {
                    html = '<p class="text-muted">Tidak ada peminjaman aktif</p>';
                } else {
                    response.loans.forEach(loan => {
                        const overdueClass = loan.isOverdue ? 'overdue' : '';
                        const overdueText = loan.isOverdue ? `<br><small class="text-danger">Terlambat ${loan.daysOverdue} hari!</small>` : '';
                        html += `
                            <div class="loan-item ${overdueClass}">
                                <div class="loan-header">
                                    <span class="loan-equipment">${loan.equipment}</span>
                                </div>
                                <div class="loan-date">Dipinjam: ${loan.borrowedAt}</div>
                                <div class="loan-date">Tenggat: ${loan.dueDate}</div>
                                ${overdueText}
                            </div>
                        `;
                    });
                }
                document.getElementById('active-loans-container').innerHTML = html;
            }
        }
    });
}

function loadTodaySchedule() {
    $.ajax({
        url: "{{ route('schedule.api') }}",
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let html = '';
                const schedules = response.schedules.filter(s => {
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const today = days[new Date().getDay()];
                    return s.day === today;
                });
                
                if (schedules.length === 0) {
                    html = '<p class="text-muted small">Tidak ada jadwal hari ini</p>';
                } else {
                    schedules.forEach(schedule => {
                        html += `
                            <div class="mb-2 p-2 bg-light rounded">
                                <strong style="color: var(--unimus-primary);">${schedule.title}</strong><br>
                                <small>${schedule.time} | Ruang: ${schedule.room}</small>
                            </div>
                        `;
                    });
                }
                document.getElementById('today-schedule').innerHTML = html;
            }
        }
    });
}

function loadTopItems() {
    $.ajax({
        url: "{{ route('statistics.top-items') }}",
        method: 'GET',
        data: { limit: 5 },
        success: function(response) {
            if (response.success && response.items) {
                let html = '';
                response.items.forEach((item, index) => {
                    html += `
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div style="font-weight: 600; color: var(--unimus-primary); margin-bottom: 0.5rem;">
                                        #${index + 1} - ${item.name}
                                    </div>
                                    <div class="text-muted small">
                                        <i class="bi bi-arrow-repeat"></i> Dipinjam ${item.total_borrows}x
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                document.getElementById('top-items-container').innerHTML = html;
            }
        }
    });
}
</script>
@endsection
