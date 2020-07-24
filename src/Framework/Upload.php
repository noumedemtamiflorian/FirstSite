<?php


namespace App\Framework;

use Intervention\Image\ImageManager;
use Psr\Http\Message\UploadedFileInterface;

class Upload
{
    protected $path;
    protected $formats = [];

    public function __construct(?string $path = null)
    {
        if ($path) {
            $this->path = $path;
        }
    }

    public function upload(UploadedFileInterface $file, ?string $oldfile = null)
    {
        if ($file->getError() === UPLOAD_ERR_OK) {
            $this->delete($oldfile);
            $filename = $file->getClientFilename();
            $targetPath = $this->addCopySuffix($this->path . DIRECTORY_SEPARATOR . $filename);
            $dirname = pathinfo($targetPath, PATHINFO_DIRNAME);
            if (!file_exists($dirname)) {
                mkdir($dirname, 777, true);
            }
            $file->moveTo($targetPath);
            $this->generateFormats($targetPath);
            return pathinfo($targetPath)['basename'];
        }
        return null;
    }

    private function addCopySuffix(string $targetPath)
    {
        if (file_exists($targetPath)) {
            $targetPath = $this->getPathWithSuffix($targetPath, "copy");
            return $this->addCopySuffix($targetPath);
        }
        return $targetPath;
    }

    public function delete(?string $oldfile = null)
    {
        if ($oldfile) {
            $oldfile = $this->path . DIRECTORY_SEPARATOR . $oldfile;
            if (file_exists($oldfile)) {
                unlink($oldfile);
            }
            foreach ($this->formats as $format => $size) {
                $oldfileFormat = $this->getPathWithSuffix($oldfile, $format);
                if (file_exists($oldfileFormat)) {
                    unlink($oldfileFormat);
                }
            }
        }
    }

    public function getPathWithSuffix(string $path, string $suffix)
    {
        $info = pathinfo($path);

        return $info['dirname'] .
            DIRECTORY_SEPARATOR .
            $info['filename']
            . "_$suffix." .
            $info['extension'];
    }

    private function generateFormats(string $targetPath)
    {
        foreach ($this->formats as $format => $size) {
            $destination = $this->getPathWithSuffix($targetPath, $format);
            $manager = new  ImageManager(['driver' => 'gd']);
            [$width, $height] = $size;
            $manager->make($targetPath)
                ->fit($width, $height)
                ->save($destination);
        }
    }
}
