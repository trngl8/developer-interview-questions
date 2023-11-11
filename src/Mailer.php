<?php

namespace App;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class Mailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendConfirmEmail(string $email, string $token): void
    {
        $email = (new TemplatedEmail())
            ->from('test@example.com') //TODO: add custom domain and valid email
            ->to(new Address($email))
            ->subject('Thanks for signing up!')
            ->htmlTemplate('emails/signup.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
                'token' => $token
            ]);
        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            // TODO: log error and choose alternative transport
            throw new \Exception($e->getMessage());
        }
    }

}