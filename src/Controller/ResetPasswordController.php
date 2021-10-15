<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\ResetPassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    public function __construct(private ResetPassword $resetPassword)
    {
    }

    /**
     * Display & process form to request a password reset.
     * @throws TransportExceptionInterface
     */
    public function request(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $userRepository
                ->findOneBy([
                    'email' => $form->get('email')->getData(),
                ]);

            // Do not reveal whether a user account was found or not.
            if (!$user) {
                return $this->redirectToRoute('app_reset_password_request');
            }

            $this->resetPassword->processSendingPasswordResetEmail($user);

            $this->addFlash('success', 'Un lien de réinitialisation de mot de passe vient de vous être envoyé par email, veuillez cliquer dessus.');
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     */
    public function reset(Request $request, UserPasswordHasherInterface $passwordEncoder, string $token = null): Response
    {
        if ($token === null) {
            throw $this->createNotFoundException("Votre lien de réinitialisation est incorrect");
        }

        try {
            $user = $this->resetPassword->validateTokenAndFetchUser($token);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_reset_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password, and set it.
            $encodedPassword = $passwordEncoder->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $user->setToken(null);
            $user->setExpiresAt(null);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Votre mot de passe a bien été modifié, vous pouvez maintenant vous connecter.");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
