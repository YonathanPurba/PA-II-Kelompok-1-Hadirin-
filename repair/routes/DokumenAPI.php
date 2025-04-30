======================================================================================================================
1. Autentikasi
# Mendaftarkan pengguna baru
POST /api/register

# Melakukan login dan mendapatkan token
POST /api/login

# Melakukan logout (menghapus token)
POST /api/logout

# Mendapatkan informasi pengguna yang sedang login
GET /api/me
======================================================================================================================
2. Absensi
# Mendapatkan semua data absensi
GET /api/absensi

# Membuat data absensi baru
POST /api/absensi

# Mendapatkan detail absensi berdasarkan ID
GET /api/absensi/{id}

# Mengupdate data absensi
PUT /api/absensi/{id}

# Menghapus data absensi
DELETE /api/absensi/{id}

# Mendapatkan absensi berdasarkan siswa
GET /api/absensi/siswa/detail?id_siswa={id_siswa}

# Mendapatkan absensi berdasarkan kelas
GET /api/absensi/kelas/detail?id_kelas={id_kelas}

# Mendapatkan ringkasan absensi (hadir, sakit, izin, alpa)
GET /api/absensi/summary

# Mendapatkan absensi hari ini
GET /api/absensi/today
======================================================================================================================
3. Jadwal
# Mendapatkan semua jadwal
GET /api/jadwal

# Membuat jadwal baru
POST /api/jadwal

# Mendapatkan detail jadwal berdasarkan ID
GET /api/jadwal/{id}

# Mengupdate jadwal
PUT /api/jadwal/{id}

# Menghapus jadwal
DELETE /api/jadwal/{id}

# Mendapatkan jadwal berdasarkan guru
GET /api/jadwal/guru/detail?id_guru={id_guru}

# Mendapatkan jadwal berdasarkan kelas
GET /api/jadwal/kelas/detail?id_kelas={id_kelas}

# Mendapatkan jadwal hari ini
GET /api/jadwal/today
======================================================================================================================
4. # Mendapatkan semua data siswa
GET /api/siswa

# Membuat data siswa baru
POST /api/siswa

# Mendapatkan detail siswa berdasarkan ID
GET /api/siswa/{id}

# Mengupdate data siswa
PUT /api/siswa/{id}

# Menghapus data siswa
DELETE /api/siswa/{id}

# Mendapatkan siswa berdasarkan orangtua
GET /api/siswa/orangtua/detail?id_orangtua={id_orangtua}

# Mendapatkan siswa berdasarkan kelas
GET /api/siswa/kelas/{kelasId}

# Mencari siswa berdasarkan nama atau NIS
GET /api/siswa/search?query={query}
======================================================================================================================
5. SuratIzin
# Mendapatkan semua surat izin
GET /api/surat-izin

# Membuat surat izin baru
POST /api/surat-izin

# Mendapatkan detail surat izin berdasarkan ID
GET /api/surat-izin/{id}

# Mengupdate surat izin
PUT /api/surat-izin/{id}

# Menghapus surat izin
DELETE /api/surat-izin/{id}

# Mengupdate status surat izin (menunggu/disetujui/ditolak)
PUT /api/surat-izin/{id}/status

# Mendapatkan surat izin berdasarkan siswa
GET /api/surat-izin/siswa/{siswaId}

# Mendapatkan surat izin berdasarkan orangtua
GET /api/surat-izin/orangtua/{orangtuaId}
======================================================================================================================
6. RekapAbsensi
# Mendapatkan semua rekap absensi
GET /api/rekap-absensi

# Membuat rekap absensi baru (generate otomatis)
POST /api/rekap-absensi/generate

# Mendapatkan detail rekap absensi berdasarkan ID
GET /api/rekap-absensi/{id}

# Mendapatkan rekap absensi berdasarkan kelas
GET /api/rekap-absensi/kelas/detail?id_kelas={id_kelas}

# Mendapatkan rekap absensi berdasarkan siswa
GET /api/rekap-absensi/siswa/detail?id_siswa={id_siswa}

# Mendapatkan rekap absensi berdasarkan periode
GET /api/rekap-absensi/bulan/{bulan}/tahun/{tahun}

# Mengekspor rekap absensi (PDF/Excel)
GET /api/rekap-absensi/export
======================================================================================================================
7. Notifikasi
# Mendapatkan semua notifikasi
GET /api/notifikasi

# Membuat notifikasi baru
POST /api/notifikasi

# Mendapatkan detail notifikasi berdasarkan ID
GET /api/notifikasi/{id}

# Menandai notifikasi sebagai telah dibaca
PUT /api/notifikasi/{id}/read

# Menandai semua notifikasi sebagai telah dibaca
PUT /api/notifikasi/read-all

# Mendapatkan jumlah notifikasi yang belum dibaca
GET /api/notifikasi/unread-count

# Menghapus notifikasi
DELETE /api/notifikasi/{id}

# Mendapatkan notifikasi berdasarkan user
GET /api/notifikasi/user/{userId}
======================================================================================================================
8. Kelas
# Mendapatkan semua kelas
GET /api/kelas

# Membuat kelas baru
POST /api/kelas

# Mendapatkan detail kelas berdasarkan ID
GET /api/kelas/{id}

# Mengupdate kelas
PUT /api/kelas/{id}

# Menghapus kelas
DELETE /api/kelas/{id}

# Mendapatkan kelas berdasarkan tahun ajaran
GET /api/kelas/tahun-ajaran/{tahunAjaranId}

# Mendapatkan siswa dalam kelas
GET /api/kelas/{id}/siswa

# Mendapatkan jadwal kelas
GET /api/kelas/{id}/jadwal

# Mendapatkan wali kelas
GET /api/kelas/{id}/wali-kelas

# Mendapatkan kelas berdasarkan tingkat
GET /api/kelas/tingkat/{tingkat}

# Mencari kelas berdasarkan nama
GET /api/kelas/search?query={query}
======================================================================================================================
9. Guru
# Mendapatkan semua guru
GET /api/guru

# Membuat data guru baru
POST /api/guru

# Mendapatkan detail guru berdasarkan ID
GET /api/guru/{id}

# Mengupdate data guru
PUT /api/guru/{id}

# Menghapus data guru
DELETE /api/guru/{id}

# Mendapatkan jadwal mengajar guru
GET /api/guru/{id}/jadwal

# Mendapatkan mata pelajaran yang diajar guru
GET /api/guru/{id}/mata-pelajaran

# Mendapatkan kelas yang diwalikan oleh guru
GET /api/guru/{id}/kelas-wali

# Mencari guru berdasarkan nama atau NIP
GET /api/guru/search?query={query}

# Mendapatkan guru berdasarkan user ID
GET /api/guru/by-user/{userId}
======================================================================================================================
10. Orangtua
# Mendapatkan semua orangtua
GET /api/orangtua

# Membuat data orangtua baru
POST /api/orangtua

# Mendapatkan detail orangtua berdasarkan ID
GET /api/orangtua/{id}

# Mengupdate data orangtua
PUT /api/orangtua/{id}

# Menghapus data orangtua
DELETE /api/orangtua/{id}

# Mendapatkan siswa dari orangtua
GET /api/orangtua/{id}/siswa

# Mendapatkan orangtua berdasarkan user ID
GET /api/orangtua/by-user/{userId}

# Mencari orangtua berdasarkan nama
GET /api/orangtua/search?query={query}
======================================================================================================================
11. Matapelajaran
# Mendapatkan semua mata pelajaran
GET /api/mata-pelajaran

# Membuat mata pelajaran baru
POST /api/mata-pelajaran

# Mendapatkan detail mata pelajaran berdasarkan ID
GET /api/mata-pelajaran/{id}

# Mengupdate mata pelajaran
PUT /api/mata-pelajaran/{id}

# Menghapus mata pelajaran
DELETE /api/mata-pelajaran/{id}

# Mendapatkan mata pelajaran berdasarkan tingkat
GET /api/mata-pelajaran/by-tingkat/{tingkat}

# Mendapatkan guru yang mengajar mata pelajaran
GET /api/mata-pelajaran/{id}/guru

# Mencari mata pelajaran berdasarkan nama atau kode
GET /api/mata-pelajaran/search?query={query}

# Mendapatkan mata pelajaran berdasarkan kode
GET /api/mata-pelajaran/kode/{kode}
======================================================================================================================
12. Tahun Ajaran
# Mendapatkan semua tahun ajaran
GET /api/tahun-ajaran

# Membuat tahun ajaran baru
POST /api/tahun-ajaran

# Mendapatkan detail tahun ajaran berdasarkan ID
GET /api/tahun-ajaran/{id}

# Mengupdate tahun ajaran
PUT /api/tahun-ajaran/{id}

# Menghapus tahun ajaran
DELETE /api/tahun-ajaran/{id}

# Mengatur tahun ajaran menjadi aktif
PUT /api/tahun-ajaran/{id}/set-active

# Mendapatkan tahun ajaran yang aktif
GET /api/tahun-ajaran/active

# Mendapatkan kelas pada tahun ajaran
GET /api/tahun-ajaran/{id}/kelas

# Mendapatkan jadwal pada tahun ajaran
GET /api/tahun-ajaran/{id}/jadwal
======================================================================================================================
13. User
# Mendapatkan semua user
GET /api/user

# Membuat user baru
POST /api/user

# Mendapatkan detail user berdasarkan ID
GET /api/user/{id}

# Mengupdate user
PUT /api/user/{id}

# Menghapus user
DELETE /api/user/{id}

# Mengubah password user
PUT /api/user/{id}/change-password

# Mendapatkan user berdasarkan role
GET /api/user/role/{roleId}

# Mencari user berdasarkan username
GET /api/user/search?query={query}
======================================================================================================================
14. Role
# Mendapatkan semua role
GET /api/role

# Membuat role baru
POST /api/role

# Mendapatkan detail role berdasarkan ID
GET /api/role/{id}

# Mengupdate role
PUT /api/role/{id}

# Menghapus role
DELETE /api/role/{id}
======================================================================================================================
15. Staf
# Mendapatkan semua staf
GET /api/staf

# Membuat data staf baru
POST /api/staf

# Mendapatkan detail staf berdasarkan ID
GET /api/staf/{id}

# Mengupdate data staf
PUT /api/staf/{id}

# Menghapus data staf
DELETE /api/staf/{id}

# Mendapatkan staf berdasarkan user ID
GET /api/staf/by-user/{userId}

# Mencari staf berdasarkan nama atau NIP
GET /api/staf/search?query={query}