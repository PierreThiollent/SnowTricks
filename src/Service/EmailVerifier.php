<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailVerifier
{
    public function __construct(
        private UrlGeneratorInterface  $urlGenerator,
        private MailerInterface        $mailer,
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        $resetUrl = $this->urlGenerator->generate($verifyEmailRouteName, [
            'expires' => $user->getExpiresAt()?->getTimestamp(),
            'id'      => $user->getId(),
            'token'   => $user->getVerifyToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $context = $email->getContext();
        $context['signedUrl'] = $resetUrl;
        $context['expiresAt'] = $user->getExpiresAt()?->format('d/m/Y');

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws \Exception
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        if ($user->isExpired()) {
            throw new \Exception('Votre lien de confirmation est expiré, veuillez nous contacter, nous vous enverrons un nouveau lien.');
        } elseif ((int)$request->get('id') !== $user->getId() || $request->get('token') !== $user->getVerifyToken()) {
            throw new \Exception('Votre lien de confirmation est invalide.');
        }

        $user->setIsVerified(true);
        $user->setVerifyToken(null);
        $user->setExpiresAt(null);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
