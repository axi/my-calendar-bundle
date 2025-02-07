<?php

namespace Axi\MyCalendarBundle;

use Axi\MyCalendar\Recipe\RecipeInterface;
use Axi\MyCalendar\Service\CalendarService;
use Axi\MyCalendarBundle\DependencyInjection\Compiler\InjectRecipesPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * @todo: can the vendor dir be not /vendor ? composer https://getcomposer.org/doc/06-config.md#vendor-dir says yes
 */
class AxiMyCalendarBundle extends AbstractBundle
{
    public const RECIPE_TAG = 'axi_my_calendar.recipe';

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('only_recipes')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('except_recipes')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // Declare vendor recipes to Sf
        $container->services()->load(
            'Axi\\MyCalendar\\Recipe\\',
            '%kernel.project_dir%/vendor/axi/mycalendar/src/Recipe/*'
        )->autoconfigure();

        // Register tagging of every RecipeInterface
        $builder
            ->registerForAutoconfiguration(RecipeInterface::class)
            ->addTag(self::RECIPE_TAG)
        ;

        // Declare CalendarService for autowiring, using potential config values
        $container->services()
            ->set(CalendarService::class)
            ->class(CalendarService::class)
            // Applies from found configuration
            ->call('setOnlyRecipes', [$config['only_recipes']])
            ->call('setExceptRecipes', [$config['except_recipes']])
        ;
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // Add library translation path to translator pathes
        $builder->prependExtensionConfig(
            'framework',
            [
                'translator' => [
                    'paths' => [
                        '%kernel.project_dir%/vendor/axi/mycalendar/translations',
                    ],
                ],
            ]
        );
    }

    /**
     * Register compiler pass that will inject Recipes later in CalendarService
     */
    public function build (ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new InjectRecipesPass());
    }
}
