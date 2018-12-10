<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }



    public function listAction(){
    	$userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

    	$data = array(	  'titre'=>'Liste des Utilisateurs',
    					  'users'=>$users,	                                          
	                      'isUser' => 'active');   
	    return $this->render('UcmBundle:Admin:list_user.html.twig',$data);
    }
}
