@extends('layouts.app-enhanced')

@section('title', 'Jadwal Laboratorium - SimpleLab')

@section('css')
<style>
    .schedule-container {
        background: white;
        border-radius: 8px;
        padding: 2rem;
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

    .schedule-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--unimus-primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .schedule-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .schedule-time {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--unimus-secondary);
        margin-bottom: 0.5rem;
    }

    .schedule-subject {
        font-weight: 600;
        color: var(--unimus-primary);
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .schedule-detail {
        font-size: 0.9rem;
        color: #666;
        margin: 0.25rem 0;
    }

    .schedule-badge {
        display: inline-block;
        background-color: rgba(0, 51, 102, 0.1);
        color: var(--unimus-primary);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        margin-right: 0.5rem;
        margin-top: 0.5rem;
    }

    .day-group {
        margin-bottom: 2rem;
    }

    .day-header {
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        background-color: var(--unimus-primary);
        padding: 0.75rem 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #999;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .filter-section {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .schedule-container {
            padding: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="schedule-container">
    <h2 class="section-title"><i class="bi bi-calendar-week"></i> Jadwal Laboratorium</h2>

    <div class="filter-section">
        <div class="row">
            <div class="col-md-6">
                <input type="text" id="searchSchedule" class="form-control" placeholder="Cari mata kuliah, dosen, atau kelas...">
            </div>
            <div class="col-md-6">
                <select id="filterDay" class="form-select">
                    <option value="">Semua Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="mb-4">
        <h4 class="section-title" style="border-bottom: 2px solid var(--unimus-secondary);">📅 Jadwal Hari Ini</h4>
        <div id="todaySchedules"></div>
    </div>

    <!-- All Schedules by Day -->
    <div>
        <h4 class="section-title" style="border-bottom: 2px solid var(--unimus-secondary);">📋 Jadwal Minggu Depan</h4>
        <div id="scheduleList"></div>
    </div>

    <!-- Admin Section -->
    @if(Auth::user()->role === 'admin')
    <div class="mt-4">
        <h4 class="section-title" style="border-bottom: 2px solid var(--unimus-secondary);">⚙️ Kelola Jadwal</h4>
        
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
            <i class="bi bi-plus-circle"></i> Tambah Jadwal Baru
        </button>

        <!-- Add Schedule Modal -->
        <div class="modal fade" id="addScheduleModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: var(--unimus-primary); color: white;">
                        <h5 class="modal-title">Tambah Jadwal Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addScheduleForm">
                            <div class="mb-3">
                                <label class="form-label">Hari</label>
                                <select name="hari" class="form-select" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="Monday">Senin</option>
                                    <option value="Tuesday">Selasa</option>
                                    <option value="Wednesday">Rabu</option>
                                    <option value="Thursday">Kamis</option>
                                    <option value="Friday">Jumat</option>
                                    <option value="Saturday">Sabtu</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mata Kuliah</label>
                                <input type="text" name="mata_kuliah" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dosen</label>
                                <input type="text" name="dosen" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ruangan</label>
                                <input type="text" name="ruangan" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kapasitas</label>
                                <input type="number" name="kapasitas" class="form-control">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="saveSchedule()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('js')
<script>
let allSchedules = [];

document.addEventListener('DOMContentLoaded', function() {
    loadSchedules();
    
    // Setup filters
    document.getElementById('searchSchedule').addEventListener('input', filterSchedules);
    document.getElementById('filterDay').addEventListener('change', filterSchedules);
});

function loadSchedules() {
    $.ajax({
        url: "{{ route('schedule.api') }}",
        method: 'GET',
        success: function(response) {
            if (response.success) {
                allSchedules = response.schedules;
                displaySchedules();
            }
        }
    });
}

function displaySchedules() {
    // Display today's schedules
    const today = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][new Date().getDay()];
    const todaySchedules = allSchedules.filter(s => s.day === today);
    
    let todayHTML = '';
    if (todaySchedules.length === 0) {
        todayHTML = `
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                <p>Tidak ada jadwal hari ini</p>
            </div>
        `;
    } else {
        todaySchedules.sort((a, b) => a.time.localeCompare(b.time)).forEach(schedule => {
            todayHTML += createScheduleCard(schedule);
        });
    }
    document.getElementById('todaySchedules').innerHTML = todayHTML;
    
    // Display all schedules grouped by day
    const daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    const groupedByDay = {};
    
    daysOrder.forEach(day => {
        groupedByDay[day] = allSchedules.filter(s => s.day === day).sort((a, b) => a.time.localeCompare(b.time));
    });
    
    let scheduleHTML = '';
    Object.entries(groupedByDay).forEach(([day, schedules]) => {
        if (schedules.length > 0) {
            scheduleHTML += `<div class="day-group">
                <div class="day-header">${day}</div>`;
            schedules.forEach(schedule => {
                scheduleHTML += createScheduleCard(schedule);
            });
            scheduleHTML += '</div>';
        }
    });
    
    document.getElementById('scheduleList').innerHTML = scheduleHTML || 
        '<div class="empty-state"><i class="bi bi-info-circle"></i><p>Tidak ada jadwal tersedia</p></div>';
}

function createScheduleCard(schedule) {
    return `
        <div class="schedule-card">
            <div class="schedule-time"><i class="bi bi-clock"></i> ${schedule.time}</div>
            <div class="schedule-subject">${schedule.title}</div>
            <div class="schedule-detail"><i class="bi bi-person"></i> <strong>Dosen:</strong> ${schedule.instructor}</div>
            <div class="schedule-detail"><i class="bi bi-door-closed"></i> <strong>Ruangan:</strong> ${schedule.room}</div>
            <div class="schedule-detail"><i class="bi bi-people"></i> <strong>Kapasitas:</strong> ${schedule.capacity} orang</div>
            <div>
                <span class="schedule-badge">${schedule.day}</span>
            </div>
        </div>
    `;
}

function filterSchedules() {
    const searchTerm = document.getElementById('searchSchedule').value.toLowerCase();
    const filterDay = document.getElementById('filterDay').value;
    
    const filtered = allSchedules.filter(schedule => {
        const matchSearch = !searchTerm || 
            schedule.title.toLowerCase().includes(searchTerm) ||
            schedule.instructor.toLowerCase().includes(searchTerm);
        const matchDay = !filterDay || schedule.day === filterDay;
        return matchSearch && matchDay;
    });
    
    if (filtered.length === 0) {
        document.getElementById('scheduleList').innerHTML = `
            <div class="empty-state">
                <i class="bi bi-search"></i>
                <p>Tidak ada jadwal yang cocok</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    filtered.forEach(schedule => {
        html += createScheduleCard(schedule);
    });
    document.getElementById('scheduleList').innerHTML = html;
}

@if(Auth::user()->role === 'admin')
function saveSchedule() {
    const form = document.getElementById('addScheduleForm');
    const formData = new FormData(form);
    
    $.ajax({
        url: "{{ route('schedule.store') }}",
        method: 'POST',
        data: Object.fromEntries(formData),
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
                form.reset();
                document.querySelector('[data-bs-dismiss="modal"]').click();
                loadSchedules();
            }
        },
        error: function(xhr) {
            alert('Terjadi kesalahan: ' + xhr.responseJSON?.message);
        }
    });
}
@endif
</script>
@endsection
