<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function index()
    {
        try {
            $files = File::where('user_id', Auth::id())->get();
            $response = response()->json(['files' => $files], 200);
            return $response;
        } catch (\Exception $e) {
            // Log::error('Failed to fetch files:', $e->getMessage());
            return response()->json(['message' => 'Failed to fetch files'], 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:10240', // file format allowed and max size 10MB
            'folder_id' => 'nullable|exists:folders,id',
            'type' => 'required|string|max:255',
        ]);

        try {
            $path = $request->file('file')->store('files');

            $file = File::create([
                'name' => $validatedData['name'],
                'path' => $path,
                'type' => $validatedData['type'],
                'folder_id' => $validatedData['folder_id'] ?? null,
                'user_id' => Auth::id(),
            ]);

            return response()->json(['file' => $file], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);

            // return response()->json(['message' => 'File format not accepted, failed to upload file'], 500);
        }
    }

    public function show($id)
    {
        try {
            $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            return response()->json(['file' => $file], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'File not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:10240',
            'folder_id' => 'nullable|exists:folders,id',
            'type' => 'required|string|max:255',
        ]);

        try {
            if ($request->hasFile('file')) {
                Storage::delete($file->path);
                $path = $request->file('file')->store('files');
                $file->path = $path;
            }

            $file->update([
                'name' => $validatedData['name'],
                'folder_id' => $validatedData['folder_id'] ?? null,
                'type' => $validatedData['type'],
            ]);

            return response()->json(['file' => $file], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update file', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            Storage::delete($file->path);
            $file->delete();
            return response()->json("Delete file succeed", 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete file'], 500);
        }
    }
}
