<?php
	namespace UcmBundle\Controller;
	
	use UcmBundle\Entity\Motdirecteur;
	use UcmBundle\Entity\Directeur;
	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	Use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	Use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	Use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	
	Use Symfony\Component\Form\Extension\Core\Type\FileType;
	
	class MotDirecteurController extends Controller {
		
		public function indexAction(request $request){
			$repository = $this->getDoctrine()->getRepository(Motdirecteur::class);
			$queryAll = $repository->findAll();
			
			$motdirecteurs  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        
			$data = array(
					'motdirecteurs' => $motdirecteurs,
					'isMotdirecteur' => 'active',
					'titre' => 'Liste des mots de directeur',
					);
			
			return $this->render('UcmBundle:Admin:motdirecteur.html.twig', $data);			
		}
		
		public function rechercheMotdirecteurAction(Request $request){
			$form = $this->createFormBuilder(null)
					->add('recherche', TextType::class)
					->add('save', SubmitType::class, array(
						'label' => 'Rechercher',	
						/*'attr' => array(
							'class' => 'btn btn-primary btn-sm'
						),*/
						
					))
					->getForm();			
		
			return $this->render('UcmBundle:Admin:recherche.html.twig',
					[
						'form' => $form->createView(),
						'route_recherche' => "ucm_admin_motdirecteur_execute_search"
					]
					);		
		}
		
		public function executerRechercheAction(Request $request){
			
			$em = $this->getDoctrine()->getManager();
			$message = "resultat de recherche";
			$param = $request->request->get('form');
			$critere = $param['recherche'];			
			
			$queryAll = $this->getDoctrine()
						->getRepository(Motdirecteur::class)
						->rechercheMotdirecteur($critere);
						
			$motdirecteurs  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        			
			$data = array(
				'motdirecteurs' => $motdirecteurs,
				'isMotdirecteur' => 'active',
				'titre' => $message
			);	
			
			return $this->render('UcmBundle:Admin:motdirecteur.html.twig', $data);			
		}
		
		public function addMotdirecteurAction(Request $request){
			$motdirecteur = new Motdirecteur();
			
			//créer formulaire
			$form = $this->createFormBuilder($motdirecteur)
					->add('titre', 'text')
					->add('contenu', TextareaType::class,
						array(
							'required' => true,
							'attr' => array(
								'class' => 'tynimce',
								'data_theme' => 'medium',
							)
						)
					)
					->add('directeur', 'entity', 
						array(
							'class' => 'UcmBundle\Entity\Directeur',
							'property' => 'nom'
						)
					)
					->add('actif','checkbox',array('required' => false))
					->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
					->getForm();
					
			$form->handleRequest($request);
			
			// On vérifie que les valeurs entrées sont correctes        
			if ($form->isValid()) {
				// On l'enregistre notre objet $typemenu dans la base de données, par exemple
				$em = $this->getDoctrine()->getManager();				
				$em->persist($motdirecteur);
				$em->flush();
				$request->getSession()->getFlashBag()->add('notice', 'Actualité bien enregistré.');
				// On redirige vers la page de visualisation de l'annonce nouvellement créée
				return $this->redirect($this->generateUrl('ucm_admin_motdirecteur_list', array('id' => $motdirecteur->getId())));
			}				
					
					
			return $this->render('UcmBundle:Admin:addmotdirecteur.html.twig', array(
				'form' => $form->createView(),
				'titre' => 'Ajout un mot de directeur',
				'isMotDirecteur'=>'active'
			));		
		}
		
		public function editMotdirecteurAction(Request $request, $id){
			$em = $this->getDoctrine()->getManager();
			$motdirecteur = $em->getRepository(Motdirecteur::class)->findOneBy(array('id' => $id));
			
			if( !$motdirecteur ){
				throw $this->createNotFoundException(
					'No news found for id ' . $id
				);	
			}
			
			//créer formulaire
			$form = $this->createFormBuilder($motdirecteur)
					->add('titre', 'text')
					->add('contenu', TextareaType::class,
						array(
							'required' => true,
							'attr' => array(
								'class' => 'tynimce',
								'data_theme' => 'medium',
							)
						)
					)
					->add('directeur', 'entity', 
						array(
							'class' => 'UcmBundle\Entity\Directeur',
							'property' => 'nom'
						)
					)
					->add('actif','checkbox',array('required' => false))
					->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
					->getForm();
					
			$form->handleRequest($request);
			
			// On vérifie que les valeurs entrées sont correctes        
			if ($form->isValid()) {
				// On l'enregistre notre objet $typemenu dans la base de données, par exemple
				$em = $this->getDoctrine()->getManager();				
				$em->persist($motdirecteur);
				$em->flush();
				$request->getSession()->getFlashBag()->add('notice', 'mot de directeur bien enregistré.');
				// On redirige vers la page de visualisation de l'annonce nouvellement créée
				return $this->redirect($this->generateUrl('ucm_admin_motdirecteur_list', array('id' => $motdirecteur->getId())));
			}				
					
					
			return $this->render('UcmBundle:Admin:addmotdirecteur.html.twig', array(
				'form' => $form->createView(),
				'titre' => 'Ajout un mot de directeur',
				'isMotDirecteur'=>'active'
			));		
		}
		
		public function showDeleteMotdirecteurAction(Request $request){
			
			$em = $this->getDoctrine()->getManager();
			
			$motdirecteur = $em->getRepository(Motdirecteur::class)->find( $request->request->get('id'));
			$message = "Voulez-vous vraiment supprimmer le mot du directeur ".$motdirecteur->getTitre();
			
			
			
			$l_btnConfirm = "<button class='btn btn-primary' data-dismiss='modal' onclick='deleteMotDirecteur(" . $request->request->get('id') . ");'>Supprimer</button>";
			
			return $this->render('UcmBundle:Admin:confirmdelete.html.twig',
				array(
					'titre'=>"Suppression d'un mot directeur",
					'message'=>$message,
					'l_btnConfirm'=>$l_btnConfirm
					)
			);
		}
		
		public function deleteMotdirecteurAction(Request $request){
			$l_emEntityManager = $this->getDoctrine()->getManager();
			$motdirecteur = $l_emEntityManager->getRepository(Motdirecteur::class)
				->findOneBy(array("id" => $request->request->get('id')));
				
			$l_emEntityManager->remove($motdirecteur);
			$l_emEntityManager->flush();
			
			
			return $this->redirect($this->generateUrl('ucm_admin_motdirecteur_list'));
		}
		
		
		
	}
?>	