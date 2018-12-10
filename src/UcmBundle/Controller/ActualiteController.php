<?php 

	namespace UcmBundle\Controller;
	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\FileType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	
	use UcmBundle\Entity\Actualite;
	
	class ActualiteController extends Controller {
		
		public function indexAction(Request $request){
			
			$repository = $this->getDoctrine()->getRepository(Actualite::class)->findBy(array('estactif' => 1))	;
			$actualites = $this->get('knp_paginator')->paginate($repository,$request->query->get('page', 1),6);        
			
			
			return $this->render('UcmBundle:Admin:actualite.html.twig',
					array(
						'titre'=>'Liste des actualites',
						'isActualite' => 'active',
						'actualites' => $actualites,
						)
				    );
		}
		
		public function addActualiteAction(Request $request){
			$actualite = new Actualite();
			//cr�er formulaire
			$form = $this->createFormBuilder($actualite)
				->add('titre', 'text')
				->add('url', FileType::class,
					array(
					'required' => false,
					'data_class' => null,
					)
				)
				->add('contenu' , TextareaType::class,
					array(
						'required' => false,
						'attr' => array(
							'class' => 'tinymce',
							'data_theme' => 'medium',
						)
					)
				)
				->add('estactif', 'checkbox', array('required' => false))
				->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
                ->getForm();
			
			$form->handleRequest($request);
			
			// On v�rifie que les valeurs entr�es sont correctes        
			if ($form->isValid()) {
				  // On l'enregistre notre objet $typemenu dans la base de donn�es, par exemple
				  $em = $this->getDoctrine()->getManager();
				  $file = $actualite->getUrl();

				  $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

				  // moves the file to the directory where brochures are stored
				  $file->move(
					  $this->getParameter('actualite_directory'),
					  $fileName
				  );

				  // updates the 'brochure' property to store the PDF file name
				  // instead of its contents
				  $actualite->setUrl($fileName);
				  $em->persist($actualite);
				  $em->flush();
				  $request->getSession()->getFlashBag()->add('notice', 'Actualit� bien enregistr�.');
				  // On redirige vers la page de visualisation de l'annonce nouvellement cr��e
				  return $this->redirect($this->generateUrl('ucm_admin_actualite_list', array('id' => $actualite->getId())));
			}
			
			 return $this->render('UcmBundle:Admin:addactualite.html.twig', array(
					'form' => $form->createView(),
					'titre' => 'Ajouter un partenaire',
					'isActualite' => 'active'
			)); 
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
		
		public function showDeleteActuAction(Request $rqRequest){
			$l_emEntityManager = $this->getDoctrine()->getManager();
			$actualite = $l_emEntityManager->getRepository(Actualite::class)
				->findOneBy(array("id" => $rqRequest->request->get('id')));
			$message = "Voulez-vous vraiment supprimmer ".$actualite->getTitre();
			
			$l_btnConfirm = "<button class='btn btn-primary' data-dismiss='modal' onclick='deleteActualite(" . $rqRequest->request->get('id') . ");'>Supprimer</button>";
				
			return $this->render('UcmBundle:Admin:confirmdelete.html.twig',
				array(
					'titre'=>'Suppression actualit�',
					'message'=>$message,
					'l_btnConfirm'=>$l_btnConfirm,
					)
			);	
		}
		
		public function deleteActuAction(Request $request){
			$l_emEntityManager = $this->getDoctrine()->getManager();
			$actualite = $l_emEntityManager->getRepository(Actualite::class)
				->findOneBy(array("id" => $request->request->get('id')));
				
			$l_emEntityManager->remove($actualite);
			$l_emEntityManager->flush();
			
			//$repository = $this->getDoctrine()->getRepository(Actualite::class)->findBy(array('estactif' => 1))	;
			//$actualites = $this->get('knp_paginator')->paginate($repository,$request->query->get('page', 1),6);        
			
			return $this->redirect($this->generateUrl('ucm_admin_actualite_list'));
		}
		
		public function editActuAction(Request $request,$id){
			$em = $this->getDoctrine()->getManager();
			$actualite = $em->getRepository('UcmBundle:Actualite')->find($id);
			if (!$actualite) {
			  throw $this->createNotFoundException(
					  'No news found for id ' . $id
			  );
			}

			//cr�er le formulaire
			$form = $this->createFormBuilder($actualite)
									->add('titre', 'text')
									->add('url', 'text',array('required' => false))
									->add('contenu', 'textarea',array('required' => false))
									->add('estactif','checkbox',array('required' => false))
									->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
									->getForm();

			// On fait le lien Requ�te <-> Formulaire
			// � partir de maintenant, la variable $actualite contient les valeurs entr�es dans le formulaire par le visiteur
			$form->handleRequest($request);

			// On v�rifie que les valeurs entr�es sont correctes        
			if ($form->isValid()) {
				  // On l'enregistre notre objet $actualite dans la base de donn�es, par exemple
				  
				  $em->flush();
				  $request->getSession()->getFlashBag()->add('notice', 'Mis � jour actualit� avec succ�s.');
				  // On redirige vers la page de visualisation de l'annonce nouvellement cr��e
				  return $this->redirect($this->generateUrl('ucm_admin_actualite_list', array('id' => $actualite->getId())));
			}

			
			return $this->render('UcmBundle:Admin:addactualite.html.twig', array(
			  'form' => $form->createView(),
			  'titre' => 'Modifier une actualit�',
			  'isActualite'=>'active'
			));
		}	

		public function rechercheActualiteAction(Request $request){
			$form = $this->createFormBuilder(null)
					->add('recherche', TextType::class)
					->add('save', SubmitType::class, array(
						'label' => 'Rechercher',							
					))
					->getForm();			
		
			return $this->render('UcmBundle:Admin:recherche.html.twig',
					[
						'form' => $form->createView(),
						'route_recherche' => "ucm_admin_actualite_execute_search"
					]
					);		
		}
		
		public function executerRechercheAction(Request $request){
		
			$em = $this->getDoctrine()->getManager();
			$message = "";
			$param = $request->request->get('form');
			$critere = $param['recherche'];
			$queryAll = $this->getDoctrine()
				->getRepository(Actualite::class)
				->rechercheActualites($critere);
				
			$actualites  = $this->get('knp_paginator')->paginate($queryAll,$request->query->get('page', 1),6);        
			
			if($actualites){
				$message = "Resultat de recherche";
			}
			else
				$message = "Aucun r�sultat";
			
			$data = array(
				'actualites' => $actualites,
				'isActualite' => 'active',
				'titre' => $message
			);
			
			return $this->render('UcmBundle:Admin:actualite.html.twig', $data);			
		}
	}
?>