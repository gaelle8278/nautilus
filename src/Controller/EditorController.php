<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Editor;
use App\Form\EditorType;

class EditorController extends AbstractController
{
    /**
     *  @Route("/editor/add", name="add_editor")
     */
    public function save(Request $request) {
    
        //form creation
        $editor = new Editor();
        $form = $this->createForm(EditorType::class,$editor);
    
        //process form from request
        $form->handleRequest($request);
    
        //behind the scene isValid() method use Validator component
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($editor);
            $entityManager->flush();
        
        
        }
    
        return $this->redirectToRoute('list_book');
        
    }
}

