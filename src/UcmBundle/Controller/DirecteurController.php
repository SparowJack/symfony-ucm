<?php
	
	namespace UcmBundle\Controller;
	
	use UcmBundle\Entity\Directeur;
	
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\response;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	
	Use Symfony\Component\Form\Extension\Core\Type\TextType;
	Use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	Use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	
	Use Symfony\Component\Form\Extension\Core\Type\FileType;
	
	
	
	class DirecteurController extends Controller{
		
		public function indexAction(Request $request){
			$repository = $this->getDoctrine()->getRepository(Directeur::class);
			$queryAll = $repository->findAll();			
			$directeurs  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        
			
			$data = array(
				'directeurs' => $directeurs,
				'isDirecteur' => 'active',
				'titre' => 'Liste des directeurs',
				
			);
			
			return $this->render('UcmBundle:Admin:directeur.html.twig', $data);
		}
		
		public function editDirecteurAction(Request $request, $id){
			
			$em = $this->getDoctrine()->getManager();
			$directeur = $em->getRepository(Directeur::class)->findOneBy(array('id' => $id));
			
			if (!$directeur) {
			  throw $this->createNotFoundException(
					  'No news found for id ' . $id
			  );
			}

			//créer le formulaire
			$form = $this->createFormBuilder($directeur)
									->add('nom', 'text')
									->add('prenom')
									->add('profile', FileType::class, 
										array(
										'required' => false,
										'data_class' => null
										)
									)
									->add('active','checkbox',array('required' => false))
									->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
									->getForm();

			// On fait le lien Requête <-> Formulaire
			// À partir de maintenant, la variable $actualite contient les valeurs entrées dans le formulaire par le visiteur
			$form->handleRequest($request);

			// On vérifie que les valeurs entrées sont correctes        
			if ($form->isValid()) {
				  // On l'enregistre notre objet $actualite dans la base de données, par exemple
				  
				  $em->flush();
				  $request->getSession()->getFlashBag()->add('notice', 'Mis à jour actualité avec succès.');
				  // On redirige vers la page de visualisation de l'annonce nouvellement créée
				  return $this->redirect($this->generateUrl('ucm_admin_directeur_list', array('id' => $directeur->getId())));
			}

			
			return $this->render('UcmBundle:Admin:adddirecteur.html.twig', array(
			  'form' => $form->createView(),
			  'titre' => 'Modifier un directeur',
			  'isDirecteur'=>'active'
			));
			
		}
		
		public function showDeleteDirecteurAction(Request $rqRequest){
			$l_emEntityManager = $this->getDoctrine()->getManager();
			$directeur = $l_emEntityManager->getRepository(Directeur::class)
				->findOneBy(array("id" => $rqRequest->request->get('id')));
				
			$message = "Voulez-vous vraiment supprimmer le directeur ".$directeur->getNom()."  ".$directeur->getPrenom();
			
			$l_btnConfirm = "<button class='btn btn-primary' data-dismiss='modal' onclick='deleteDirecteur(" . $rqRequest->request->get('id') . ");'>Supprimer</button>";
				
			return $this->render('UcmBundle:Admin:confirmdelete.html.twig',
				array(
					'titre'=>"Suppression d'un directeur",
					'message'=>$message,
					'l_btnConfirm'=>$l_btnConfirm,
					)
			);	
		}
		
		public function deleteDirecteurAction(Request $request){
			$l_emEntityManager = $this->getDoctrine()->getManager();
			$directeur = $l_emEntityManager->getRepository(Directeur::class)
				->findOneBy(array("id" => $request->request->get('id')));
				
			$l_emEntityManager->remove($directeur);
			$l_emEntityManager->flush();
			
			//$repository = $this->getDoctrine()->getRepository(Actualite::class)->findBy(array('estactif' => 1))	;
			//$actualites = $this->get('knp_paginator')->paginate($repository,$request->query->get('page', 1),6);        
			
			return $this->redirect($this->generateUrl('ucm_admin_directeur_list'));
		}
		
		public function rechercheDirecteurAction(Request $request){
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
						'route_recherche' => "ucm_admin_directeur_execute_search"
					]
					);		
		}
		
		public function executerRechercheAction(Request $request){
		
			$em = $this->getDoctrine()->getManager();
			$message = "";
			$param = $request->request->get('form');
			$critere = $param['recherche'];
			$queryAll = $this->getDoctrine()
				->getRepository(Directeur::class)
				->rechercheDirecteur($critere);
				
			$directeurs  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        
			
			if($directeurs){
				$message = "Resultat de recherche";
			}
			else
				$message = "Aucun résultat";
			
			$data = array(
				'directeurs' => $directeurs,
				'isDirecteur' => 'active',
				'titre' => $message
			);	
			
			return $this->render('UcmBundle:Admin:directeur.html.twig', $data);		
			
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
		
		public function addDirecteurAction(Request $request){
			$directeur = new Directeur();
			//créer le formulaire
			$form = $this->createFormBuilder($directeur)
									->add('nom', 'text')
									->add('prenom')
									->add('profile', FileType::class, 
										array('required' => false)
									)
									->add('active','checkbox',array('required' => false))
									->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
									->getForm();
									
			$form->handleRequest($request);
			
			// On vérifie que les valeurs entrées sont correctes        
			if ($form->isValid()) {
				// On l'enregistre notre objet $typemenu dans la base de données, par exemple
				$em = $this->getDoctrine()->getManager();
				$file = $directeur->getProfile();
				$fileName = "";

				if( $file ){
				  $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

				  // moves the file to the directory where brochures are stored
				  $file->move(
					  $this->getParameter('directeur_directory'),
					  $fileName
				  );

				  // updates the 'brochure' property to store the PDF file name
				  // instead of its contents
				  $directeur->setProfile($fileName);
				}
				
				$em->persist($directeur);
				$em->flush();
				$request->getSession()->getFlashBag()->add('notice', 'Actualité bien enregistré.');
				// On redirige vers la page de visualisation de l'annonce nouvellement créée
				return $this->redirect($this->generateUrl('ucm_admin_directeur_list', array('id' => $directeur->getId())));
			}						
									
									
			return $this->render('UcmBundle:Admin:adddirecteur.html.twig', array(
				'form' => $form->createView(),
				'titre' => 'Ajout un directeur',
				'isDirecteur'=>'active'
			));
		}
		
	}
?>