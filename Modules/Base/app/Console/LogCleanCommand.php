<?php

namespace Modules\Base\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LogCleanCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     */
    protected $description = 'Clear the log files in the storage/logs directory, stroage/clockwork and storage/debugbar directory.';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paths = [
            'debugbar'  => storage_path('debugbar'),
            'clockwork' => storage_path('clockwork'),
            'logs'      => storage_path('logs')
        ];

        $totalCount = 0;
        $totalSize  = 0;

        foreach ($paths as $name => $path) {
            if (File::exists($path)) {
                $this->output->progressStart(count(File::allFiles($path)));
                list($count, $size) = $this->clearCacheFiles($path);
                ${$name . 'Count'} = $count;
                ${$name . 'Size'} = $size;
                $totalCount += $count;
                $totalSize += $size;
            } else {
                ${$name . 'Count'} = 0;
                ${$name . 'Size'} = 0;
            }
        }

        if ($totalCount === 0) {
            $this->info('No log files found to clear.');
            return;
        }

        $this->output->progressFinish();
        $this->info("Cleared a total of {$totalCount} files. Freed up " . $this->formatBytes($totalSize) . ".");
        $this->info("Details: Cleared {$debugbarCount} debug bar files, {$clockworkCount} clockwork files, and {$logsCount} log files.");
    }

    protected function clearCacheFiles($path)
    {
        $count = 0;
        $size = 0;
        $files = File::allFiles($path);
        foreach ($files as $file) {
            if ($file->getFilename() !== '.gitignore' && ($file->getExtension() === 'json' || $file->getExtension() === 'log')) {
                $count++;
                $size += $file->getSize();
                File::delete($file->getPathname());
                $this->output->progressAdvance();
            }
        }
        return [$count, $size];
    }

    protected function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)] . 'B';
    }
}
