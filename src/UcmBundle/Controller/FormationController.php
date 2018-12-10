<?php

namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use UcmBundle\Entity\Formation;
use UcmBundle\Form\FormationType;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class FormationController extends Controller{

	public function indexAction(Request $request){
		$repository = $this->getDoctrine()->getRepository(Formation::class);
        $queryAll = $repository->findAll();
        $formation  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }        
        $data = array('titre'=>'Formation Liste',
                      'formations'=>$formation,
                      'deleteForms'=>$deleteForms,
                      'isRubrique' => 'active');                

        return $this->render('UcmBundle:Admin/formation/formation:index.html.twig',$data);

	}

	public function addAction(Request $request){

		    $formation = new Formation();
        //créer le formulaire        
        $form = $this->createForm(FormationType::class, $formation);                               

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($formation);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Formation bien enregistrée.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_formation_formation', array('id' => $formation->getId())));
        }
        
        return $this->render('UcmBundle:Admin/formation/formation:add.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter une formation',
          'isFormation' => 'active'
        ));
	}

	public function editAction(Request $request,$id){
    $em = $this->getDoctrine()->getManager();
        $formation = $em->getRepository('UcmBundle:Formation')->find($id);
        if (!$formation) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }

        //créer le formulaire
        $form = $this->createForm(FormationType::class, $formation);   

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Catégorie mis à jour.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_formation_formation', array('id' => $formation->getId())));
        }

        
        return $this->render('UcmBundle:Admin/formation/formation:add.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Modifier une formation',
          'isFormation' => 'active'
        ));
		
	}

	public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $formation = $em->getRepository('UcmBundle:Formation')->find($id);
 
            if (!$formation) {
                throw $this->createNotFoundException('Unable to find Formation entity.');
            }
    
            $em->remove($formation);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Formation supprimée.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_formation_formation'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                    ->add('id', 'hidden')
                    ->getForm();
    }
}