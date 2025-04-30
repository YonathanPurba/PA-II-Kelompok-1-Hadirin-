@extends('layouts.admin-layout')

@section('title', 'kelas-rekapitulasi')

@section('content')
<link rel="stylesheet" href="{{ asset('css/konten.css') }}">
<link rel="stylesheet" href="{{ asset('css/rekapitulasi.css') }}">

<div class="container">
    <main class="main-content">
        <div class="isi">
            <div class="judul">
                <h1>Rekapitulasi Kehadiran Siswa 7-A</h1>
                <p>Staff dapat melihat hasil rekapitulasi kehadiran para siswa</p>
            </div>
            <div class="data">
                <table class="data-rekapitulasi">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2" class="nama-lengkap">Nama Lengkap</th>
                            <th colspan="4">Januari</th>
                            <th colspan="4">Februari</th>
                            <th colspan="4">Maret</th>
                            <th colspan="4">April</th>
                            <th colspan="4">Mei</th>
                            <th colspan="4">Juni</th>
                            <th colspan="4">Total</th>
                        </tr>
                        <tr class="hsia">
                            <th>H</th>
                            <th>S</th>
                            <th>I</th>
                            <th>A</th>
                            <th>H</th>
                            <th>S</th>
                            <th>I</th>
                            <th>A</th>
                            <th>H</th>
                            <th>S</th>
                            <th>I</th>
                            <th>A</th>
                            <th>H</th>
                            <th>S</th>
                            <th>I</th>
                            <th>A</th>
                            <th>H</th>
                            <th>S</th>
                            <th>I</th>
                            <th>A</th>
                            <th>H</th>
                            <th>S</th>
                            <th>I</th>
                            <th>A</th>
                            <th>H</th>
                            <th>S</th>
                            <th>I</th>
                            <th>A</th>
                        </tr>
                    </thead>
                    <tbody>
                        <script>
                            for (var i = 0; i <= 18; i++) {
                                document.write("<tr>");
                                document.write(`<td>${i}</td>`);
                                document.write("<td></td>");
                                for (var j = 0; j < 27; j++) {
                                    document.write(`<td></td>`);
                                }
                                document.write(`<td></td>`);
                                document.write("</tr>");
                            }
                        </script>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

@endsection