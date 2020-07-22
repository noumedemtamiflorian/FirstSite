<?php


namespace App\Framework;


use Psr\Http\Message\UploadedFileInterface;

class Upload
{
    protected $path;
    protected $formats;

    public function __construct(?string $path = null)
    {
        if ($path) {
            $this->path = $path;
        }
    }

    public function upload(UploadedFileInterface $file, ?string $oldfile = null)
    {
        $this->delete($oldfile);
        $filename = $file->getClientFilename();
        $targetPath = $this->addSuffix($this->path . DIRECTORY_SEPARATOR . $filename);
        $dirname = pathinfo($targetPath, PATHINFO_DIRNAME);
        if (!file_exists($dirname)) {
            mkdir($dirname, 777, true);
        }
        $file->moveTo($targetPath);
        return pathinfo($targetPath)['basename'];
    }

    private function addSuffix(string $targetPath)
    {
        if (file_exists($targetPath)) {
            $info = pathinfo($targetPath);
            $targetPath = $info['dirname'] .
                DIRECTORY_SEPARATOR .
                $info['filename']
                . '_copy.' .
                $info['extension'];
            return $this->addSuffix($targetPath);
        }
        return $targetPath;
    }

    private function delete(?string $oldfile = null)
    {
        if ($oldfile) {
            $oldfile = $this->path . DIRECTORY_SEPARATOR . $oldfile;
            if (file_exists($oldfile)) {
                unlink($oldfile);
            }
        }
    }
}