<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\FormAuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Component\HttpFoundation\Request; 

#[Route('/author')]
final class AuthorController extends AbstractController
{

    //affichage de tous les authors
    #[Route('/', name: 'authors')]
    public function getAuthors(AuthorRepository $repo): Response
    {   
        //récupérer les auteurs
        $authors=$repo->findAll();
        //envoie la liste des auteurs à Twig
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }



    //ajout statique
    #[Route('/add', name: 'author_add')]
    public function addAuthor(EntityManagerInterface $mr): Response
    {
        $author=new Author();
        $author->setUsername('abouelkassem');
        $author->setEmail("abouelkasse@gmaill.com");
        $author->setNbBooks(5);

        $mr->persist($author);
        //sauvegarder dans la BD
        $mr->flush();

        return $this->redirectToRoute('authors');
    }

    //ajout avec formulaire 
    #[Route('/insert', name:'author_insert')]
    public function insertAuthor(EntityManagerInterface $mr , Request $request):Response
    {
        $author=new Author();
        //créer le formulaire
        $form=$this->createForm(FormAuthorType::class, $author);
        //traiter la requete
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $mr->persist($author);
            $mr->flush();
            return $this->redirectToRoute('authors');
        }
        return $this->render('author/form.html.twig',[
            'authorForm'=>$form->createView(),
        ]);

    }

    //supprimer un auteur
    #[Route('/delete/{id}', name:'author_delete')]
    public function delete(EntityManagerInterface $mr , $id): Response
    {
        $author=$mr->getRepository(Author::class)->find($id);
        $mr->remove($author);
        $mr->flush();

        return $this->redirectToRoute('authors');
    }

    //editer un auteur 
    #[Route('/update/{id}', name:'author_update')]
    public function update(EntityManagerInterface $mr , $id , Request $request):Response 
    {
        $author =$mr->getRepository(Author::class)->find($id);
        $form=$this->createForm(FormAuthorType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $mr->persist($author);
            $mr->flush();
            return $this->redirectToRoute('authors');
        }
        return $this->render('author/form.html.twig',[
            'authorForm'=>$form->createView(),
        ]);
        
    }

    




    
}


