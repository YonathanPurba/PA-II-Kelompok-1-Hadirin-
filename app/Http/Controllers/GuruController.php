<?php

namespace App\Http\Controllers;

use App\Exports\GuruExport;
use App\Models\Guru;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;

class GuruController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index(Request $request)
{
    // Get filter parameters
    $mataPelajaranId = $request->input('mata_pelajaran');
    $status = $request->input('status');
    $search = $request->input('search');
    
    // Get all subjects for the filter dropdown
    $mataPelajaranList = MataPelajaran::orderBy('nama')->get();
    
    // Query teachers with filters
    $gurusQuery = Guru::with(['user', 'mataPelajaran', 'jadwal'])
        ->when($mataPelajaranId, function ($query) use ($mataPelajaranId) {
            return $query->whereHas('mataPelajaran', function ($q) use ($mataPelajaranId) {
                // Fully qualify the column name to avoid ambiguity
                $q->where('mata_pelajaran.id_mata_pelajaran', $mataPelajaranId);
            });
        })
        ->when($request->has('status'), function ($query) use ($status) {
            if ($status !== '' && $status !== 'semua') {
                return $query->where('guru.status', $status);
            }
            // If status is 'semua', don't apply any filter
        }, function ($query) {
            // Default to 'aktif' when status parameter is not present
            return $query->where('guru.status', 'aktif');
        })
        ->when($search, function ($query) use ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%");
            });
        })
        ->orderBy('nama_lengkap');
            
    // Get paginated results
    $gurus = $gurusQuery->paginate(10)->withQueryString();

    return view('admin.pages.guru.manajemen_data_guru', compact('gurus', 'mataPelajaranList'));
}

/**
 * Export data guru to PDF.
 */
public function exportPdf(Request $request)
{
    $mataPelajaranId = $request->input('mata_pelajaran');
    $status = $request->input('status');
    $search = $request->input('search');
    
    $gurus = Guru::with(['user', 'mataPelajaran', 'jadwal'])
        ->when($mataPelajaranId, function ($query) use ($mataPelajaranId) {
            return $query->whereHas('mataPelajaran', function ($q) use ($mataPelajaranId) {
                // Fully qualify the column name to avoid ambiguity
                $q->where('mata_pelajaran.id_mata_pelajaran', $mataPelajaranId);
            });
        })
        ->when($request->has('status'), function ($query) use ($status) {
            if ($status !== '' && $status !== 'semua') {
                return $query->where('guru.status', $status);
            }
        }, function ($query) {
            return $query->where('guru.status', 'aktif');
        })
        ->when($search, function ($query) use ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%");
            });
        })
        ->orderBy('nama_lengkap')
        ->get();

    $pdf = Pdf::loadView('exports.guru_pdf', compact('gurus'));
    return $pdf->download('data_guru.pdf');
}

/**
 * Export data guru to Excel.
 */
public function exportExcel(Request $request)
{
    return Excel::download(
        new GuruExport(
            $request->input('mata_pelajaran'), 
            $request->input('status'),
            $request->input('search')
        ), 
        'data_guru.xlsx'
    );
}

// Update the create method to load mata pelajaran data
public function create()
{
    // Mengambil semua mata pelajaran untuk dropdown
    $allMataPelajaran = MataPelajaran::orderBy('nama')->get();
    
    return view('admin.pages.guru.tambah_guru', compact('allMataPelajaran'));
}

    public function show($id)
    {
        $guru = Guru::with([
            'user',
            'mataPelajaran',
            'jadwal' => function($query) {
                $query->orderBy('hari', 'asc')
                      ->orderBy('waktu_mulai', 'asc');
            },
            'jadwal.kelas',
            'jadwal.mataPelajaran',
        ])->findOrFail($id);

        // Get classes where this teacher is a homeroom teacher (wali kelas)
        $waliKelas = \App\Models\Kelas::where('id_guru', $id)
            ->with('tahunAjaran')
            ->get()
            ->map(function($kelas) {
                return [
                    'id_kelas' => $kelas->id_kelas,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat' => $kelas->tingkat,
                    'tahun_ajaran' => $kelas->tahunAjaran->nama_tahun_ajaran ?? '-',
                    'status_tahun_ajaran' => $kelas->tahunAjaran->aktif ? 'Aktif' : 'Tidak Aktif'
                ];
            });

        // Format data for better display
        $formattedGuru = [
            'id_guru' => $guru->id_guru,
            'nama_lengkap' => $guru->nama_lengkap,
            'nip' => $guru->nip ?? '-',
            'nomor_telepon' => $guru->nomor_telepon ?? '-',
            'status' => $guru->status,
            'mata_pelajaran' => $guru->mataPelajaran->pluck('nama')->join(', ') ?: '-',
            'jumlah_jadwal' => $guru->jadwal->count(),
            'wali_kelas' => $waliKelas,
            'jadwal' => $guru->jadwal->map(function($jadwal) {
                return [
                    'id_jadwal' => $jadwal->id_jadwal,
                    'hari' => ucfirst($jadwal->hari),
                    'waktu_mulai' => date('H:i', strtotime($jadwal->waktu_mulai)),
                    'waktu_selesai' => date('H:i', strtotime($jadwal->waktu_selesai)),
                    'kelas' => $jadwal->kelas->nama_kelas ?? '-',
                    'mata_pelajaran' => $jadwal->mataPelajaran->nama ?? '-'
                ];
            })
        ];

        return response()->json($formattedGuru);
    }

// Update the edit method to load related data
public function edit($id)
{
    // Mengambil data guru beserta mata pelajaran yang terkait melalui pivot table
    $guru = Guru::with(['mataPelajaran', 'jadwal.kelas', 'jadwal.mataPelajaran'])->findOrFail($id);

    // Mengambil semua mata pelajaran untuk dropdown
    $allMataPelajaran = MataPelajaran::orderBy('nama')->get();

    // Mengirimkan data guru dan mata pelajaran ke view edit
    return view('admin.pages.guru.edit_guru', compact('guru', 'allMataPelajaran'));
}


// Update the store method to handle mata_pelajaran and user creation properly
public function store(Request $request)
{
    $request->validate([
        'nama_lengkap'      => 'required|string|max:255',
        'nip'               => 'nullable|numeric|digits:18|unique:guru,nip',
        'nomor_telepon'     => 'nullable|numeric|digits_between:10,15',
        'bidang_studi'      => 'nullable|string|max:255',
        'username'          => 'required|string|min:6|max:255|unique:users,username',
        'password'          => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'
        ],
        'mata_pelajaran'    => 'nullable|array',
        'mata_pelajaran.*'  => 'exists:mata_pelajaran,id_mata_pelajaran',
    ], [
        'nama_lengkap.required' => 'Nama lengkap harus diisi',
        'nip.numeric' => 'NIP harus berupa angka',
        'nip.digits' => 'NIP harus terdiri dari 18 digit',
        'nip.unique' => 'NIP sudah digunakan',
        'nomor_telepon.numeric' => 'Nomor telepon harus berupa angka',
        'nomor_telepon.digits_between' => 'Nomor telepon harus terdiri dari 10-15 digit',
        'username.required' => 'Username harus diisi',
        'username.min' => 'Username minimal 6 karakter',
        'username.unique' => 'Username sudah digunakan',
        'password.required' => 'Password harus diisi',
        'password.min' => 'Password minimal 8 karakter',
        'password.regex' => 'Password harus mengandung huruf dan angka',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
    ]);

    DB::beginTransaction();
    try {
        // Create user account
        $user = User::create([
            'username'      => $request->username,
            'password'      => bcrypt($request->password),
            'id_role'       => 3, // Role id for guru
            'dibuat_pada'   => now(),
            'dibuat_oleh'   => Auth::user()->username ?? 'system',
        ]);

        // Create guru record
        $guru = Guru::create([
            'id_user'       => $user->id_user,
            'nama_lengkap'  => $request->nama_lengkap,
            'nip'           => $request->nip,
            'nomor_telepon' => $request->nomor_telepon,
            'bidang_studi'  => $request->bidang_studi,
            'status'        => 'aktif', // Default to active
            'dibuat_pada'   => now(),
            'dibuat_oleh'   => Auth::user()->username ?? 'system',
        ]);

        // Sync mata pelajaran if provided
        if ($request->has('mata_pelajaran')) {
            $guru->mataPelajaran()->sync($request->mata_pelajaran);
        }

        DB::commit();
        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Gagal menyimpan data guru: ' . $e->getMessage());
    }
}

// Update the update method to handle mata_pelajaran properly
public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'nama_lengkap'      => 'required|string|max:255',
        'nip'               => 'nullable|numeric|digits:18|unique:guru,nip,'.$id.',id_guru',
        'nomor_telepon'     => 'nullable|numeric|digits_between:10,15',
        'bidang_studi'      => 'nullable|string|max:255',
        'mata_pelajaran'    => 'required|array',
        'mata_pelajaran.*'  => 'exists:mata_pelajaran,id_mata_pelajaran',
        'status'            => 'required|string|in:aktif,nonaktif',
    ], [
        'nama_lengkap.required' => 'Nama lengkap harus diisi',
        'nip.numeric' => 'NIP harus berupa angka',
        'nip.digits' => 'NIP harus terdiri dari 18 digit',
        'nip.unique' => 'NIP sudah digunakan',
        'nomor_telepon.numeric' => 'Nomor telepon harus berupa angka',
        'nomor_telepon.digits_between' => 'Nomor telepon harus terdiri dari 10-15 digit',
        'mata_pelajaran.required' => 'Pilih minimal satu mata pelajaran',
        'status.required' => 'Status harus dipilih',
    ]);

    // Ambil data guru
    $guru = Guru::findOrFail($id);

    // Update data guru
    $guru->update([
        'nama_lengkap'      => $request->nama_lengkap,
        'nip'               => $request->nip,
        'nomor_telepon' => $request->nomor_telepon,
        'bidang_studi'      => $request->bidang_studi,
        'status'            => $request->status,
        'diperbarui_pada'   => now(),
        'diperbarui_oleh'   => Auth::user()->username ?? 'system',
    ]);

    // Sinkronisasi relasi guru <-> mata pelajaran (pivot table)
    $guru->mataPelajaran()->sync($request->mata_pelajaran);

    return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui.');
}
        

    public function destroy($id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        $guru->delete();

        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil dihapus',
        ], 200);
    }
}
