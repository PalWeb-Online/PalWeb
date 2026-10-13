<?php

namespace App\Console\Commands;

use App\Models\Root;
use Illuminate\Console\Command;

class AssignRootTypes extends Command
{
    protected $signature = 'roots:assign-types';

    protected $description = 'Assign root type values for all roots.';

    public function handle(): int
    {
        $updated = 0;

        Root::query()
            ->select(['id', 'root', 'type'])
            ->orderBy('id')
            ->chunkById(500, function ($roots) use (&$updated) {
                foreach ($roots as $root) {
                    $type = $this->classify($root->root);

                    if ($root->type === $type) {
                        continue;
                    }

                    $root->type = $type;
                    $root->save();

                    $updated++;
                }
            });

        $this->info("Assigned root types. Updated {$updated} roots.");

        return Command::SUCCESS;
    }

    private function classify(string $root): string
    {
        $letters = mb_str_split($root);
        $lastLetter = $letters[array_key_last($letters)] ?? null;

        if ($this->isWeakLetter($lastLetter)) {
            return 'B';
        }

        if (count($letters) === 3) {
            if ($letters[1] === $letters[2]) {
                return 'C';
            }

            if ($this->isWeakLetter($letters[1])) {
                return 'D';
            }

            if ($this->isWeakLetter($letters[0])) {
                return 'E';
            }
        }

        return 'A';
    }

    private function isWeakLetter(?string $letter): bool
    {
        return in_array($letter, ['ي', 'و'], true);
    }
}
