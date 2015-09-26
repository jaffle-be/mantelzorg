<?php namespace App\Questionnaire\Export;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class FileManager
{

    /**
     * @var Filesystem
     */
    protected $files;

    public function __construct(Filesystem $files, Report $report)
    {
        $this->files = $files;

        $this->report = $report;

        $this->path = storage_path('exports');
    }

    public function listFiles()
    {
        return $this->report->get();
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