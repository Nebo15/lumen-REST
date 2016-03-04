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
    protected $signature = 'rest:create {model}';

    protected $description = 'Create API CRUD endpoints, Model, Repository and Tests';

    public function handle()
    {
        $modelName = ucfirst($this->argument('model'));

        $this->generateController($modelName);
        $this->generateRepository($modelName);
        $this->generateModel($modelName);
    }

    private function generateController($model)
    {
        $plural = Inflector::pluralize($model);
        $this->createFile("app/Http/Controllers/{$plural}Controller.php", $this->getTemplate('Controller', [
            '{namespace}' => 'App\Http\Controllers',
            '{controllerName}' => $plural,
            '{repositoryClassName}' => "App\\Repositories\\{$plural}Repository",
        ]));
    }

    private function generateRepository($model)
    {
        $plural = Inflector::pluralize($model);
        $this->createFile("app/Repositories/{$plural}Repository.php", $this->getTemplate('Repository', [
            '{namespace}' => 'App\Repositories',
            '{repositoryName}' => $plural,
            '{modelClassName}' => "App\\Models\\$model",
        ]));
    }

    private function generateModel($model)
    {
        $this->createFile("app/Models/{$model}.php", $this->getTemplate('Model', [
            '{namespace}' => 'App\Models',
            '{modelName}' => $model,
        ]));
    }

    private function getTemplate($file, array $replaceVars = [])
    {
        $content = file_get_contents(__DIR__ . '/../../Templates/' . $file . '.tpl');
        if ($replaceVars) {
            $content = str_replace(array_keys($replaceVars), $replaceVars, $content);
        }

        return $content;
    }

    private function createFile($path, $contents)
    {
        $parts = explode('/', $path);
        $file = array_pop($parts);
        $dir = './';
        foreach ($parts as $part) {
            if (!is_dir($dir .= "/$part")) {
                mkdir($dir);
            }
        }
        file_put_contents("$dir/$file", $contents);
    }
}
