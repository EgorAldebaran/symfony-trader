<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TraderController extends AbstractController
{
    #[Route('/trader/quest', name: 'quest_trader')]
    public function quest(): Response
    {
        return $this->render('/trader/quest.html.twig');
    }
    
    #[Route('/trader/nasdaq', name: 'nasdaq_trader')]
    public function nasdaq(): Response
    {
        return $this->render('/trader/nasdaq.html.twig');
    }

    #[Route('/trader/forex', name: 'forex_trader')]
    public function forex(): Response
    {
        return $this->render('/trader/forex.html.twig');
    }
}
