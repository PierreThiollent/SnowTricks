<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ResetPassword
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface        $mailer,
        private RequestStack           $requestStack,
        private UserRepository         $userRepository
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function generateResetToken(User $user): string
    {
        $token = bin2hex(random_bytes(20));

        $user->setToken($token);
        $user->setExpiresAt(new \DateTime('now +7 days'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $token;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function processSendingPasswordResetEmail(User $user): void
    {
        $resetToken = $this->generateResetToken($user);

        $email = (new TemplatedEmail())
            ->from(new Address('reset-password@snowtricks.pierre-thiollent.fr', 'Snowtricks'))
            ->to($user->getEmail())
            ->subject('Votre demande de réinitialisation de mot de passe')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'expiresAt'  => $user->getExpiresAt()

            ]);

        $this->mailer->send($email);
    }

    /**
     * @throws \Exception
     */
    public function validateTokenAndFetchUser(string $token): User
    {
        $user = $this->userRepository
            ->findOneBy(['token' => $token]);

        if ($user === null || $user->isExpired()) {
            throw new \Exception('Votre lien de réinitialisation est invalide ou expiré, veuillez refaire une demande de réinitialisation de mot de passe.');
        }

        return $user;
    }
}