<header class="topbar">
    <!-- Left Section: Mobile Toggle & Premium Live Calendar Chip -->
    <div class="d-flex align-items-center gap-3">
        <button type="button" id="sidebar-toggle" class="btn btn-sm btn-outline-secondary d-lg-none shadow-sm rounded-circle p-2" style="width: 38px; height: 38px;">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="d-none d-md-flex align-items-center gap-2 bg-light px-3 py-2 rounded-pill border shadow-sm" style="font-size: 13px;">
            <span class="position-relative d-inline-flex align-items-center justify-content-center me-1">
                <span class="pulse-indicator bg-success rounded-circle position-absolute" style="width: 10px; height: 10px;"></span>
                <span class="bg-success rounded-circle" style="width: 6px; height: 6px;"></span>
            </span>
            <i class="fas fa-calendar-alt text-primary"></i>
            <span class="fw-bold text-dark tracking-wide">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- Right Section: Role Mode Chip, Notifications & Sleek Profile Dropdown -->
    <div class="d-flex align-items-center gap-3">
        <!-- Role Access Mode Chip -->
        <div class="d-none d-xl-flex align-items-center gap-2 px-3 py-1 rounded-pill border shadow-sm" style="background: var(--color-primary-light); font-size: 12px; border-color: rgba(27,94,32,0.2) !important;">
            <i class="fas fa-shield-halved text-primary"></i>
            <span class="text-muted">Mode Akses:</span>
            <span class="fw-bold text-primary">{{ auth()->user()->role === 'owner' ? 'Owner Eksekutif' : 'Operasional Lapangan' }}</span>
        </div>

        @if(auth()->user()->role === 'owner')
            @php 
                $alertStok = \App\Models\Stok::whereHas('produk', fn($q) => $q->whereColumn('stoks.jumlah_stok', '<=', 'produks.stok_minimum'))->count();
                $alertDraft = \App\Models\Produksi::where('status', 'draft')->count();
                $totalAlert = $alertStok + $alertDraft;
            @endphp
            <div class="dropdown">
                <button class="btn position-relative rounded-circle p-0 nav-icon-btn d-flex align-items-center justify-content-center shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell text-secondary fs-6"></i>
                    @if($totalAlert > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-white shadow-sm d-flex align-items-center justify-content-center fw-bold" style="font-size: 10px; padding: 3px 6px;">
                            {{ $totalAlert }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-0 mt-2 overflow-hidden" style="width: 320px;">
                    <li class="p-3 text-white d-flex align-items-center justify-content-between" style="background: var(--color-primary);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-bell"></i>
                            <h6 class="mb-0 fw-bold fs-6">Notifikasi Sistem</h6>
                        </div>
                        @if($totalAlert > 0)
                            <span class="badge bg-warning text-dark fw-bold rounded-pill small">{{ $totalAlert }} Baru</span>
                        @endif
                    </li>
                    <div class="p-2" style="max-height: 320px; overflow-y: auto;">
                        @if($alertStok > 0)
                            <li class="mb-1">
                                <a class="dropdown-item p-3 d-flex align-items-start gap-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-10" style="transition: all .15s;" href="{{ route('stok.index') }}">
                                    <div class="bg-danger text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0 mt-1 shadow-sm" style="width: 32px; height: 32px;">
                                        <i class="fas fa-exclamation-triangle small"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-danger small mb-1">Stok Gudang Kritis</div>
                                        <div class="text-muted lh-sm" style="font-size: 11.5px;">Terdapat <strong>{{ $alertStok }} produk</strong> menipis di bawah batas stok minimum.</div>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if($alertDraft > 0)
                            <li class="mb-1">
                                <a class="dropdown-item p-3 d-flex align-items-start gap-3 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-10" style="transition: all .15s;" href="{{ route('produksi.index') }}">
                                    <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0 mt-1 shadow-sm" style="width: 32px; height: 32px;">
                                        <i class="fas fa-clock small"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small mb-1">Produksi Tertahan Draft</div>
                                        <div class="text-muted lh-sm" style="font-size: 11.5px;">Terdapat <strong>{{ $alertDraft }} laporan produksi</strong> lapangan menunggu verifikasi Anda.</div>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if($totalAlert === 0)
                            <li class="text-center py-4 text-muted small">
                                <i class="fas fa-check-circle text-success fs-3 mb-2 d-block opacity-75"></i>
                                Tidak ada aktivitas darurat baru.
                            </li>
                        @endif
                    </div>
                </ul>
            </div>
        @endif

        <div class="dropdown">
            <a class="d-flex align-items-center text-decoration-none dropdown-toggle p-1 pe-3 rounded-pill user-profile-chip gap-2 shadow-sm" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 34px; height: 34px; font-size: 14px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="d-none d-md-block text-start lh-1">
                    <div class="fw-bold text-dark mb-1" style="font-size: 12.5px;">{{ auth()->user()->name }}</div>
                    <span class="badge {{ auth()->user()->role === 'owner' ? 'bg-warning text-dark fw-bold' : 'bg-secondary' }} rounded-pill text-uppercase tracking-wider" style="font-size: 8.5px; padding: 2.5px 7px;">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2 mt-2" style="width: 220px;">
                <li>
                    <div class="p-2 px-3 text-muted small border-bottom mb-2">
                        Akun aktif: <strong class="text-dark">{{ auth()->user()->username }}</strong>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item rounded-2 small py-2 d-flex align-items-center gap-2" href="{{ route('profil') }}">
                        <i class="fas fa-user-circle text-secondary fs-6"></i> Profil & Password
                    </a>
                </li>
                <li><hr class="dropdown-divider my-2"></li>
                <li>
                    <a class="dropdown-item rounded-2 small py-2 text-danger fw-bold d-flex align-items-center gap-2" href="javascript:void(0)" onclick="SwalHelper.confirmLogout('form-logout-navbar')">
                        <i class="fas fa-sign-out-alt fs-6"></i> Keluar Sistem
                    </a>
                    <form id="form-logout-navbar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
