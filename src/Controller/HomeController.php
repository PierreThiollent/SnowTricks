<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findBy(
            [], ['publishedDate' => 'ASC'], 3
        );

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    public function loadMoreTricks(int $offset, TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findBy(
            [], ['publishedDate' => 'ASC'], 5, $offset
        );

        return $this->render('tricks/tricks.html.twig', [
            'tricks' => $tricks
        ]);
    }
}
