<?php

namespace Application\EservicesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CertificatsProjet
 *
 * @ORM\Table(name="eproduits_categories")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\EservicesBundle\Repository\EproduitCategoriesRepository")
 */
class EproduitCategories {

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
     * @ORM\Column(name="name", type="string", length=40, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
     private $description;
   

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nameCategorie
     *
     * @param string $name
     * @return EproduitCategories
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get nameproduit
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CertificatsProjet
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    public function __construct() {
      //  $this->projets = new \Doctrine\Common\Collections\ArrayCollection();
       
    }

     
     public function __toString() {
        return $this->getName();
    }
    
}