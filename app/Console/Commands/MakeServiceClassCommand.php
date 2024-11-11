<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Filesystem\Filesystem;

class MakeServiceClassCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * The Filesystem instance.
     */
    protected Filesystem $files;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->getSourceFilePath();

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->components->info("Service Class : {$path} created successfully.");
        } else {
            $this->components->error("Service class already exits");
        }
    }

    /**
     * Return the stub file path.
     */
    public function getStubPath(): string
    {
        return __DIR__ . '/../../Stubs/service.stub';
    }

    /**
     * Map the stub variables present in stub to its value.
     */
    public function getStubVariables(): array
    {
        $nameArray = explode('/', $this->getSingularClassName($this->argument('name')));
        $name = array_pop($nameArray);
        $subPath = implode('\\', $nameArray);

        $namespace = 'App\Services' . ($subPath ? '\\' . $subPath : '');
        return [
            'NAMESPACE' => $namespace,
            'CLASS_NAME' => $name . 'Service',
        ];
    }

    /**
     * Get the stub path and the stub variables.
     */
    public function getSourceFile(): mixed
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }

    /**
     * Replace the stub variables(key) with the desire value.
     *
     * @param $stub
     * @param array $stubVariables
     */
    public function getStubContents($stub, $stubVariables = []): mixed
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get the full path of generate class.
     */
    public function getSourceFilePath(): string
    {
        return base_path('app/Services') . '/' . $this->getSingularClassName($this->argument('name')) . 'Service.php';
    }

    /**
     * Return the Singular Capitalize Name.
     * @param $name
     */
    public function getSingularClassName(string $name): string
    {
        return ucwords(Pluralizer::singular($name));
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     */
    protected function makeDirectory(string $path): string
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
