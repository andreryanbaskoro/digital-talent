<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">

    @php
    $role = request()->segment(2);

    $perusahaan = \App\Models\ProfilPerusahaan::where(
    'id_pengguna',
    auth()->user()->id_pengguna ?? null
    )->first();
    @endphp

    <!-- ================= BRAND LOGO ================= -->
    <a href="/admin/{{ $role }}/dashboard" class="brand-link">
        <img src="{{ asset('images/Lambang_Kota_Jayapura.jpeg') }}"
            alt="Logo Jayapura"
            class="brand-image img-circle elevation-3"
            style="opacity:.9; width:35px; height:35px;">

        <span class="brand-text font-weight-bold text-dark">
            Digital Talent Hub
        </span>
    </a>
    <!-- ================= END BRAND LOGO ================= -->


    <!-- Sidebar -->
    <div class="sidebar">

        <!-- ================= USER PANEL ================= -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">

            <div class="image">
                @if($perusahaan && $perusahaan->logo)
                <img src="{{ asset('storage/' . $perusahaan->logo) }}"
                    class="img-circle elevation-2"
                    style="width:40px; height:40px; object-fit:cover;">
                @else
                <i class="fas fa-building fa-2x text-dark"></i>
                @endif
            </div>

            <div class="info">
                <a href="#" class="d-block text-dark font-weight-bold">
                    {{ $perusahaan->nama_perusahaan ?? 'Perusahaan' }}
                </a>

                <small class="text-muted d-block">
                    ID: {{ $perusahaan->id_perusahaan ?? '-' }}
                </small>
            </div>

        </div>
        <!-- ================= END USER PANEL ================= -->


        <!-- ================= MENU ================= -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="/admin/{{ $role }}/dashboard"
                        class="nav-link {{ request()->is('admin/'.$role.'/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Profil Perusahaan -->
                <li class="nav-item">
                    <a href="/admin/{{ $role }}/profil"
                        class="nav-link {{ request()->is('admin/'.$role.'/profil*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Profil Perusahaan</p>
                    </a>
                </li>

                <!-- Kelola Lowongan -->
                <li class="nav-item">
                    <a href="/admin/{{ $role }}/lowongan"
                        class="nav-link {{ request()->is('admin/'.$role.'/lowongan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Kelola Lowongan</p>
                    </a>
                </li>

                <!-- Daftar Lamaran Kerja -->
                <li class="nav-item">
                    <a href="{{ route('perusahaan.lamaran-pekerjaan.index') }}"
                        class="nav-link {{ request()->routeIs('perusahaan.lamaran-pekerjaan.*') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-users"></i>
                        <p>Daftar Lamaran Kerja</p>

                    </a>
                </li>

                <!-- Hasil Ranking / Matching -->
                <li class="nav-item">
                    <a href="/admin/{{ $role }}/matching"
                        class="nav-link {{ request()->is('admin/'.$role.'/matching') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Hasil Ranking / Matching</p>
                    </a>
                </li>

                <!-- Keputusan Seleksi -->
                <li class="nav-item">
                    <a href="/admin/{{ $role }}/seleksi"
                        class="nav-link {{ request()->is('admin/'.$role.'/seleksi') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-check-circle"></i>
                        <p>Keputusan Seleksi</p>
                    </a>
                </li>
                <!-- Notifikasi -->
                <li class="nav-item">
                    <a href="/admin/{{ $role }}/notifikasi"
                        class="nav-link {{ request()->is('admin/'.$role.'/notifikasi*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notifikasi</p>
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
        <!-- ================= END MENU ================= -->

    </div>
</aside>