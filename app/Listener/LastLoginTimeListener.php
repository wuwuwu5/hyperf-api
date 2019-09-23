<?php

namespace App\Listener;


use App\Event\LastLoginTimeEvent;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class LastLoginTimeListener implements ListenerInterface
{

    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            LastLoginTimeEvent::class
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     */
    public function process(object $event)
    {
        $user = $event->getUser();

        $user->last_login_time = date('Y-m-d H:i:s');
        $user->save();
    }
}