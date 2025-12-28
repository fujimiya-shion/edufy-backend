<?php

namespace App\Traits;
use App\Utils\StringUtil;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

trait StorageBehaviour
{
    public function storeFile(UploadedFile $file, string $path, $options = 'public') {
        return Storage::url($file->store($path, $options));
    }

    /**
     * Get a temporary URL for the file.
     *
     * @param UploadedFile|null $file a file to store, if null, the file will not be stored
     * @param string $filePath the path to store the file
     * @param int $expireMinutes the number of minutes the URL should be valid for
     * @param string $options  the storage options
     * @return string the temporary URL
     */
    public function getTemporalUrl(?UploadedFile $file, string $filePath, int $expireMinutes = 5, bool $autoDelete = true,  string $options = 'public') {
        $file?->store($filePath, $options);

        $fileUrl = Storage::url($filePath);

        if (!$autoDelete)
            return $fileUrl;

        dispatch(function () use ($filePath) {
            if (Storage::exists($filePath))
                Storage::delete($filePath);
        })->delay(now()->addMinutes($expireMinutes));
        return $fileUrl;
    }

    public function downloadImage($image, $path): string
    {
        $storagePath = storage_path("app/$path");

        if (!file_exists($storagePath)) {
            Storage::makeDirectory($path);
        }

        if (StringUtil::isValidImage($image)) {
            if (str_starts_with($image, 'data:image/')) {
                return $this->saveBase64Image($image, $path);
            } else {
                return $this->downloadHttpImage($image, $path);
            }
        }
        return '';
    }
    public function saveBase64Image($base64, $path): string
    {
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
        $uuidName = uniqid() . '.' . StringUtil::getFileExtensionFromBase64($base64);
        $fullPath = storage_path("app/$path/$uuidName");
        file_put_contents($fullPath, $image);
        return "$path/$uuidName";
    }

    private function downloadHttpImage($url, $path): string
    {
        $request = Http::get($url);
        $uuidName = uniqid() . '.jpg';
        $filePath = "$path/'$uuidName";
        Storage::put($filePath, $request->body());
        return $filePath;
    }

    public function deleteImage($path): void
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        $formattedPath = str_replace('/storage/', '/', $path);
        $formattedPath = parse_url($formattedPath);
        Storage::disk('public')->delete($formattedPath['path']);
    }
}
