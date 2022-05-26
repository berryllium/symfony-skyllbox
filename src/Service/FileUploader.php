<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private SluggerInterface $slugger;
    private FilesystemOperator $filesystem;

    public function __construct(SluggerInterface $slugger, FilesystemOperator $articleFileSystem ) {

        $this->slugger = $slugger;
        $this->filesystem = $articleFileSystem;
    }

    public function uploadFile(File $file, ?string $oldFileName = null) : string {
        $fileName = $this->slugger
            ->slug($file instanceof UploadedFile ? $file->getClientOriginalName() : $file->getFilename())
            ->append('-' . uniqid())
            ->append('.' . $file->guessExtension());

        $steam = fopen($file->getPathname(), 'r');
        $this->filesystem->writeStream($fileName, $steam);

        if(is_resource($steam)) {
            fclose($steam);
        }

        if($oldFileName && $this->filesystem->fileExists($oldFileName)) {
            $this->filesystem->delete($oldFileName);
        }
        return $fileName;
    }
}