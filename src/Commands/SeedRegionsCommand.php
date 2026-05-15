<?php

namespace DyanGalih\LaravelRegion\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedRegionsCommand extends Command
{
    public $signature = 'indonesia:seed {--path= : Path to CSV files}';

    public $description = 'Seed Indonesian regional data from CSV source';

    public function handle(): int
    {
        $path = $this->option('path') ?? config('region.csv_path');

        if (! is_dir($path)) {
            $this->error("Invalid CSV path: {$path}");

            return self::FAILURE;
        }

        $this->info('Starting regional data seeding...');

        $this->seedProvinces("{$path}/provinces.csv");
        $this->seedRegencies("{$path}/regencies.csv");
        $this->seedDistricts("{$path}/districts.csv");
        $this->seedVillages("{$path}/villages.csv");

        $this->info('Seeding completed successfully.');

        return self::SUCCESS;
    }

    protected function seedProvinces(string $file)
    {
        $this->comment('Seeding provinces...');
        $this->seedTable(config('region.table_prefix') . 'provinces', $file, ['id', 'name']);
    }

    protected function seedRegencies(string $file)
    {
        $this->comment('Seeding regencies...');
        $this->seedTable(config('region.table_prefix') . 'regencies', $file, ['id', 'province_id', 'name']);
    }

    protected function seedDistricts(string $file)
    {
        $this->comment('Seeding districts...');
        $this->seedTable(config('region.table_prefix') . 'districts', $file, ['id', 'regency_id', 'name']);
    }

    protected function seedVillages(string $file)
    {
        $this->comment('Seeding villages (this may take a while)...');
        $this->seedTable(config('region.table_prefix') . 'villages', $file, ['id', 'district_id', 'name']);
    }

    protected function seedTable(string $table, string $file, array $columns)
    {
        if (! file_exists($file)) {
            $this->warn("File not found: {$file}. Skipping...");

            return;
        }

        $handle = fopen($file, 'r');
        fgetcsv($handle, 0, ';'); // Skip header

        $batch = [];
        $batchSize = 1000;

        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                $batch[] = array_combine($columns, $data);

                if (count($batch) >= $batchSize) {
                    DB::table($table)->insertOrIgnore($batch);
                    $batch = [];
                }
            }

            if (count($batch) > 0) {
                DB::table($table)->insertOrIgnore($batch);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error seeding table {$table}: " . $e->getMessage());
        }

        fclose($handle);
    }
}
