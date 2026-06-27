<?php

$rules = json_decode(file_get_contents(__DIR__ . '/config/php-cs-fixer-rules.json'), true);

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests']);

return (new PhpCsFixer\Config())
    ->setRules($rules)
    ->setFinder($finder);
