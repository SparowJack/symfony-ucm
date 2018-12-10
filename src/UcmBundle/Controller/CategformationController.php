<?php

namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use UcmBundle\Entity\Categformation;

use UcmBundle\Form\CategformationType;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class CategformationController extends Controller{
	public function indexAction(Request $request){
		$repository = $this->getDoctrine()->getRepository(Categformation::class);
        $queryAll = $repository->findAll();
        $categformation  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }        
        $data = array('titre'=>'Categorie Information Liste',
                      'categformations'=>$categformation,
                      'deleteForms'=>$deleteForms,
                      'isRubrique' => 'active');                

        return $this->render('UcmBundle:Admin/formation/categformation:index.html.twig',$data);

	}

	public function addAction(Request $request){

		$categformation = new Categformation();
        //créer le formulaire        
        $form = $this->createForm(CategformationType::class, $categformation);                               

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($categformation);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Categorie formation bien enregistré.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_formation_categformation', array('id' => $categformation->getId())));
        }
        
        return $this->render('UcmBundle:Admin/formation/categformation:add.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter une categorie formation',
          'isDepartement' => 'active'
        ));
	}

	public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $categformation = $em->getRepository('UcmBundle:Categformation')->find($id);
        if (!$categformation) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }
        $form = $this->createForm(CategformationType::class, $categformation);
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($categformation);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Categorie formation modifié.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_formation_categformation', array('id' => $categformation->getId())));
        }
        
        return $this->render('UcmBundle:Admin/formation/categformation:add.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter une categorie formation',
          'isDepartement' => 'active'
        ));
		
	}

	public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $categformation = $em->getRepository('UcmBundle:Categformation')->find($id);
 
            if (!$categformation) {
                throw $this->createNotFoundException('Unable to find Categorie formation entity.');
            }
    
            $em->remove($categformation);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Categorie formation supprimé.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_formation_categformation'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                    ->add('id', 'hidden')
                    ->getForm();
    }

}