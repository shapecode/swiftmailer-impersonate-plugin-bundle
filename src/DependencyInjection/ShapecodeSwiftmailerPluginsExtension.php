<?php

namespace Shapecode\Bundle\SwiftmailerPluginsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class ShapecodeSwiftmailerPluginsExtension
 *
 * @package Shapecode\Bundle\SwiftmailerPluginsBundle\DependencyInjection
 * @author  Nikita Loges
 * @company tenolo GbR
 */
class ShapecodeSwiftmailerPluginsExtension extends ConfigurableExtension
{
    /**
     * @inheritDoc
     */
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        foreach ($config['mailers'] as $name => $mailer) {
            $this->configureMailer($name, $mailer, $container);
        }
    }

    /**
     * @param string           $name      The mailer name
     * @param array            $mailer    The mailer configuration
     * @param ContainerBuilder $container The container builder
     */
    protected function configureMailer($name, array $mailer, ContainerBuilder $container)
    {
        $this->configureMailerFromAddress($name, $mailer, $container);
    }

    /**
     * @param string           $name      The mailer name
     * @param array            $mailer    The mailer configuration
     * @param ContainerBuilder $container The container builder
     */
    protected function configureMailerFromAddress($name, array $mailer, ContainerBuilder $container)
    {
        if (!empty($mailer['from_address'])) {
            $container->setParameter(
                sprintf('shapecode_swiftmailer_plugins.mailer.%s.impersonate.from_address', $name),
                !empty($mailer['from_address']) ? $mailer['from_address'] : null
            );
            $container->setParameter(
                sprintf('shapecode_swiftmailer_plugins.mailer.%s.impersonate.from_name', $name),
                !empty($mailer['from_name']) ? $mailer['from_name'] : null
            );

            $definitionDecorator = new DefinitionDecorator(
                'shapecode_swiftmailer_plugins.impersonate'
            );

            $container
                ->setDefinition(
                    sprintf('shapecode_swiftmailer_plugins.mailer.%s.plugin.impersonate', $name),
                    $definitionDecorator
                )
                ->setArguments([
                    sprintf('%%shapecode_swiftmailer_plugins.mailer.%s.impersonate.from_address%%', $name),
                    sprintf('%%shapecode_swiftmailer_plugins.mailer.%s.impersonate.from_name%%', $name)
                ]);

            $container
                ->getDefinition(sprintf('shapecode_swiftmailer_plugins.mailer.%s.plugin.impersonate', $name))
                ->addTag(sprintf('swiftmailer.%s.plugin', $name));
        }
    }

}
