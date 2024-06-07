<?php

namespace App\Http\Controllers;

use App\Http\Services\FolderService;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    protected $folderService;

    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    public function index()
    {
        try {
            $folders = $this->folderService->getFolders();
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
            $folder = $this->folderService->createFolder($validatedData);
            return response()->json(['folder' => $folder], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $folder = $this->folderService->getFolder($id);
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
            $folder = $this->folderService->updateFolder($id, $validatedData);
            return response()->json(['folder' => $folder], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update folder'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->folderService->deleteFolder($id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete folder'], 500);
        }
    }
}