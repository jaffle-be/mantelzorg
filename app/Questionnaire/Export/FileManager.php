<?php namespace App\Questionnaire\Export;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class FileManager
{

    /**
     * @var Filesystem
     */
    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;

        $this->path = storage_path('exports');
    }

    public function listFiles()
    {
        if(!$this->files->isDirectory($this->path))
        {
            return [];
        }

        $exports = $this->files->allFiles($this->path);

        $files = [];

        while ($file = array_shift($exports)) {
            /** @var SplFileInfo $file */
            array_push($files, $file->getFilename());
        }

        return $files;
    }

    public function delete($filename)
    {
        $filepath = $this->path . '/' . $filename;

        if($this->files->exists($filepath, false))
        {
            $this->files->delete($filepath);
        }
    }

    public function exists($filename, $buildPath = true)
    {
        if($buildPath)
        {
            $path = $this->path . '/' . $filename;
        }

        return $this->files->exists($path);
    }
}