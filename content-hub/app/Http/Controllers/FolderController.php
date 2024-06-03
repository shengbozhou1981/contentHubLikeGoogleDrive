<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $folders = Folder::where('user_id', $request->user()->id)
                         ->where('parent_id', $request->query('parent_id'))
                         ->get();
        $files = File::where('user_id', $request->user()->id)
                     ->where('folder_id', $request->query('parent_id'))
                     ->get();

        return response()->json(['success' => true, 'data' => ['folders' => $folders, 'files' => $files]], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id'
        ]);

        try {
            $folder = Folder::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'parent_id' => $request->parent_id
            ]);
        
            return response()->json(['success' => true, 'data' => $folder], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Folder $folder)
    {
        $folder->delete();
        return response()->json(['success' => true, 'message' => 'Folder deleted successfully'], 200);
    }
}