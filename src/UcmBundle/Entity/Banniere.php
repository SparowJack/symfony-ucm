<?php

namespace UcmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UcmBundle\Entity\Rubrique;

/**
 * Banniere
 *
 * @ORM\Table(name="banniere")
 * @ORM\Entity(repositoryClass="UcmBundle\Repository\BanniereRepository")
 */
class Banniere
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
     * @ORM\Column(name="banniere", type="string", length=255, nullable=true)
     */
    private $banniere;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @var bool
     *
     * @ORM\Column(name="onpage", type="boolean", nullable=true)
     */
    private $onpage;

    /**
     * @ORM\ManyToOne(targetEntity="UcmBundle\Entity\Rubrique")
     * @ORM\JoinColumn(name="rubrique", referencedColumnName="id",onDelete="SET NULL")
     */
    private $rubrique;


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
     * Set banniere
     *
     * @param string $banniere
     *
     * @return Banniere
     */
    public function setBanniere($banniere)
    {
        $this->banniere = $banniere;

        return $this;
    }

    /**
     * Get banniere
     *
     * @return string
     */
    public function getBanniere()
    {
        return $this->banniere;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Banniere
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Banniere
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Banniere
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return bool
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set rubrique
     *
     * @param \UcmBundle\Entity\Rubrique $rubrique
     *
     * @return Banniere
     */
    public function setRubrique(\UcmBundle\Entity\Rubrique $rubrique = null)
    {
        $this->rubrique = $rubrique;

        return $this;
    }

    /**
     * Get rubrique
     *
     * @return \UcmBundle\Entity\Rubrique
     */
    public function getRubrique()
    {
        return $this->rubrique;
    }

    /**
     * Set onpage
     *
     * @param boolean $onpage
     *
     * @return Banniere
     */
    public function setOnpage($onpage)
    {
        $this->onpage = $onpage;

        return $this;
    }

    /**
     * Get onpage
     *
     * @return boolean
     */
    public function getOnpage()
    {
        return $this->onpage;
    }
}
