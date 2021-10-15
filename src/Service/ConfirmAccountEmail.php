<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConfirmAccountEmail
{
    public function __construct(
        private MailerInterface        $mailer,
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailConfirmation(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address(
                'inscription@snowtricks.pierre-thiollent.fr',
                'Inscription Snowtricks'))
            ->to($user->getEmail())
            ->subject("Snowtricks - Confirmation de l'email")
            ->htmlTemplate('registration/confirmation_email.html.twig');

        $context = $email->getContext();
        $context['id'] = $user->getId();
        $context['token'] = $user->getToken();
        $context['expiresAt'] = $user->getExpiresAt();
        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws \Exception
     */
    public function handleEmailConfirmation(User $user, string $token): void
    {
        if ($user->isExpired() || $token !== $user->getToken()) {
            throw new \Exception('Votre lien de confirmation est expiré, veuillez nous contacter, nous vous enverrons un nouveau lien.');
        }

        $user->setIsVerified(true);
        $user->setToken(null);
        $user->setExpiresAt(null);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
