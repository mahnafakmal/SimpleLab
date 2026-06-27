@extends('layouts.app-enhanced')

@section('title', 'Pengembalian Alat - SimpleLab')

@section('css')
<style>
    .scanner-section {
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

    .scanner-input {
        font-size: 1.2rem;
        padding: 1rem;
        border: 2px solid var(--unimus-primary);
        border-radius: 8px;
        font-weight: 600;
    }

    .scanner-input:focus {
        border-color: var(--unimus-secondary);
        box-shadow: 0 0 8px rgba(255, 107, 53, 0.3);
    }

    .scanner-result {
        margin-top: 1.5rem;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid var(--unimus-primary);
        background-color: #f8f9fa;
    }

    .scanner-result.success {
        background-color: #d4edda;
        border-left-color: #28a745;
        color: #155724;
    }

    .scanner-result.error {
        background-color: #f8d7da;
        border-left-color: #dc3545;
        color: #721c24;
    }

    .scanner-result.warning {
        background-color: #fff3cd;
        border-left-color: #ffc107;
        color: #856404;
    }

    .result-icon {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }

    .loan-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--unimus-primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .loan-card.overdue {
        border-left-color: #dc3545;
        background-color: #ffe6e6;
    }

    .loan-title {
        font-weight: 600;
        color: var(--unimus-primary);
        margin-bottom: 0.5rem;
    }

    .loan-detail {
        font-size: 0.9rem;
        color: #666;
        margin: 0.25rem 0;
    }

    .overdue-badge {
        display: inline-block;
        background-color: #dc3545;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 600;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .instructions {
        background: rgba(0, 51, 102, 0.05);
        border-left: 4px solid var(--unimus-primary);
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    .instructions li {
        margin-bottom: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Scanner Section -->
        <div class="scanner-section">
            <h2 class="section-title"><i class="bi bi-qr-code"></i> Scan RFID untuk Pengembalian</h2>
            
            <div class="instructions">
                <strong>Petunjuk Penggunaan:</strong>
                <ol>
                    <li>Tunjuk pembaca RFID ke kartu/tag barang yang akan dikembalikan</li>
                    <li>Sistem akan memvalidasi kecocokan barang</li>
                    <li>Jika barang rusak, laporkan segera</li>
                    <li>Konfirmasi pengembalian akan ditampilkan</li>
                </ol>
            </div>

            <form id="scanForm">
                <div class="mb-3">
                    <label for="rfidInput" class="form-label"><strong>Pindai RFID Tag</strong></label>
                    <input 
                        type="text" 
                        id="rfidInput" 
                        class="form-control scanner-input" 
                        placeholder="Arahkan pembaca RFID ke sini..."
                        autocomplete="off"
                        autofocus
                    >
                    <small class="text-muted">Fokus pada kolom ini dan arahkan tag RFID</small>
                </div>
            </form>

            <div id="scanResult"></div>

            <div class="alert alert-info mt-3" role="alert">
                <i class="bi bi-info-circle"></i>
                <strong>Catatan:</strong> Jika RFID tidak terdeteksi, silakan hubungi admin atau gunakan form manual di bawah.
            </div>
        </div>
    </div>

    <!-- Right Sidebar: Active Loans -->
    <div class="col-lg-4">
        <div class="scanner-section">
            <h3 class="section-title"><i class="bi bi-clock-history"></i> Peminjaman Aktif Anda</h3>
            
            <div id="loansContainer">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manual Return Section -->
<div class="scanner-section mt-4">
    <h3 class="section-title"><i class="bi bi-keyboard"></i> Pengembalian Manual</h3>
    
    <p class="text-muted">Jika RFID scanner tidak bekerja, Anda dapat memilih barang dari daftar di bawah:</p>
    
    <div class="row" id="manualReturnItems">
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
let lastScannedTime = 0;

// Auto-focus on input
document.getElementById('rfidInput').focus();

// Handle RFID scan
document.getElementById('rfidInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const rfidUid = this.value.trim();
        if (rfidUid.length > 0) {
            // Prevent duplicate scans within 2 seconds
            const now = Date.now();
            if (now - lastScannedTime < 2000) {
                return;
            }
            lastScannedTime = now;
            
            processScan(rfidUid);
            this.value = '';
        }
    }
});

function processScan(rfidUid) {
    const resultDiv = document.getElementById('scanResult');
    resultDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
    
    $.ajax({
        url: "{{ route('equipment.return.scan') }}",
        method: 'POST',
        data: {
            rfid_uid: rfidUid,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                resultDiv.innerHTML = `
                    <div class="scanner-result success">
                        <span class="result-icon"><i class="bi bi-check-circle"></i></span>
                        <strong>${response.message}</strong><br>
                        <small>Barang: ${response.equipment}</small><br>
                        <small>Waktu Pengembalian: ${response.returnedAt}</small>
                    </div>
                `;
                // Reload loans list
                loadActiveLoans();
                // Clear result after 3 seconds
                setTimeout(function() {
                    resultDiv.innerHTML = '';
                    document.getElementById('rfidInput').focus();
                }, 3000);
            } else {
                let alertClass = 'error';
                let icon = 'bi-x-circle';
                
                if (response.error === 'unregistered_rfid') {
                    alertClass = 'error';
                    icon = 'bi-exclamation-triangle';
                } else if (response.error === 'no_active_loan') {
                    alertClass = 'warning';
                    icon = 'bi-info-circle';
                } else if (response.error === 'condition_check_failed') {
                    alertClass = 'error';
                    icon = 'bi-exclamation-triangle';
                }
                
                resultDiv.innerHTML = `
                    <div class="scanner-result ${alertClass}">
                        <span class="result-icon"><i class="bi ${icon}"></i></span>
                        <strong>${response.message}</strong><br>
                        ${response.equipment ? `<small>Barang: ${response.equipment}</small>` : ''}
                    </div>
                `;
                
                document.getElementById('rfidInput').focus();
            }
        },
        error: function(xhr) {
            const error = xhr.responseJSON;
            resultDiv.innerHTML = `
                <div class="scanner-result error">
                    <span class="result-icon"><i class="bi bi-x-circle"></i></span>
                    <strong>Terjadi Kesalahan</strong><br>
                    <small>${error?.message || 'Silakan coba lagi'}</small>
                </div>
            `;
            document.getElementById('rfidInput').focus();
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
                        const overdueHTML = loan.isOverdue ? 
                            `<span class="overdue-badge">Terlambat ${loan.daysOverdue} hari!</span>` : '';
                        
                        html += `
                            <div class="loan-card ${overdueClass}">
                                <div class="loan-title">${loan.equipment}</div>
                                <div class="loan-detail"><i class="bi bi-calendar"></i> Dipinjam: ${loan.borrowedAt}</div>
                                <div class="loan-detail"><i class="bi bi-clock"></i> Tenggat: ${loan.dueDate}</div>
                                ${overdueHTML}
                            </div>
                        `;
                    });
                    
                    // Also populate manual return items
                    html += `
                        <div class="mt-3">
                            <a href="#" class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#reportDamageModal">
                                <i class="bi bi-exclamation-triangle"></i> Laporkan Kerusakan
                            </a>
                        </div>
                    `;
                }
                document.getElementById('loansContainer').innerHTML = html;
                
                // Load manual return items
                loadManualReturnItems(response.loans);
            }
        }
    });
}

function loadManualReturnItems(loans) {
    if (loans.length === 0) {
        document.getElementById('manualReturnItems').innerHTML = 
            '<div class="col-12"><p class="text-muted">Anda tidak memiliki barang yang dipinjam</p></div>';
        return;
    }
    
    let html = '';
    loans.forEach(loan => {
        html += `
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">${loan.equipment}</h6>
                        <small class="text-muted d-block mb-2">Dipinjam: ${loan.borrowedAt}</small>
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="confirmReturn('${loan.rfidUid}')">
                            Kembalikan
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    document.getElementById('manualReturnItems').innerHTML = html;
}

function confirmReturn(rfidUid) {
    if (confirm('Apakah Anda yakin ingin mengembalikan barang ini?')) {
        processScan(rfidUid);
    }
}

// Load active loans on page load
document.addEventListener('DOMContentLoaded', function() {
    loadActiveLoans();
    // Refresh every 30 seconds
    setInterval(loadActiveLoans, 30000);
});
</script>
@endsection
