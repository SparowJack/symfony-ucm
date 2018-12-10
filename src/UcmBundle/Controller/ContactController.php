<?php

namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use UcmBundle\Entity\Typemenu;

use UcmBundle\Entity\Contact;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ContactController extends Controller{

	public function indexAction(Request $request){
		/*
    	* Par defaut on va  afficher la  liste du typemenu
    	* dans index admin
    	*/
    	//Repository Banniere
    	$repository = $this->getDoctrine()->getRepository(Contact::class);
    	$queryAll = $repository->findAll();
    	

        $contact  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }
        $data = array('titre'=>'Contact liste',
                      'contacts'=>$contact,
                      'deleteForms'=>$deleteForms,
                      'isContact'=>'active');

        return $this->render('UcmBundle:Admin:contact.html.twig',$data);
	}

   public function addAction(Request $request)
    {
        $contact = new Contact();

        //créer le formulaire
        $form = $this->createFormBuilder($contact)                                
                                ->add('nom', 'text') 
                                ->add('email', 'text') 
                                ->add('sujet', 'text')                
                                
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($contact);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Contact bien enregistré.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_contact_list', array('id' => $contact->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addcontact.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter un contact',
          'isContact' => 'active'
        ));

    }


    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('UcmBundle:Contact')->find($id);
        if (!$contact) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }

        //créer le formulaire
        $form = $this->createFormBuilder($contact)
                                ->add('nom', 'text') 
                                ->add('email', 'text') 
                                ->add('sujet', 'text')  
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Contact mis à jour.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_contact_list', array('id' => $contact->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addcontact.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Modifier un Contact',
          'isContact' => 'active'
        ));


    }

    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $contact = $em->getRepository('UcmBundle:Contact')->find($id);
 
            if (!$contact) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }
    
            $em->remove($contact);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Contact supprimé.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_contact_list'));
    }   

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                    ->add('id', 'hidden')
                    ->getForm();
    }



}