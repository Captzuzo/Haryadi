<?php
namespace Haryadi\Console;

use Haryadi\Console\Commands\ServeCommand;
use Haryadi\Console\Commands\MakeModelCommand;
use Haryadi\Console\Commands\MakeControllerCommand;
use Haryadi\Console\Commands\MakeMigrationCommand;
use Haryadi\Console\Commands\MigrateCommand;
use Haryadi\Console\Commands\HelpCommand;

class Application
{
    private $commands = [];

    public function __construct()
    {
        $this->registerCommands();
    }

    private function registerCommands(): void
    {
        $this->commands = [
            'serve' => new ServeCommand(),
            'make:model' => new MakeModelCommand(),
            'make:controller' => new MakeControllerCommand(),
            'make:migration' => new MakeMigrationCommand(),
            'migrate' => new MigrateCommand(),
            'help' => new HelpCommand(),
            'list' => new HelpCommand(),
        ];
    }

    public function run(): void
    {
        global $argv;
        
        $commandName = $argv[1] ?? 'help';
        $args = array_slice($argv, 2);

        if (!isset($this->commands[$commandName])) {
            $this->error("Command not found: {$commandName}");
            $this->showAvailableCommands();
            exit(1);
        }

        try {
            $this->commands[$commandName]->execute($args);
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            exit(1);
        }
    }

    private function showAvailableCommands(): void
    {
        $this->info("Available commands:");
        echo "\n";

        foreach ($this->commands as $name => $command) {
            echo "  " . str_pad($name, 20) . $command->getDescription() . "\n";
        }

        echo "\n";
        $this->info("Use 'php haryadi help' for more information");
    }

    private function info(string $message): void
    {
        echo "\033[32m{$message}\033[0m\n";
    }

    private function error(string $message): void
    {
        echo "\033[31m{$message}\033[0m\n";
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
}