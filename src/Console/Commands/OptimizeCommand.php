<?php namespace Orchestra\Foundation\Console\Commands;

use ClassPreloader\Exceptions\SkipFileException;
use ClassPreloader\Exceptions\VisitorExceptionInterface;
use Illuminate\Foundation\Console\OptimizeCommand as Command;

class OptimizeCommand extends Command
{
    /**
     * Generate the compiled class file.
     *
     * @return void
     */
    protected function compileClasses()
    {
        $preloader = $this->getClassPreloader();

        $path = $this->laravel->getCachedCompilePath();

        if (file_exists($path)) {
            unlink($path);
        }

        $handle = $preloader->prepareOutput($path.'.tmp');

        foreach ($this->getClassFiles() as $file) {
            try {
                fwrite($handle, $preloader->getCode($file, false)."\n");
            } catch (SkipFileException $ex) {
                // Class Preloader 2.x
            } catch (VisitorExceptionInterface $e) {
                // Class Preloader 3.x
            }
        }

        fclose($handle);

        rename($path.'.tmp', $path);
    }
}
