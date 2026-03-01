<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Helper
{

    // File or Image Upload
    public static function fileUpload($file, $folder, $name)
    {
        if ($file) {
            $imageName = Str::slug($name) . '-' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/' . $folder), $imageName);
            return 'uploads/' . $folder . '/' . $imageName;
        }
        return null;
    }

    public static function videoUpload($file, $folder, $name)
    {
        // Check if file is not null
        if ($file) {
            $videoName = Str::slug($name) . '.' . $file->extension();
            $file->move(public_path('uploads/' . $folder), $videoName);
            $path = 'uploads/' . $folder . '/' . $videoName;
            return $path;
        }
        return null; // Or handle this case as needed
    }

    /**
     * Upload a file and return the public path.
     */
    public static function uploadFile($file, $directory)
    {
        try {
            $fileName = uniqid('media_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($directory, $fileName, 'public');
            return 'storage/' . $filePath;
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', 'Failed to upload the file');
        }
    }

    /**
     * Delete an image and return a boolean.
     */
    public static function deleteFile($imageUrl)
    {
        try {
            if (is_string($imageUrl) && !empty($imageUrl)) {
                $parsedUrl = parse_url($imageUrl);
                $relativePath = $parsedUrl['path'] ?? '';
                $relativePath = preg_replace('/^\/?storage\//', '', $relativePath);

                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }


    // JSON Response
    public static function jsonResponse(bool $status, string $message, int $code, $data = null): JsonResponse
    {
        $response = [
            'status'  => $status,
            'message' => $message,
            'code'    => $code,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }
        return response()->json($response, $code);
    }
}


/**
 * Generate a unique slug for the given model and title.
 *
 * @param string $title
 * @param string $table
 * @param string $slugColumn
 * @return string
 */
function generateUniqueSlug($title, $table, $slugColumn = 'slug')
{
    // Generate initial slug
    $slug = str::slug($title);

    // Check if the slug exists
    $count = DB::table($table)->where($slugColumn, 'LIKE', "$slug%")->count();

    // If it exists, append the count
    return $count ? "{$slug}-{$count}" : $slug;
}
