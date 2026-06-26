<header class="topbar bg-white border-bottom py-2 px-4" style="background: rgba(255,255,255,0.95) !important; backdrop-filter: blur(8px);">
    <!-- Left Section: Mobile Toggle & Minimalist Clean Date -->
    <div class="d-flex align-items-center gap-3">
        <button type="button" id="sidebar-toggle" class="btn btn-sm btn-light border d-lg-none rounded">
            <i class="fas fa-bars text-secondary"></i>
        </button>
        
        <span class="text-secondary fw-medium d-none d-md-flex align-items-center gap-2" style="font-size: 13.5px; letter-spacing: -0.1px;">
            <i class="fas fa-calendar-day text-muted opacity-75"></i> 
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
        </span>
    </div>

    <!-- Right Section: Minimalist Controls & Clean Actions -->
    <div class="d-flex align-items-center gap-4">
        <!-- Subtle Clean Role Indicator -->
        <span class="text-muted d-none d-xl-inline" style="font-size: 12.5px;">
            Akses: <strong class="text-dark fw-semibold text-capitalize">{{ auth()->user()->role }}</strong>
        </span>

        @if(auth()->user()->role === 'owner')
            @php 
                $alertStok = \App\Models\Stok::whereHas('produk', fn($q) => $q->whereColumn('stoks.jumlah_stok', '<=', 'produks.stok_minimum'))->count();
                $alertDraft = \App\Models\Produksi::where('status', 'draft')->count();
                $totalAlert = $alertStok + $alertDraft;
            @endphp
            <div class="dropdown">
                <button class="btn btn-link text-secondary p-1 position-relative border-0 text-decoration-none d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="transition: color .15s;">
                    <i class="fas fa-bell fs-5"></i>
                    @if($totalAlert > 0)
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-2 border-white rounded-circle"></span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border border-light rounded-3 p-0 mt-2" style="width: 300px;">
                    <li class="p-3 border-bottom bg-light bg-opacity-50 d-flex align-items-center justify-content-between">
                        <span class="fw-bold small text-dark">Notifikasi Sistem</span>
                        @if($totalAlert > 0)
                            <span class="badge bg-danger rounded-pill">{{ $totalAlert }}</span>
                        @endif
                    </li>
                    <div class="py-1" style="max-height: 300px; overflow-y: auto;">
                        @if($alertStok > 0)
                            <li>
                                <a class="dropdown-item py-2.5 px-3 d-flex align-items-start gap-3" href="{{ route('stok.index') }}">
                                    <i class="fas fa-exclamation-circle text-danger mt-1 fs-6"></i>
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13px;">Stok Menipis</div>
                                        <div class="text-muted" style="font-size: 12px;">{{ $alertStok }} produk di bawah stok minimum.</div>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if($alertDraft > 0)
                            <li>
                                <a class="dropdown-item py-2.5 px-3 d-flex align-items-start gap-3" href="{{ route('produksi.index') }}">
                                    <i class="fas fa-clock text-warning mt-1 fs-6"></i>
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13px;">Produksi Draft</div>
                                        <div class="text-muted" style="font-size: 12px;">{{ $alertDraft }} data produksi belum diverifikasi.</div>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if($totalAlert === 0)
                            <li class="text-center py-4 text-muted small">
                                Tidak ada notifikasi baru.
                            </li>
                        @endif
                    </div>
                </ul>
            </div>
        @endif

        <div class="dropdown border-start ps-4">
            <a class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-semibold shadow-sm" style="width: 34px; height: 34px; font-size: 13px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="d-none d-md-inline fw-semibold small ms-1">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border border-light rounded-3 p-2 mt-2" style="width: 200px;">
                <li>
                    <div class="px-3 py-1 text-muted text-truncate mb-1" style="font-size: 11.5px;">
                        {{ auth()->user()->email }}
                    </div>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <a class="dropdown-item rounded small py-2 d-flex align-items-center gap-2 text-secondary" href="{{ route('profil') }}">
                        <i class="fas fa-user-circle fs-6"></i> Profil Saya
                    </a>
                </li>
                <li>
                    <a class="dropdown-item rounded small py-2 d-flex align-items-center gap-2 text-danger fw-semibold" href="javascript:void(0)" onclick="SwalHelper.confirmLogout('form-logout-navbar')">
                        <i class="fas fa-sign-out-alt fs-6"></i> Logout
                    </a>
                    <form id="form-logout-navbar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
