<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function storeUpload(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'org' => 'required|string|max:10',
            'file_dokumen' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:30480'
        ]);

        $file = $request->file('file_dokumen');
        $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('documents', $filename, 'public');

        Document::create([
            'user_id' => Auth::id(),
            'jenis_dokumen' => $request->nama_dokumen,
            'org' => $request->org,
            'file_path' => $path
        ]);

        return redirect()->route('user.documents')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function updateUpload(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'org' => 'required|string|max:10',
            'file_dokumen' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:30480'
        ]);

        $document->jenis_dokumen = $request->nama_dokumen;
        $document->org = $request->org;

        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('documents', $filename, 'public');

            // Optionally delete old file here if needed:
            // Storage::disk('public')->delete($document->file_path);

            $document->file_path = $path;
            $document->rev = $document->rev + 1; // Increment revision only when file is changed
        }

        $document->save();

        return redirect()->route('user.documents')->with('success', 'Dokumen berhasil diupdate.');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|size:16',
        ]);

        $template = Template::findOrFail($request->template_id);
        $templatePath = storage_path('app/public/' . $template->file_path);

        if (!file_exists($templatePath)) {
            return back()->with('error', 'File template fisik tidak ditemukan di server.');
        }

        try {
            $ext = pathinfo($template->file_path, PATHINFO_EXTENSION);
            // Bersihkan nama template dari karakter tidak valid dan spasi berlebih
            $safeSlug = preg_replace('/[^A-Za-z0-9\-]/', '_', $template->nama_template);
            $fileName = uniqid() . '_' . $safeSlug . '.' . $ext;
            $outputDir = storage_path('app/public/documents');

            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            $outputPath = $outputDir . '/' . $fileName;

            // Hanya proses dengan PhpWord jika file berformat docx
            if (strtolower($ext) === 'docx') {
                $processor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
                $processor->setValue('nama', $request->nama ?? Auth::user()->name);
                $processor->setValue('nik', $request->nik ?? Auth::user()->nik);
                $processor->saveAs($outputPath);
            } else {
                // Jika bukan docx (contoh: pdf, xls, ppt), cukup salin (copy) filenya saja
                copy($templatePath, $outputPath);
            }

            Document::create([
                'user_id' => Auth::id(),
                'jenis_dokumen' => $template->nama_template,
                'org' => $template->org,
                'file_path' => 'documents/' . $fileName
            ]);

            return redirect()->intended('/user/documents')->with('success', 'Dokumen berhasil di-generate secara otomatis.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses dokumen: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $filePath = storage_path('app/public/' . $document->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }

        return response()->download($filePath, basename($document->file_path));
    }

    public function preview($id)
    {
        $document = Document::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $filePath = storage_path('app/public/' . $document->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }

        // Return file directly to be rendered in browser (inline)
        return response()->file($filePath);
    }
}
