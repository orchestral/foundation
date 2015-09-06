<?php namespace Orchestra\Foundation\Console\Commands;

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
        $preloader = new ClassPreloader(new PrettyPrinter, new Parser(new Lexer), $this->getTraverser());

        $path = $this->laravel->getCachedCompilePath();

        if (file_exists($path)) {
            unlink($path);
        }

        $handle = $preloader->prepareOutput($path.'.tmp');

        foreach ($this->getClassFiles() as $file) {
            try {
                fwrite($handle, $preloader->getCode($file, false)."\n");
            } catch (SkipFileException $ex) {
                //
            }
        }

        fclose($handle);

        rename($path.'.tmp', $path);
    }
}
