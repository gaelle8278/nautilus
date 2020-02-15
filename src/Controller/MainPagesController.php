<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainPagesController extends AbstractController
{
    
    /**
     * @Route("/", name="homepage")
     */
    public function home() {
        return $this->render('main_pages/home.html.twig');
    }
}

