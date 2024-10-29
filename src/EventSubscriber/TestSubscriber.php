<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TestSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents(){
        return [
            KernelEvents::VIEW => ['testSubscr', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function testSubscr(ViewEvent $event){
        dump($event);
    }
}