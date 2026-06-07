<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class WilayahSeeder extends Seeder
{
    private string $dataPath;

    public function __construct()
    {
        $this->dataPath = database_path('data/wilayah');
    }

    public function run(): void
    {
        /*
         * Download folder csv dari:
         * https://github.com/guzfirdaus/Wilayah-Administrasi-Indonesia/tree/master/csv
         * lalu letakkan file provinces.csv, regencies.csv, districts.csv, dan villages.csv
         * di database/data/wilayah/.
         */
        Schema::disableForeignKeyConstraints();
        DB::table('wilayah_villages')->truncate();
        DB::table('wilayah_districts')->truncate();
        DB::table('wilayah_regencies')->truncate();
        DB::table('wilayah_provinces')->truncate();
        Schema::enableForeignKeyConstraints();

        $this->importCsv('provinces.csv', 'wilayah_provinces', ['id', 'name']);
        $this->importCsv('regencies.csv', 'wilayah_regencies', ['id', 'province_id', 'name']);
        $this->importCsv('districts.csv', 'wilayah_districts', ['id', 'regency_id', 'name']);
        $this->importCsv('villages.csv', 'wilayah_villages', ['id', 'district_id', 'name']);
    }

    private function importCsv(string $fileName, string $table, array $requiredColumns): void
    {
        $path = $this->dataPath . DIRECTORY_SEPARATOR . $fileName;

        if (! File::exists($path)) {
            $this->command?->warn("File {$fileName} tidak ditemukan di database/data/wilayah. Import {$table} dilewati.");
            return;
        }

        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
        $file->setCsvControl(';');

        $headers = [];
        $rows = [];
        $now = now();

        foreach ($file as $index => $row) {
            if ($row === [null] || $row === false) {
                continue;
            }

            $row = array_map(fn ($value) => is_string($value) ? trim($value) : $value, $row);

            if ($index === 0) {
                $headers = $row;
                $missingColumns = array_diff($requiredColumns, $headers);

                if (! empty($missingColumns)) {
                    throw new \RuntimeException("Kolom " . implode(', ', $missingColumns) . " tidak ditemukan pada {$fileName}.");
                }

                continue;
            }

            $record = array_combine($headers, array_pad($row, count($headers), null));

            if (! $record || empty($record['id']) || empty($record['name'])) {
                continue;
            }

            $data = array_intersect_key($record, array_flip($requiredColumns));
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
            $rows[] = $data;

            if (count($rows) >= 1000) {
                DB::table($table)->upsert($rows, ['id'], array_diff(array_keys($rows[0]), ['id', 'created_at']));
                $rows = [];
            }
        }

        if (! empty($rows)) {
            DB::table($table)->upsert($rows, ['id'], array_diff(array_keys($rows[0]), ['id', 'created_at']));
        }
    }
}
