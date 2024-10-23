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
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\ConfirmEmail;


class ProductProvider implements ProviderInterface
{

    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface $itemProvider,
        private EntityManagerInterface $entityManagerInterface,
        private ProductRepository $productRepository,
        private MailerInterface $mailer,
        private MessageBusInterface $messageBusInterface,
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

        if (null===$product)
            return;

        $this->productRepository->updateViews($id);

        $this->messageBusInterface->dispatch(new ConfirmEmail($product));

        // $this->mailSender($product->name, $product->description, $product->count, $product->view);

    }


}
