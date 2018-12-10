<?php
namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UcmBundle\Entity\Configuration;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConfigurationController extends Controller
{
	public function indexAction(Request $request){
		/*
    	* Par defaut on va  afficher la  liste du typemenu
    	* dans index admin
    	*/
    	//Repository Configuration
    	$repository = $this->getDoctrine()->getRepository(Configuration::class);           
        $queryAll = $repository->find(1);


        $data = array('titre'=>'Configuration generale du site',
                      'configs'=>$queryAll,                      
                      'isConfiguration'=>'active');         

        return $this->render('UcmBundle:Admin:configuration.html.twig',$data);
	}

    public function editConfigGeneraleAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();      
        $configInformation = $this->getDoctrine()->getManager()->getRepository("UcmBundle:Configuration")->findOneBy(array("id" => $id));
         

    

        //créer le formulaire
        $form = $this->createFormBuilder($configInformation)
                                ->add('email', 'text')
                                ->add('logo', 'text')
                                ->add('description', 'textarea',array('required' => false))  
                                ->add('administrateur', 'text')   
                                ->add('charte', 'text')     
                                ->add('description', 'textarea',array('required' => false)) 
                                ->add('adresse', 'text')    
                                ->add('nom_site', 'text')                  
                                ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                                ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $typemenu contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes        
        if ($form->isValid()) {
              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
              
              $em->flush();
              $request->getSession()->getFlashBag()->add('notice', 'Mis à jour de configuration avec succèes.');
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->redirect($this->generateUrl('ucm_admin', array('id' => $configInformation->getId())));
        }
        
            return $this->render('UcmBundle:Admin:editconfiguration.html.twig',
            array(
                'form' => $form->createView(),
                'titre' => 'Modifier  Configuration générale',
                'isConfiguration' => 'active'  
                )
            );
    }

}