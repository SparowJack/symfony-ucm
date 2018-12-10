<?php
namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Banniere;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class BanniereController extends Controller
{
	public function indexAction(Request $request){
		/*
    	* Par defaut on va  afficher la  liste du typemenu
    	* dans index admin
    	*/
    	//Repository Banniere
    	$repository = $this->getDoctrine()->getRepository(Banniere::class);
    	$queryAll = $repository->findAll();
    	

        $banniere  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }
        $data = array('titre'=>'Banniere liste',
                      'bannieres'=>$banniere,
                      'deleteForms'=>$deleteForms,
                      'isBanniere'=>'active');

        return $this->render('UcmBundle:Admin:banniere.html.twig',$data);
	}

    public function addAction(Request $request)
    {
        $banniere = new Banniere();

        //créer le formulaire
        $form = $this->createFormBuilder($banniere)                                
                                ->add('libelle', 'text')                                
                                ->add('banniere', FileType::class,
                                                  array('required' => false, 'data_class' => null                                                          
                                                      ))
                                ->add('rubrique', 'entity',
                                                    array(
                                                        'class' => 'UcmBundle:Rubrique',
                                                        'required' => false, 
                                                        'empty_value' => '',
                                                        'label' => 'Type', 
                                                    )
                                                )
                                ->add('description', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      ))           
                               
                                ->add('actif','checkbox',array('required' => false))
                                ->add('onpage','checkbox',array('required' => false))
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($banniere);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Bannière bien enregistré.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_banniere_list', array('id' => $banniere->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addbanniere.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter une bannière',
          'isBanniere' => 'active'
        ));

    }

    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $banniere = $em->getRepository('UcmBundle:Banniere')->find($id);
        if (!$banniere) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }

        //créer le formulaire
        $form = $this->createFormBuilder($banniere)
                                ->add('libelle', 'text')
                                ->add('banniere', FileType::class,
                                                  array('required' => false, 'data_class' => null                                                          
                                                      ))
                                 ->add('rubrique', 'entity',
                                                    array(
                                                        'class' => 'UcmBundle:Rubrique',
                                                        'required' => false, 
                                                        'empty_value' => '',
                                                        'label' => 'Type', 
                                                    )
                                                )
                                ->add('description', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      ))           
                               
                                ->add('actif','checkbox',array('required' => false))
                                ->add('onpage','checkbox',array('required' => false))
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Banniere mis à jour.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_banniere_list', array('id' => $banniere->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addbanniere.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Modifier une banniere',
          'isBanniere' => 'active'
        ));


    }


     public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $banniere = $em->getRepository('UcmBundle:Banniere')->find($id);
 
            if (!$banniere) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }
    
            $em->remove($banniere);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Banniere supprimé.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_banniere_list'));
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