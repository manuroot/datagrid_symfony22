<?php

namespace Application\EservicesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Environnement
 *
 * @ORM\Table(name="eproduit_status")
 * @ORM\Entity
 */
class EproduitStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=40, nullable=false)
     */
    private $nom;

     /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;
    
  /**
     * @ORM\OneToMany(targetEntity="Eproduit", mappedBy="idStatus", cascade={"remove", "persist"}))
     */
    private $eproduit;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomProjet
     *
     * @param string $nom
     * @return Environnement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

     public function __toString() {
        return $this->getNom();    // this will not look good if SonataAdminBundle uses this ;)
    }

    /**
     * Get nomapplis
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

     /**
     * Set description
     *
     * @param string $description
     * @return Environnement
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
     * Constructor
     */
    public function __construct()
    {
    }
    
   
  

    /**
     * Add eproduit
     *
     * @param \Application\EservicesBundle\Entity\Eproduit $produit
     * @return EproduitStatus
     */
    public function addEproduit(\Application\EservicesBundle\Entity\Eservice $eproduit)
    {
        $this->eproduit[] = $eproduit;
    
        return $this;
    }

    /**
     * Remove eservice
     *
     * @param \Application\EservicesBundle\Entity\Eservice $eservice
     */
    public function removeEproduit(\Application\EservicesBundle\Entity\Eservice $eproduit)
    {
        $this->eproduit->removeElement($eproduit);
    }

    /**
     * Get eservice
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEpodruit()
    {
        return $this->eproduit;
    }
}