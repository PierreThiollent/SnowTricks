<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
    public function show(Trick $trick): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        return $this->render('trick/trick_detail.html.twig', [
            'trick'       => $trick,
            'commentForm' => $form->createView(),
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->find($id);

        $user = $this->getUser();

        if ($trick?->getUser() !== $user || !in_array('ROLE_ADMIN', $user?->getRoles(), true)) {
            return $this->redirectToRoute('app_home');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trick);
        $entityManager->flush();

        $this->addFlash('success', 'Le post a bien été supprimé.');

        return $this->redirectToRoute('app_home');
    }
}
