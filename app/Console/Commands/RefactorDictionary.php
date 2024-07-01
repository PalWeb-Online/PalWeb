<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use App\Models\Term;
use Illuminate\Console\Command;

class RefactorDictionary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:dictionary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refactor database contents.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->updateCategories();
        $terms = Term::all();

        foreach ($terms as $term) {

            $attributes = [$term->oldCategory->subtype, $term->oldCategory->metatype];

            if ($term->oldCategory->subtype == 'amb') {
                array_push($attributes, 'masculine', 'feminine');
            }
            if ($term->oldCategory->subtype == 'collective') {
                array_push($attributes, 'masculine');
            }
            if ($term->oldCategory->subtype == 'demonym' && $term->category == 'noun') {
                array_push($attributes, 'feminine', 'plural');
            }

            foreach ($attributes as $attributeName) {
                // Find the attribute that matches the current string
                $attribute = Attribute::where('attribute', $attributeName)->first();
                $term->attributes()->attach($attribute);
            }
        }

        $this->info('Terms updated successfully');
        return Command::SUCCESS;
    }

    private function updateCategories()
    {
        \DB::table('categories')
            ->where('subtype', 'msc')
            ->update(['subtype' => 'masculine']);

        \DB::table('categories')
            ->where('subtype', 'fem')
            ->update(['subtype' => 'feminine']);

        \DB::table('categories')
            ->where('subtype', 'plr')
            ->update(['subtype' => 'plural']);
    }
}
