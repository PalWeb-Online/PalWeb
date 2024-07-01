<?php

namespace App\Console\Commands;

use App\Models\Dialect;
use App\Models\Pronunciation;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefactorDialects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:dialects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refactor dialect nomenclature.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::transaction(function () {
            User::chunk(100, function ($users) {
                foreach ($users as $user) {
                    $id = Dialect::where('name', $user->dialect)->value('id');
                    if ($id) {
                        $user->update(['dialect_id' => $id]);
                    } else {
                        $user->update(['dialect_id' => '1']);
                    }
                }
            });

            Pronunciation::chunk(100, function ($pronunciations) {
                foreach ($pronunciations as $pronunciation) {
                    $id = Dialect::where('name', $pronunciation->dialect)->value('id');
                    if ($id) {
                        $pronunciation->update(['dialect_id' => $id]);
                    }
                }
            });
        });

        return Command::SUCCESS;
    }
}
