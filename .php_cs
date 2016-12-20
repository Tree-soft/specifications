<?php

$phpCSConfig = function () {
    $output = [];

    #exec('git diff dev..HEAD --name-status --diff-filter=ACM', $output);
    exec('git diff --cached --name-status --diff-filter=ACM', $output);

    $files = array_filter(
        array_map(function ($str) {
            return new SplFileInfo(trim(substr($str, 1)));
        }, $output), function (SplFileInfo $file) {
        return $file->getExtension() == 'php';
    }
    );

    return PhpCsFixer\Config::create()
        ->setRules([
            '@Symfony' => true,
            'psr0' => false,
            'phpdoc_inline_tag' => false,
            'phpdoc_no_empty_return' => false,
            'phpdoc_to_comment' => false,
            'phpdoc_var_without_name' => false,
            'phpdoc_no_package' => true,
            'phpdoc_single_line_var_spacing' => false,
            'phpdoc_add_missing_param_annotation' => true,
            'phpdoc_align' => false
        ])
        ->setFinder(new ArrayIterator($files));
};

return $phpCSConfig();