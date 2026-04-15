<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">

    @php
    $user = auth()->user();
    @endphp

    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <i class="fas fa-briefcase fa-lg text-warning mr-2"></i>
        <span class="brand-text font-weight-bold">Talent</span>
        <span class="brand-text font-weight-light">Hub</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        @php
        $profil = \App\Models\ProfilPencariKerja::where('id_pengguna', $user->id_pengguna)->first();
        @endphp

        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">

            <div class="image mr-2">
                @if($profil && $profil->foto)
                <img src="{{ asset('storage/'.$profil->foto) }}"
                    class="img-circle elevation-2"
                    alt="User Image"
                    style="width:40px; height:40px; object-fit:cover;">
                @else
                <i class="fas fa-user-circle fa-2x text-white"></i>
                @endif
            </div>

            <div class="info">
                <a href="#" class="d-block">
                    {{ $profil->nama_lengkap ?? $user->nama }}
                </a>
                <small class="text-light">Pencari Kerja</small>
            </div>

        </div>

        <!-- Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('pencaker.dashboard') }}"
                        class="nav-link {{ request()->is('admin/pencaker/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Profil -->
                <li class="nav-item">
                    <a href="{{ route('pencaker.profil.index') }}"
                        class="nav-link {{ request()->is('admin/pencaker/profil*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profil Saya</p>
                    </a>
                </li>

                <!-- AK1 -->
                <li class="nav-item {{ request()->is('admin/pencaker/ak1*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/pencaker/ak1*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>
                            Kartu Kuning (AK1)
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pencaker.ak1.formulir') }}"
                                class="nav-link {{ request()->is('admin/pencaker/ak1/formulir*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Isi Formulir AK1</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('pencaker.ak1.index') }}"
                                class="nav-link {{ request()->is('admin/pencaker/ak1') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Status & Riwayat AK1</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Lamaran -->
                <li class="nav-item">
                    <a href="/admin/pencaker/lamaran"
                        class="nav-link {{ request()->is('admin/pencaker/lamaran*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Lamaran Saya</p>
                    </a>
                </li>

                <!-- Notifikasi -->
                <li class="nav-item">
                    <a href="/admin/pencaker/notifikasi"
                        class="nav-link {{ request()->is('admin/pencaker/notifikasi*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notifikasi</p>
                    </a>
                </li>

            </ul>
        </nav>

    </div>
</aside>