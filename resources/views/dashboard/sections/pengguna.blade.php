<div id="pengguna" class="tab-content">
    <div class="search-container">
        <div class="search-input-wrapper">
            <i data-lucide="search"></i>
            <input type="text" class="search-input" placeholder="Cari nama, email...">
        </div>
    </div>

    <form action="/rfid/card/register" method="POST" class="card form-card">
        @csrf
        <h3>Registrasi Kartu RFID User</h3>
        <div class="input-group">
            <select name="user_id" class="input-custom" required>
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                @endforeach
            </select>
            <input type="text" name="card_uid" class="input-custom" placeholder="UID Kartu RFID" required>
        </div>
        <button class="btn-primary" type="submit">Daftarkan Kartu</button>
    </form>

    <div class="list-card">
        <h3>Daftar Kartu RFID</h3>
        @if($cards->isEmpty())
            <div class="empty-state">
                <i data-lucide="credit-card" size="48"></i>
                <p>Belum ada kartu RFID pengguna.</p>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>UID Kartu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cards as $card)
                            <tr>
                                <td>{{ $card->user->name ?? '-' }}</td>
                                <td>{{ $card->user->email ?? '-' }}</td>
                                <td>{{ $card->uid }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
