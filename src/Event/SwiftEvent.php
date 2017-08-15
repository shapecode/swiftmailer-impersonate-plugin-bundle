<?php

namespace Shapecode\Bundle\SwiftmailerPluginsBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class SwiftEvent
 *
 * @package Shapecode\Bundle\SwiftmailerPluginsBundle\Event
 * @author  Nikita Loges
 */
class SwiftEvent extends Event
{

    const CommandSent = 'shapecode.swift_mailer.command_sent';
    const ResponseReceived = 'shapecode.swift_mailer.response_received';
    const BeforeSendPerformed = 'shapecode.swift_mailer.before_send_performed';
    const SendPerformed = 'shapecode.swift_mailer.send_performed';
    const BeforeTransportStarted = 'shapecode.swift_mailer.before_transport_started';
    const TransportStarted = 'shapecode.swift_mailer.transport_started';
    const BeforeTransportStopped = 'shapecode.swift_mailer.before_transport_stopped';
    const TransportStopped = 'shapecode.swift_mailer.transport_stopped';

    /** @var \Swift_Events_Event|\Swift_Events_CommandEvent|\Swift_Events_ResponseEvent|\Swift_Events_SendEvent|\Swift_Events_TransportChangeEvent */
    protected $event;

    /**
     * @param \Swift_Events_Event $event
     */
    public function __construct(\Swift_Events_Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return \Swift_Events_Event|\Swift_Events_CommandEvent|\Swift_Events_ResponseEvent|\Swift_Events_SendEvent|\Swift_Events_TransportChangeEvent
     */
    public function getEvent()
    {
        return $this->event;
    }
}
