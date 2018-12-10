<?php

namespace UcmBundle\Entity;

use UcmBundle\Entity\Typemenu;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="UcmBundle\Repository\CategorieRepository")
 */
class Categorie
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
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;    

     
     /**
     * @ORM\ManyToOne(targetEntity="UcmBundle\Entity\Categorie")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id",onDelete="SET NULL")
     */
    private $parentId;

    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif;
   

     /**
     * @ORM\ManyToOne(targetEntity="UcmBundle\Entity\Typemenu")
     * @ORM\JoinColumn(name="typemenu_id", referencedColumnName="id",onDelete="SET NULL")
     */
    private $typemenu;



    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;


    /**
     * @var string
     *
     * @ORM\Column(name="modele_page", type="string", length=255, nullable=true)
     */
    private $modele_page;

    /**
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer", length=10, nullable=true)
     */
    private $sequence;


    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;


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
     * @return Categorie
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
     * @return Categorie
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
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return Categorie
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Categorie
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
     * Set typemenu
     *
     * @param \UcmBundle\Entity\Typemenu $typemenu
     *
     * @return Categorie
     */
    public function setTypemenu(\UcmBundle\Entity\Typemenu $typemenu = null)
    {
        $this->typemenu = $typemenu;

        return $this;
    }

    /**
     * Get typemenu
     *
     * @return \UcmBundle\Entity\Typemenu
     */
    public function getTypemenu()
    {
        return $this->typemenu;
    }

     public function __toString()
    {
        return $this->getLibelle();
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Categorie
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
     * Set modelePage
     *
     * @param string $modelePage
     *
     * @return Categorie
     */
    public function setModelePage($modelePage)
    {
        $this->modele_page = $modelePage;

        return $this;
    }

    /**
     * Get modelePage
     *
     * @return string
     */
    public function getModelePage()
    {
        return $this->modele_page;
    }

    /**
     * Set sequence
     *
     * @param int $sequence
     *
     * @return Categorie
     */
    public function setSequence(int $sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Categorie
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
