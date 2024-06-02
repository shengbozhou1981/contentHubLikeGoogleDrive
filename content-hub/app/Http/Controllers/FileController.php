<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $files = File::where('user_id', $request->user()->id)
                     ->where('folder_id', $request->query('folder_id'))
                     ->get();

        return response()->json(['success' => true, 'data' => $files], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,txt',
            'folder_id' => 'nullable|exists:folders,id'
        ]);

        $file = $request->file('file');
        $path = $file->store('files', 'public');

        $file = File::create([
            'user_id' => $request->user()->id,
            'folder_id' => $request->folder_id,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $file->getClientMimeType(),
        ]);

        return response()->json(['success' => true, 'data' => $file], 201);
    }

    public function destroy(File $file)
    {
        Storage::disk('public')->delete($file->path);
        $file->delete();
        return response()->json(['success' => true, 'message' => 'File deleted successfully'], 200);
    }
}
