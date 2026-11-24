<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PDO;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * MySQL is the single source of truth for all website data.
     *
     * The canonical data lives in database/sql/anuj-website.sql, a data-only
     * MySQL dump. This seeder imports that dump instead of keeping content in
     * PHP arrays, so the same SQL snapshot is used locally and in production.
     *
     * The dump uses INSERT IGNORE, so running this seeder on an existing
     * database never overwrites or deletes live rows. Use migrate:fresh --seed
     * only when you intentionally want a clean install from the SQL snapshot.
     */
    public function run(): void
    {
        $this->importMySqlDump(database_path('sql/anuj-website.sql'));

        $this->call(PageCopySeeder::class);
        $this->call(PageTemplateSeeder::class);
        $this->call(ServiceCategorySeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ServiceNavigationSeeder::class);

        // Safety net: if the dump did not contain a user (or the database is
        // not MySQL), keep the admin panel reachable.
        if (! User::query()->where('email', 'admin@example.com')->exists()) {
            User::query()->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => 'password',
                'role' => 'admin',
            ]);
        }

        $this->command?->info(
            'Website data imported from database/sql/anuj-website.sql. '
            .'Existing rows were left untouched (INSERT IGNORE).'
        );
    }

    /**
     * Import the data-only MySQL snapshot.
     *
     * A dedicated PDO connection is used because PHP's default PDO MySQL
     * connection does not enable multi-statement execution, which a dump file
     * needs.
     */
    private function importMySqlDump(string $path): void
    {
        if (! is_file($path)) {
            throw new RuntimeException("Database snapshot not found: {$path}");
        }

        if (DB::getDriverName() !== 'mysql') {
            $this->command?->warn(
                'MySQL snapshot skipped: the default database connection is not MySQL.'
            );

            return;
        }

        $config = DB::connection()->getConfig();

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'] ?? 3306,
            $config['database'],
            $config['charset'] ?? 'utf8mb4',
        );

        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS')) {
            $options[PDO::MYSQL_ATTR_MULTI_STATEMENTS] = true;
        }

        $pdo = new PDO($dsn, $config['username'], $config['password'], $options);

        $statement = $pdo->query(file_get_contents($path));

        if ($statement !== false) {
            // Drain every result set so errors in later statements surface.
            while ($statement->nextRowset()) {
                // Nothing to fetch; nextRowset() advances and validates.
            }
        }

        $pdo = null;
    }
}
