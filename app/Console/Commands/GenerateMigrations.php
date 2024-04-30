<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GenerateMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate migration files based on database schema';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
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
        // Get list of tables in the database
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tables as $table) {
            // Generate migration file for each table
            $this->generateMigrationForTable($table);
        }

        $this->info('Migration files generated successfully.');
    }

    protected function generateMigrationForTable($table)
    {
        // Get table columns and details
        $columns = Schema::getColumnListing($table);
        $schema = DB::getDoctrineSchemaManager()->listTableDetails($table);

        // Define migration file content
        $content = "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\n";
        $content .= "class Create{$this->getClassName($table)}Table extends Migration\n{\n";
        $content .= "    public function up()\n    {\n";
        $content .= "        Schema::create('{$table}', function (Blueprint \$table) {\n";
        
        // Add columns to migration file
        foreach ($columns as $column) {
            $columnDetails = $schema->getColumn($column);
            $content .= "            \$table->{$columnDetails->getType()->getName()}('{$column}')";

            if ($columnDetails->getNotnull()) {
                $content .= '->nullable(false)';
            } else {
                $content .= '->nullable()';
            }

            if ($columnDetails->getDefault()) {
                $content .= "->default('{$columnDetails->getDefault()}')";
            }

            $content .= ";\n";
        }

        // Add primary key and timestamps
        $content .= "            \$table->primary('id');\n";
        $content .= "            \$table->timestamps();\n";
        $content .= "        });\n    }\n\n";
        $content .= "    public function down()\n    {\n";
        $content .= "        Schema::dropIfExists('{$table}');\n    }\n}";

        // Save migration file
        $fileName = date('Y_m_d_His') . '_create_' . $this->getMigrationName($table) . '_table.php';
        file_put_contents(database_path('migrations/') . $fileName, $content);
    }

    protected function getClassName($table)
    {
        return str_replace('_', '', ucwords($table, '_'));
    }

    protected function getMigrationName($table)
    {
        return Str::plural(Str::snake($table));
    }

}
