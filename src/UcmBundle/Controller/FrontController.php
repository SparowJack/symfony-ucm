<?php

namespace UcmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

use UcmBundle\Entity\Categorie;
use UcmBundle\Entity\Rubrique;

use UcmBundle\Entity\Formation;
use UcmBundle\Entity\Programme;

use UcmBundle\Entity\Evenement;
use UcmBundle\Entity\Banniere;
use UcmBundle\Entity\Actualite;

use UcmBundle\Entity\Partenaire;

use UcmBundle\Entity\Motdirecteur;

use UcmBundle\Entity\Categformation;
use UcmBundle\Entity\Contact;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FrontController extends Controller
{
	public function indexAction(){
		
		$em = $this->getDoctrine()->getManager();

		$actualites = $this->getDoctrine()
			->getRepository(Actualite::class)
			->getActualities();		

        $banniere = $em->getRepository(Banniere::class)->findBy( ['actif' => true]);

        $partenaires = $this->getDoctrine()	->getRepository(Partenaire::class)->findAll();

        $evenements = $this->getDoctrine()
			->getRepository(Evenement::class)
			->getEvenements();

		$motdirecteur = $this->getDoctrine()
			->getRepository(Motdirecteur::class)
			->getMotDirecteur();

		$categformation = $em->getRepository(Categformation::class)->findBy( ['active' => true]);
		$formation = $em->getRepository(Formation::class)->findAll();

		$data = array('titre'=>'Front',
                      'bannieres'=>$banniere, 
                      'actualites' => $actualites, 
                      'partenaires'=>$partenaires,
                      'evenements'=>$evenements,
                      'motdirecteurs'=>$motdirecteur,
                      'categformations'=>$categformation,
                      'formations'=> $formation                            
                      );			
		return $this->render('UcmBundle:Front:index.html.twig',$data);
	}

	public function showAction(Request $request){
		$categorie = $request->get('id');
		$em = $this->getDoctrine()->getManager();		

        $rubrique = $em->getRepository(Rubrique::class)->findBy(array('categorie' => $categorie));

        $evenements = $this->getDoctrine()
			->getRepository(Evenement::class)
			->getEvenements();

		$categformation = $em->getRepository(Categformation::class)->findBy( ['active' => true]);
		$formation = $em->getRepository(Formation::class)->findAll();
        

		$data = array('titre'=>'Front',
                      'rubriques'=>$rubrique,
                      'layout'=>'layout',
                      'evenements'=>$evenements,
                      'categformations'=>$categformation,
                      'formations'=> $formation                       
                      );                        	
		return $this->render('UcmBundle:Front:show.html.twig',$data);
	}


	public function formationAction(Request $request){
		$categorie = $request->get('id');
		$em = $this->getDoctrine()->getManager();		

        $formation = $em->getRepository(Formation::class)->findBy(array('categorie' => $categorie));

        $id = 0;
		foreach($formation as $val){
			$id = $val->getId();
		}

		$programme = $em->getRepository(Programme::class)->findBy(array('formation' => $id));
        

		$data = array('titre'=>'Formation',
                      'formations'=>$formation,
                      'layout'=>'layout',
                      'programmes'=>$programme,                   
                      );                        	
		return $this->render('UcmBundle:Front:formation.html.twig',$data);

	}

	public function evenementAction(Request $request){
		$categorie = $request->get('id');
		$em = $this->getDoctrine()->getManager();

		$evenement = $em->getRepository(Evenement::class)->findBy( ['actif' => true],
    															   ['date' => 'DESC']);


		$data = array('titre'=>'Evenement',
                      'evenements'=>$evenement,
                      'layout'=>'layout',                                         
                      );                        	
		return $this->render('UcmBundle:Front:evenement.html.twig',$data);

	}

	public function tousevenementAction(){
		$em = $this->getDoctrine()->getManager();

		$evenement = $em->getRepository(Evenement::class)->findBy( ['actif' => true],
    															   ['date' => 'DESC']);


		$data = array('titre'=>'Evenement',
                      'evenements'=>$evenement,
                      'layout'=>'layout',                                         
                      );                        	
		return $this->render('UcmBundle:Front:evenement.html.twig',$data);

	}

	public function tousformationAction(Request $request){
		$em = $this->getDoctrine()->getManager();
		$search = [];
		if($request->get('accessible_en') || $request->get('discipline')){
			$search['accessible_en'] = $request->get('accessible_en');
			$search['discipline'] = $request->get('discipline');
			$formations = $em->getRepository(Formation::class)->findBySearch($search);
		}else{
			$formations = $em->getRepository(Formation::class)->findall();
		}
	
		
		

		$data = array('titre'=>'Evenement',
                      'formations'=>$formations,
                      'layout'=>'layout',                                         
                      );                        	
		return $this->render('UcmBundle:Front:allformation.html.twig',$data);

	}

    public function contactAction(){
    	return $this->render('UcmBundle:Front:contact.html.twig');
    }

    public function addcontactfrontAction(Request $request){
    		$contact = new Contact();
    		

	        // On vérifie que les valeurs entrées sont correctes        
	        if ($request) {
	              // On l'enregistre notre objet $typemenu dans la base de données, par exemple
	              $em = $this->getDoctrine()->getManager();
	              $nom = $request->get('nom');
	              $email = $request->get('email');
	              $sujet = $request->get('sujet');

	              $contact->setNom($nom);
	              $contact->setEmail($email);
	              $contact->setSujet($sujet);

	              $em->persist($contact);
	              $em->flush();
	              $request->getSession()->getFlashBag()->add('notice', 'Information bien envoyée.');
	              // On redirige vers la page de visualisation de l'annonce nouvellement créée
	              return $this->redirect($this->generateUrl('ucm_front_contact', array('id' => $contact->getId())));
	        }
    		return $this->render('UcmBundle:Front:contact.html.twig');

    }

    public function galerieAction(){
    	$em = $this->getDoctrine()->getManager();
    	$galerie = $em->getRepository(Banniere::class)->findAll();
    	$data = array('titre'=>'Galerie',
                      'bannieres'=>$galerie,
                      'layout'=>'layout',                                         
                      ); 
    	return $this->render('UcmBundle:Front:galerie.html.twig',$data); 
    }
}