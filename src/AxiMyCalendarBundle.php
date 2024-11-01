<?php

namespace Axi\MyCalendarBundle;

use Axi\MyCalendar\Service\CalendarService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class AxiMyCalendarBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // Declare CalendarService for autowiring
        $container->services()->set('Axi\MyCalendar\Service\CalendarService')->class(CalendarService::class);
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // Add library translation path to translator pathes
        $builder->prependExtensionConfig(
            'framework',
            [
                'translator' => [
                    'paths' => [
                        '%kernel.project_dir%/vendor/axi/mycalendar/translations'
                    ],
                ],
            ]
        );
    }
}
