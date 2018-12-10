<?php

namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use UcmBundle\Entity\Departement;

use UcmBundle\Form\DepartementType;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class DepartementController extends Controller{

	public function indexAction(Request $request){
		$repository = $this->getDoctrine()->getRepository(Departement::class);
        $queryAll = $repository->findAll();
        $departement  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }        
        $data = array('titre'=>'Departement Liste',
                      'departements'=>$departement,
                      'deleteForms'=>$deleteForms,
                      'isRubrique' => 'active');                

        return $this->render('UcmBundle:Admin/formation/departement:index.html.twig',$data);

	}

	public function addAction(Request $request){

		    $departement = new Departement();
        //créer le formulaire        
        $form = $this->createForm(DepartementType::class, $departement);                               

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($departement);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Departement bien enregistré.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_formation_departement', array('id' => $departement->getId())));
        }
        
        return $this->render('UcmBundle:Admin/formation/departement:add.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter un departement',
          'isDepartement' => 'active'
        ));
	}

	public function editAction(Request $request,$id){
		    $em = $this->getDoctrine()->getManager();
        $departement = $em->getRepository('UcmBundle:Departement')->find($id);
        if (!$departement) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }
        $form = $this->createForm(DepartementType::class, $departement);
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($departement);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Departement modifié.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_formation_departement', array('id' => $departement->getId())));
        }
        
        return $this->render('UcmBundle:Admin/formation/departement:add.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter un departement',
          'isDepartement' => 'active'
        ));
	}

	public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $departement = $em->getRepository('UcmBundle:Departement')->find($id);
 
            if (!$departement) {
                throw $this->createNotFoundException('Unable to find Departement entity.');
            }
    
            $em->remove($departement);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Departement supprimé.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_formation_departement'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                    ->add('id', 'hidden')
                    ->getForm();
    }

}