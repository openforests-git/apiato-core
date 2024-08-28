<?php

namespace Apiato\Core\Generator\Traits;

use Illuminate\Support\Facades\File;

trait SuggestionHelperTrait
{
    public function getActionsList(
        string $section,
        string $container,
        bool $removeActionPostFix = false,
        bool $removePhpPostFix = true,
        bool $unCamelizeAndReplaceWithSpace = false,
    ): array {
        $actionsDirectory = base_path('app/Containers/' . $section . '/' . $container . '/Actions');
        $files = File::allFiles($actionsDirectory);

        $actions = [];

        foreach ($files as $action) {
            $fileName = $originalFileName = $action->getFilename();

            if ($removeActionPostFix) {
                $fileName = str_replace(['Action.php'], '', $fileName);
            }
            if ($removePhpPostFix) {
                $fileName = str_replace(['.php'], '', $fileName);
            }
            if ($unCamelizeAndReplaceWithSpace) {
                $fileName = uncamelize($fileName);
            }

            $actions[] = $fileName;
        }

        return $actions;
    }

    public function getModelsList(
        string $section,
        string $container,
        bool $removeModelPostFix = false,
        bool $removePhpPostFix = true,
        bool $unCamelizeAndReplaceWithSpace = false,
    ): array {
        $modelsDirectory = base_path('app/Containers/' . $section . '/' . $container . '/Models');
        $files = File::allFiles($modelsDirectory);

        $models = [];

        foreach ($files as $model) {
            $fileName = $originalFileName = $model->getFilename();

            if ($removeModelPostFix) {
                $fileName = str_replace(['.php'], '', $fileName);
            }
            if ($removePhpPostFix) {
                $fileName = str_replace(['.php'], '', $fileName);
            }
            if ($unCamelizeAndReplaceWithSpace) {
                $fileName = uncamelize($fileName);
            }

            $models[] = $fileName;
        }

        return $models;
    }
}
