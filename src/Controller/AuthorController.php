<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;


class AuthorController extends AbstractController
{
    /**
     * @Route("/author/add", name="create_author")
     */
    public function create() : RedirectResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $author = new Author();
        $author->setFirstname("Christian");
        $author->setLastname("JAcq");
        
        
        $entityManager->persist($author);
        
        $entityManager->flush();
        
        $this->addFlash('info', 'Auteur enregistré !');
        
        return $this->redirectToRoute('show_author', [
            'id' => $author->getId()
        ]);
    }
    
    
    /**
     * @Route("/author/{id}", name="show_author")
     */
    public function show(Author $author) : Response
    {
       
        
        return $this->render('author/show.html.twig',
                                 ['author' => $author]);
    }
    
    
    
   
}
