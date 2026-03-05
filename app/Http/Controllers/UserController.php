<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $myDocumentsCount = Document::where('user_id', $userId)->count();
        $recentDocs = Document::select('id', 'jenis_dokumen', 'org', 'rev', 'file_path', 'created_at')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();
        return view('user.dashboard', compact('myDocumentsCount', 'recentDocs'));
    }

    public function upload()
    {
        return view('user.documents.upload');
    }

    public function myDocuments()
    {
        $documents = Document::select('id', 'jenis_dokumen', 'org', 'rev', 'file_path', 'created_at', 'updated_at')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('user.documents.index', compact('documents'));
    }
}
