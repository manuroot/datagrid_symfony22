<?php

namespace Application\EpostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * EpostNotes
 *
 * @ORM\Table(name="epost_notes")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\EpostBundle\Repository\EpostNotesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EpostNotes {

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
     * @ORM\ManyToOne(targetEntity="Epost", inversedBy="notes", cascade={"persist"})
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $epost;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;
    
    public function getId() {
        return $this->id;
    }

    public function __construct() {
      //  $this->projets = new \Doctrine\Common\Collections\ArrayCollection();
           $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
     
    }
 /**
     * @ORM\preUpdate()
     */
    public function UpdatedValue()
    {
       $this->setUpdated(new \DateTime());
    }
    
    /**
 * @ORM\PrePersist()
 */
public function setCreatedValue()
{
    $this->created = new \DateTime();
}
    
    
      public function __toString() {
        return $this->getId();
    }
    
    

    /**
     * Set note
     *
     * @param integer $note
     * @return EpostNotes
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
     * @return EpostNotes
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
     * Set post
     *
     * @param \Application\EpostBundle\Entity\Epost $post
     * @return EpostNotes
     */
    public function setEpost(\Application\EpostBundle\Entity\Epost $epost = null)
    {
        $this->epost = $epost;
    
        return $this;
    }

    /**
     * Get post
     *
     * @return \Application\EpostBundle\Entity\Epost 
     */
    public function getEpost()
    {
        return $this->epost;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return EpostNotes
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return EpostNotes
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}