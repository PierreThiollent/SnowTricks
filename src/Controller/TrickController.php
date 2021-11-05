<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentFormType;
use App\Form\NewTrickFormType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    public function __construct(
        private SluggerInterface       $slugger,
        private EntityManagerInterface $entityManager,
        private FileUploader           $fileUploader,
    )
    {
    }

    public function show(Trick $trick): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        return $this->render('trick/trick_detail.html.twig', [
            'trick'       => $trick,
            'commentForm' => $form->createView(),
        ]);
    }

    public function new(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(NewTrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            $imagesNames = [];

            foreach ($images as $image) {
                $imagesNames[] = $this->fileUploader->upload($image);
            }

            $trick->setImages($imagesNames);
            $trick->setUser($this->getUser());
            $trick->setSlug($this->slugger->slug($form->get('name')->getData()));

            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre trick a bien été ajouté.');

            $this->redirectToRoute('app_trick_new');
        }

        return $this->render('trick/trick_new.html.twig', [
            'newTrickForm' => $form->createView()
        ]);
    }

    public function delete(Trick $trick): RedirectResponse
    {
        $user = $this->getUser();

        if ($trick?->getUser() !== $user || !in_array('ROLE_ADMIN', $user?->getRoles(), true)) {
            return $this->redirectToRoute('app_home');
        }

        foreach ($trick->getImages() as $image) {
            $this->fileUploader->remove($image);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trick);
        $entityManager->flush();

        $this->addFlash('success', 'Le post a bien été supprimé.');

        return $this->redirectToRoute('app_home');
    }
}