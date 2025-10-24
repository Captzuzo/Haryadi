<?php
namespace Haryadi\Core;

class Console
{
    private $commands = [];
    private $colors;

    public function __construct()
    {
        $this->colors = [
            'reset' => "\033[0m",
            'black' => "\033[0;30m",
            'red' => "\033[0;31m",
            'green' => "\033[0;32m",
            'yellow' => "\033[0;33m",
            'blue' => "\033[0;34m",
            'magenta' => "\033[0;35m",
            'cyan' => "\033[0;36m",
            'white' => "\033[0;37m",
            'bold' => "\033[1m",
        ];

        $this->registerCommands();
    }

    private function registerCommands(): void
    {
        $this->commands = [
            'serve' => [
                'description' => 'Menjalankan development server',
                'usage' => 'php haryadi serve [<host>] [<port>]',
                'handler' => 'serve'
            ],
            'make:model' => [
                'description' => 'Membuat model baru',
                'usage' => 'php haryadi make:model <nama_model>',
                'handler' => 'makeModel'
            ],
            'make:controller' => [
                'description' => 'Membuat controller baru',
                'usage' => 'php haryadi make:controller <nama_controller> [--api]',
                'handler' => 'makeController'
            ],
            'make:migration' => [
                'description' => 'Membuat file migration',
                'usage' => 'php haryadi make:migration <nama_migration>',
                'handler' => 'makeMigration'
            ],
            'migrate' => [
                'description' => 'Menjalankan migration',
                'usage' => 'php haryadi migrate [--fresh]',
                'handler' => 'migrate'
            ],
            'make:seeder' => [
                'description' => 'Membuat file seeder baru',
                'usage' => 'php haryadi make:seeder <nama_seeder>',
                'handler' => 'makeSeeder'
            ],
            'migrate:fresh' => [
                'description' => 'Migrate ulang dan seed database',
                'usage' => 'php haryadi migrate:fresh',
                'handler' => 'freshMigrateWithSeed'
            ],
            'db:seed' => [
                'description' => 'Menjalankan seeder',
                'usage' => 'php haryadi db:seed [<seeder_name>] [--class=ClassName]',
                'handler' => 'runSeeder'
            ],
            'make:middleware' => [
                'description' => 'Membuat middleware baru',
                'usage' => 'php haryadi make:middleware <nama_middleware>',
                'handler' => 'makeMiddleware'
            ],
            'route:list' => [
                'description' => 'Menampilkan daftar routes',
                'usage' => 'php haryadi route:list',
                'handler' => 'routeList'
            ],
            'key:generate' => [
                'description' => 'Generate application key',
                'usage' => 'php haryadi key:generate',
                'handler' => 'keyGenerate'
            ],
            'storage:link' => [
                'description' => 'Membuat symbolic link untuk storage',
                'usage' => 'php haryadi storage:link',
                'handler' => 'storageLink'
            ],
            'clear:cache' => [
                'description' => 'Membersihkan cache',
                'usage' => 'php haryadi clear:cache',
                'handler' => 'clearCache'
            ],
            'help' => [
                'description' => 'Menampilkan bantuan',
                'usage' => 'php haryadi help [<command>]',
                'handler' => 'showHelp'
            ]
        ];
    }

    public function run(array $argv): void
    {
        $command = $argv[1] ?? 'help';
        $args = array_slice($argv, 2);

        if (!isset($this->commands[$command])) {
            $this->error("Command tidak ditemukan: {$command}");
            $this->showHelp();
            exit(1);
        }

        $handler = $this->commands[$command]['handler'];
        $this->$handler($args);
    }

    private function serve(array $args): void
    {
        $host = $args[0] ?? 'localhost';
        $port = $args[1] ?? '8000';

        $this->info("🚀 Menjalankan Haryadi Framework development server...");
        $this->info("📡 Server berjalan di: http://{$host}:{$port}");
        $this->info("⏹️  Tekan Ctrl+C untuk menghentikan server");

        passthru("php -S {$host}:{$port} -t public");
    }

    private function makeModel(array $args): void
    {
        if (empty($args[0])) {
            $this->error("Nama model harus diisi!");
            $this->info("Usage: php haryadi make:model <nama_model>");
            exit(1);
        }

        $modelName = $args[0];
        $modelFile = APP_PATH . "/models/{$modelName}.php";

        if (file_exists($modelFile)) {
            $this->error("Model {$modelName} sudah ada!");
            exit(1);
        }

        $template = $this->getModelTemplate($modelName);
        file_put_contents($modelFile, $template);

        $this->success("✅ Model {$modelName} berhasil dibuat: app/models/{$modelName}.php");
    }

    private function makeController(array $args): void
    {
        if (empty($args[0])) {
            $this->error("Nama controller harus diisi!");
            $this->info("Usage: php haryadi make:controller <nama_controller> [--api]");
            exit(1);
        }

        $controllerName = $args[0];
        $isApi = in_array('--api', $args);
        
        $directory = $isApi ? 'Api' : '';
        $controllerFile = APP_PATH . "/controllers/{$directory}/{$controllerName}.php";

        // Buat directory jika belum ada
        if ($directory && !is_dir(dirname($controllerFile))) {
            mkdir(dirname($controllerFile), 0755, true);
        }

        if (file_exists($controllerFile)) {
            $this->error("Controller {$controllerName} sudah ada!");
            exit(1);
        }

        $template = $this->getControllerTemplate($controllerName, $isApi);
        file_put_contents($controllerFile, $template);

        $type = $isApi ? 'API Controller' : 'Controller';
        $this->success("✅ {$type} {$controllerName} berhasil dibuat: app/controllers/{$directory}/{$controllerName}.php");
    }

    private function makeMigration(array $args): void
    {
        if (empty($args[0])) {
            $this->error("Nama migration harus diisi!");
            $this->info("Usage: php haryadi make:migration <nama_migration>");
            exit(1);
        }

        $migrationName = $args[0];
        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_{$migrationName}.php";
        $migrationFile = BASE_PATH . "/database/migrations/{$filename}";

        // Buat directory migrations jika belum ada
        if (!is_dir(BASE_PATH . "/database/migrations")) {
            mkdir(BASE_PATH . "/database/migrations", 0755, true);
        }

        $template = $this->getMigrationTemplate($migrationName);
        file_put_contents($migrationFile, $template);

        $this->success("✅ Migration berhasil dibuat: database/migrations/{$filename}");
    }

    private function migrate(array $args): void
    {
        $fresh = in_array('--fresh', $args);
        
        $this->info("🔄 Menjalankan migration...");
        
        // Buat migrations table jika belum ada
        $this->createMigrationsTable();
        
        // Dapatkan migration files
        $migrationFiles = $this->getMigrationFiles();
        $ranMigrations = $this->getRanMigrations();
        
        $pendingMigrations = array_diff($migrationFiles, $ranMigrations);
        
        if (empty($pendingMigrations)) {
            $this->info("✅ Tidak ada migration yang pending.");
            return;
        }
        
        if ($fresh) {
            $this->info("♻️  Mode fresh - menghapus dan membuat ulang tabel");
            $this->freshMigrate();
            return;
        }
        
        sort($pendingMigrations); // Pastikan urutan benar
        $this->runMigrations($pendingMigrations);
    }

    private function createMigrationsTable(): void
    {
        $db = new Database();
        $sql = "CREATE TABLE IF NOT EXISTS `migrations` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `migration` VARCHAR(255) NOT NULL,
            `batch` INT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->getConnection()->exec($sql);
    }

    private function getMigrationFiles(): array
    {
        $migrationPath = BASE_PATH . '/database/migrations';
        
        if (!is_dir($migrationPath)) {
            return [];
        }
        
        $files = scandir($migrationPath);
        $migrationFiles = [];
        
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $migrationFiles[] = pathinfo($file, PATHINFO_FILENAME);
            }
        }
        
        sort($migrationFiles);
        return $migrationFiles;
    }

    private function getRanMigrations(): array
    {
        $db = new Database();
        
        try {
            $stmt = $db->getConnection()->query("SELECT migration FROM migrations ORDER BY id");
            $results = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            return $results ?: [];
        } catch (\PDOException $e) {
            return [];
        }
    }

    private function runMigrations(array $migrations): void
    {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        $batch = $this->getNextBatchNumber();
        
        foreach ($migrations as $migration) {
            $this->info("📦 Running: {$migration}");
            
            try {
                // Include migration file
                $migrationFile = BASE_PATH . "/database/migrations/{$migration}.php";
                
                if (!file_exists($migrationFile)) {
                    $this->error("❌ File migration tidak ditemukan: {$migrationFile}");
                    continue;
                }
                
                require_once $migrationFile;
                
                // Get class name from file name
                $className = $this->getMigrationClassName($migration);
                
                if (!class_exists($className)) {
                    $this->error("❌ Class {$className} tidak ditemukan!");
                    continue;
                }
                
                // Run migration
                $migrationInstance = new $className();
                $migrationInstance->up();
                
                // Record migration
                $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
                $stmt->execute([$migration, $batch]);
                
                $this->success("✅ {$migration} berhasil dijalankan");
                
            } catch (\Exception $e) {
                $this->error("❌ Error menjalankan {$migration}: " . $e->getMessage());
                break; // Stop jika ada error
            }
        }
        
        $this->success("🎉 Migration selesai!");
    }

    private function freshMigrate(): void
    {
        $db = new Database();
        
        // Drop all tables
        $tables = $db->getConnection()->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $db->getConnection()->exec("DROP TABLE IF EXISTS `{$table}`");
            $this->info("🗑️  Dropped table: {$table}");
        }
        
        // Re-run all migrations
        $migrationFiles = $this->getMigrationFiles();
        $this->runMigrations($migrationFiles);
    }

    private function getMigrationClassName(string $migration): string
    {
        // 2024_01_15_143000_create_users_table → CreateUsersTable
        $className = preg_replace('/[0-9]+_/', '', $migration);
        $className = str_replace('_', '', ucwords($className, '_'));
        return $className;
    }

    private function getNextBatchNumber(): int
    {
        $db = new Database();
        
        try {
            $stmt = $db->getConnection()->query("SELECT MAX(batch) FROM migrations");
            $batch = $stmt->fetchColumn();
            return ($batch ?: 0) + 1;
        } catch (\PDOException $e) {
            return 1;
        }
    }

    private function recordMigration(string $migration, int $batch): void
    {
        $db = new Database();
        $stmt = $db->getConnection()->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
    }

    private function makeMiddleware(array $args): void
    {
        if (empty($args[0])) {
            $this->error("Nama middleware harus diisi!");
            $this->info("Usage: php haryadi make:middleware <nama_middleware>");
            exit(1);
        }

        $middlewareName = $args[0];
        $middlewareFile = APP_PATH . "/middleware/{$middlewareName}.php";

        if (!is_dir(dirname($middlewareFile))) {
            mkdir(dirname($middlewareFile), 0755, true);
        }

        if (file_exists($middlewareFile)) {
            $this->error("Middleware {$middlewareName} sudah ada!");
            exit(1);
        }

        $template = $this->getMiddlewareTemplate($middlewareName);
        file_put_contents($middlewareFile, $template);

        $this->success("✅ Middleware {$middlewareName} berhasil dibuat: app/middleware/{$middlewareName}.php");
    }

    private function routeList(array $args): void
    {
        $this->info("🛣️  Daftar Routes Haryadi Framework:");
        $this->info("======================================");
        
        // Load routes untuk mendapatkan daftar
        require_once BASE_PATH . '/routes/web.php';
        
        $router = new Router();
        $routes = $router->getRoutes();
        
        foreach ($routes as $route) {
            $method = str_pad($route['method'], 6);
            $path = $route['path'];
            $name = $route['name'] ?? '-';
            
            $this->line("{$method} {$path} {$this->colors['cyan']}({$name}){$this->colors['reset']}");
        }
    }

    private function keyGenerate(array $args): void
    {
        $key = bin2hex(random_bytes(32));
        
        $envFile = BASE_PATH . '/.env';
        if (!file_exists($envFile)) {
            $this->error("File .env tidak ditemukan!");
            exit(1);
        }
        
        $content = file_get_contents($envFile);
        $content = preg_replace('/APP_KEY=.*/', "APP_KEY={$key}", $content);
        file_put_contents($envFile, $content);
        
        $this->success("✅ Application key berhasil di-generate!");
        $this->info("🔑 Key: {$key}");
    }

    private function storageLink(array $args): void
    {
        $link = BASE_PATH . '/public/storage';
        $target = BASE_PATH . '/storage/app/public';
        
        if (!is_dir($target)) {
            mkdir($target, 0755, true);
        }
        
        if (file_exists($link)) {
            unlink($link);
        }
        
        symlink($target, $link);
        
        $this->success("✅ Symbolic link storage berhasil dibuat!");
    }

    private function clearCache(array $args): void
    {
        $cachePath = STORAGE_PATH . '/cache';
        
        if (is_dir($cachePath)) {
            $this->deleteDirectory($cachePath);
            mkdir($cachePath, 0755, true);
        }
        
        $this->success("✅ Cache berhasil dibersihkan!");
    }

    private function showHelp(array $args = []): void
    {
        $command = $args[0] ?? null;
        
        if ($command && isset($this->commands[$command])) {
            $cmd = $this->commands[$command];
            $this->info("📖 Bantuan untuk command: {$command}");
            $this->info("======================================");
            $this->info("Deskripsi: {$cmd['description']}");
            $this->info("Usage: {$cmd['usage']}");
            return;
        }

        $this->info("🚀 Haryadi Framework CLI Tool");
        $this->info("======================================");
        $this->info("Gunakan: php haryadi <command> [options]");
        $this->info("");
        $this->info("📋 Daftar Commands:");
        $this->info("");

        foreach ($this->commands as $cmd => $info) {
            $this->line("  {$this->colors['green']}{$cmd}{$this->colors['reset']}");
            $this->line("      {$info['description']}");
            $this->line("");
        }
    }

    private function makeSeeder(array $args): void
{
    if (empty($args[0])) {
        $this->error("Nama seeder harus diisi!");
        $this->info("Usage: php haryadi make:seeder <nama_seeder>");
        exit(1);
    }

    $seederName = $args[0];
    $filename = "{$seederName}.php";
    $seederFile = BASE_PATH . "/database/seeders/{$filename}";

    // Buat directory seeders jika belum ada
    if (!is_dir(BASE_PATH . "/database/seeders")) {
        mkdir(BASE_PATH . "/database/seeders", 0755, true);
    }

    if (file_exists($seederFile)) {
        $this->error("Seeder {$seederName} sudah ada!");
        exit(1);
    }

    $template = $this->getSeederTemplate($seederName);
    file_put_contents($seederFile, $template);

    $this->success("✅ Seeder berhasil dibuat: database/seeders/{$filename}");
}

private function runSeeder(array $args): void
{
    $specificSeeder = $args[0] ?? null;
    $className = null;

    // Cek jika ada option --class
    foreach ($args as $arg) {
        if (str_starts_with($arg, '--class=')) {
            $className = substr($arg, 8);
            break;
        }
    }

    $this->info("🌱 Menjalankan seeder...");

    if ($specificSeeder) {
        $this->runSpecificSeeder($specificSeeder);
    } elseif ($className) {
        $this->runSeederByClass($className);
    } else {
        $this->runAllSeeders();
    }
}

private function runAllSeeders(): void
{
    $seederPath = BASE_PATH . '/database/seeders';
    
    if (!is_dir($seederPath)) {
        $this->error("❌ Directory seeders tidak ditemukan!");
        return;
    }

    $files = scandir($seederPath);
    $seeders = [];

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $seeders[] = pathinfo($file, PATHINFO_FILENAME);
        }
    }

    if (empty($seeders)) {
        $this->info("ℹ️ Tidak ada seeder yang ditemukan.");
        return;
    }

    sort($seeders);

    foreach ($seeders as $seeder) {
        $this->runSpecificSeeder($seeder);
    }

    $this->success("🎉 Semua seeder berhasil dijalankan!");
}

private function runSpecificSeeder(string $seederName): void
{
    $seederFile = BASE_PATH . "/database/seeders/{$seederName}.php";
    
    if (!file_exists($seederFile)) {
        $this->error("❌ File seeder tidak ditemukan: {$seederFile}");
        return;
    }

    require_once $seederFile;

    $className = $this->getSeederClassName($seederName);
    
    if (!class_exists($className)) {
        $this->error("❌ Class {$className} tidak ditemukan!");
        return;
    }

    $this->info("📦 Running: {$seederName}");
    
    try {
        $seederInstance = new $className();
        $seederInstance->run();
        $this->success("✅ {$seederName} berhasil dijalankan");
    } catch (\Exception $e) {
        $this->error("❌ Error menjalankan {$seederName}: " . $e->getMessage());
    }
}

private function runSeederByClass(string $className): void
{
    if (!class_exists($className)) {
        $this->error("❌ Class {$className} tidak ditemukan!");
        return;
    }

    $this->info("📦 Running: {$className}");
    
    try {
        $seederInstance = new $className();
        $seederInstance->run();
        $this->success("✅ {$className} berhasil dijalankan");
    } catch (\Exception $e) {
        $this->error("❌ Error menjalankan {$className}: " . $e->getMessage());
    }
}

private function freshMigrateWithSeed(): void
{
    $this->info("♻️  Migrate fresh dengan seeding...");
    
    // Jalankan migrate --fresh
    $this->freshMigrate();
    
    // Jalankan semua seeder
    $this->runAllSeeders();
}

private function getSeederClassName(string $seederName): string
{
    // users_seeder → UsersSeeder
    // database_seeder → DatabaseSeeder
    return str_replace('_', '', ucwords($seederName, '_'));
}

private function getSeederTemplate(string $seederName): string
{
    $className = $this->getSeederClassName($seederName);
    
    return "<?php

use Haryadi\Core\Database\Seeder;
use Haryadi\Core\Database;

class {$className} extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
";
}

    // ==================== TEMPLATE METHODS ====================

    private function getModelTemplate(string $modelName): string
    {
        return "<?php
namespace App\Models;

use Haryadi\Core\Model;

class {$modelName} extends Model
{
    protected string \$table = '" . strtolower($modelName) . "s';
    
    protected array \$fillable = [
        // Tambahkan field yang bisa diisi di sini
    ];
    
    // Tambahkan method custom di bawah ini
}
";
    }

    private function getControllerTemplate(string $controllerName, bool $isApi = false): string
    {
        $namespace = $isApi ? 'App\Controllers\Api' : 'App\Controllers';
        $baseClass = $isApi ? 'ApiController' : 'Controller';
        
        return "<?php
namespace {$namespace};

use Haryadi\Core\\{$baseClass};

class {$controllerName} extends {$baseClass}
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Implementasi method index di sini
        \$this->success(['message' => 'Method index {$controllerName}']);
    }
    
    /**
     * Store a newly created resource.
     */
    public function store()
    {
        // Implementasi method store di sini
        \$this->success(['message' => 'Method store {$controllerName}'], 'Data berhasil dibuat', 201);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(\$id)
    {
        // Implementasi method show di sini
        \$this->success(['id' => \$id, 'message' => 'Method show {$controllerName}']);
    }
    
    /**
     * Update the specified resource.
     */
    public function update(\$id)
    {
        // Implementasi method update di sini
        \$this->success(['id' => \$id, 'message' => 'Method update {$controllerName}']);
    }
    
    /**
     * Remove the specified resource.
     */
    public function destroy(\$id)
    {
        // Implementasi method destroy di sini
        \$this->success(['id' => \$id, 'message' => 'Method destroy {$controllerName}']);
    }
}
";
    }

private function getMigrationTemplate(string $migrationName): string
{
    $className = str_replace('_', '', ucwords($migrationName, '_'));
    $tableName = $this->getTableName($migrationName);
    
    return "<?php

use Haryadi\Core\Database\Migration;

class {$className} extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \$this->createTable('{$tableName}', function(\$table) {
            \$table->id();
            \$table->string('name');
            \$table->timestamps();
            // Tambahkan kolom lainnya di sini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \$this->dropTable('{$tableName}');
    }
}
";
}

    private function getMiddlewareTemplate(string $middlewareName): string
    {
        return "<?php
namespace App\Middleware;

use Haryadi\Core\Request;
use Haryadi\Core\Response;

class {$middlewareName}
{
    /**
     * Handle the incoming request.
     */
    public function handle(Request \$request): bool
    {
        // Implementasi middleware logic di sini
        
        // Return true untuk melanjutkan request
        return true;
        
        // Return false untuk menghentikan request
        // Response::json(['error' => 'Unauthorized'], 401);
        // return false;
    }
}
";
    }

    private function getTableName(string $migrationName): string
{
    // Extract table name from migration name
    // create_sessions_table → sessions
    // create_barang_belis_table → barang_belis
    // add_status_to_users_table → users (tetap)
    
    if (str_starts_with($migrationName, 'create_') && str_ends_with($migrationName, '_table')) {
        return substr($migrationName, 7, -6); // remove 'create_' and '_table'
    }
    
    if (str_starts_with($migrationName, 'add_') && str_contains($migrationName, '_to_') && str_ends_with($migrationName, '_table')) {
        // Untuk migration add column, extract table name
        $parts = explode('_to_', $migrationName);
        return substr($parts[1], 0, -6); // remove '_table'
    }
    
    if (str_starts_with($migrationName, 'create_')) {
        return substr($migrationName, 7);
    }
    
    return $migrationName;
}

    // ==================== HELPER METHODS ====================

    private function info(string $message): void
    {
        echo $this->colors['blue'] . $message . $this->colors['reset'] . PHP_EOL;
    }

    private function success(string $message): void
    {
        echo $this->colors['green'] . $message . $this->colors['reset'] . PHP_EOL;
    }

    private function error(string $message): void
    {
        echo $this->colors['red'] . $message . $this->colors['reset'] . PHP_EOL;
    }

    private function line(string $message): void
    {
        echo $message . PHP_EOL;
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            is_dir("$dir/$file") ? $this->deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }
        rmdir($dir);
    }
}