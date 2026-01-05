<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class OptimizeAvatars extends Command
{
    protected $signature = 'app:optimize-avatars';
    protected $description = 'Resize avatars to 320x320 and convert to WebP';

    public function handle()
    {
        $directory = public_path('img/avatars');

        if (!File::exists($directory)) {
            $this->error("Directory not found: $directory");
            return;
        }

        $manager = new ImageManager(new Driver());

        $files = File::files($directory);
        $count = 0;

        $this->info('Starting optimization...');

        foreach ($files as $file) {
            if ($file->getExtension() === 'webp') {
                continue;
            }

            try {
                $filename = $file->getFilenameWithoutExtension();
                $newPath = $directory . '/' . $filename . '.webp';

                $this->line("Processing: " . $file->getFilename());

                $manager->read($file->getPathname())
                    ->cover(320, 320)
                    ->toWebp(quality: 80)
                    ->save($newPath);

                 File::delete($file->getPathname());

                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to process " . $file->getFilename() . ": " . $e->getMessage());
            }
        }

        $this->info("Success! Optimized $count images.");
    }
}
