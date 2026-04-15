<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">

    <!-- Brand Logo -->
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <i class="fas fa-briefcase fa-lg text-warning mr-2"></i>
        <span class="brand-text font-weight-bold">Talent</span>
        <span class="brand-text font-weight-light">Hub</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">

        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="icon mr-2">
                <i class="fas fa-user-circle fa-2x text-white"></i>
            </div>
            <div class="info">
                <a href="#" class="d-block">Disnaker</a>
            </div>
        </div>

        <!-- Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!-- Pengguna -->
                <li class="nav-item">
                    <a href="/admin/disnaker/pengguna"
                        class="nav-link {{ request()->is('admin/disnaker/pengguna') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Pengguna</p>
                    </a>
                </li>

                <!-- Verifikasi Lowongan -->
                <li class="nav-item">
                    <a href="/admin/disnaker/verifikasi-lowongan"
                        class="nav-link {{ request()->is('admin/disnaker/verifikasi-lowongan') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Verifikasi Lowongan</p>
                    </a>
                </li>

                <!-- AK1 -->
                <li class="nav-item">
                    <a href="/admin/disnaker/ak1"
                        class="nav-link {{ request()->is('admin/disnaker/ak1') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>AK1</p>
                    </a>
                </li>

                <!-- Data Pencari Kerja -->
                <li class="nav-item">
                    <a href="{{ route('pencari_kerja.index') }}"
                        class="nav-link {{ request()->routeIs('pencari_kerja.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Pencari Kerja</p>
                    </a>
                </li>

                <!-- Data Perusahaan -->
                <li class="nav-item">
                    <a href="{{ route('perusahaan.index') }}"
                        class="nav-link {{ request()->routeIs('perusahaan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Data Perusahaan</p>
                    </a>
                </li>

                <!-- Laporan -->
                <li class="nav-item {{ request()->is('admin/disnaker/laporan*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('admin/disnaker/laporan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laporan Lowongan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laporan Pencari Kerja</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laporan Penempatan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Pengaturan -->
                <li class="nav-item">
                    <a href="#"
                        class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Pengaturan Sistem</p>
                    </a>
                </li>

            </ul>
        </nav>

    </div>
</aside>