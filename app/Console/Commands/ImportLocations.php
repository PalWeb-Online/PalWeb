<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import locations from locations.json into the database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = storage_path('app/locations.json');

        if (!File::exists($filePath)) {
            $this->error("File not found: $filePath");
            return;
        }

        $jsonData = File::get($filePath);
        $locations = json_decode($jsonData, true);

        if ($locations === null) {
            $this->error("Invalid JSON format in $filePath");
            return;
        }

        $this->info("Starting import of ".count($locations)." locations...");

        foreach ($locations as $location) {
            try {
                $coordinates = str_replace(['Point(', ')'], '', $location['coordinates']);
                $coordinatesArray = explode(' ', $coordinates);
                $lat = $coordinatesArray[1];
                $lon = $coordinatesArray[0];
                $wkt = "POINT($lon $lat)";

                DB::table('locations')->updateOrInsert(
                    ['qid' => $location['qid']],
                    [
                        'name' => $location['name'],
                        'coordinates' => DB::raw("ST_GeomFromText('$wkt')"),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );

                $this->info("Imported: ".$location['name']);
            } catch (\Exception $e) {
                $this->error("Failed to import: ".$location['name']." (QID: ".$location['qid'].")");
                $this->error($e->getMessage());
            }
        }

        $this->info("Import completed!");
    }
}
