<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function(ContainerConfigurator $configurator): void {
    // Import project configuration
    $projectDirectory = dirname(__DIR__) . '/src';

    $configurator->import("{$projectDirectory}/Infrastructure/services.php");
    $configurator->import("{$projectDirectory}/Module/**/services.php");
};
