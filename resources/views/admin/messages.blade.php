<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Masuk | SeHerbal</title>
    
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-messages.css') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <h2 class="brand">SeHerbal</h2>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="{{ route('admin.messages') }}" class="nav-item active">
                    <i class="fa-solid fa-envelope"></i> Pesan Masuk
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-item logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i> Log Out
                    </button>
                </form>
            </nav>
        </aside>

        <main class="main-content">
            <header class="content-header">
                <div>
                    <h1>Pesan Masuk</h1>
                    <p>Daftar pesan dari pengunjung website</p>
                </div>
            </header>

            <section class="table-controls">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Cari nama atau subjek...">
                </div>
                <div class="stats-box">
                    <span class="stats-label">Total Pesan</span>
                    <span class="stats-value">{{ $totalPesan }}</span>
                </div>
                <div class="stats-box">
                    <span class="stats-label">Belum Dibaca</span>
                    <span class="stats-value" style="color: #16a34a;">{{ $pesanBaru }}</span>
                </div>
            </section>

            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Pengirim</th>
                            <th>Subjek</th>
                            <th>Isi Pesan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesan as $msg)
                        <tr class="{{ $msg->is_read ? '' : 'unread' }}">
                            <td class="sender-info">
                                {{ $msg->nama }}
                                @if(!$msg->is_read) <span class="badge-new">Baru</span> @endif
                                <small>{{ $msg->email }}</small>
                            </td>
                            <td>{{ $msg->subjek }}</td>
                            <td><div class="msg-content">{{ $msg->pesan }}</div></td>
                            <td>{{ $msg->created_at->diffForHumans() }}</td>
                            <td class="action-btns">
                                <form action="{{ route('messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #9ca3af;">
                                Tidak ada pesan masuk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>