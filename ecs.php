<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    // https://tomasvotruba.com/blog/introducing-up-to-16-times-faster-easy-coding-standard/
//    $parameters->set(Option::PARALLEL, true); // requires 9.4.70+

    // Laravel app setup
    $parameters->set(Option::PATHS, [
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
        __DIR__ . '/ecs.php',  // check this file too!
    ]);

    $services = $containerConfigurator->services();
    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [[
            'syntax' => 'short',
        ]]);

    // add declare(strict_types=1); to all php files:
    $services->set(DeclareStrictTypesFixer::class);

    // run and fix, one by one
    $containerConfigurator->import(SetList::SPACES);
    $containerConfigurator->import(SetList::ARRAY);
    $containerConfigurator->import(SetList::DOCBLOCK);
    $containerConfigurator->import(SetList::NAMESPACES);
    $containerConfigurator->import(SetList::CONTROL_STRUCTURES);
    $containerConfigurator->import(SetList::CLEAN_CODE);
    $containerConfigurator->import(SetList::STRICT);
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::PHPUNIT);

    // align key value pairs (mostly)
    $services->set(BinaryOperatorSpacesFixer::class)
        ->call('configure', [[
            // Likely problems are pipe operators
            // if this is a problem change 'default' => 'single'
            // and uncomment this lines:
            'default'   => 'align_single_space_minimal',
            'operators' => [
                '|'  => 'no_space',
                '=>' => 'align_single_space_minimal',
            ],
        ]]);

    $parameters->set(Option::INDENTATION, 'spaces');

    $parameters->set(Option::LINE_ENDING, "\n");
};
