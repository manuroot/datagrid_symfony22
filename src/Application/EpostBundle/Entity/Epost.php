<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\EpostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;

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
 * @ORM\Table(name="epost")
 * @ORM\Entity(repositoryClass="Application\EpostBundle\Repository\EpostRepository")
 * @UniqueEntity(fields="name", message="Ce post existe déjà...")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Epost {

  //  protected $comments = array();
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

    /**
     * @var string
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
     * @Assert\File(
     * maxSize="5M",
     * mimeTypes={"image/png", "image/jpeg", "image/pjpeg","image/gif"}
     * )
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     *
     * @var File $image
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255, name="image_name", nullable=true)
     *
     * @var string $imageName
     */
    protected $imageName;

      /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\OrderBy({"username" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proprietaire", referencedColumnName="id")
     * })
     */
    private $proprietaire;

    /**
     * @var \EpostCategories
     *
     * @ORM\ManyToOne(targetEntity="EpostCategories")
     * @ORM\OrderBy({"nom" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie", referencedColumnName="id")
     * })
     */
    private $categorie;

    /**
     * @var \EpostStatus
     *
     * @ORM\ManyToOne(targetEntity="EpostStatus", inversedBy="epost"))
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_status", referencedColumnName="id")
     *  })
     */
    private $idStatus;

    /**
    * @ORM\OneToMany(targetEntity="EpostComments", mappedBy="epost",cascade={"persist"})
 */
private $comments;

 /**
    * @ORM\OneToMany(targetEntity="EpostNotes", mappedBy="epost",cascade={"persist"})
 */
private $notes;


     // @ORM\Column(type="text")
   
 //protected $tags;

    public function getId() {
        return $this->id;
    }

    public function __toString() {
        return $this->getName();
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
     //   $this->history = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Epost
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
     * Set image
     *
     * @param string $image
     * @return Message
     */
    public function setImage($image) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     * @return Message
     */
    public function setImageName($imageName) {
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName() {
        return $this->imageName;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Message
     */
    public function setUpdatedAt() {
        $this->updatedAt = new \DateTime();
//$this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
        if (!isset($this->updatedAt))
            $this->updatedAt = new \DateTime();
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
       $this->setUpdatedAt(new \DateTime());
    }

    /**
     * Set proprietaire
     *
     * @param \Application\Sonata\UserBundle\Entity\User $proprietaire
     * @return Epost
     */
    public function setProprietaire(\Application\Sonata\UserBundle\Entity\User $proprietaire = null) {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * Get proprietaire
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getProprietaire() {
        return $this->proprietaire;
    }

    /**
     * Set project
     *
     * @param \Application\EpostBundle\Entity\CertificatsProjet $project
     * @return CertificatsCenter
     */
    public function setCategorie(\Application\EpostBundle\Entity\EpostCategories $categorie = null) {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Application\EpostBundle\Entity\CertificatsProjet 
     */
    public function getCategorie() {
        return $this->categorie;
    }

    /**
     * Set idStatus
     *
     * @param \Application\EpostBundle\Entity\EpostStatus $idStatus
     * @return Epost
     */
    public function setIdStatus(\Application\EpostBundle\Entity\EpostStatus $idStatus = null) {
        $this->idStatus = $idStatus;

        return $this;
    }

    /**
     * Get idStatus
     *
     * @return \Application\EpostBundle\Entity\EpostStatus 
     */
    public function getIdStatus() {
        return $this->idStatus;
    }

   
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Epost
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Remove comments
     *
     * @param \Application\EpostBundle\Entity\EpostComments $comments
     */
    public function removeComment(\Application\EpostBundle\Entity\EpostComments $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Add comments
     *
     * @param \Application\EpostBundle\Entity\EpostComments $comments
     * @return Epost
     */
    public function addComment(\Application\EpostBundle\Entity\EpostComments $comments)
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

    /**
     * Add notes
     *
     * @param \Application\EpostBundle\Entity\EpostNotes $notes
     * @return Epost
     */
    public function addNote(\Application\EpostBundle\Entity\EpostNotes $notes)
    {
        $this->notes[] = $notes;
    
        return $this;
    }

    /**
     * Remove notes
     *
     * @param \Application\EpostBundle\Entity\EpostNotes $notes
     */
    public function removeNote(\Application\EpostBundle\Entity\EpostNotes $notes)
    {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotes()
    {
        return $this->notes;
    }
}