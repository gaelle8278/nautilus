<?php

namespace App\Controller;

use App\Form\BookType;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SearchBookType;

class BookController extends AbstractController
{
    /**
     * @Route("/book/add", name="add_book")
     */
    public function add(Request $request)
    {
        //form creation
        $book = new Book();
        $form = $this->createForm(BookType::class,$book);
        
        //process form from request
        $form->handleRequest($request);
        
        //behind the scene isValid() method use Validator component
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            
            $this->addFlash('info', 'Livre enregistré !');
            return $this->redirectToRoute('add_book');
        }
        
        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    
    /**
     * @Route("/book/{id}", name="show_book")
     */
    public function show(Book $book) : Response
    {
        return $this->render('book/show.html.twig',
            ['book' => $book]);
    }
    
    /**
     * @Route("/search", name="search_book")
     */
    public function search(Request $request) {
        $books=[];
        
        
        $form = $this->createForm(SearchBookType::class);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "term" keys
            $data = $form->getData();
            $books= $this->getDoctrine()->getRepository(Book::class)->findBookByLikeAuhtorLastname($data["term"]);
            
            
        }
        
        return $this->render('book/search.html.twig', [
            'form' => $form->createView(),
            'books' => $books
        ]);
        
    }
    
    
    
}
