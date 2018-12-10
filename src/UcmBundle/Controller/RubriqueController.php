<?php

namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use UcmBundle\Entity\Rubrique;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class RubriqueController extends Controller{

	public function indexAction(Request $request){	
    	  	
        $repository = $this->getDoctrine()->getRepository(Rubrique::class);
        $queryAll = $repository->findAll();
        $rubrique  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }        
        $data = array('titre'=>'Rubrique Liste',
                      'rubriques'=>$rubrique,
                      'deleteForms'=>$deleteForms,
                      'isRubrique' => 'active');                

        return $this->render('UcmBundle:Admin:rubrique.html.twig',$data);
	}

  public function addAction(Request $request)
    {
        $rubrique = new Rubrique();

        //créer le formulaire
        $form = $this->createFormBuilder($rubrique)
                                ->add('libelle', 'text')
                                ->add('url', 'text',array('required'=> true))                                
                                ->add('layout', ChoiceType::class, array(
                                                        'choices'  => array(
                                                            'layout_front.html.twig' => 'Page home',
                                                            'layout_etudiant.html.twig' => 'Page etudiant',
                                                            'layout_evenement.html.twig' => 'Page evenement',
                                                            'layout_fiche.html.twig' => 'Page fiche',
                                                            'layout_formation.html.twig' => 'Page formation'                                                          
                                                        ),))
                                ->add('description1', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme'=>'bbcode')
                                                      )) 
                                ->add('description2', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      )) 

                                ->add('description3', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      )) 
                                ->add('description3', TextareaType::class,
                                                  array('required' => false,                                                        
                                                      ))
                                ->add('image1', FileType::class,
                                                  array('required' => false,                                                        
                                                      ))
                                ->add('image2', FileType::class,
                                                  array('required' => false,                                                        
                                                      )) 
                                ->add('image3', FileType::class,
                                                  array('required' => false,                                                        
                                                      ))
                               ->add('categorie', 'entity',
                                                    array(
                                                        'class' => 'UcmBundle:Categorie',
                                                        'required' => false, 
                                                        'empty_value' => '',
                                                        'label' => 'Type', 
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

              $file = $rubrique->getImage1();
              $file1 = $rubrique->getImage2();
              $file2 = $rubrique->getImage3();
              $fileName= '';
              $fileName1 = '';
              $fileName2  = '';


              if($file){
                  $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                   $file->move(
                    $this->getParameter('rubrique_directory'),
                    $fileName
                );
              }
              if($file1){
                $fileName1 = $this->generateUniqueFileName().'.'.$file->guessExtension();
                 $file1->move(
                  $this->getParameter('rubrique_directory'),
                  $fileName1
              );
              }
              if($file2){
                $fileName2 = $this->generateUniqueFileName().'.'.$file->guessExtension();
                $file2->move(
                  $this->getParameter('rubrique_directory'),
                  $fileName2
              );
              }
              
              
              

              // moves the file to the directory where brochures are stored
             
             

              


              // updates the 'brochure' property to store the PDF file name
              // instead of its contents
              $rubrique->setImage1($fileName);
              $rubrique->setImage2($fileName1);
              $rubrique->setImage3($fileName2);

              $em->persist($rubrique);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Rubrique bien enregistrée.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_rubrique_list', array('id' => $rubrique->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addrubrique.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter une rubrique',
          'isRubrique' => 'active'
        ));

    }

    


    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $rubrique = $em->getRepository('UcmBundle:Rubrique')->find($id);
        if (!$rubrique) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }

        //créer le formulaire
        $form = $this->createFormBuilder($rubrique)
                                ->add('libelle', 'text')
                                ->add('url', 'text')
                                 ->add('layout', ChoiceType::class, array(
                                                        'choices'  => array(
                                                            'layout_front.html.twig' => 'Page home',
                                                            'layout_etudiant.html.twig' => 'Page etudiant',
                                                            'layout_evenement.html.twig' => 'Page evenement',
                                                            'layout_fiche.html.twig' => 'Page fiche',
                                                            'layout_formation.html.twig' => 'Page formation'                                                          
                                                        ),))
                                ->add('description1', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      )) 
                                ->add('description2', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      )) 

                                ->add('description3', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      )) 
                                ->add('description3', TextareaType::class,
                                                  array('required' => false,                                                        
                                                      ))
                                ->add('image1', FileType::class,
                                                  array('required' => false, 'data_class' => null                                                      
                                                      ))
                                ->add('image2', FileType::class,
                                                  array('required' => false,  'data_class' => null                                                       
                                                      )) 
                                ->add('image3', FileType::class,
                                                  array('required' => false,   'data_class' => null                                                      
                                                      ))
                               ->add('categorie', 'entity',
                                                    array(
                                                        'class' => 'UcmBundle:Categorie',
                                                        'required' => false, 
                                                        'empty_value' => '',
                                                        'label' => 'Type', 
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

              /////////////////////////
              $file = $rubrique->getImage1();
              $file1 = $rubrique->getImage2();
              $file2 = $rubrique->getImage3();

              
              
              

              // moves the file to the directory where brochures are stored

              if($file !=""){
                    $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                    $file->move(
                        $this->getParameter('rubrique_directory'),
                    $fileName
                    );
                    $rubrique->setImage1($fileName);
              }


              if($file1 != ""){
                  $fileName1 = $this->generateUniqueFileName().'.'.$file->guessExtension();
                  $file1->move(
                      $this->getParameter('rubrique_directory'),
                      $fileName1
                  );
                  $rubrique->setImage2($fileName1);
              }
              
              if($file2 != ""){
                $fileName2 = $this->generateUniqueFileName().'.'.$file->guessExtension();
                  $file2->move(
                  $this->getParameter('rubrique_directory'),
                  $fileName2
                  );
                  $rubrique->setImage3($fileName2);
              }



              // updates the 'brochure' property to store the PDF file name
              // instead of its contents
              
              //$rubrique->setImage2($fileName1);
              //$rubrique->setImage3($fileName2);
              /////////////////////////
              
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Catégorie mis à jour.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_rubrique_list', array('id' => $rubrique->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addrubrique.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Modifier une rubrique',
          'isRubrique' => 'active'
        ));


    }

    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $rubrique = $em->getRepository('UcmBundle:Rubrique')->find($id);
 
            if (!$rubrique) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }
    
            $em->remove($rubrique);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Rubrique supprimée.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_rubrique_list'));
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