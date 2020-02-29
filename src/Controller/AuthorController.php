<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Form\AuthorType;


class AuthorController extends AbstractController
{
    /**
     * @Route("/author/add", name="add_author", methods={"POST"})
     *
     */
    public function save(Request $request) : RedirectResponse
    {
        //form creation
        $author = new Author();
        $form = $this->createForm(AuthorType::class,$author);
        
        //process form from request
        $form->handleRequest($request);
        
        //behind the scene isValid() method use Validator component
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();
            
            
        }
        
        return $this->redirectToRoute('list_book');
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
