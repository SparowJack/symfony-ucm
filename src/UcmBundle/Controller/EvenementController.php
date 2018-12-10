<?php

namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Evenement;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EvenementController extends Controller
{
	public function indexAction(Request $request){
		$repository = $this->getDoctrine()->getRepository(Evenement::class);
        $queryAll = $repository->findAll();
        $evenement  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        } 
		 $data = array('titre'=>'Evenement Liste',
                      'evenements'=>$evenement,
                      'deleteForms'=>$deleteForms,
                      'isEvenement' => 'active');   
		 return $this->render('UcmBundle:Admin:evenement.html.twig',$data);
	}


	public function addAction(Request $request)
    {
        $evenement = new Evenement();

        //créer le formulaire
        $form = $this->createFormBuilder($evenement)
                                ->add('libelle', 'text')
                                ->add('description', TextareaType::class,
                                                  		array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      )) 
                                ->add('date', 'datetime') 
                                ->add('categorie', 'entity',
                                                    array(
                                                        'class' => 'UcmBundle:Categorie',
                                                        'required' => false, 
                                                        'empty_value' => '',
                                                        'label' => 'Categorie', 
                                                    )
                                                )                        
                               
                                ->add('actif','checkbox',array('required' => false))
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($evenement);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Evenement bien enregistré.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_evenement_list', array('id' => $evenement->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addevenement.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter un evenement',
          'isEvenement' => 'active'
        ));

    }


    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository('UcmBundle:Evenement')->find($id);
        if (!$evenement) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }

        //créer le formulaire
        $form = $this->createFormBuilder($evenement)
                                ->add('libelle', 'text')
                                ->add('description', 'textarea',array('required' => false))                               
                               ->add('date', 'datetime')
                                ->add('categorie', 'entity',
                                                    array(
                                                        'class' => 'UcmBundle:Categorie',
                                                        'required' => false, 
                                                        'empty_value' => '',
                                                        'label' => 'Categorie', 
                                                    )
                                                )
                                ->add('actif','checkbox',array('required' => false))
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Evenement mis à jour.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_evenement_list', array('id' => $evenement->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addevenement.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Modifier un evenement',
          'isEvenement' => 'active'
        ));


    }


	public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $evenement = $em->getRepository('UcmBundle:Evenement')->find($id);
 
            if (!$evenement) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }
    
            $em->remove($evenement);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Evenement supprimé.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_evenement_list'));
    }   

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                    ->add('id', 'hidden')
                    ->getForm();
    }
}