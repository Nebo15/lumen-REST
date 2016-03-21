<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 14:27
 */

namespace Nebo15\REST\Console\Command;

use Doctrine\Common\Inflector\Inflector;
use Illuminate\Console\Command;

class CreateCRUD extends Command
{
    protected $signature = 'rest:create {model} {--fillable=} {--listable=} {--visible=} {--force} {--doc=json}';

    protected $description = 'Create API CRUD endpoints, Model, Repository and Tests';

    public function handle()
    {
        $modelName = ucfirst($this->argument('model'));
        $this->info("Start to generate REST API for $modelName");

        $properties = [];
        foreach (['fillable', 'listable', 'visible'] as $opt) {
            if ($$opt = $this->option($opt)) {
                $properties[$opt] = array_map('trim', explode(',', $$opt));
            } else {
                $properties[$opt] = null;
            }
        }

        $this->generateModel($modelName, $properties);
        $this->generateController($modelName);
        $this->generateRepository($modelName);
        $this->generateObserver($modelName);
        $this->generateDoc($modelName, $properties, $this->option('doc'));
//        $this->generateTests($modelName);
        $this->info('DONE');
    }

    private function generateDoc($model, $properties, $type = 'json')
    {
        if(!in_array($type, ['md', 'json'])){
            $this->warn("Unavailable documentation extension '$type'");
            $type = 'json';
        }
        $visible = '';
        $fillable = '';
        $listable = '';
        foreach ($properties as $key => $values) {
            if ($values) {
                foreach ($values as $item) {
                    $$key .= " * `$item` - string\n";
                }
            }
        }
        $this->line("Generating .md documentation");
        $this->createFile("$model.md", $this->getTemplate('Docs/API', [
            '{modelName}' => $model,
            '{routeName}' => strtolower($model),
            '{routePrefix}' => 'api/v1/admin',
            '{fieldsVisible}' => $visible,
            '{fieldsFillable}' => $fillable,
            '{fieldsListable}' => $listable,
        ], $type));
    }

    private function generateController($model)
    {
        $this->line('Generating Controller');
        $plural = Inflector::pluralize($model);
        $this->createFile("app/Http/Controllers/{$plural}Controller.php", $this->getTemplate('Controller', [
            '{namespace}' => 'App\Http\Controllers',
            '{controllerName}' => $plural,
            '{repositoryClassName}' => "App\\Repositories\\{$plural}Repository",
        ]));
    }

    private function generateRepository($model)
    {
        $this->line('Generating Repository');
        $plural = Inflector::pluralize($model);
        $this->createFile("app/Repositories/{$plural}Repository.php", $this->getTemplate('Repository', [
            '{namespace}' => 'App\Repositories',
            '{repositoryName}' => $plural,
            '{modelClassName}' => "App\\Models\\$model",
            '{observerClassName}' => "App\\Observers\\" . $this->getObserverNameFromModelName($model),
        ]));
    }

    private function generateObserver($model)
    {
        $this->line('Generating Observer');
        $observerName = $this->getObserverNameFromModelName($model);
        $this->createFile("app/Observers/{$observerName}.php", $this->getTemplate('Observer', [
            '{namespace}' => 'App\Observers',
            '{observerName}' => $observerName,
            '{modelClassName}' => $model,
            '{modelName}' => strtolower($model),
            '{modelNamespace}' => "\\App\\Models\\$model"
        ]));
    }

    private function generateModel($model, array $properties)
    {
        $this->line('Generating Model');
        $this->createFile("app/Models/{$model}.php", $this->getTemplate('Model', [
            '{namespace}' => 'App\Models',
            '{modelName}' => $model,
            '{visible}' => $this->prepareModelProp($properties['visible']),
            '{fillable}' => $this->prepareModelProp($properties['fillable']),
            '{listable}' => $this->prepareModelProp($properties['listable']),
        ]));
    }

    private function prepareModelProp($value)
    {
        return $value ? "'" . rtrim(implode("', '", $value)) . "'" : null;
    }

    private function getObserverNameFromModelName($model)
    {
        return $model . "Observer";
    }

    private function generateTests($model)
    {
        $this->line('Generating Tests');
    }

    private function getTemplate($file, array $replaceVars = [], $ext = 'tpl')
    {
        $content = file_get_contents(__DIR__ . '/../../Templates/' . $file . ".$ext");
        if ($replaceVars) {
            $content = str_replace(array_keys($replaceVars), $replaceVars, $content);
        }

        return $content;
    }

    private function createFile($path, $contents)
    {
        $parts = explode('/', $path);
        $file = array_pop($parts);
        $dir = '.';
        foreach ($parts as $part) {
            if (!is_dir($dir .= "/$part")) {
                mkdir($dir);
            }
        }
        if (file_exists("$dir/$file") and !$this->option('force')) {
            $this->warn("File already exists: $dir/$file. Use flag --force to rewrite it");
        } else {
            file_put_contents("$dir/$file", $contents);
        }
    }
}
