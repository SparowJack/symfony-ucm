<?php

namespace UcmBundle\Services;

use Doctrine\ORM\EntityManager;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use UcmBundle\Entity\Typemenu;
use UcmBundle\Entity\Categorie;
use UcmBundle\Entity\Configuration;
use UcmBundle\Entity\Categformation;
use UcmBundle\Entity\Formation;
use UcmBundle\Entity\Banniere;




class MenuService extends \Twig_Extension{

	private $em;

	public function __construct(EntityManager $entityManager) {
		$this->em = $entityManager;
	}

	 public function getFunctions()
    {      

        return array(
            new \Twig_SimpleFunction('getMenuTop', array($this, 'getMenuTop')),
            new \Twig_SimpleFunction('getMenuMiddle', array($this, 'getMenuMiddle')),
            new \Twig_SimpleFunction('getChildMenu', array($this, 'getChildMenu')),
            new \Twig_SimpleFunction('getLogo', array($this, 'getLogo')),
            new \Twig_SimpleFunction('getMenuBottom', array($this, 'getMenuBottom')),
            new \Twig_SimpleFunction('getCategFormation', array($this, 'getCategFormation')),
            new \Twig_SimpleFunction('getFormation', array($this, 'getFormation')),
            new \Twig_SimpleFunction('getBanniere', array($this, 'getBanniere'))            

        );

    } 

    public function getFilters(){
		return array(
			new \Twig_SimpleFilter('couperPhrase', array($this, 'couperPhrase'))
			);
	}


	public function getMenuTop(){
		//Supposons
		//menutop= 1 correspond au menutop
		//menutop = 2 corresponnd au mileu
		//menutop = 3 correspond au menu bas		
		$queryAll = $this->em->getRepository(Categorie::class)->findBy(array('typemenu' => 1));    	
		return $queryAll;
	}

	public function getMenuMiddle(){
		//Supposons
		//menutop= 1 correspond au menutop
		//menutop = 2 corresponnd au mileu
		//menutop = 3 correspond au menu bas		
		$queryAll = $this->em->getRepository(Categorie::class)->findBy(['typemenu'=>2],['sequence'=>'ASC']);   
		return $queryAll;
	}
	public function getChildMenu($id){
		$queryAll = $this->em->getRepository(Categorie::class)->findBy(['parentId' => (int)$id],['sequence'=>'ASC']);  		  	
		return $queryAll;
	}

	public function getLogo(){
		$queryAll = $this->em->getRepository(Configuration::class)->findBy(['id' => 1]); 

		$logo = "";
		
		foreach($queryAll as $val){
			$logo = $val->getLogo();
		}

		return $logo;
	}

	public function getMenuBottom(){
		//Supposons
		//menutop= 1 correspond au menutop
		//menutop = 2 corresponnd au mileu
		//menutop = 3 correspond au menu bas		
		$queryAll = $this->em->getRepository(Categorie::class)->findBy(array('typemenu' => 3, 'actif' => 1));    	
		
		return $queryAll;
	}

	public function couperPhrase($phrase, $charlimit){
		if(strlen($phrase) > $charlimit){
			$phrase = substr($phrase,'0',$charlimit);
			$array = explode(' ',$phrase);
			array_pop($array);
			$new_string = implode(' ',$array);
			return $new_string.'...';
		} else {
			return $phrase;
		}
	}



	//get categformation
	public function getCategFormation(){		   
		$categformation = $this->em->getRepository(Categformation::class)->findBy( ['active' => true]);		
		return $categformation;
	}

	//get formation
	public function getFormation(){
		$formation = $this->em->getRepository(Formation::class)->findAll();
		return $formation;
	}


	public function getBanniere(){
		$banniere = $this->em->getRepository(Banniere::class)->findBy( ['onpage' => true]);
		return $banniere;
	}
}