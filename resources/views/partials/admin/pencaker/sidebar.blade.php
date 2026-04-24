<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">

    @php
    $user = auth()->user();
    $profil = \App\Models\ProfilPencariKerja::where('id_pengguna', $user->id_pengguna)->first();
    @endphp

    <!-- ================= BRAND ================= -->
    <a href="{{ route('pencaker.dashboard') }}" class="brand-link">
        <img src="{{ asset('images/Lambang_Kota_Jayapura.jpeg') }}"
            class="brand-image img-circle elevation-3"
            style="opacity:.9; width:35px; height:35px;">
        <span class="brand-text font-weight-bold text-dark">
            Digital Talent Hub
        </span>
    </a>
    <!-- ================= END BRAND ================= -->


    <div class="sidebar">

        <!-- ================= USER PANEL ================= -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">

            <div class="image">
                @if($profil && $profil->foto)
                <img src="{{ asset('storage/'.$profil->foto) }}"
                    class="img-circle elevation-2"
                    style="width:40px; height:40px; object-fit:cover;">
                @else
                <i class="fas fa-user-circle fa-2x text-dark"></i>
                @endif
            </div>

            <div class="info">
                <a href="#" class="d-block text-dark font-weight-bold">
                    {{ $profil->nama_lengkap ?? $user->nama }}
                </a>
                <small class="text-muted d-block">
                    ID: {{ $profil->id_pencari_kerja ?? '-' }}
                </small>
                <small class="text-muted d-block">
                    Pencari Kerja
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
                    <a href="{{ route('pencaker.dashboard') }}"
                        class="nav-link {{ request()->routeIs('pencaker.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Profil -->
                <li class="nav-item">
                    <a href="{{ route('pencaker.profil.index') }}"
                        class="nav-link {{ request()->routeIs('pencaker.profil.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profil Saya</p>
                    </a>
                </li>

                <!-- Isi Formulir AK1 -->
                <li class="nav-item">
                    <a href="{{ route('pencaker.ak1.formulir') }}"
                        class="nav-link {{ request()->routeIs('pencaker.ak1.formulir') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>Isi Formulir AK1</p>
                    </a>
                </li>

                <!-- Status & Riwayat AK1 -->
                <li class="nav-item">
                    <a href="{{ route('pencaker.ak1.index') }}"
                        class="nav-link {{ request()->routeIs('pencaker.ak1.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Status & Riwayat AK1</p>
                    </a>
                </li>

                <!-- Lamaran -->
                <li class="nav-item">
                    <a href="{{ route('pencaker.lamaran.index') }}"
                        class="nav-link {{ request()->is('pencaker/lamaran*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Lamaran Saya</p>
                    </a>
                </li>

                <!-- Notifikasi -->
                <li class="nav-item">
                    <a href="{{ route('pencaker.notifikasi.index') }}"
                        class="nav-link {{ request()->routeIs('pencaker.notifikasi.*') ? 'active' : '' }}">

                        <div class="bell-wrapper">

                            <i id="notifBell"
                                class="nav-icon fas fa-bell 
               {{ isset($globalUnreadNotif) && $globalUnreadNotif > 0 ? 'bell-animate' : '' }}">
                            </i>

                            @if(isset($globalUnreadNotif) && $globalUnreadNotif > 0)
                            <span class="bell-dot"></span>
                            @endif

                        </div>

                        <p>
                            Notifikasi

                            @if(isset($globalUnreadNotif) && $globalUnreadNotif > 0)
                            <span class="badge badge-danger right badge-modern">
                                {{ $globalUnreadNotif }}
                            </span>
                            @endif
                        </p>

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