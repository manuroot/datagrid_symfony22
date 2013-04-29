<?php

namespace Application\EpostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Environnement
 *
 * @ORM\Table(name="epost_status")
 * @ORM\Entity
 */
class EpostStatus
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
     * @ORM\OneToMany(targetEntity="Epost", mappedBy="idStatus", cascade={"remove", "persist"}))
     */
    private $epost;

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
     * Add epost
     *
     * @param \Application\EpostBundle\Entity\Epost $epost
     * @return EpostStatus
     */
    public function addEpost(\Application\EpostBundle\Entity\Epost $epost)
    {
        $this->epost[] = $epost;
    
        return $this;
    }

    /**
     * Remove eservice
     *
     * @param \Application\EpostBundle\Entity\Epost $epost
     */
    public function removeEpost(\Application\EpostBundle\Entity\Epost $epost)
    {
        $this->epost->removeElement($epost);
    }

    /**
     * Get eservice
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEpost()
    {
        return $this->epost;
    }
}