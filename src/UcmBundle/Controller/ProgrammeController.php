<?php

namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use UcmBundle\Entity\Programme;
use UcmBundle\Form\ProgrammeType;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProgrammeController extends Controller{

	public function indexAction(Request $request){
		    $repository = $this->getDoctrine()->getRepository(Programme::class);
        $queryAll = $repository->findAll();
        $programme  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }        
        $data = array('titre'=>'Proramme Liste',
                      'programmes'=>$programme,
                      'deleteForms'=>$deleteForms,
                      'isProgramme' => 'active');                

        return $this->render('UcmBundle:Admin/formation/programme:index.html.twig',$data);

	}

	public function addAction(Request $request){

		    $programme = new Programme();
        //créer le formulaire        
        $form = $this->createForm(ProgrammeType::class, $programme);                               

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($programme);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Programme bien enregistrée.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_formation_programme', array('id' => $programme->getId())));
        }
        
        return $this->render('UcmBundle:Admin/formation/programme:add.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter un programme',
          'isFormation' => 'active'
        ));
	}

	public function editAction($id,Request $request){
        $em = $this->getDoctrine()->getManager();
        $programme = $em->getRepository('UcmBundle:Programme')->find($id);
        if (!$programme) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }
        //créer le formulaire        
        $form = $this->createForm(ProgrammeType::class, $programme);                               

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($programme);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Programme bien enregistrée.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_formation_programme', array('id' => $programme->getId())));
        }
        
        return $this->render('UcmBundle:Admin/formation/programme:add.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'modifier un programme',
          'isFormation' => 'active'
        ));
		
	}

	public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $programme = $em->getRepository('UcmBundle:Programme')->find($id);
 
            if (!$programme) {
                throw $this->createNotFoundException('Unable to find Programme entity.');
            }
    
            $em->remove($programme);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Programme supprimée.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_formation_programme'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                    ->add('id', 'hidden')
                    ->getForm();
    }
}