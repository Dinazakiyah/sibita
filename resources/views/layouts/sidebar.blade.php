<nav class="col-md-3 col-lg-2 d-md-block sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <!-- Dashboard - Untuk Semua Role -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>

            @if(auth()->user()->isAdmin())
                <!-- Menu Admin -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/mahasiswa*') ? 'active' : '' }}" href="{{ route('admin.mahasiswa.index') }}">
                        <i class="bi bi-people-fill"></i>
                        Kelola Mahasiswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dosen*') ? 'active' : '' }}" href="{{ route('admin.dosen.index') }}">
                        <i class="bi bi-person-badge-fill"></i>
                        Kelola Dosen
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/laporan') ? 'active' : '' }}" href="{{ route('admin.laporan') }}">
                        <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        Laporan Aktivitas
                    </a>
                </li>

            @elseif(auth()->user()->isDosen())
                <!-- Menu Dosen -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dosen/mahasiswa*') ? 'active' : '' }}" href="{{ route('dosen.mahasiswa.index') }}">
                        <i class="bi bi-people"></i>
                        Mahasiswa Bimbingan
                    </a>
                </li>

            @elseif(auth()->user()->isMahasiswa())
                <!-- Menu Mahasiswa -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('mahasiswa/bimbingan/create') ? 'active' : '' }}" href="{{ route('mahasiswa.bimbingan.create') }}">
                        <i class="bi bi-upload"></i>
                        Upload Bimbingan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('mahasiswa/riwayat*') ? 'active' : '' }}" href="{{ route('mahasiswa.riwayat.export') }}">
                        <i class="bi bi-file-text"></i>
                        Riwayat Bimbingan
                    </a>
                </li>
            @endif
        </ul>

        <!-- Info User di Sidebar -->
        <div class="mt-5 px-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-person-circle" style="font-size: 3rem; color: var(--unej-red);"></i>
                    <h6 class="mt-2 mb-0">{{ auth()->user()->name }}</h6>
                    <small class="text-muted">{{ auth()->user()->nim_nip ?? '-' }}</small>
                </div>
            </div>
        </div>
    </div>
</nav>
