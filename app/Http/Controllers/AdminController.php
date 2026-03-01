<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use App\Models\Template;

class AdminController extends Controller
{
    public function dashboard()
    {
        $cacheSeconds = 300; // 5 Menit
        $stats = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_stats', $cacheSeconds, function () {
            return [
                'totalUsers' => User::count(),
                'totalDocuments' => Document::count(),
                'totalTemplates' => Template::count(),
                'recentDocumentsCount' => Document::whereDate('created_at', today())->count(),
                'chartData' => $this->getChartData(),
                'templateStats' => Document::select('jenis_dokumen')
                    ->selectRaw('count(*) as total')
                    ->groupBy('jenis_dokumen')
                    ->orderByDesc('total')
                    ->limit(5)
                    ->get()
            ];
        });

        // Data untuk Tab Aktivitas (Selalu Real-time / Latest 10)
        $recentDocs = Document::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', array_merge($stats, ['recentDocs' => $recentDocs]));
    }

    public function manageUsers()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user',
            'nik' => 'nullable|string|max:50',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'nik' => $request->nik,
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,user',
            'nik' => 'nullable|string|max:50',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nik' => $request->nik,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus!');
    }

    public function manageTemplates()
    {
        $templates = Template::all();
        return view('admin.templates.index', compact('templates'));
    }

    public function storeTemplate(Request $request)
    {
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'org' => 'required|string|max:50',
            'file_template' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480', // Max 20MB
        ]);

        $file = $request->file('file_template');
        // Store with original name but append time to avoid conflicts
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('templates', $filename, 'public');

        // Auto Generate Nomor based on Org
        $lastTemplate = Template::latest('id')->first();
        $nextId = $lastTemplate ? $lastTemplate->id + 1 : 1;
        $nomorUrut = '00-' . $request->org . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        Template::create([
            'nama_template' => $request->nama_template,
            'nomor' => $nomorUrut,
            'org' => $request->org,
            'rev' => '0',
            'file_path' => $path,
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah!');
    }

    public function updateTemplate(Request $request, $id)
    {
        $template = Template::findOrFail($id);

        $request->validate([
            'nama_template' => 'required|string|max:255',
            'org' => 'required|string|max:50',
            'file_template' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480', // Max 20MB
        ]);

        $template->nama_template = $request->nama_template;
        $template->org = $request->org;

        if ($request->hasFile('file_template')) {
            // Hapus file lama jika ada
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($template->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($template->file_path);
            }

            $file = $request->file('file_template');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('templates', $filename, 'public');

            $template->file_path = $path;

            // Naikkan Revisi jika upload file baru
            $currentRev = is_numeric($template->rev) ? (int) $template->rev : 0;
            $template->rev = (string) ($currentRev + 1);
        }

        $template->save();

        return back()->with('success', 'Template ' . $template->nama_template . ' berhasil diperbarui!');
    }

    public function destroyTemplate($id)
    {
        $template = Template::findOrFail($id);

        // Cek dan hapus file fisik
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($template->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($template->file_path);
        }

        $template->delete();

        return back()->with('success', 'Template berhasil dihapus!');
    }

    public function historyDocuments()
    {
        $documents = Document::with('user')->latest()->get();
        return view('admin.documents.history', compact('documents'));
    }

    public function destroyDocument($id)
    {
        $document = Document::findOrFail($id);

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($document->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Catatan log cetak dan file fisik dokumen terkait berhasil dihapus!');
    }

    public function exportDocumentsPdf()
    {
        $documents = Document::with('user')->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.documents.pdf', compact('documents'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Cetak_Dokumen_PTDI_' . date('Y-m-d') . '.pdf');
    }

    public function publicDashboard($token)
    {
        // Token could be validated here if stored in DB. For now, it's just an obfuscated URL.
        $totalUsers = User::count();
        $totalDocuments = Document::count();
        $totalTemplates = Template::count();
        $recentDocumentsCount = Document::whereDate('created_at', \Carbon\Carbon::today())->count();
        $recentDocs = Document::with('user')->latest()->take(10)->get();

        // Template Stats for Laporan
        $templateStats = Document::select('jenis_dokumen', \DB::raw('count(*) as total'))
            ->groupBy('jenis_dokumen')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Get Chart Data
        $chartData = $this->getChartData();

        // ----------------------------------------
        // Real System Logs
        // ----------------------------------------
        $systemLogs = collect();

        foreach ($recentDocs->take(5) as $doc) {
            $systemLogs->push([
                'title' => 'Dokumen Dihasilkan',
                'message' => 'Pegawai ' . ($doc->user->name ?? 'Unknown') . ' mencetak ' . mb_strtoupper($doc->jenis_dokumen),
                'icon' => 'mdi-printer',
                'color' => 'primary',
                'time' => $doc->created_at
            ]);
        }

        $recentTemplatesLog = Template::latest()->take(3)->get();
        foreach ($recentTemplatesLog as $tpl) {
            $systemLogs->push([
                'title' => 'Template Diperbarui',
                'message' => 'Template ' . $tpl->nama_template . ' (' . $tpl->org . ') ditambahkan.',
                'icon' => 'mdi-file-upload-outline',
                'color' => 'info',
                'time' => $tpl->created_at
            ]);
        }

        $recentUsersLog = User::latest()->take(3)->get();
        foreach ($recentUsersLog as $usr) {
            $systemLogs->push([
                'title' => 'Akun Baru Terdaftar',
                'message' => $usr->name . ' terdaftar sebagai ' . $usr->role,
                'icon' => 'mdi-account-plus',
                'color' => 'success',
                'time' => $usr->created_at
            ]);
        }

        $systemLogs = $systemLogs->sortByDesc('time')->take(8);

        $isPublic = true; // Keep this for public dashboard context

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTemplates',
            'totalDocuments',
            'recentDocumentsCount',
            'recentDocs',
            'templateStats',
            'chartData',
            'systemLogs',
            'isPublic'
        ));
    }

    public function exportDashboardPdf()
    {
        $totalUsers = User::count();
        $totalDocuments = Document::count();
        $totalTemplates = Template::count();
        $recentDocumentsCount = Document::whereDate('created_at', today())->count();

        $chartData = $this->getChartData();

        $recentDocs = Document::with('user')->latest()->take(10)->get();

        $templateStats = Document::select('jenis_dokumen')
            ->selectRaw('count(*) as total')
            ->groupBy('jenis_dokumen')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.dashboard_pdf', compact('totalUsers', 'totalDocuments', 'totalTemplates', 'recentDocumentsCount', 'chartData', 'recentDocs', 'templateStats'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Statistik_Dashboard_PTDI_' . date('Y-m-d') . '.pdf');
    }

    public function globalSearch(Request $request)
    {
        $keyword = $request->input('query');

        if (empty($keyword)) {
            return back()->with('error', 'Silakan masukkan kata kunci pencarian.');
        }

        // Cari Karyawan
        $users = User::where('name', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%")
            ->orWhere('nik', 'like', "%{$keyword}%")
            ->get();

        // Cari Template
        $templates = Template::where('nama_template', 'like', "%{$keyword}%")
            ->orWhere('org', 'like', "%{$keyword}%")
            ->orWhere('nomor', 'like', "%{$keyword}%")
            ->get();

        // Cari Dokumen (Riwayat)
        $documents = Document::with('user')
            ->where('jenis_dokumen', 'like', "%{$keyword}%")
            ->orWhereHas('user', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('nik', 'like', "%{$keyword}%");
            })
            ->latest()
            ->take(50) // Limit to 50 for performance
            ->get();

        return view('admin.search_results', compact('keyword', 'users', 'templates', 'documents'));
    }

    private function getChartData()
    {
        $now = \Carbon\Carbon::now();
        $startOfWeek = \Carbon\Carbon::now()->subDays(6)->startOfDay();

        // 1. Minggu Ini (7 Hari Terakhir) - Database Agnostic Grouping
        $mingguResults = Document::where('created_at', '>=', $startOfWeek)
            ->get()
            ->groupBy(function ($doc) {
                return $doc->created_at->format('Y-m-d');
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        $mingguLabels = [];
        $mingguData = [];
        $hariIndo = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $mingguLabels[] = $hariIndo[$date->format('w')];
            $mingguData[] = $mingguResults[$dateStr] ?? 0;
        }

        // 2. Bulan Ini - Database Agnostic Grouping
        $bulanIniResults = Document::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->get()
            ->groupBy(function ($doc) {
                return (int) $doc->created_at->format('j');
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        $bulanIniLabels = [];
        $bulanIniData = [];
        for ($i = 1; $i <= $now->daysInMonth; $i++) {
            $bulanIniLabels[] = (string) $i;
            $bulanIniData[] = $bulanIniResults[$i] ?? 0;
        }

        // 3. Bulan Lalu - Database Agnostic Grouping
        $lastMonth = \Carbon\Carbon::now()->subMonth();
        $bulanLaluResults = Document::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->get()
            ->groupBy(function ($doc) {
                return (int) $doc->created_at->format('j');
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        $bulanLaluLabels = [];
        $bulanLaluData = [];
        for ($i = 1; $i <= $lastMonth->daysInMonth; $i++) {
            $bulanLaluLabels[] = (string) $i;
            $bulanLaluData[] = $bulanLaluResults[$i] ?? 0;
        }

        // 4. Tahun Ini - Database Agnostic Grouping
        $tahunIniResults = Document::whereYear('created_at', $now->year)
            ->get()
            ->groupBy(function ($doc) {
                return (int) $doc->created_at->format('n');
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        $tahunIniLabels = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        $tahunIniData = [];
        for ($i = 1; $i <= 12; $i++) {
            $tahunIniData[] = $tahunIniResults[$i] ?? 0;
        }

        return [
            'minggu_ini' => [
                'labels' => $mingguLabels,
                'data' => $mingguData,
                'total' => array_sum($mingguData)
            ],
            'bulan_ini' => [
                'labels' => $bulanIniLabels,
                'data' => $bulanIniData,
                'total' => array_sum($bulanIniData)
            ],
            'bulan_lalu' => [
                'labels' => $bulanLaluLabels,
                'data' => $bulanLaluData,
                'total' => array_sum($bulanLaluData)
            ],
            'tahun_ini' => [
                'labels' => $tahunIniLabels,
                'data' => $tahunIniData,
                'total' => array_sum($tahunIniData)
            ]
        ];
    }
}
