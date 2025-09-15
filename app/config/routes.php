<?php
declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add('app.swagger', '/docs.json')
        ->defaults([
            '_controller' => 'nelmio_api_doc.controller.swagger',
        ])
        ->methods(['GET']);

    $routes->add('app.swagger_ui', '/docs')
        ->defaults([
            '_controller' => 'nelmio_api_doc.controller.swagger_ui',
        ])
        ->methods(['GET']);
};
