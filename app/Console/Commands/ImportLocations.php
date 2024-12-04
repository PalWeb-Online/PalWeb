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
        // Path to the JSON file
        $filePath = storage_path('app/locations.json');

        // Check if the file exists
        if (!File::exists($filePath)) {
            $this->error("File not found: $filePath");
        }

        // Read and decode the JSON file
        $jsonData = File::get($filePath);
        $locations = json_decode($jsonData, true);

        if ($locations === null) {
            $this->error("Invalid JSON format in $filePath");
        }

        $this->info("Starting import of ".count($locations)." locations...");

        foreach ($locations as $location) {
            try {
                // Parse the coordinates into WKT (Well-Known Text) format
                $coordinates = str_replace(['Point(', ')'], '', $location['coordinates']);
                $coordinatesArray = explode(' ', $coordinates);
                $lat = $coordinatesArray[1]; // Latitude
                $lon = $coordinatesArray[0]; // Longitude
                $wkt = "POINT($lon $lat)";

                // Insert or update the location
                DB::table('locations')->updateOrInsert(
                    ['qid' => $location['qid']], // Match existing records by QID
                    [
                        'name' => $location['name'],
                        'coordinates' => DB::raw("ST_GeomFromText('$wkt')"), // Convert to geometry
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );

                $this->info("Imported: " . $location['name']);
            } catch (\Exception $e) {
                $this->error("Failed to import: " . $location['name'] . " (QID: " . $location['qid'] . ")");
                $this->error($e->getMessage());
            }
        }

        $this->info("Import completed!");
    }
}
