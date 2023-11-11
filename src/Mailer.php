<?php

namespace App;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class Mailer
{
    private MailerInterface $mailer;

    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function sendConfirmEmail(string $email, string $token): void
    {
        $settings = Core::getMailerSettings();
        $email = (new TemplatedEmail())
            ->from($settings->adminEmail)
            ->to(new Address($email))
            ->subject('Thanks for signing up!')
            ->htmlTemplate('emails/confirm.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
                'token' => $token
            ]);
        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

}
