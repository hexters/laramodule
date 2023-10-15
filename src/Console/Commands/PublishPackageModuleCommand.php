<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class PublishPackageModuleCommand extends Command
{

    protected $name;
    protected $provider;
    protected $namespace;
    protected $composer_json;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:publish {module? : Module target name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make the module into a package for free use or sale.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $module = $this->argument('module');
        if (is_null($module)) {
            $module = select(label: "Select an available module!", options: module_name_lists());
        }

        $this->input->setArgument('module', Str::of($module)->slug('-')->studly());

        $currentUser = Str::studly(get_current_user());
        $moduleName = "{$currentUser}/$module";
        $email = env('MAIL_FROM_ADDRESS', 'example@mail.com');
        $authorDefault = "{$currentUser} <$email>";

        $moduleNameLower = Str::of($moduleName)->lower();

        $this->line('');
        $this->line('This command will guide you through creating your Ladmin Module.');

        $this->name = text(label: "What is the Package Name?", default: $moduleNameLower, required: true, placeholder: "E.g username/package-name", validate: fn ($value) => match (true) {
            !preg_match('/\w+\/\w+/', $value) => 'Format does not match, name must mention username/package',
            default => null
        });

        $namespace = [];
        foreach (explode('/', $this->name) as $name) {
            $namespace[] = Str::studly($name);
        }
        $this->namespace = implode('\\', $namespace) . '\\';

        $description = text('Description (optional)');

        $author = text(label: 'Author ', default: $authorDefault);
        preg_match_all('!(.*?)\s+<\s*(.*?)\s*>!', $author, $matches);
        $authors = [];
        for ($i = 0; $i < count($matches[0]); $i++) {
            $authors[] = array(
                'name' => $matches[1][$i],
                'email' => $matches[2][$i],
            );
        }
        $keywords = text(label: 'Keywords:', default: $this->name . ', Laravel, Ladmin Package');

        $license = text(label: 'License:', default: 'MIT');

        $composer['name'] = $this->name;
        $composer['type'] = 'library';
        if (isset($description)) {
            $composer['description'] = $description;
        }
        if (isset($license)) {
            $composer['license'] = $license;
        }
        if (isset($keywords)) {
            $composer['keywords'] = array_map(fn ($keyword) => $this->mappingKeywords($keyword), explode(',', $keywords));
        }
        if (count($authors) > 0) {
            $composer['authors'] = $authors;
        }

        $composer['autoload'] = [
            'psr-4' => [
                $this->namespace => 'src/',
            ]
        ];

        $this->provider = $this->namespace . Str::studly($module) . "ServiceProvider";
        $composer['extra'] = [
            'laravel' => [
                'providers' => [
                    $this->provider,
                ],
                'aliases' => []
            ]
        ];

        $composer['require'] = (object) [];

        $this->composer_json = json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        $this->line(
            $this->composer_json
        );

        $correct = select(label: 'Are you sure you want to generate this module?', options: ['yes', 'no'], required: true);

        if (in_array($correct, ['Yes', 'yes', 'YES', 'y', 'Y'])) {

            $this->components->info('Generating package...');
            $this->line('');
            $nodeModules = base_path('Modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'node_modules');

            if (is_dir($nodeModules)) {
                $this->error('Please delete the ' . $nodeModules . ' folder first and try again...');
                exit();
            }

            $this->createModule($module);
        }
    }

    /**
     * Remove space in keywords
     *
     * @param String $keyword
     * @return string
     */
    protected function mappingKeywords($keyword)
    {
        return trim($keyword);
    }

    /**
     * Create module
     *
     * @param string $module
     * @return void
     */
    protected function createModule($module)
    {

        $folders = [
            'stubs',
            'src',
        ];

        $moduleDirectory = base_path('published-modules');
        if (!is_dir($moduleDirectory)) {
            File::makeDirectory($moduleDirectory);
        }

        $dirs = explode('/', rtrim($this->name, '/'));

        $moduleDirectory = $moduleDirectory . DIRECTORY_SEPARATOR . strtolower(($dirs[0] ?? ''));

        if (!is_dir($moduleDirectory)) {
            File::makeDirectory($moduleDirectory);
        }

        foreach ($dirs as $index => $dir) {
            if ($index > 0) {

                $moduleDirectory = $moduleDirectory . DIRECTORY_SEPARATOR . strtolower($dir);
                if (!is_dir($moduleDirectory)) {
                    if (File::makeDirectory($moduleDirectory)) {
                        $moduleDirectory = $moduleDirectory;
                    }
                } else {
                    $this->error('Package already exists');
                    exit();
                }
            }
        }


        foreach ($folders as $folder) {
            File::makeDirectory($moduleDirectory . DIRECTORY_SEPARATOR . $folder);
        }

        file_put_contents($moduleDirectory . '/src/' . $this->providerName($module) . '.php', $this->contentProvider($module));
        file_put_contents($moduleDirectory . DIRECTORY_SEPARATOR . 'composer.json', $this->composer_json);
        file_put_contents($moduleDirectory . DIRECTORY_SEPARATOR . '.gitignore', 'vendor/' . PHP_EOL . '*.lock');
        file_put_contents($moduleDirectory . DIRECTORY_SEPARATOR . 'README.md', $this->contentReadme($module));


        $targetPackage = $moduleDirectory . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . $module;
        File::copyDirectory(base_path('Modules/' . $module), $targetPackage);

        $this->components->info('# Package generated successfully.');
        $this->line('');
        $this->line('-------------------------------------------------------------------');
        $this->line('');
        $this->line('If you need other packages to support your package, you can run the example command below');
        $this->line('');
        $this->info('cd ' . $moduleDirectory);
        $this->line('');
        $this->info('e.g: composer require laravel/horizon');
        $this->line('');
        $this->line('-------------------------------------------------------------------');
        $this->line('');
        $this->line('Upload your repository to github or gitlab.');
        $this->line('');
        $this->info('Sell your package at https://ppmarket.org/developers or you can share it for free at https://packagist.org');
        $this->line('');
    }

    /**
     * Get service provider name
     *
     * @param string $module
     * @return string
     */
    protected function providerName($module)
    {
        return Str::studly($module) . "ServiceProvider";
    }

    /**
     * Content of service provider
     *
     * @param string $module
     * @return string
     */
    protected function contentProvider($module)
    {
        $stub = file_get_contents(__DIR__ . '/stubs/publisn.provider.stub');
        return str_replace([
            '{{ namespace }}',
            '{{ moduleName }}',
            '{{ moduleNameLower }}',
            '{{ className }}',
        ], [
            rtrim($this->namespace, '\\'),
            $module,
            Str::of($module)->lower(),
            $this->providerName($module),
        ], $stub);
    }

    /**
     * Content of README.md
     *
     * @param string $module
     * @return string
     */
    protected function contentReadme($module)
    {
        $stub = file_get_contents(__DIR__ . '/stubs/publish.readme.stub');
        return str_replace([
            '{{ moduleTitle }}',
            '{{ moduleName }}',
            '{{ name }}'
        ], [
            Str::studly($module),
            $this->name,
            strtolower($module),
        ], $stub);
    }
}
