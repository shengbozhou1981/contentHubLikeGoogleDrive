<?php

namespace App\Http\Services;

use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FolderService
{
    public function getFolders()
    {
        return Folder::where('user_id', Auth::id())->with(['children', 'files'])->get();
    }

    public function createFolder($data)
    {
        return Folder::create([
            'name' => $data['name'],
            'user_id' => Auth::id(),
            'parent_id' => $data['parent_id'] ?? null,
        ]);
    }

    public function getFolder($id)
    {
        return Folder::where('id', $id)->where('user_id', Auth::id())->with('children')->firstOrFail();
    }

    public function updateFolder($id, $data)
    {
        $folder = Folder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $folder->update($data);
        return $folder;
    }

    public function deleteFolder($id)
    {
        $folder = Folder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $folder->delete();
    }
}