<?php

namespace UcmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use UcmBundle\Entity\Categorie;
/**
 * Rubrique
 *
 * @ORM\Table(name="rubrique")
 * @ORM\Entity(repositoryClass="UcmBundle\Repository\RubriqueRepository")
 */
class Rubrique
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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="description1", type="string", length=1000, nullable=true)
     */
    private $description1;

    /**
     * @var string
     *
     * @ORM\Column(name="description2", type="string", length=1000, nullable=true)
     */
    private $description2;

    /**
     * @var string
     *
     * @ORM\Column(name="description3", type="string", length=1000, nullable=true)
     */
    private $description3;

    

    /**
     * @ORM\ManyToOne(targetEntity="UcmBundle\Entity\Categorie")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id",onDelete="SET NULL")
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="image1", type="string", length=255, nullable=true)
     */
    private $image1;


    /**
     * @var string
     *
     * @ORM\Column(name="image2", type="string", length=255, nullable=true)
     */
    private $image2;


    /**
     * @var string
     *
     * @ORM\Column(name="image3", type="string", length=255, nullable=true)
     */
    private $image3;


    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif;

    


    /**
     * @var string
     *
     * @ORM\Column(name="layout", type="string", length=255, nullable=true)
     */
    private $layout;


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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Rubrique
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
     * Set url
     *
     * @param string $url
     *
     * @return Rubrique
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set description1
     *
     * @param string $description1
     *
     * @return Rubrique
     */
    public function setDescription1($description1)
    {
        $this->description1 = $description1;

        return $this;
    }

    /**
     * Get description1
     *
     * @return string
     */
    public function getDescription1()
    {
        return $this->description1;
    }

    /**
     * Set description2
     *
     * @param string $description2
     *
     * @return Rubrique
     */
    public function setDescription2($description2)
    {
        $this->description2 = $description2;

        return $this;
    }

    /**
     * Get description2
     *
     * @return string
     */
    public function getDescription2()
    {
        return $this->description2;
    }

    /**
     * Set description3
     *
     * @param string $description3
     *
     * @return Rubrique
     */
    public function setDescription3($description3)
    {
        $this->description3 = $description3;

        return $this;
    }

    /**
     * Get description3
     *
     * @return string
     */
    public function getDescription3()
    {
        return $this->description3;
    }

    /**
     * Set image1
     *
     * @param string $image1
     *
     * @return Rubrique
     */
    public function setImage1($image1)
    {
        $this->image1 = $image1;

        return $this;
    }

    /**
     * Get image1
     *
     * @return string
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * Set image2
     *
     * @param string $image2
     *
     * @return Rubrique
     */
    public function setImage2($image2)
    {
        $this->image2 = $image2;

        return $this;
    }

    /**
     * Get image2
     *
     * @return string
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * Set image3
     *
     * @param string $image3
     *
     * @return Rubrique
     */
    public function setImage3($image3)
    {
        $this->image3 = $image3;

        return $this;
    }

    /**
     * Get image3
     *
     * @return string
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * Set categorie
     *
     * @param \UcmBundle\Entity\Categorie $categorie
     *
     * @return Rubrique
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

    /**
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Rubrique
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

    /**
     * Set layout
     *
     * @param string $layout
     *
     * @return Rubrique
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get layout
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

     public function __toString()
    {
        return $this->getLibelle();
    }
}
