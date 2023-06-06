<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    #[Route('/{slug}', methods: 'GET')]
    public function redirectAction(Request $request): Response
    {
        return $this->redirect('https://google.com', 302);
    }
}
