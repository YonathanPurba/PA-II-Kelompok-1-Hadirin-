<aside class="sidebar" id="sidebar">
    <!-- Sidebar header -->
    <header class="sidebar-header">
        <a href="{{ url('/admin/beranda') }}" class="header-logo">
            <img src="{{ asset('images/HadirIn.jpg') }}" alt="HadirIn Logo" style="width: 200px">
        </a>
        <button class="toggler" type="button" aria-label="Toggle Sidebar" onclick="toggleSidebar()">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </header>
    <nav class="sidebar-nav">
        <!-- Primary top nav -->
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="{{ url('/admin/beranda') }}" class="nav-link {{ Request::is('admin/beranda') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">dashboard</span>
                    <span class="nav-label">Beranda</span>
                    <span class="nav-tooltip">Beranda</span>
                </a>
            </li>

            <!-- <li class="nav-item">
                <a href="{{ url('/admin/dokumen') }}" class="nav-link {{ Request::is('admin/dokumen*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">description</span>
                    <span class="nav-label">Dokumen</span>
                    <span class="nav-tooltip">Dokumen</span>
                </a>
            </li> -->

            <li class="nav-item">
                <a href="{{ url('/admin/rekapitulasi') }}" class="nav-link {{ Request::is('admin/rekapitulasi*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">edit_note</span>
                    <span class="nav-label">Rekapitulasi</span>
                    <span class="nav-tooltip">Rekapitulasi</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ url('/guru') }}" class="nav-link {{ Request::is('guru*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">school</span>
                    <span class="nav-label">Manajemen Data Guru</span>
                    <span class="nav-tooltip">Manajemen Data Guru</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ url('/orang-tua') }}" class="nav-link {{ Request::is('orang-tua*') || Request::is('admin/orang_tua*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">family_restroom</span>
                    <span class="nav-label">Manajemen Data Orang Tua</span>
                    <span class="nav-tooltip">Manajemen Data Orang Tua</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ url('/siswa') }}" class="nav-link {{ Request::is('siswa*') || Request::is('admin/siswa*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">person_pin</span>
                    <span class="nav-label">Manajemen Data Siswa</span>
                    <span class="nav-tooltip">Manajemen Data Siswa</span>
                </a>
            </li>       

            <li class="nav-item">
                <a href="{{ url('/users') }}" class="nav-link {{ Request::is('users*') || Request::is('admin/users*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">manage_accounts</span>
                    <span class="nav-label">Manajemen Akun Pengguna</span>
                    <span class="nav-tooltip">Manajemen Akun Pengguna</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ url('/kelas') }}" class="nav-link {{ Request::is('kelas*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">class</span>
                    <span class="nav-label">Manajemen Data Kelas</span>
                    <span class="nav-tooltip">Manajemen Data Kelas</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('/mata-pelajaran') }}" class="nav-link {{ Request::is('mata-pelajaran*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">menu_book</span>
                    <span class="nav-label">Manajemen Mata Pelajaran</span>
                    <span class="nav-tooltip">Manajemen Mata Pelajaran</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('/jadwal-pelajaran') }}" class="nav-link {{ Request::is('jadwal-pelajaran*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">event</span>
                    <span class="nav-label">Jadwal Pelajaran</span>
                    <span class="nav-tooltip">Jadwal Pelajaran</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('/tahun-ajaran') }}" class="nav-link {{ Request::is('tahun-ajaran*') ? 'active' : '' }}">
                    <span class="nav-icon material-symbols-rounded">date_range</span>
                    <span class="nav-label">Manajemen Tahun Ajaran</span>
                    <span class="nav-tooltip">Manajemen Tahun Ajaran</span>
                </a>
            </li>
        </ul>

        <!-- Secondary bottom nav -->
        <ul class="nav-list secondary-nav">
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="submit" class="nav-link logout-btn" aria-label="Logout">
                        <span class="nav-icon material-symbols-rounded">logout</span>
                        <span class="nav-label">Logout</span>
                        <span class="nav-tooltip">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
        
        // Store sidebar state in localStorage for persistence across page loads
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }
    
    // Restore sidebar state on page load
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
        }
    });
</script>