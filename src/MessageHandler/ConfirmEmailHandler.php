<?php

namespace App\MessageHandler;

use App\Message\ConfirmEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


#[AsMessageHandler]
class ConfirmEmailHandler {

    public function __construct(
        private MailerInterface $mailer,
    )
    {}

    public function __invoke(ConfirmEmail $message){

        $product = $message->getProduct();
        $this->mailSender($product);
    }

    private function mailSender($product):void {

        $userEmail = $product->brand->users->email;

        $mail = (new TemplatedEmail())
            ->from('andreidemianov2013@yandex.ru')
            ->to($userEmail)
            ->subject('Информация о товаре!')
            ->htmlTemplate('emails/info.html.twig')
            ->context(['product' => $product]);

        $this->mailer->send($mail);
    }
}