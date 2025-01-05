<?php

namespace App\Console\Commands;

use App\Models\Dialect;
use App\Models\Speaker;
use App\Models\Term;
use App\Services\AudioService;
use Illuminate\Console\Command;

class ReassignDialect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reassign:dialect {speaker_id} {dialect_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reassigns a Speaker\'s Dialect & all their Audios to that Dialect';

    public function __construct(protected AudioService $audioService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dialectId = $this->argument('dialect_id');
        $speakerId = $this->argument('speaker_id');

        $dialect = Dialect::findOrFail($dialectId);
        $dialectIds = $dialect->ancestors->sortDesc()->pluck('id')->prepend($dialect->id);

        $speaker = Speaker::findOrFail($speakerId);
        $speaker->dialect_id = $dialectId;
        $speaker->save();

        foreach ($speaker->audios as $audio) {
            $term = Term::find($audio->pronunciation->term_id);

            $pronunciation = $this->findPronunciation($term, $dialectIds);

            if ($pronunciation) {
                $newFilename = 'apc-'.$speaker->dialect_id.'-'.$speaker->location_id.'-'.$pronunciation->id.'-'.$pronunciation->translit.'-'.$speaker->id.'.mp3';
                $this->audioService->renameAudio($audio->filename, $newFilename);

                $audio->pronunciation_id = $pronunciation->id;
                $audio->filename = $newFilename;
                $audio->save();

            } else {
                $this->audioService->deleteAudio($audio->filename);
                $audio->delete();
            }
        }

        return Command::SUCCESS;
    }

    protected function findPronunciation(Term $term, $dialectIds)
    {
        foreach ($dialectIds as $dialectId) {
            $pronunciation = $term->pronunciations->firstWhere('dialect_id', $dialectId);
            if ($pronunciation) {
                return $pronunciation;
            }
        }

        return null;
    }
}
