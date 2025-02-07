<?php

namespace Axi\MyCalendarBundle\DependencyInjection\Compiler;

use Axi\MyCalendar\Service\CalendarService;
use Axi\MyCalendarBundle\AxiMyCalendarBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class InjectRecipesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(CalendarService::class)) {
            return;
        }

        $definition = $container->findDefinition(CalendarService::class);

        // find all service IDs with the AxiMyCalendarBundle::RECIPE_TAG tag
        $recipes = [];
        foreach ($container->findTaggedServiceIds(AxiMyCalendarBundle::RECIPE_TAG) as $id => $tags) {
            $recipes[$id] = new Reference($id);
        }
        // Inject the found classes
        $definition->addMethodCall('setRecipes', [$recipes]);

        // find all service IDs with the AxiMyCalendarBundle::RENDERER_TAG tag
        $renderers = [];
        foreach ($container->findTaggedServiceIds(AxiMyCalendarBundle::RENDERER_TAG) as $id => $tags) {
            $renderers[$id] = new Reference($id);
        }
        // Inject the found classes
        $definition->addMethodCall('setRenderers', [$renderers]);
    }
}
