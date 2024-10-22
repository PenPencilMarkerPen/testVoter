<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use App\Repository\ProductRepository;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;


class ProductProvider implements ProviderInterface
{

    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface $itemProvider,
        private EntityManagerInterface $entityManagerInterface,
        private ProductRepository $productRepository,
        private MailerInterface $mailer,
    )
    {}


    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($id = $uriVariables['id'])
        {
            $this->onUpdateViews($id);
        }

        return $this->itemProvider->provide($operation, $uriVariables, $context);
    }

    private function onUpdateViews(int $id){

        $product = $this->entityManagerInterface->getRepository(Product::class)->find($id);

        if (!$product instanceof Product)
            return;

        $this->productRepository->updateViews($id);

        $this->mailSender($product->name, $product->description, $product->count, $product->view);

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
