<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
    public function show(Trick $trick): Response
    {
        return $this->render('trick/trick_detail.html.twig', [
            'trick' => $trick
        ]);
    }
}
