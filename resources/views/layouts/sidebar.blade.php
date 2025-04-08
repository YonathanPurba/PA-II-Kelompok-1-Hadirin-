<aside class="sidebar">
    <!-- Sidebar header -->
    <header class="sidebar-header" style="justify-content: center">
        <a href="#" class="header-logo">
            <img src="{{asset('images/HadirIn.jpg')}}" alt="CodingNepal" style="width: 200px">
        </a>
    </header>
    <nav class="sidebar-nav">
        <!-- Primary top nav -->
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="{{ url('/beranda') }}" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">dashboard</span>
                    <span class="nav-label">Beranda</span>
                </a>
                <span class="nav-tooltip">Beranda</span>
            </li>
            <li class="nav-item">
                <a href="{{ url('/admin/dokumen') }}" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">description</span>
                    <span class="nav-label">Dokumen</span>
                </a>
                <span class=nav-tooltip>Dokumen</span>
            </li>
            <li class="nav-item">
                <a href="{{ url('/admin/rekapitulasi/rekapitulasi') }}" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">edit_note</span>
                    <span class="nav-label">Rekapitulasi</span>
                </a>
                <span class="nav-tooltip">Rekapitulasi</span>
            </li>
            <li class="nav-item">
                <a href="{{ url('/guru') }}" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">manage_accounts</span>
                    <span class="nav-label">Manajemen Data Guru</span>
                </a>
                <span class="nav-tooltip">Manajemen Data Guru</span>
            </li>
            <li class="nav-item">
                <a href="{{ url('/admin/orang_tua/manajemen_data_orang_tua') }}" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">manage_accounts</span>
                    <span class="nav-label">Manajemen Data Orang Tua</span>
                </a>
                <span class="nav-tooltip">Manajemen Data Orang Tua</span>
            </li>
            <li class="nav-item">
                <a href="{{ url('/admin/siswa/manajemen_data_siswa') }}" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">manage_accounts</span>
                    <span class="nav-label">Manajemen Data Siswa</span>
                </a>
                <span class="nav-tooltip">Manajemen Data Siswa</span>
            </li>

            <li class="nav-item">
                <a href="{{ url('/admin/jadwal_pelajaran') }}" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">event</span>
                    <span class="nav-label">Jadwal Pelajaran</span>
                </a>
                <span class="nav-tooltip">Jadwal Pelajaran</span>
            </li>
        </ul>
        <!-- Secondary bottom nav -->
        <ul class="nav-list secondary-nav">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">account_circle</span>
                    <span class="nav-label">Profile</span>
                </a>
                <span class="nav-tooltip">Profile</span>
            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link" style="border: none; background: none;">
                        <span class="nav-icon material-symbols-rounded">logout</span>
                        <span class="nav-label">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>