<?php
namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Partenaire;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PartenaireController extends Controller
{
	public function indexAction(Request $request){
		/*
    	* Par defaut on va  afficher la  liste du typemenu
    	* dans index admin
    	*/
    	//Repository Partenaire
    	$repository = $this->getDoctrine()->getRepository(Partenaire::class);
    	$queryAll = $repository->findAll();
    	

        $partenaire  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        

        $deleteForms = array();

        foreach ($queryAll as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }
        $data = array('titre'=>'Partenaire liste',
                      'partenaires'=>$partenaire,
                      'deleteForms'=>$deleteForms,
                      'isPartenaire'=>'active');

        return $this->render('UcmBundle:Admin:partenaire.html.twig',$data);
	}

    public function addAction(Request $request)
    {
        $partenaire = new Partenaire();
        //créer le formulaire
        $form = $this->createFormBuilder($partenaire)                                
                                ->add('libelle', 'text')
                                ->add('logo', FileType::class,
                                                  array('required' => false, 'data_class' => null                                                          
                                                      ))
                                ->add('description', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      ))           
                               
                                ->add('actif','checkbox',array('required' => false))
                                ->add('url','text')
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $file = $partenaire->getLogo();
              $fileName  = '';

              if($file){
                  $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

              // moves the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('partenaire_directory'),
                    $fileName
                  );
              }

              

              // updates the 'brochure' property to store the PDF file name
              // instead of its contents
              $partenaire->setLogo($fileName);
              $em->persist($partenaire);
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Partenaire bien enregistré.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_partenaire_list', array('id' => $partenaire->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addpartenaire.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Ajouter un partenaire',
          'isPartenaire' => 'active'
        ));

    }

    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $partenaire = $em->getRepository('UcmBundle:Partenaire')->find($id);
        if (!$partenaire) {
          throw $this->createNotFoundException(
                  'No news found for id ' . $id
          );
        }

        //créer le formulaire
        $form = $this->createFormBuilder($partenaire)
                                ->add('libelle', 'text')
                                ->add('logo', 'text')
                                ->add('description', TextareaType::class,
                                                  array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme' => 'medium')
                                                      ))           
                               
                                ->add('actif','checkbox',array('required' => false))
                                ->add('url','text')
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Partenaire mis à jour.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin_partenaire_list', array('id' => $partenaire->getId())));
        }

        
        return $this->render('UcmBundle:Admin:addpartenaire.html.twig', array(
          'form' => $form->createView(),
          'titre' => 'Modifier un partenaire',
          'isPartenaire' => 'active'
        ));


    }


     public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $partenaire = $em->getRepository('UcmBundle:Partenaire')->find($id);            
 
            if (!$partenaire) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }
    
            $em->remove($partenaire);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Partenaire supprimé.');
        }
 
        return $this->redirect($this->generateUrl('ucm_admin_partenaire_list'));
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