<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">

    @php $role = request()->segment(2); @endphp

    <!-- Brand Logo -->
    <!-- Brand Logo -->
    <a href="/admin/{{ $role }}/dashboard" class="brand-link">
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
                <a href="#" class="d-block">Perusahaan</a>
            </div>
        </div>

        <!-- Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="/admin/{{ $role }}/dashboard"
                        class="nav-link {{ request()->is('admin/'.$role.'/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/admin/{{ $role }}/profil-perusahaan"
                        class="nav-link {{ request()->is('admin/'.$role.'/profil-perusahaan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Profil Perusahaan</p>
                    </a>
                </li>

                <!-- Kelola Lowongan -->
                <li class="nav-item">
                    <a href="/admin/{{ $role }}/lowongan"
                        class="nav-link {{ request()->is('admin/'.$role.'/lowongan*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase nav-icon"></i>
                        <p>Kelola Lowongan</p>
                    </a>
                </li>

                <!-- Pelamar -->
                <li class="nav-item {{ request()->is('admin/'.$role.'/pelamar*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('admin/'.$role.'/pelamar*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Pelamar
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/admin/{{ $role }}/pelamar"
                                class="nav-link {{ request()->is('admin/'.$role.'/pelamar') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Pelamar</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/admin/{{ $role }}/matching"
                                class="nav-link {{ request()->is('admin/'.$role.'/matching') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hasil Ranking / Matching</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/admin/{{ $role }}/seleksi"
                                class="nav-link {{ request()->is('admin/'.$role.'/seleksi') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Keputusan Seleksi</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Notifikasi -->
                <li class="nav-item">
                    <a href="/admin/{{ $role }}/notifikasi"
                        class="nav-link {{ request()->is('admin/'.$role.'/notifikasi') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notifikasi</p>
                    </a>
                </li>

            </ul>
        </nav>

    </div>
</aside>