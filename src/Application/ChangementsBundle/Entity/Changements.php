<?php


namespace Application\ChangementsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use CalendR\Event\AbstractEvent;

/**
 * Changements
 *
 * @ORM\Table(name="changements")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Application\ChangementsBundle\Entity\ChangementsRepository")
 * @GRID\Source(columns="id,nom,dateDebut,dateFin,idProjet.nomprojet,demandeur.nomUser,idEnvironnement.nom:GroupConcat",groupBy={"id"}))
  * @Vich\Uploadable
 */

// @GRID\Source(columns="id,nom,dateDebut,dateFin,idProjet.nomprojet,demandeur.nomUser,idEnvironnement.nom:GroupConcat,idusers.nomUser:GroupConcat",groupBy={"id"})

class Changements extends AbstractEvent
//class Changements
{
    
    protected $begin;
    protected $end;
    protected $uid;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @GRID\Column(title="id", size="20", type="text",filter="false")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     /**
     * @Assert\MinLength(
     *      limit=5,
     *     message="At least {{ limit }} characters.|
      Au moins {{ limit }} caracteres."
      )
     * @Assert\MaxLength(
     *      limit=35,
     *      message="Less than {{ limit }} characters.|
      Au max {{ limit }} caracteres."
     * )
     * @GRID\Column(field="nom", title="Nom",size="55")
    */
  
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=false)
     * @GRID\Column(title="Début", size="30",format="Y-m-d",type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=false)
      * @GRID\Column(title="Fin", size="30",format="Y-m-d",type="datetime")
     * 
     */
    private $dateFin;

      // @GRID\Column(title="Fin", size="40",format="Y-m-d h:i",type="datetime")
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_comep", type="datetime", nullable=true)
     * @GRID\Column(title="COMEP", size="50",format="Y-m-d",type="datetime")
     */
    private $dateComep;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_vsr", type="date", nullable=true)
     * @GRID\Column(title="VSR", size="50",format="Y-m-d",type="datetime")
     */
    private $dateVsr;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

     
    /**
     * @var \CertificatsProjet
     *
     * @ORM\ManyToOne(targetEntity="Application\CertificatsBundle\Entity\CertificatsProjet")
     * @ORM\OrderBy({"nomprojet" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projet", referencedColumnName="id",nullable=false)
     * })
     * @GRID\Column(field="idProjet.nomprojet", title="Projet",size="20",filter="select",selectFrom="query")
 
    */
     private $idProjet;
   
     
          /**
     * @ORM\ManyToMany(targetEntity="Docchangements", inversedBy="idchangement",cascade={"persist"})
     * @ORM\JoinTable(name="changements_documents")
     */
     protected $picture;
   
    
    
     /**
     * @ORM\ManyToMany(targetEntity="Application\CertificatsBundle\Entity\Environnements", inversedBy="idchangements",cascade={"persist"})
     * @ORM\OrderBy({"nom" = "ASC"})
     * @ORM\JoinTable(name="changements_environnements")
     * @GRID\Column(title="Env", field="idEnvironnement.nom:GroupConcat", size="30", visible=true, sortable=true, filtrable="true")
     */
  
     
    private $idEnvironnement;
   // GRID\Column(title="Environnements", field="idEnvironnement.nom:GroupConcat",  visible=true, sortable=true, filter="select",selectFrom="query")
     
     /**
     *  @ORM\ManyToMany(targetEntity="Application\CertificatsBundle\Entity\CertificatsApplis", inversedBy="idprojets",cascade={"persist"})
     * @ORM\OrderBy({"nomapplis" = "ASC"})
     * @ORM\JoinTable(name="changements_applis")
     */
    private $idapplis;
 
    /**
     * @ORM\ManyToMany(targetEntity="Application\CertificatsBundle\Entity\ChronoUser", inversedBy="idchangement",cascade={"persist"})
     * @ORM\OrderBy({"nomUser" = "ASC"})
     * @ORM\JoinTable(name="changements_users")
      */
    private $idusers;
   // @GRID\Column(title="Users", field="idusers.nomUser:GroupConcat", size="20", visible=true, sortable=true, filtrable="true")
    
     /**
     * @var \ChronoUser
     *
     * @ORM\ManyToOne(targetEntity="Application\CertificatsBundle\Entity\ChronoUser")
     * @ORM\OrderBy({"nomUser" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="demandeur", referencedColumnName="id")
     * })
     * @GRID\Column(field="demandeur.nomUser", title="Demandeur",size="20",filter="select",selectFrom="query")
    */
    private $demandeur;

    /**
     * @var \ChangementStatus
     *
     * @ORM\ManyToOne(targetEntity="ChangementsStatus")
     * @ORM\OrderBy({"nom" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_status", referencedColumnName="id",nullable=false)
     *  })
     * @GRID\Column(field="idStatus.nom", title="Projet",size="20",filter="select",selectFrom="query")
    */
    private $idStatus;
   
      /**
    * @ORM\OneToMany(targetEntity="ChangementsComments", mappedBy="changement",cascade={"persist"})
 */
private $comments;

  /*//**
 //* @Assert\File(
 *     maxSize="1M",
 *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
 * )
 * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatar")
 * @ORM\Column(type="string", length=255)
 *
 * @var File $avatar
 */
//protected $avatar;

    
  /*  public function getUid()
    {
        return $this->uid;
    }

    public function getBegin()
    {
        return $this->begin;
    }

    public function getEnd()
    {
        return $this->end;
    }*/
    
    
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
     * Set nom
     *
     * @param string $nom
     * @return Changements
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Changements
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    
        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Changements
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    
        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set dateComep
     *
     * @param \DateTime $dateComep
     * @return Changements
     */
    public function setDateComep($dateComep)
    {
        $this->dateComep = $dateComep;
    
        return $this;
    }

    /**
     * Get dateComep
     *
     * @return \DateTime 
     */
    public function getDateComep()
    {
        return $this->dateComep;
    }

    /**
     * Set dateVsr
     *
     * @param \DateTime $dateVsr
     * @return Changements
     */
    public function setDateVsr($dateVsr)
    {
        $this->dateVsr = $dateVsr;
    
        return $this;
    }

    /**
     * Get dateVsr
     *
     * @return \DateTime 
     */
    public function getDateVsr()
    {
        return $this->dateVsr;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Changements
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
     * Set demandeur
     *
     * @param \Application\ChangementsBundle\Entity\ChronoUser $demandeur
     * @return Changements
     */
    public function setDemandeur(\Application\CertificatsBundle\Entity\ChronoUser $demandeur = null)
    {
        $this->demandeur = $demandeur;
    
        return $this;
    }

    /**
     * Get idProject
     *
     * @return \Application\CertificatsBundle\Entity\ChronoUser 
     */
    public function getDemandeur()
    {
        return $this->demandeur;
    }

    /**
     * Set idProject
     *
     * @param \Application\CertificatsBundle\Entity\CertificatsProjet $idProjet
     * @return Changements
     */
    public function setIdProjet(\Application\CertificatsBundle\Entity\CertificatsProjet $idProjet = null)
    {
        $this->idProjet = $idProjet;
    
        return $this;
    }

    /**
     * Get idProject
     *
     * @return \Application\CertificatsBundle\Entity\CertificatsProjet 
     */
    public function getIdProjet()
    {
        return $this->idProjet;
    }
    
     /**
     * Set idProject
     *
     * @param \Application\ChangementsBundle\Entity\ChangementsStatut $idStatus
     * @return Changements
     */
    public function setIdStatus(\Application\ChangementsBundle\Entity\ChangementsStatus $idStatus = null)
    {
        $this->idStatus = $idStatus;
    
        return $this;
    }

    /**
     * Get idProject
     *
     * @return \Application\ChangementsBundle\Entity\ChangementsStatus 
     */
    public function getIdStatus()
    {
        return $this->idStatus;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // ????????
        $this->idusers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idapplis = new \Doctrine\Common\Collections\ArrayCollection();
          $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idEnvironnement = new \Doctrine\Common\Collections\ArrayCollection();
     //   $this->idapplis = new \Doctrine\Common\Collections\ArrayCollection();
     /*         $this->uid = $uid;
        $this->begin = clone $start;
        $this->end = clone $end;*/
    }
    
    /**
     * Add idusers
     *
     * @param \Application\CertificatsBundle\Entity\ChronoUser $idusers
     * @return Changements
     */
    public function addIduser(\Application\CertificatsBundle\Entity\ChronoUser $idusers)
    {
        $this->idusers[] = $idusers;
    
        return $this;
    }

    /**
     * Remove idusers
     *
     * @param \Application\CertificatsBundle\Entity\ChronoUser $idusers
     */
    public function removeIduser(\Application\CertificatsBundle\Entity\ChronoUser $idusers)
    {
        $this->idusers->removeElement($idusers);
    }

    /**
     * Get idusers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdusers()
    {
        return $this->idusers;
    }
     public function __toString() {
        return $this->getNom();    // this will not look good if SonataAdminBundle uses this ;)
    }

    /**
     * Add idapplis
     *
     * @param \Application\CertificatsBundle\Entity\CertificatsApplis $idapplis
     * @return Changements
     */
    public function addIdappli(\Application\CertificatsBundle\Entity\CertificatsApplis $idapplis)
    {
        $this->idapplis[] = $idapplis;
    
        return $this;
    }

    /**
     * Remove idapplis
     *
     * @param \Application\ChangementsBundle\Entity\CertificatsApplis $idapplis
     */
    public function removeIdappli(\Application\CertificatsBundle\Entity\CertificatsApplis $idapplis)
    {
        $this->idapplis->removeElement($idapplis);
    }

    /**
     * Get idapplis
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdapplis()
    {
        return $this->idapplis;
    }
    
    /**
     * Set fic
     *
     * @param string $fic
     */
    public function setFic($fic)
    {
        $this->fic = $fic;
    }
 
    

    /**
     * Add idEnvironnement
     *
     * @param \Application\ChangementsBundle\Entity\Environnements $idEnvironnement
     * @return Changements
     */
    public function addIdEnvironnement(\Application\CertificatsBundle\Entity\Environnements $idEnvironnement)
    {
        $this->idEnvironnement[] = $idEnvironnement;
    
        return $this;
    }

    /**
     * Remove idEnvironnement
     *
     * @param \Application\ChangementsBundle\Entity\Environnements $idEnvironnement
     */
    public function removeIdEnvironnement(\Application\CertificatsBundle\Entity\Environnements $idEnvironnement)
    {
        $this->idEnvironnement->removeElement($idEnvironnement);
    }

    /**
     * Get idEnvironnement
     *
     * @return \Doctrine\Common\Collections\Collection 
  
     */
    public function getIdEnvironnement()
    {
        return $this->idEnvironnement;
    }

    
   
    

    /**
     * Set picture
     *
     * @param \Application\ChangementsBundle\Entity\Document $picture
     * @return Changements
     */
    public function setPicture(\Application\ChangementsBundle\Entity\Document $picture = null)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return \Application\ChangementsBundle\Entity\Document 
     */
    public function getPicture()
    {
        return $this->picture;
    }

   
    /**
     * Set avatar
     *
     * @param string $avatar
     * @return Changements
     */
   /* public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    
        return $this;
    }*/

    /**
     * Get avatar
     *
     * @return string 
     */
   /* public function getAvatar()
    {
        return $this->avatar;
    }*/

    /**
     * Add picture
     *
     * @param \Application\ChangementsBundle\Entity\Document $picture
     * @return Changements
     */
    public function addPicture(\Application\ChangementsBundle\Entity\Document $picture)
    {
        $this->picture[] = $picture;
    
        return $this;
    }

    /**
     * Remove picture
     *
     * @param \Application\ChangementsBundle\Entity\Document $picture
     */
    public function removePicture(\Application\ChangementsBundle\Entity\Document $picture)
    {
        $this->picture->removeElement($picture);
    }

    public function getConfirmation(){
        
        return "Nom:" . $this->getNom() . "<br>" . $this->getIdapplis();
    }
   
    
    public function getUid()
    {
        return $this->id;
    }

    public function getBegin()
    {
        return $this->dateDebut;
    }

    public function getEnd()
    {
        return $this->dateFin;
    }
    /**
     * Remove comments
     *
     * @param \Application\EservicesBundle\Entity\ChangementsComments $comments
     */
    public function removeComment(\Application\ChangementsBundle\Entity\ChangementsComments $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Add comments
     *
     * @param \Application\EservicesBundle\Entity\ChangementsComments $comments
     * @return Eproduit
     */
    public function addComment(\Application\EservicesBundle\Entity\ChangementsComments $comments)
    {
        $this->comments[] = $comments;
    
        return $this;
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }
}