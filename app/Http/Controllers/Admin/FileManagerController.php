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

    // Only these extensions may be uploaded / renamed to (no php, phtml, exe, etc.)
    protected array $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt',
        'mp4', 'webm', 'mp3',
    ];

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Reject path traversal and absolute paths, keeping everything under baseFolder.
     * Returns the sanitized relative path (without the base folder prefix) or null if invalid.
     */
    protected function sanitizeRelativePath(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '';
        }

        // Normalize separators, block traversal, null bytes, and absolute/UNC paths
        $path = str_replace('\\', '/', $path);
        if (str_contains($path, "\0") || str_contains($path, '..') || str_starts_with($path, '/')) {
            return null;
        }

        return trim($path, '/');
    }

    /**
     * Resolve an absolute storage path that is guaranteed to stay inside baseFolder.
     * $fullPath is a path that already includes the base folder prefix (as sent to the client).
     */
    protected function isInsideBaseFolder(string $fullPath): bool
    {
        $fullPath = str_replace('\\', '/', trim($fullPath));
        if (str_contains($fullPath, '..') || str_contains($fullPath, "\0")) {
            return false;
        }

        return $fullPath === $this->baseFolder
            || str_starts_with($fullPath, $this->baseFolder . '/');
    }

    /**
     * Validate a single file/folder name segment.
     */
    protected function isValidName(string $name): bool
    {
        return $name !== ''
            && !str_contains($name, '/')
            && !str_contains($name, '\\')
            && !str_contains($name, "\0")
            && $name !== '.'
            && $name !== '..';
    }

    /**
     * List files and folders
     */
    public function list(Request $request)
    {
        $path = $this->sanitizeRelativePath($request->input('path', ''));
        if ($path === null) {
            return response()->json(['success' => false, 'message' => 'Invalid path'], 400);
        }
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
            $path = $this->sanitizeRelativePath($request->input('path', ''));
            if ($path === null) {
                return response()->json(['success' => false, 'message' => 'Invalid path'], 400);
            }
            $fullPath = $this->baseFolder . ($path ? '/' . $path : '');

            $file = $request->file('file');

            // Block executable / dangerous file types (defense against RCE via uploads)
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $this->allowedExtensions, true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File type not allowed: .' . $extension,
                ], 422);
            }

            // Ensure directory exists
            if (!Storage::disk($this->disk)->exists($fullPath)) {
                Storage::disk($this->disk)->makeDirectory($fullPath, 0755, true);
            }

            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;

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

            // Prevent traversal and deletion outside baseFolder
            if (!$this->isInsideBaseFolder($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied',
                ], 403);
            }

            if (!Storage::disk($this->disk)->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File or folder not found',
                ], 404);
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

            // Prevent rename outside baseFolder
            if (!$this->isInsideBaseFolder($oldPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied',
                ], 403);
            }

            // New name must be a single safe segment (no traversal / path separators)
            if (!$this->isValidName($newName)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid name',
                ], 422);
            }

            // If it has an extension, it must be an allowed one
            $newExt = strtolower(pathinfo($newName, PATHINFO_EXTENSION));
            if ($newExt !== '' && !in_array($newExt, $this->allowedExtensions, true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File type not allowed: .' . $newExt,
                ], 422);
            }

            if (!Storage::disk($this->disk)->exists($oldPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File or folder not found',
                ], 404);
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
            $path = $this->sanitizeRelativePath($request->input('path', ''));
            if ($path === null) {
                return response()->json(['success' => false, 'message' => 'Invalid path'], 400);
            }
            $folderName = $request->input('folderName');
            if (!$this->isValidName($folderName)) {
                return response()->json(['success' => false, 'message' => 'Invalid folder name'], 422);
            }
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
