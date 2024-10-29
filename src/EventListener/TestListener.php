<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class TestListener {

    public function testEvent(ViewEvent $event)
    {
        dump('hello1');
    }
}
