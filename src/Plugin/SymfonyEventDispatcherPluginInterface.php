<?php

namespace Shapecode\Bundle\SwiftmailerPluginsBundle\Plugin;

/**
 * Interface SymfonyEventDispatcherPluginInterface
 *
 * @package Shapecode\Bundle\SwiftmailerPluginsBundle\Plugin
 * @author  Nikita Loges
 */
interface SymfonyEventDispatcherPluginInterface extends
    \Swift_Events_TransportChangeListener,
    \Swift_Events_SendListener,
    \Swift_Events_ResponseListener,
    \Swift_Events_CommandListener
{

}
