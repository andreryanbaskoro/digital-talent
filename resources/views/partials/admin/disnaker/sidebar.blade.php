<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">

    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('images/Lambang_Kota_Jayapura.jpeg') }}"
            alt="Logo Jayapura"
            class="brand-image img-circle elevation-3"
            style="opacity:.9; width:35px; height:35px;">

        <span class="brand-text font-weight-bold text-dark">
            Digital Talent Hub
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- ================= USER PANEL ================= -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">

            <div class="image">
                <i class="fas fa-user-circle fa-2x text-dark"></i>
            </div>

            <div class="info">
                <a href="#" class="d-block text-dark font-weight-bold">
                    {{ Auth::user()->nama ?? '-' }}
                </a>

                <small class="text-muted d-block">
                    NIP: {{ Auth::user()->nip ?? '-' }}
                </small>

                <span class="badge badge-primary mt-1">
                    {{ strtoupper(Auth::user()->peran ?? '-') }}
                </span>
            </div>

        </div>
        <!-- ================= END USER PANEL ================= -->

        <!-- Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('disnaker.dashboard') }}"
                        class="nav-link {{ request()->routeIs('disnaker.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Pengguna -->
                <li class="nav-item">
                    <a href="{{ route('disnaker.pengguna.index') }}"
                        class="nav-link {{ request()->routeIs('disnaker.pengguna.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Pengguna</p>
                    </a>
                </li>

                <!-- Verifikasi Lowongan -->
                <li class="nav-item">
                    <a href="{{ route('disnaker.verifikasi-lowongan.index') }}"
                        class="nav-link {{ request()->routeIs('disnaker.verifikasi-lowongan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Verifikasi Lowongan</p>
                    </a>
                </li>

                <!-- AK1 -->
                <li class="nav-item">
                    <a href="{{ route('disnaker.ak1.index') }}"
                        class="nav-link {{ request()->routeIs('disnaker.ak1.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>AK1</p>
                    </a>
                </li>

                <!-- Data Pencari Kerja -->
                <li class="nav-item">
                    <a href="{{ route('disnaker.pencari-kerja.index') }}"
                        class="nav-link {{ request()->routeIs('disnaker.pencari-kerja.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Data Pencari Kerja</p>
                    </a>
                </li>

                <!-- Data Perusahaan -->
                <li class="nav-item">
                    <a href="{{ route('disnaker.perusahaan.index') }}"
                        class="nav-link {{ request()->routeIs('disnaker.perusahaan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Data Perusahaan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan Lowongan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan Pencari Kerja</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan Penempatan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Pengaturan Sistem</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="nav-link btn btn-link text-left text-danger w-100">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>

    </div>
</aside>