<?php

namespace App\Jobs;

use App\Notifications\ScaleImageNotification;
use App\Services\OpenAI\Enums\ImageAspect;
use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\FileExtension;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Laravel\Facades\Image as ImageManager;
use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Image;

class ScaleImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Image $image;

    public int $timeout = 180;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();
            $filename = pathinfo($this->image->path, PATHINFO_FILENAME);
            $this->scale($filename, ImageAspect::MEDIUM);
            $this->scale($filename, ImageAspect::SMALL);
            $this->image->save();
            DB::commit();
            $this->image->user->notify(new ScaleImageNotification([
                'success' => true,
                'link' => '#todo' // todo
            ]));
        } catch (Throwable $e) {
            DB::rollBack();
            $this->image->user->notify(new ScaleImageNotification([
                'success' => false,
                'debug' => [
                    'component' => 'ScaleImage',
                    'message' => 'Error',
                    'context' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'image' => $this->image->getAttributes(),
                        'trace' => $e->getTraceAsString()
                    ]
                ]
            ]));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    protected function getEncoderClass(string $ext): EncoderInterface
    {
        return match ($ext) {
            FileExtension::WEBP->value => new WebpEncoder(
                quality: config('image.encoders.webp.quality'),
                strip: config('image.encoders.webp.strip')
            ),
            FileExtension::PNG->value => new PngEncoder(
                interlaced: config('image.encoders.png.interlaced'),
                indexed: config('image.encoders.png.indexed'),
            ),
            default => throw new Exception('Unsupported image extension: ' . $ext)
        };
    }

    /**
     * @throws Exception
     */
    protected function scale(string $filename, string $version): void
    {
        $extension = config('image.default_encoder', FileExtension::WEBP->value);
        $file = ImageManager::read(Storage::get($this->image->path));
        list($width, $height) = $this->image->aspect->getSize($version, true);
        $file->scale(width: $width, height: $height);
        $versionPath = "images_$version/" . $filename .'.'. $extension;
        Storage::put($versionPath, $file->encode($this->getEncoderClass($extension)));
        $field = "path_$version";
        $this->image->$field = $versionPath;
    }
}
