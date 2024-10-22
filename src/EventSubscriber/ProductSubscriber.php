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
use Symfony\Component\Mime\Email;

class ProductSubscriber implements EventSubscriberInterface {
    
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private ProductRepository $productRepository,
        private MailerInterface $mailer,
    )
    {}

    public static function getSubscribedEvents()
    {   
        return [
            // KernelEvents::RESPONSE => [
            //     'onUpdateViews'
            // ]
        ];
    }

    public function onUpdateViews (ResponseEvent $responseEvent)
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

        $this->mailSender($product->name, $product->description, $product->count, $product->view);
        // var_dump($product->name);
    }


    private function mailSender(string $name, string $description, int $count, int $view):void {

        $format = '<p> Продукт посмотрели %d пользователей <br> Наименование продукта %s <br> Описание продукта %s <br> Количество товара %d <br> </p>';

        $text =sprintf($format, $view, $name, $description, $count);

        $mail=(new Email())
            ->from('andreidemianov2013@yandex.ru')
            ->to('andreidemianov2016@yandex.ru')
            ->subject('Уведомление о просмотрах товара')
            ->text('Sending emails is fun again!')
            ->html($text);

        $this->mailer->send($mail);
    }

}