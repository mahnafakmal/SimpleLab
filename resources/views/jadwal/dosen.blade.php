@extends('layouts.app-enhanced')

@section('title', 'Jadwal Lab - Dosen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1">Jadwal Laboratorium</h4>
                            <p class="text-muted mb-0">Hanya menampilkan jadwal; tidak tersedia fitur reservasi di halaman ini.</p>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Tampilan Dosen</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    @if($schedules->isEmpty())
                        <div class="p-4 text-center text-muted">Belum ada jadwal lab yang terdaftar.</div>
                    @else
                        @php $today = \Carbon\Carbon::now()->format('l'); @endphp
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:110px">Hari</th>
                                        <th>Mata Kuliah</th>
                                        <th>Dosen</th>
                                        <th>Kelas</th>
                                        <th>Ruangan</th>
                                        <th>Waktu</th>
                                        <th style="width:100px">Kapasitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules as $s)
                                        @php $isToday = ($s->hari === $today); @endphp
                                        <tr @if($isToday) class="table-primary" @endif>
                                            <td class="fw-bold">{{ $s->getDayName() }}</td>
                                            <td>{{ $s->mata_kuliah }}</td>
                                            <td>{{ $s->dosen }}</td>
                                            <td>{{ $s->kelas }}</td>
                                            <td>{{ $s->ruangan }}</td>
                                            <td>{{ $s->jam_mulai }} - {{ $s->jam_selesai }}</td>
                                            <td>{{ $s->kapasitas ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
