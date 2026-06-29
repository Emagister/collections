<?php

$rulesJson = file_get_contents(__DIR__ . '/config/php-cs-fixer-rules.json');
if ($rulesJson === false) {
    throw new RuntimeException('Could not read config/php-cs-fixer-rules.json');
}
$rules = json_decode($rulesJson, true, 512, JSON_THROW_ON_ERROR);

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests']);

return (new PhpCsFixer\Config())
    ->setRules($rules)
    ->setFinder($finder);
