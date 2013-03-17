<?php

namespace Application\CertificatsBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tasks
 *
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="Application\CertificatsBundle\Entity\TaskRepository")
 */



class Task
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
    * @ORM\ManyToMany(targetEntity="Tag", inversedBy="tasks",cascade={"persist"})
    */
    private $tags;
    
     // good:
     //  @ORM\ManyToMany(targetEntity="Tag", inversedBy="tasks",cascade={"persist"},orphanRemoval=true))
    // a tester
    //  @ORM\ManyToMany(targetEntity="Tag", inversedBy="tasks",cascade={"persist", "remove"})

    public function __construct() {
       $this->tags = new ArrayCollection();
    }
    
 

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
     * @return Task
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
     * Add tags
     *
     * @param \Application\CertificatsBundle\Entity\Tag $tags
     * @return Task
     */
    public function addTag(\Application\CertificatsBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Application\CertificatsBundle\Entity\Tag $tags
     */
    public function removeTag(\Application\CertificatsBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection $tags
     */
    public function getTags()
    {
        return $this->tags;
    }
    
    public function setTags(ArrayCollection $tags)
{
    foreach ($tags as $tag) {
        $tag->addTask($this);
    }

    $this->tags = $tags;
}
/*
Plus compliquée est la suppression de tous les commentaires d'un utilisateur quand il est retiré du système:

$user = $em->find('User', $deleteUserId);

foreach ($user->getAuthoredComments() AS $comment) {
    $em->remove($comment);
}
$em->remove($user);
$em->flush();
*/


}