<?php

namespace Application\EpostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CertificatsProjet
 *
 * @ORM\Table(name="epost_categories")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\EpostBundle\Repository\EpostCategoriesRepository")
 */
class EpostCategories {

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
     * @ORM\Column(type="string")
     */
    protected $slug;

      /**
     * @var \EpostCategories
     *
     * @ORM\ManyToOne(targetEntity="Application\EservicesBundle\Entity\EserviceGroup"))
     * @ORM\OrderBy({"nomGroup" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_group", referencedColumnName="id",nullable=true)
     * })
     * @ORM\OrderBy({"nom_group" = "ASC"})
    */
    private $idgroup;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nameProjet
     *
     * @param string $nom
     * @return EpostCategories
     */
    public function setName($name) {
        $this->name = $name;
    $this->setSlug($this->name);
        return $this;
    }

    /**
     * Get namepost
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
    
      public function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }


    /**
     * Set slug
     *
     * @param string $slug
     * @return EpostCategories
     */
    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set idgroup
     *
     * @param \Application\EservicesBundle\Entity\EserviceGroup $idgroup
     * @return EpostCategories
     */
    public function setIdgroup(\Application\EservicesBundle\Entity\EserviceGroup $idgroup = null)
    {
        $this->idgroup = $idgroup;
    
        return $this;
    }

    /**
     * Get idgroup
     *
     * @return \Application\EservicesBundle\Entity\EserviceGroup 
     */
    public function getIdgroup()
    {
        return $this->idgroup;
    }
}