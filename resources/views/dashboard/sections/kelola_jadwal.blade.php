<div id="kelola_jadwal" class="tab-content">
    <div class="schedule-section">
        <div class="schedule-header-row">
            <div>
                <h2 class="section-title"><i class="bi bi-calendar-week"></i> Kelola Jadwal Laboratorium</h2>
                <p class="hint-text">Tambah, edit, dan hapus jadwal laboratorium langsung dari dashboard admin dengan tampilan yang lebih modern.</p>
            </div>
            <button id="openAddScheduleBtn" class="btn btn-primary btn-lg" type="button" onclick="openAddScheduleModal()">
                <i class="bi bi-plus-circle"></i> Tambah Jadwal Baru
            </button>
        </div>

        <div class="schedule-overview-grid">
            <div class="overview-card">
                <span>Total Jadwal</span>
                <strong id="totalScheduleCount">0</strong>
            </div>
            <div class="overview-card">
                <span>Jadwal Hari Ini</span>
                <strong id="todayScheduleCount">0</strong>
            </div>
            <div class="overview-card overview-card-accent">
                <span>Filter Aktif</span>
                <strong id="activeFilterLabel">Semua Hari</strong>
            </div>
        </div>

        <div class="schedule-filters">
            <div class="filter-group">
                <label class="filter-label" for="searchSchedule">Cari Jadwal</label>
                <input type="text" id="searchSchedule" class="form-control search-input" placeholder="Cari mata kuliah, dosen, atau kelas...">
            </div>
            <div class="filter-group">
                <label class="filter-label" for="filterDay">Filter Hari</label>
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

        <div class="schedule-panels">
            <div class="schedule-panel">
                <div class="panel-header">
                    <div>
                        <h4 class="section-subtitle">📅 Jadwal Hari Ini</h4>
                        <p class="panel-note">Lihat sesi yang berlangsung hari ini dan atur ulang jika diperlukan.</p>
                    </div>
                    <span class="panel-badge badge-primary">Prioritas</span>
                </div>
                <div id="todaySchedules" class="schedule-grid"></div>
            </div>

            <div class="schedule-panel">
                <div class="panel-header">
                    <div>
                        <h4 class="section-subtitle">📋 Jadwal Minggu Depan</h4>
                        <p class="panel-note">Tampilkan semua pertemuan terjadwal dalam tampilan kartu yang bersih.</p>
                    </div>
                    <span class="panel-badge badge-outline">Semua Jadwal</span>
                </div>
                <div id="scheduleList" class="schedule-grid"></div>
            </div>
        </div>

        <!-- Modals moved to main layout for consistent z-index and isolation. -->
    </div>

    <script>
        let allSchedules = [];
        const isAdmin = @json(auth()->user()->role === 'admin');
        const canEdit = @json(in_array(auth()->user()->role, ['admin', 'dosen']));
        const userRole = "{{ auth()->user()->role }}";
        const userKelas = @json(optional(auth()->user())->kelas ?? null);
        const instructorName = @json(strtolower(auth()->user()->name));
        const scheduleBaseUrl = "{{ url('/schedule') }}";

        document.addEventListener('DOMContentLoaded', () => {
            loadSchedules();
            document.getElementById('searchSchedule').addEventListener('input', filterSchedules);
            document.getElementById('filterDay').addEventListener('change', filterSchedules);
        });

        function openAddScheduleModal() {
            const modalEl = document.getElementById('addScheduleModal');
            if (!modalEl) return;
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        function loadSchedules() {
            $.ajax({
                url: "{{ route('schedule.api') }}",
                method: 'GET',
                success: (res) => {
                    if (res.success) {
                        allSchedules = res.schedules;
                        displaySchedules();
                    }
                }
            });
        }

        function displaySchedules() {
            const today = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][new Date().getDay()];
            // Apply role-based visibility
            let visibleSchedules = allSchedules;
            if (userRole === 'dosen') {
                visibleSchedules = allSchedules.filter(s => s.instructor && s.instructor.toLowerCase().includes(instructorName));
            } else if (userRole === 'mahasiswa' && userKelas) {
                visibleSchedules = allSchedules.filter(s => s.kelas && s.kelas === userKelas);
            }

            const todaySchedules = visibleSchedules.filter(s => s.day === today);
            document.getElementById('totalScheduleCount').textContent = visibleSchedules.length;
            document.getElementById('todayScheduleCount').textContent = todaySchedules.length;
            document.getElementById('activeFilterLabel').textContent = document.getElementById('filterDay').value || 'Semua Hari';

            let todayHTML = '';
            if (!todaySchedules.length) {
                todayHTML = `<div class="empty-state"><i class="bi bi-calendar-x"></i><p>Tidak ada jadwal hari ini</p></div>`;
            } else {
                todayHTML = todaySchedules.sort((a,b) => a.time.localeCompare(b.time)).map(s => createScheduleCard(s)).join('');
            }
            document.getElementById('todaySchedules').innerHTML = todayHTML;

            const days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
            let scheduleHTML = '';
            days.forEach(day => {
                const list = visibleSchedules.filter(s => s.day === day).sort((a,b) => a.time.localeCompare(b.time));
                if (list.length) {
                    scheduleHTML += `<div class="day-group"><div class="day-header">${day}</div>`;
                    scheduleHTML += list.map(s => createScheduleCard(s)).join('');
                    scheduleHTML += '</div>';
                }
            });
            document.getElementById('scheduleList').innerHTML = scheduleHTML || `<div class="empty-state"><i class="bi bi-info-circle"></i><p>Tidak ada jadwal tersedia</p></div>`;
        }

        function createScheduleCard(s) {
            let actions = '';
            if (canEdit) {
                const editBtn = `<button class="btn btn-sm btn-outline-primary" onclick="openEditModal(${s.id})"><i class="bi bi-pencil-square"></i> Edit</button>`;
                const delBtn = isAdmin ? `<button class="btn btn-sm btn-outline-danger" onclick="deleteSchedule(${s.id})"><i class="bi bi-trash"></i> Hapus</button>` : '';
                actions = `<div class="schedule-action-row">${editBtn}${delBtn}</div>`;
            }

            return `
                <div class="schedule-card" id="schedule-card-${s.id}">
                    <div class="schedule-time"><i class="bi bi-clock"></i> ${s.time}</div>
                    <div class="schedule-subject">${s.title}</div>
                    <div class="schedule-detail"><i class="bi bi-building"></i> <strong>Kelas:</strong> ${s.kelas}</div>
                    <div class="schedule-detail"><i class="bi bi-person"></i> <strong>Dosen:</strong> ${s.instructor}</div>
                    <div class="schedule-detail"><i class="bi bi-door-closed"></i> <strong>Ruangan:</strong> ${s.room}</div>
                    <div class="schedule-detail"><i class="bi bi-people"></i> <strong>Kapasitas:</strong> ${s.capacity} orang</div>
                    <div class="schedule-badges"><span class="schedule-badge">${s.day}</span></div>
                    ${actions}
                </div>`;
        }

        function filterSchedules() {
            const term = document.getElementById('searchSchedule').value.toLowerCase();
            const day = document.getElementById('filterDay').value;
            const today = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][new Date().getDay()];
            const filtered = allSchedules.filter(s =>
                (!term || s.title.toLowerCase().includes(term) || s.instructor.toLowerCase().includes(term) || s.kelas.toLowerCase().includes(term)) &&
                (!day || s.day === day)
            );

            document.getElementById('totalScheduleCount').textContent = filtered.length;
            document.getElementById('todayScheduleCount').textContent = filtered.filter(s => s.day === today).length;
            document.getElementById('activeFilterLabel').textContent = day || 'Semua Hari';

            if (!filtered.length) {
                document.getElementById('scheduleList').innerHTML = `<div class="empty-state"><i class="bi bi-search"></i><p>Tidak ada jadwal yang cocok</p></div>`;
                return;
            }
            document.getElementById('scheduleList').innerHTML = filtered.map(s => createScheduleCard(s)).join('');
        }

        function handleAjaxError(xhr, fallbackMessage) {
            const response = xhr.responseJSON;
            if (response?.message) {
                return response.message;
            }
            if (response?.errors) {
                return Object.values(response.errors).flat().join('\n');
            }
            return fallbackMessage;
        }

        function saveSchedule() {
            const form = document.getElementById('addScheduleForm');
            const data = Object.fromEntries(new FormData(form));
            $.ajax({
                url: "{{ route('schedule.store') }}",
                method: 'POST',
                data,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: (res) => {
                    if (res.success) {
                        alert(res.message);
                        form.reset();
                        bootstrap.Modal.getInstance(document.getElementById('addScheduleModal')).hide();
                        loadSchedules();
                    }
                },
                error: (xhr) => {
                    alert('Terjadi kesalahan: ' + handleAjaxError(xhr, 'Gagal menyimpan jadwal.'));
                }
            });
        }

        function openEditModal(id) {
            const s = allSchedules.find(i => i.id === id);
            if (!s) return;
            document.getElementById('edit_id').value = s.id;
            document.getElementById('edit_hari').value = s.hari;
            document.getElementById('edit_mata_kuliah').value = s.mata_kuliah;
            document.getElementById('edit_jam_mulai').value = s.jam_mulai;
            document.getElementById('edit_jam_selesai').value = s.jam_selesai;
            document.getElementById('edit_dosen').value = s.instructor;
            document.getElementById('edit_kelas').value = s.kelas;
            document.getElementById('edit_ruangan').value = s.room === 'N/A' ? '' : s.room;
            document.getElementById('edit_kapasitas').value = s.capacity === 'N/A' ? '' : s.capacity;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editScheduleModal')).show();
        }

        function updateSchedule() {
            const id = document.getElementById('edit_id').value;
            const data = Object.fromEntries(new FormData(document.getElementById('editScheduleForm')));
            $.ajax({
                url: `${scheduleBaseUrl}/${id}`,
                method: 'POST',
                data: {...data, _method: 'PUT'},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: (res) => {
                    if (res.success) {
                        alert(res.message);
                        bootstrap.Modal.getInstance(document.getElementById('editScheduleModal')).hide();
                        loadSchedules();
                    }
                },
                error: (xhr) => {
                    alert('Terjadi kesalahan: ' + handleAjaxError(xhr, 'Gagal memperbarui jadwal.'));
                }
            });
        }

        function deleteSchedule(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) return;
            $.ajax({
                url: `${scheduleBaseUrl}/${id}`,
                method: 'POST',
                data: {_method: 'DELETE'},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: (res) => {
                    if (res.success) {
                        alert(res.message);
                        loadSchedules();
                    }
                },
                error: (xhr) => {
                    alert('Terjadi kesalahan: ' + handleAjaxError(xhr, 'Gagal menghapus jadwal.'));
                }
            });
        }
    </script>
</div>
