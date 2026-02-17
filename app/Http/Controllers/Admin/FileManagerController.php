<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    protected $disk = 'public';
    protected $baseFolder = 'news'; // News images folder only

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * List files and folders
     */
    public function list(Request $request)
    {
        $path = $request->input('path', '');
        $fullPath = $this->baseFolder . ($path ? '/' . $path : '');

        try {
            $items = [];

            if (Storage::disk($this->disk)->exists($fullPath) || !$path) {
                $files = Storage::disk($this->disk)->files($fullPath);
                $directories = Storage::disk($this->disk)->directories($fullPath);

                // Add files
                foreach ($files as $file) {
                    $filename = basename($file);
                    $items[] = [
                        'name' => $filename,
                        'type' => 'file',
                        'path' => $file,
                        'size' => Storage::disk($this->disk)->size($file),
                        'modified' => Storage::disk($this->disk)->lastModified($file),
                        'url' => Storage::disk($this->disk)->url($file),
                    ];
                }

                // Add directories
                foreach ($directories as $dir) {
                    $dirname = basename($dir);
                    $items[] = [
                        'name' => $dirname,
                        'type' => 'folder',
                        'path' => $dir,
                        'modified' => Storage::disk($this->disk)->lastModified($dir),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'items' => collect($items)->sortByDesc('type')->sortBy('name')->values()->all(),
                'currentPath' => $path,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error listing files: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Upload file
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
            'path' => 'nullable|string',
        ]);

        try {
            $path = $request->input('path', '');
            $fullPath = $this->baseFolder . ($path ? '/' . $path : '');

            // Ensure directory exists
            if (!Storage::disk($this->disk)->exists($fullPath)) {
                Storage::disk($this->disk)->makeDirectory($fullPath, 0755, true);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            $storagePath = $fullPath . '/' . $filename;
            Storage::disk($this->disk)->putFileAs($fullPath, $file, $filename);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file' => [
                    'name' => $filename,
                    'size' => Storage::disk($this->disk)->size($storagePath),
                    'url' => Storage::disk($this->disk)->url($storagePath),
                    'path' => $storagePath,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete file or folder
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->input('path');

            if (!Storage::disk($this->disk)->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File or folder not found',
                ], 404);
            }

            // Prevent deletion outside baseFolder
            if (!Str::startsWith($path, $this->baseFolder)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied',
                ], 403);
            }

            // Check if it's a directory
            if (Storage::disk($this->disk)->directoryExists($path)) {
                Storage::disk($this->disk)->deleteDirectory($path);
            } else {
                Storage::disk($this->disk)->delete($path);
            }

            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Rename file or folder
     */
    public function rename(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'newName' => 'required|string|max:255',
        ]);

        try {
            $oldPath = $request->input('path');
            $newName = $request->input('newName');
            
            if (!Storage::disk($this->disk)->exists($oldPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File or folder not found',
                ], 404);
            }

            // Prevent rename outside baseFolder
            if (!Str::startsWith($oldPath, $this->baseFolder)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied',
                ], 403);
            }

            $directory = dirname($oldPath);
            $newPath = $directory === '.' ? $newName : $directory . '/' . $newName;

            Storage::disk($this->disk)->move($oldPath, $newPath);

            return response()->json([
                'success' => true,
                'message' => 'Renamed successfully',
                'newPath' => $newPath,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rename failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create folder
     */
    public function createFolder(Request $request)
    {
        $request->validate([
            'folderName' => 'required|string|max:255',
            'path' => 'nullable|string',
        ]);

        try {
            $path = $request->input('path', '');
            $folderName = $request->input('folderName');
            $fullPath = $this->baseFolder . ($path ? '/' . $path : '') . '/' . $folderName;

            if (Storage::disk($this->disk)->exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder already exists',
                ], 400);
            }

            Storage::disk($this->disk)->makeDirectory($fullPath, 0755, true);

            return response()->json([
                'success' => true,
                'message' => 'Folder created successfully',
                'folderPath' => $fullPath,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Create folder failed: ' . $e->getMessage(),
            ], 400);
        }
    }
}
