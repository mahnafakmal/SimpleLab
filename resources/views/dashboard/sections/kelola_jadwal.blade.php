<div id="kelola_jadwal" class="tab-content">
    <div class="schedule-section">
        <div class="schedule-header-row">
            <div>
                <h2 class="section-title"><i class="bi bi-calendar-week"></i> Kelola Jadwal Laboratorium</h2>
                <p class="hint-text">Tambah, edit, dan hapus jadwal laboratorium langsung dari dashboard admin.</p>
            </div>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                <i class="bi bi-plus-circle"></i> Tambah Jadwal Baru
            </button>
        </div>

        <div class="schedule-filters">
            <input type="text" id="searchSchedule" class="form-control" placeholder="Cari mata kuliah, dosen, atau kelas...">
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

        <div class="mb-4">
            <h4 class="section-subtitle">📅 Jadwal Hari Ini</h4>
            <div id="todaySchedules"></div>
        </div>

        <div>
            <h4 class="section-subtitle">📋 Jadwal Minggu Depan</h4>
            <div id="scheduleList"></div>
        </div>

        <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: var(--primary); color: white;">
                        <h5 class="modal-title">Tambah Jadwal Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
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
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jam Selesai</label>
                                    <input type="time" name="jam_selesai" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dosen</label>
                                <input type="text" name="dosen" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" class="form-control" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Ruangan</label>
                                    <input type="text" name="ruangan" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kapasitas</label>
                                    <input type="number" name="kapasitas" class="form-control">
                                </div>
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

        <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: var(--primary); color: white;">
                        <h5 class="modal-title">Edit Jadwal</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editScheduleForm">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label">Hari</label>
                                <select name="hari" id="edit_hari" class="form-select" required>
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
                                <input type="text" name="mata_kuliah" id="edit_mata_kuliah" class="form-control" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" id="edit_jam_mulai" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jam Selesai</label>
                                    <input type="time" name="jam_selesai" id="edit_jam_selesai" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dosen</label>
                                <input type="text" name="dosen" id="edit_dosen" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" id="edit_kelas" class="form-control" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Ruangan</label>
                                    <input type="text" name="ruangan" id="edit_ruangan" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kapasitas</label>
                                    <input type="number" name="kapasitas" id="edit_kapasitas" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="updateSchedule()">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let allSchedules = [];
        const isAdmin = @json(Auth::user()->role === 'admin');
        const canEdit = @json(in_array(Auth::user()->role, ['admin', 'dosen']));

        document.addEventListener('DOMContentLoaded', () => {
            loadSchedules();
            document.getElementById('searchSchedule').addEventListener('input', filterSchedules);
            document.getElementById('filterDay').addEventListener('change', filterSchedules);
        });

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
            const todaySchedules = allSchedules.filter(s => s.day === today);
            let todayHTML = '';
            if (!todaySchedules.length) {
                todayHTML = `<div class="empty-state"><i class="bi bi-calendar-x"></i><p>Tidak ada jadwal hari ini</p></div>`;
            } else {
                todaySchedules.sort((a,b) => a.time.localeCompare(b.time)).forEach(s => { todayHTML += createScheduleCard(s); });
            }
            document.getElementById('todaySchedules').innerHTML = todayHTML;

            const days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
            let scheduleHTML = '';
            days.forEach(day => {
                const list = allSchedules.filter(s => s.day === day).sort((a,b) => a.time.localeCompare(b.time));
                if (list.length) {
                    scheduleHTML += `<div class="day-group"><div class="day-header">${day}</div>`;
                    list.forEach(s => { scheduleHTML += createScheduleCard(s); });
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
            const filtered = allSchedules.filter(s =>
                (!term || s.title.toLowerCase().includes(term) || s.instructor.toLowerCase().includes(term) || s.kelas.toLowerCase().includes(term)) &&
                (!day || s.day === day)
            );

            if (!filtered.length) {
                document.getElementById('scheduleList').innerHTML = `<div class="empty-state"><i class="bi bi-search"></i><p>Tidak ada jadwal yang cocok</p></div>`;
                return;
            }
            document.getElementById('scheduleList').innerHTML = filtered.map(s => createScheduleCard(s)).join('');
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
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Gagal menyimpan jadwal.'));
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
                url: `/schedule/${id}`,
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
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Gagal memperbarui jadwal.'));
                }
            });
        }

        function deleteSchedule(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) return;
            $.ajax({
                url: `/schedule/${id}`,
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
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Gagal menghapus jadwal.'));
                }
            });
        }
    </script>
</div>
