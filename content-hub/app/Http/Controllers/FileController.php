<?php
// app/Http/Controllers/FileController.php
namespace App\Http\Controllers;

use App\Http\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index()
    {
        return $this->fileService->index();
    }

    public function store(Request $request)
    {
        return $this->fileService->store($request);
    }

    public function show($id)
    {
        return $this->fileService->show($id);
    }

    public function update(Request $request, $id)
    {
        return $this->fileService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->fileService->destroy($id);
    }
}