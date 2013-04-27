<?php

namespace Application\EservicesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * CertificatsProjet
 *
 * @ORM\Table(name="eproduits_notes")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\EservicesBundle\Repository\EproduitNotesRepository")
 */
class EproduitNotes {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     /**
     * @var integer
     * @Assert\Min(limit = "1", message = "Note minimum: 1")
     * @Assert\Max(limit = "10", message = "Note maximum: 10")
     * @ORM\Column(name="note", type="integer", length=3, nullable=false)
     */
    private $note;

    
    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;
    /**
     * Get id
     *
     * @return integer 
     */
    
    /**
     * @ORM\ManyToOne(targetEntity="Eproduit")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    protected $produit;
    
    public function getId() {
        return $this->id;
    }

    public function __construct() {
      //  $this->projets = new \Doctrine\Common\Collections\ArrayCollection();
       
    }

      public function __toString() {
        return $this->getId();
    }
    
    

    /**
     * Set note
     *
     * @param integer $note
     * @return EproduitNotes
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return integer 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return EproduitNotes
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set produit
     *
     * @param \Application\EservicesBundle\Entity\Eproduit $produit
     * @return EproduitNotes
     */
    public function setProduit(\Application\EservicesBundle\Entity\Eproduit $produit = null)
    {
        $this->produit = $produit;
    
        return $this;
    }

    /**
     * Get produit
     *
     * @return \Application\EservicesBundle\Entity\Eproduit 
     */
    public function getProduit()
    {
        return $this->produit;
    }
}