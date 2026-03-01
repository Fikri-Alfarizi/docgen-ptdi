<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Dashboard Report PT DI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1F3BB3;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        h1,
        h2,
        h3,
        h4 {
            color: #1F3BB3;
            margin: 0;
            padding: 0;
        }

        .stats-container {
            width: 100%;
            margin-bottom: 30px;
        }

        .stat-box {
            width: 32%;
            display: inline-block;
            padding: 15px;
            margin: 5px 0;
            background-color: #f4f5f7;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .stat-title {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f5f7;
            color: #1F3BB3;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Sistem Manajemen Dokumen PT Dirgantara Indonesia</h1>
        <p>Laporan Statistik Dashboard - {{ date('d M Y') }}</p>
    </div>

    <!-- DIPINDAHKAN KE ATAS SESUAI REQUEST -->
    <h3 style="margin-top: 10px; margin-bottom: 10px;">Aktivitas Pencetakan Terbaru (10 Terakhir)</h3>
    <table>
        <thead>
            <tr>
                <th>Pegawai</th>
                <th>NIK</th>
                <th>Dokumen</th>
                <th>Waktu (WIB)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentDocs as $doc)
                <tr>
                    <td>{{ $doc->user->name ?? 'Terhapus' }}</td>
                    <td>{{ $doc->user->nik ?? '-' }}</td>
                    <td>{{ mb_strtoupper($doc->jenis_dokumen) }}</td>
                    <td>{{ $doc->created_at->format('d/m/Y - H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Belum ada aktivitas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3 style="margin-top: 40px; margin-bottom: 15px;">Ringkasan Sistem</h3>
    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-title">Total Pengguna</div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div><small style="color: green;">Status Aktif</small></div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Template Master</div>
            <div class="stat-value">{{ $totalTemplates }}</div>
            <div><small style="color: green;">Tersedia</small></div>
        </div>
        <div class="stat-box" style="margin-right: 0;">
            <div class="stat-title">Dokumen Digenerate</div>
            <div class="stat-value">{{ $totalDocuments }}</div>
            <div><small style="color: green;">Log Aktif</small></div>
        </div>
    </div>

    <h3 style="margin-top: 20px;">Laporan Template Populer (Top 5)</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Rank</th>
                <th style="width: 60%;">Nama Template / Jenis Dokumen</th>
                <th style="width: 30%;">Total Di-generate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($templateStats as $index => $stat)
                <tr>
                    <td>#{{ $index + 1 }}</td>
                    <td><strong>{{ mb_strtoupper($stat->jenis_dokumen) }}</strong></td>
                    <td>{{ $stat->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} oleh Administrator Sistem. <br>
        Sistem Informasi Dokumen - PT Dirgantara Indonesia. Copyright &copy; {{ date('Y') }}.
    </div>

</body>

</html>