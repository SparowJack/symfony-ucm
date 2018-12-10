<?php

namespace UcmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Motdirecteur
 *
 * @ORM\Table(name="motdirecteur")
 * @ORM\Entity(repositoryClass="UcmBundle\Repository\MotdirecteurRepository")
 */
class Motdirecteur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	 /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string")
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;
	
	 /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif;	
	
	/**
     * @ORM\ManyToOne(targetEntity="UcmBundle\Entity\Directeur")
     * @ORM\JoinColumn(name="directeur_id", referencedColumnName="id",onDelete="SET NULL")
     */
    private $directeur;
	


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Motdirecteur
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Motdirecteur
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set directeur
     *
     * @param \UcmBundle\Entity\Directeur $directeur
     *
     * @return Motdirecteur
     */
    public function setDirecteur(\UcmBundle\Entity\Directeur $directeur = null)
    {
        $this->directeur = $directeur;

        return $this;
    }

    /**
     * Get directeur
     *
     * @return \UcmBundle\Entity\Directeur
     */
    public function getDirecteur()
    {
        return $this->directeur;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Motdirecteur
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean
     */
    public function getActif()
    {
        return $this->actif;
    }
}
