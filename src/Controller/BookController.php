<?php

namespace App\Controller;

use App\Form\AuthorType;
use App\Form\BookType;
use App\Form\EditorType;
use App\Entity\Book;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SearchBookType;
use App\Entity\Editor;

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
    public function show($id) : Response
    {
        $book = $this->getDoctrine()->getRepository(Book::class)
        ->findWithAllInfos($id);
        
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
    
    /**
     * @Route("/books/{page}", name="list_book", requirements={"page"="\d+"}, methods={"GET"})
     */
    public function list(int $page = 1) : Response
    {
        
        // get author form
        $formAuthor = $this->createForm(AuthorType::class, new Author(), [
            'action' => $this->generateUrl('add_author')
            
        ]);
        
        // get editor form
        $formEditor = $this->createForm(EditorType::class, new Editor(), [
            'action' => $this->generateUrl('add_editor')
            
        ]);
        
        //get books list
        $nbBooksByPage = $this->getParameter('app.books.pagination');
        $books = $this->getDoctrine()->getRepository(Book::class)
        ->findAllPagined($page, $nbBooksByPage);
        
        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($books) / $nbBooksByPage),
            'nomRoute' => 'list_book',
            'paramsRoute' => array()
        );
        
        return $this->render('book/list.html.twig',
            [
                'books' => $books,
                'pagination' => $pagination,
                'formAuthor' => $formAuthor->createView(),
                'formEditor' => $formEditor->createView()
            ]);
    }
    
    
    
}
