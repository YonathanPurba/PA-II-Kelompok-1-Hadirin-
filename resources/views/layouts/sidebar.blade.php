<aside class="sidebar" id="sidebar">
    <!-- Sidebar header -->
    <header class="sidebar-header">
        <a href="#" class="header-logo">
            <img src="{{ asset('images/HadirIn.jpg') }}" alt="CodingNepal" style="width: 200px">
        </a>
        <button class="toggler" onclick="toggleSidebar()">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </header>
    <nav class="sidebar-nav">
        <!-- Primary top nav -->
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="{{ url('/admin/beranda') }}"
                    class="nav-link {{ Request::is('admin/beranda') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">dashboard</span>
                    <span class="nav-label">Beranda</span>
                </a>
                <span class="nav-tooltip">Beranda</span>
            </li>

            <li class="nav-item">
                <a href="{{ url('/admin/dokumen') }}"
                    class="nav-link {{ Request::is('admin/dokumen*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">description</span>
                    <span class="nav-label">Dokumen</span>
                </a>
                <span class="nav-tooltip">Dokumen</span>
            </li>

            <li class="nav-item">
                <a href="{{ url('/admin/rekapitulasi/rekapitulasi') }}"
                    class="nav-link {{ Request::is('admin/rekapitulasi*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">edit_note</span>
                    <span class="nav-label">Rekapitulasi</span>
                </a>
                <span class="nav-tooltip">Rekapitulasi</span>
            </li>
            
            <li class="nav-item">
                <a href="{{ url('/admin/users') }}"
                    class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">group</span>
                    <span class="nav-label">Manajemen Data User</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('/guru') }}" class="nav-link {{ Request::is('guru*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">manage_accounts</span>
                    <span class="nav-label">Manajemen Data Guru</span>
                </a>
                <span class="nav-tooltip">Manajemen Data Guru</span>
            </li>

            <li class="nav-item">
                <a href="{{ url('/orang-tua') }}"
                class="nav-link {{ Request::is('admin/orang_tua*') || Request::is('orang-tua*') ? 'active' : '' }}">
                <span class="nav-icon material-symbols-rounded">manage_accounts</span>
                <span class="nav-label">Manajemen Data Orang Tua</span>
            </a>
            <span class="nav-tooltip">Manajemen Data Orang Tua</span>
        </li>
        
        <li class="nav-item">
            <a href="{{ url('/siswa') }}"
            class="nav-link {{ Request::is('admin/siswa*') || Request::is('siswa*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">manage_accounts</span>
                    <span class="nav-label">Manajemen Data Siswa</span>
                </a>
                <span class="nav-tooltip">Manajemen Data Siswa</span>
            </li>       

            <li class="nav-item">
                <a href="{{ url('kelas') }}"
                    class="nav-link {{ Request::is('kelas*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">class</span>
                    <span class="nav-label">Manajemen Data Kelas</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('/mata-pelajaran') }}"
                    class="nav-link {{ Request::is('mata-pelajaran*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">menu_book</span>
                    <span class="nav-label">Manajemen Mata Pelajaran</span>
                </a>
            </li>

            <!-- Jadwal -->
            <li class="nav-item">
                <a href="{{ url('/admin/jadwal_pelajaran') }}"
                    class="nav-link {{ Request::is('admin/jadwal_pelajaran*') || Request::is('jadwal-pelajaran*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">event</span>
                    <span class="nav-label">Jadwal Pelajaran</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('tahun-ajaran') }}"
                    class="nav-link {{ Request::is('tahun-ajaran*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">date_range</span>
                    <span class="nav-label">Manajemen Tahun Ajaran</span>
                </a>
                <span class="nav-tooltip">Manajemen Tahun Ajaran</span>
            </li>


        </ul>

        <!-- Secondary bottom nav -->
        <ul class="nav-list secondary-nav">
            <li class="nav-item">
                <a href="{{ url('/profile') }}" class="nav-link {{ Request::is('profile') ? 'active' : '' }}">
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

<!-- Tambahkan script ini di akhir body HTML -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
    }
</script>
