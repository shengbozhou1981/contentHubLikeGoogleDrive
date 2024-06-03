<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function index()
    {
        try {
            $folders = Folder::where('user_id', Auth::id())->get();
            return response()->json(['folders' => $folders], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch folders'], 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        try {
            $folder = Folder::create([
                'name' => $validatedData['name'],
                'user_id' => Auth::id(),
                'parent_id' => $validatedData['parent_id'],
            ]);
            return response()->json(['folder' => $folder], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create folder'], 500);
        }
    }

    public function show($id)
    {
        try {
            $folder = Folder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            return response()->json(['folder' => $folder], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Folder not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        try {
            $folder = Folder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $folder->update($validatedData);
            return response()->json(['folder' => $folder], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update folder'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $folder = Folder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $folder->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete folder'], 500);
        }
    }
}
