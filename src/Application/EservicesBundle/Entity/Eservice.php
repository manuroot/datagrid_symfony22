<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\EservicesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Application\Sonata\UserBundle\entity\User as User;


use Symfony\Component\Validator\Constraints as Assert;
/*use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;*/


/**
 * This file has been generated by the Sonata EasyExtends bundle ( http://sonata-project.org/bundles/easy-extends )
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */

/**
 * Changements
 *
 * @ORM\Table(name="eservices")
 * @ORM\Entity(repositoryClass="Application\EservicesBundle\Repository\EserviceRepository")
 */
class Eservice {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Get id
     *
     * @return integer 
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=false)
     * @GRID\Column(title="Fin", size="40",format="Y-m-d h:i",type="datetime")
     * 
     */
    private $dateFin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_comep", type="datetime", nullable=true)
     * @GRID\Column(title="COMEP", size="50",format="Y-m-d",type="datetime")
     */

    /**
     * @var string
     * @Assert\NotBlank(message="Title must not be empty")
     * @Assert\Length(
     *      min = "5",
     *      max = "30",
     *      minMessage = "the name must be at least {{ limit }} characters length |
     *  Au minimum {{ limit }} caracteres",
     *  maxMessage = "Your first name cannot be longer than than {{ limit }} characters length |
     *  Au maximum {{ limit }} caracteres"
     * )
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var integer
     * @Assert\Min(limit = "10", message = "Valeur minimum: 10 Brouzoufs")
     * @Assert\Max(limit = "200", message = "Valeur maximum: 200 Brouzoufs")
     * @ORM\Column(name="brouzoufs", type="integer", nullable=false)
     */
    private $brouzoufs;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User",cascade={"persist"})
     * @ORM\OrderBy({"username" = "ASC"})
     * @ORM\JoinTable(name="eservice_users")
     */
    private $idusers;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\OrderBy({"username" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="demandeur", referencedColumnName="id")
     * })
     */
    private $demandeur;

    
 /**
     * @ORM\ManyToMany(targetEntity="Eproduit", inversedBy="services",cascade={"persist"})
     * @ORM\JoinTable(name="eservice_produit",
     *   joinColumns={
     *     @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     *   }
     * )
     */
    private $produits;

    
    /**
     * @var \EserviceStatus
     *
     * @ORM\ManyToOne(targetEntity="EserviceStatus", inversedBy="eservice"))
      * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_status", referencedColumnName="id")
     *  })
     */
    private $idStatus;
    
    /**
   * @var boolean $isEnabled
   *
  * @ORM\Column(name="isDemande", type="boolean")
  */
  private $isDemande;

    public function __toString() {
        return $this->getName();
    }

    public function getId() {
        return $this->id;
    }

    /**
     *  Set nom
     *
     * @param string $fileName
     * @return CertificatsCenter
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct() {
    //public function __construct($id_user) {
        $this->idusers = new \Doctrine\Common\Collections\ArrayCollection();
 $this->isDemande = true; // Default value for column is_visible
       // $this->demandeur = $id_user;
    }
/**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Eservice
     */
    public function setDateDebut($dateDebut) {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateDebut() {
        return $this->dateDebut;
    }
    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Eservice
     */
    public function setDateFin($dateFin) {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin() {
        return $this->dateFin;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Eservice
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

    /**
     * Add idusers
     *
     * @param \Application\Sonata\UserBundle\Entity\User $idusers
     * @return Eservice
     */
    public function addIduser(\Application\Sonata\UserBundle\Entity\User $idusers) {
        $this->idusers[] = $idusers;

        return $this;
    }

    /**
     * Remove idusers
     *
     * @param \Application\Sonata\UserBundle\Entity\User $idusers
     */
    public function removeIduser(\Application\Sonata\UserBundle\Entity\User $idusers) {
        $this->idusers->removeElement($idusers);
    }

    /**
     * Get idusers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdusers() {
        return $this->idusers;
    }

    /**
     * Set demandeur
     *
     * @param \Application\Sonata\UserBundle\Entity\User $demandeur
     * @return Eservice
     */
    public function setDemandeur(\Application\Sonata\UserBundle\Entity\User $demandeur = null) {
        $this->demandeur = $demandeur;

        return $this;
    }

    /**
     * Get demandeur
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getDemandeur() {
        return $this->demandeur;
    }

    /**
     * Set brouzoufs
     *
     * @param integer $brouzoufs
     * @return Eservice
     */
    public function setBrouzoufs($brouzoufs) {
        $this->brouzoufs = $brouzoufs;

        return $this;
    }

    /**
     * Get brouzoufs
     *
     * @return integer 
     */
    public function getBrouzoufs() {
        return $this->brouzoufs;
    }

    /**
     * Add produits
     *
     * @param \Application\EservicesBundle\Entity\Eproduit $produits
     * @return Eservice
     */
    public function addProduit(\Application\EservicesBundle\Entity\Eproduit $produits) {
        $this->produits[] = $produits;

        return $this;
    }

    /**
     * Remove produits
     *
     * @param \Application\EservicesBundle\Entity\Eproduit $produits
     */
    public function removeProduit(\Application\EservicesBundle\Entity\Eproduit $produits) {
        $this->produits->removeElement($produits);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduits() {
        return $this->produits;
    }


    /**
     * Set idStatus
     *
     * @param \Application\EservicesBundle\Entity\EserviceStatus $idStatus
     * @return Eservice
     */
    public function setIdStatus(\Application\EservicesBundle\Entity\EserviceStatus $idStatus = null)
    {
        $this->idStatus = $idStatus;
    
        return $this;
    }

    /**
     * Get idStatus
     *
     * @return \Application\EservicesBundle\Entity\EserviceStatus 
     */
    public function getIdStatus()
    {
        return $this->idStatus;
    }

    /**
     * Set isDemande
     *
     * @param boolean $isDemande
     * @return Eservice
     */
    public function setIsDemande($isDemande)
    {
        $this->isDemande = $isDemande;
    
        return $this;
    }

    /**
     * Get isDemande
     *
     * @return boolean 
     */
    public function getIsDemande()
    {
        return $this->isDemande;
    }
}