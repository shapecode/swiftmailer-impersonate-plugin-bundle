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

        $swiftMailers = $container->getParameter('swiftmailer.mailers');

        foreach ($swiftMailers as $name => $fullName) {
            $this->configureMailer($name, $config['mailers'], $container);
        }
    }

    /**
     * @param string           $name      The mailer name
     * @param array            $config    The mailer configuration
     * @param ContainerBuilder $container The container builder
     */
    protected function configureMailer($name, array $config, ContainerBuilder $container)
    {
        $mailerConfig = (isset($config[$name])) ? $config[$name] : [];

        $this->configureMailerFromAddress($name, $mailerConfig, $container);
        $this->configureEventDispatcher($name, $container);
    }

    /**
     * @param string           $name      The mailer name
     * @param array            $config    The mailer configuration
     * @param ContainerBuilder $container The container builder
     */
    protected function configureMailerFromAddress($name, array $config, ContainerBuilder $container)
    {
        if (!empty($config['from_address'])) {
            $container->setParameter(
                sprintf('shapecode_swiftmailer_plugins.mailer.%s.impersonate.from_address', $name),
                !empty($config['from_address']) ? $config['from_address'] : null
            );
            $container->setParameter(
                sprintf('shapecode_swiftmailer_plugins.mailer.%s.impersonate.from_name', $name),
                !empty($config['from_name']) ? $config['from_name'] : null
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

    /**
     * @param string           $name      The mailer name
     * @param ContainerBuilder $container The container builder
     */
    protected function configureEventDispatcher($name, ContainerBuilder $container)
    {
        $definitionDecorator = new DefinitionDecorator('shapecode_swiftmailer_plugins.symfony_event_dispatcher');

        $definitionName = sprintf('shapecode_swiftmailer_plugins.mailer.%s.plugin.symfony_event_dispatcher', $name);
        $tagName = sprintf('swiftmailer.%s.plugin', $name);

        $newDefinition = $container->setDefinition($definitionName, $definitionDecorator);
        $newDefinition->addTag($tagName);
    }

}
