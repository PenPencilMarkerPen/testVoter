<?php

namespace App\MessageHandler;

use App\Message\ConfirmEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class ConfirmEmailHandler {

    public function __construct(
        private MailerInterface $mailer,
    )
    {}

    public function __invoke(ConfirmEmail $message){

        $product = $message->getProduct();
        $this->mailSender($product->name, $product->description, $product->count, $product->view);
    }

    private function mailSender(string $name, string $description, int $count, int $view):void {

        $format = '<p> Продукт посмотрели %d пользователей <br> Наименование продукта %s <br> Описание продукта %s <br> Количество товара %d <br> </p>';

        $text =sprintf($format, $view, $name, $description, $count);

        $mail=(new Email())
            ->from('andreidemianov2013@yandex.ru')
            ->to('andreidemianov2016@yandex.ru')
            ->subject('Уведомление о просмотрах товара')
            ->html($text);

        $this->mailer->send($mail);
    }
}