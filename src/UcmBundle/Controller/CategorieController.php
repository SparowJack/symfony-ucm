<?php

namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use UcmBundle\Entity\Typemenu;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CategorieController extends Controller{

	public function indexAction(Request $request)
    {
      	/*
      	* Par defaut on va  afficher la categorie
      	* dans index admin
      	*/ 
    	  	
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $queryAll = $repository->findAll();
        $categorie  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }        
        $data = array('titre'=>'Catégorie Liste',
                      'categories'=>$categorie,
                      'deleteForms'=>$deleteForms,
                      'isCategorie' => 'active');                

        return $this->render('UcmBundle:Admin:categorie.html.twig',$data);
    }

    public function addcategorieAction(Request $request)
    {
        $categorie = new Categorie();

        //créer le formulaire
        $form = $this->createFormBuilder($categorie)
                                ->add('libelle', 'text')
                                ->add('url', 'text')
                                
                                ->add('modele_page', ChoiceType::class, array(
                                                        'choices'  => array(
                                                            'home' => 'Home',
                                                            'evenement' => 'Evenement',
                                                            'formation' => 'Formation',
                                                            'etudiant' => 'Etudiant',
                                                            'fiche' => 'Fiche',
                                                                                                                     
                                                        ),))
                                ->add('description', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      ))                               
                               ->add('typemenu', 'entity',
                                                    array(
                                                        'class' => 'UcmBundle:Typemenu',
                                                        'required' => true, 
                                                        'empty_value' => '',
                                                        'label' => 'Type', 
                                                    )
                                                )
                               ->add('parentId', 'entity',
                                                    array(
                                                        'class' => 'UcmBundle:Categorie',
                                                        'required' => false, 
                                                        'empty_value' => '',
                                                        'label' => 'Type', 
                                                    )
                                                )
                                ->add('actif','checkbox',array('required' => false))
                                ->add('sequence',IntegerType::class,array('required' => false))
                                ->add('image', FileType::class,
                                                  array('required' => false, 'data_class' => null                                                          
                                                      ))
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();

              $file = $categorie->getImage();
              $fileName = '';
              if($file){
                  $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
              }
              
              if($file){
                    $file->move(
                        $this->getParameter('categorie_directory'),
                        $fileName
                    );
                    $categorie->setImage($fileName);
              }
              


              $em->persist($categorie);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Catégorie bien enregistré.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_categorie_list', array('id' => $categorie->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addcategorie.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter une catégorie',
          'isCategorie' => 'active'
        ));

    }


    public function editcategorieAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('UcmBundle:Categorie')->find($id);
        if (!$categorie) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }

        //créer le formulaire
        $form = $this->createFormBuilder($categorie)
                                ->add('libelle', 'text')
                                ->add('url', 'text')
                                
                                ->add('modele_page', ChoiceType::class, array(
                                                        'choices'  => array(
                                                            'home' => 'Home',
                                                            'evenement' => 'Evenement',
                                                            'formation' => 'Formation',
                                                            'etudiant' => 'Etudiant',
                                                            'fiche' => 'Fiche',
                                                                                                                     
                                                        ),))
                                ->add('description', 'textarea',array('required' => false))                               
                               ->add('typemenu', 'entity',
                                                    array(
                                                        'class' => 'UcmBundle:Typemenu',
                                                        'required' => true, 
                                                        'empty_value' => '',
                                                        'label' => 'Type', 
                                                    )
                                                )
                               ->add('parentId', 'entity',
                                                    array(
                                                        'class' => 'UcmBundle:Categorie',
                                                        'required' => false, 
                                                        'empty_value' => '',
                                                        'label' => 'Type', 
                                                    )
                                                )
                                ->add('actif','checkbox',array('required' => false))
                                ->add('image', FileType::class,
                                                  array('required' => false, 'data_class' => null                                                          
                                                      ))
                                ->add('sequence',IntegerType::class,array('required' => false))
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple

              /////////////////////////
              $file = $categorie->getImage();
              $fileName = '';
              if($file){
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
              }
              
              

              // moves the file to the directory where brochures are stored

              if($file !=""){
                    $file->move(
                        $this->getParameter('categorie_directory'),
                    $fileName
                    );
                    $categorie->setImage($fileName);
              }


              
              
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Catégorie mis à jour.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_categorie_list', array('id' => $categorie->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addcategorie.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Modifier une catégorie',
          'isCategorie' => 'active'
        ));


    }



    public function deletecategorieAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $categorie = $em->getRepository('UcmBundle:Categorie')->find($id);
 
            if (!$categorie) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }
    
            $em->remove($categorie);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Categorie supprimé.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_categorie_list'));
    }   

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                    ->add('id', 'hidden')
                    ->getForm();
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

}