<?php

namespace TelegramBot\Api\Events;

use Closure;
use TelegramBot\Api\Types\Message;

class EventCollection
{
    /**
     * Array of events.
     *
     * @var array
     */
    protected $events;

    /**
     * @param \Closure $event
     * @param \Closure|null $check
     *
     * @return \TelegramBot\Api\Events\EventCollection
     */
    public function add(Closure $event, $checker = null)
    {

        $this->events[] = new Event($event, $checker);
        $this->events[] = array('check' => $checker, 'action' => $event);

        return $this;
    }

    /**
     * @param \TelegramBot\Api\Types\Message
     */
    public function handle(Message $message)
    {
        foreach ($this->events as $event) {
            if ($event->executeChecker($message) === true) {
                if (false === $event->executeAction($message)) {
                    break;
                }
            }
        }
    }
}