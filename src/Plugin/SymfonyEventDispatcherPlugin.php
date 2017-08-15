<?php

namespace Shapecode\Bundle\SwiftmailerPluginsBundle\Plugin;

use Shapecode\Bundle\SwiftmailerPluginsBundle\Event\SwiftEvent;
use Swift_Events_CommandEvent;
use Swift_Events_ResponseEvent;
use Swift_Events_SendEvent;
use Swift_Events_TransportChangeEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class SymfonyEventDispatcherPlugin
 *
 * @package Shapecode\Bundle\SwiftmailerPluginsBundle\Plugin
 * @author  Nikita Loges
 */
class SymfonyEventDispatcherPlugin implements SymfonyEventDispatcherPluginInterface
{

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritDoc
     */
    public function commandSent(Swift_Events_CommandEvent $evt)
    {
        $event = new SwiftEvent($evt);
        $this->eventDispatcher->dispatch(SwiftEvent::CommandSent, $event);
    }

    /**
     * @inheritDoc
     */
    public function responseReceived(Swift_Events_ResponseEvent $evt)
    {
        $event = new SwiftEvent($evt);
        $this->eventDispatcher->dispatch(SwiftEvent::ResponseReceived, $event);
    }

    /**
     * @inheritDoc
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
        $event = new SwiftEvent($evt);
        $this->eventDispatcher->dispatch(SwiftEvent::BeforeSendPerformed, $event);
    }

    /**
     * @inheritDoc
     */
    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
        $event = new SwiftEvent($evt);
        $this->eventDispatcher->dispatch(SwiftEvent::SendPerformed, $event);
    }

    /**
     * @inheritDoc
     */
    public function beforeTransportStarted(Swift_Events_TransportChangeEvent $evt)
    {
        $event = new SwiftEvent($evt);
        $this->eventDispatcher->dispatch(SwiftEvent::BeforeTransportStarted, $event);
    }

    /**
     * @inheritDoc
     */
    public function transportStarted(Swift_Events_TransportChangeEvent $evt)
    {
        $event = new SwiftEvent($evt);
        $this->eventDispatcher->dispatch(SwiftEvent::TransportStarted, $event);
    }

    /**
     * @inheritDoc
     */
    public function beforeTransportStopped(Swift_Events_TransportChangeEvent $evt)
    {
        $event = new SwiftEvent($evt);
        $this->eventDispatcher->dispatch(SwiftEvent::BeforeTransportStopped, $event);
    }

    /**
     * @inheritDoc
     */
    public function transportStopped(Swift_Events_TransportChangeEvent $evt)
    {
        $event = new SwiftEvent($evt);
        $this->eventDispatcher->dispatch(SwiftEvent::TransportStopped, $event);
    }
}
