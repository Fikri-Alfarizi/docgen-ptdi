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
        $templates = Template::all();
        return view('user.dashboard', compact('templates'));
    }

    public function myDocuments()
    {
        $documents = Document::where('user_id', Auth::id())->latest()->get();
        return view('user.documents.index', compact('documents'));
    }
}
