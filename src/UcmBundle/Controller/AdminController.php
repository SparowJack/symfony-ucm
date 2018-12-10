<?php

namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Typemenu;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminController extends Controller
{
    
    public function indexAction(Request $request)
    {

    	/*
    	* Par defaut on va  afficher la  liste du typemenu
    	* dans index admin
    	*/
    	//Repository Typemenu
    	$repository = $this->getDoctrine()->getRepository(Typemenu::class);
        //if(){

        //}else{
        $queryAll = $repository->findAll();
        //}
    	
    	

        $typemenu  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }
        $data = array('titre'=>'Type Menu liste',
                      'typemenu'=>$typemenu,
                      'deleteForms'=>$deleteForms,
                      'isTypemenu'=>'active');

        return $this->render('UcmBundle:Admin:index.html.twig',$data);
    }

    public function addtypemenuAction(Request $request)
    {
        $typemenu = new Typemenu();

        //créer le formulaire
        $form = $this->createFormBuilder($typemenu)
                                ->add('libelle', 'text')
                                ->add('description', 'textarea',array('required' => false))
                                ->add('active','checkbox',array('required' => false))
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($typemenu);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Type menu bien enregistré.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_typemenu_list', array('id' => $typemenu->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addtypemenu.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter un type menu',
          'isTypemenu'=>'active'
        ));

    }


    public function edittypemenuAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $typemenu = $em->getRepository('UcmBundle:Typemenu')->find($id);
        if (!$typemenu) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }

        //créer le formulaire
        $form = $this->createFormBuilder($typemenu)
                                ->add('libelle', 'text')
                                ->add('description', 'textarea',array('required' => false))
                                ->add('active','checkbox',array('required' => false))
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Type menu mis à jour.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_typemenu_list', array('id' => $typemenu->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addtypemenu.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Modifier un type menu',
          'isTypemenu'=>'active'
        ));


    }

    public function deletetypemenuAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $typemenu = $em->getRepository('UcmBundle:Typemenu')->find($id);
 
            if (!$typemenu) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }
    
            $em->remove($typemenu);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Type menu supprimé.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_typemenu_list'));
    }   

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                    ->add('id', 'hidden')
                    ->getForm();
    }

   

    public function createAction()
    {
    	//Entity Manager: (instance de la base de données)
    	$entityManager = $this->getDoctrine()->getManager();
    	//Instance de l'entité
    	// $typemenu = new Typemenu();
    	// $typemenu->setLibelle("menu_first_top");
    	// $typemenu->setDescription("ce menu s'affiche tout en heut de la page");
    	// $typemenu->setActive(true);

    	$typemenu = new Typemenu();
    	$typemenu->setLibelle("menu_third_top");
    	$typemenu->setDescription("ce menu s'affiche comme etant un menu fils du second menu");
    	$typemenu->setActive(true);


    	// $typemenu = new Typemenu();
    	// $typemenu->setLibelle("menu_second_top");
    	// $typemenu->setDescription("ce menu s'affiche en deuxième de la page");
    	// $typemenu->setActive(true);

    	//Persister l'entité pour qu'il soit inserer à la base de données
    	$entityManager->persist($typemenu);
    	//Inserer à la base de données
    	$entityManager->flush();
    	return new Response('Saved new product with id ');
    }


}