<?php namespace Questionnaire\Export;

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

        $this->path = app_path('storage') . '/exports';
    }

    public function listFiles()
    {
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

        if($this->files->exists($filepath))
        {
            $this->files->delete($filepath);
        }
    }
}