<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Mailer\MailerInterface;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\ConfirmEmail;
use Symfony\Component\HttpKernel\Event\ViewEvent;


class ProductSubscriber implements EventSubscriberInterface {
    
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private ProductRepository $productRepository,
        private MailerInterface $mailer,
        private MessageBusInterface $messageBusInterface,
    )
    {}

    public static function getSubscribedEvents()
    {   
        return [
            KernelEvents::RESPONSE => 
               ['onUpdateViews', EventPriorities::POST_RESPOND],
        ];
    }

    public function onUpdateViews (ResponseEvent $responseEvent):void
    {

        $request = $responseEvent->getRequest();
        $path = $request->getPathInfo();  
        $routeParams = $request->attributes->get('_route_params');

        if (!$request->isMethod(Request::METHOD_GET) || !preg_match('#^/api/products/\d+#', $path))
            return;
        
        $productId = $routeParams['id'];

        $product = $this->entityManagerInterface->getRepository(Product::class)->find($productId);

        if (!$product instanceof Product)
            return;

        $this->productRepository->updateViews($productId);

        $this->messageBusInterface->dispatch(new ConfirmEmail($product));
    }


}