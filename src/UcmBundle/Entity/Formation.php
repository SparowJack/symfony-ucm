<?php

namespace UcmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use UcmBundle\Entity\Categformation;
use UcmBundle\Entity\Departement;

/**
 * Formation
 *
 * @ORM\Table(name="formation")
 * @ORM\Entity(repositoryClass="UcmBundle\Repository\FormationRepository")
 */
class Formation
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
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="metier", type="string", length=1000, nullable=true)
     */
    private $metier;

    /**
     * @var string
     *
     * @ORM\Column(name="poursuite", type="string", length=1000, nullable=true, unique=true)
     */
    private $poursuite;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=1000, nullable=true)
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="objectif", type="string", length=1000, nullable=true)
     */
    private $objectif;

    /**
     * @var string
     *
     * @ORM\Column(name="admission", type="string", length=1000, nullable=true)
     */
    private $admission;



    /**
     * @ORM\ManyToOne(targetEntity="UcmBundle\Entity\Departement")
     * @ORM\JoinColumn(name="departement_id", referencedColumnName="id",onDelete="SET NULL")
     */
    private $departement;


    /**
     * @ORM\ManyToOne(targetEntity="UcmBundle\Entity\Categformation")
     * @ORM\JoinColumn(name="categformation_id", referencedColumnName="id",onDelete="SET NULL")
     */
    private $categformation;



    /**
     * @ORM\ManyToOne(targetEntity="UcmBundle\Entity\Categorie")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id",onDelete="SET NULL")
     */
    private $categorie;


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
     * Set titre
     *
     * @param string $titre
     *
     * @return Formation
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
     * Set metier
     *
     * @param string $metier
     *
     * @return Formation
     */
    public function setMetier($metier)
    {
        $this->metier = $metier;

        return $this;
    }

    /**
     * Get metier
     *
     * @return string
     */
    public function getMetier()
    {
        return $this->metier;
    }

    /**
     * Set poursuite
     *
     * @param string $poursuite
     *
     * @return Formation
     */
    public function setPoursuite($poursuite)
    {
        $this->poursuite = $poursuite;

        return $this;
    }

    /**
     * Get poursuite
     *
     * @return string
     */
    public function getPoursuite()
    {
        return $this->poursuite;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return Formation
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set objectif
     *
     * @param string $objectif
     *
     * @return Formation
     */
    public function setObjectif($objectif)
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * Get objectif
     *
     * @return string
     */
    public function getObjectif()
    {
        return $this->objectif;
    }

    /**
     * Set admission
     *
     * @param string $admission
     *
     * @return Formation
     */
    public function setAdmission($admission)
    {
        $this->admission = $admission;

        return $this;
    }

    /**
     * Get admission
     *
     * @return string
     */
    public function getAdmission()
    {
        return $this->admission;
    }

    /**
     * Set departement
     *
     * @param \UcmBundle\Entity\Departement $departement
     *
     * @return Formation
     */
    public function setDepartement(\UcmBundle\Entity\Departement $departement = null)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \UcmBundle\Entity\Departement
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set categformation
     *
     * @param \UcmBundle\Entity\Categformation $categformation
     *
     * @return Formation
     */
    public function setCategformation(\UcmBundle\Entity\Categformation $categformation = null)
    {
        $this->categformation = $categformation;

        return $this;
    }

    /**
     * Get categformation
     *
     * @return \UcmBundle\Entity\Categformation
     */
    public function getCategformation()
    {
        return $this->categformation;
    }

    public function __toString()
    {
        return $this->getTitre();
    }

    /**
     * Set categorie
     *
     * @param \UcmBundle\Entity\Categorie $categorie
     *
     * @return Formation
     */
    public function setCategorie(\UcmBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \UcmBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}
