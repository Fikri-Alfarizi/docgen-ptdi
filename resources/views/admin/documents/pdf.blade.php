<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Cetak Dokumen</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }

        h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        p.subtitle {
            margin: 5px 0;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed; /* Kunci lebar tabel agar tidak meluber */
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            word-wrap: break-word; /* Paksa teks panjang turun baris */
            word-break: break-all;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            background: #ffe4ba;
            color: #d97d00;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 9px;
            color: #777;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Sistem Informasi Dokumen - PT Dirgantara Indonesia</h1>
        <p class="subtitle">Laporan Riwayat Cetak Dokumen (Global)</p>
        <p class="subtitle" style="font-size: 10px;">Tanggal Cetak Laporan:
            {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="7%" class="text-center">ID Log</th>
                <th width="15%">Nama Pemohon</th>
                <th width="12%">NIK</th>
                <th width="33%">File - Nama Dokumen</th>
                <th width="7%" class="text-center">Fmt</th>
                <th width="8%" class="text-center">Org</th>
                <th width="5%" class="text-center">Rev</th>
                <th width="13%">Waktu Cetak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $doc)
                @php
                    $ext = strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                @endphp
                <tr>
                    <td class="text-center"><span class="badge">#{{ $doc->id }}</span></td>
                    <td>{{ $doc->user->name ?? 'User Terhapus' }}</td>
                    <td>{{ $doc->user->nik ?? '-' }}</td>
                    <td>{{ mb_strtoupper($doc->jenis_dokumen) }}</td>
                    <td class="text-center">{{ $ext }}</td>
                    <td class="text-center">{{ $doc->org ?? '-' }}</td>
                    <td class="text-center">{{ $doc->rev ?? '0' }}</td>
                    <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem | Copyright © {{ date('Y') }} PT Dirgantara Indonesia (Persero)
    </div>

</body>

</html>