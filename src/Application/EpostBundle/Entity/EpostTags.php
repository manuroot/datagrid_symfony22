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
 * @ORM\Table(name="epost_tags")
 * @ORM\Entity(repositoryClass="Application\EpostBundle\Repository\EpostTagsRepository")
 * @UniqueEntity(fields="name", message="Ce post existe déjà...")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class EpostTags {

 
        /**
* Tag id
*
* @var integer $id
* @ORM\Column(name="id", type="integer")
* @ORM\Id
* @ORM\GeneratedValue(strategy="AUTO")
*/
    private $id;

    /**
* Tag text
*
* @var text $text
* @Assert\NotBlank()
* @ORM\Column(name="text", type="string", length=255)
*/
    private $name;

    /**
* @var Doctrine\Common\Collections\ArrayCollection
*
* @ORM\ManyToMany(targetEntity="Application\EpostBundle\Entity\Epost", mappedBy="tags")
*/
    private $posts;

    /**
* Entity constructor
*
* @param string $text A tag text
*
* @return void
*/
    
 /**
* Tag slug
* @var text $text
* @ORM\Column(name="slug", type="string", length=255)
*/
    
   protected $slug;

   
public function __construct($text = null)
    {
        $this->text = $text;
        $this->posts = new ArrayCollection();
    }

    /**
* Get Tag id
*
* @return int
*/
    public function getId()
    {
        return $this->id;
    }

      /**
     * {@inheritdoc}
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return $this->slug;
    }
     /**
     * source : http://snipplr.com/view/22741/slugify-a-string-in-php/
     *
     * @static
     *
     * @param string $text
     *
     * @return mixed|string
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
* Set Tag text
*
* @param string $text A tag text
*
* @return void
*/
    public function setName($name)
    {
        $this->name = $name;
         $this->setSlug(self::slugify($name));
 
    }

    /**
* Get Tag text
*
* @return string
*/
    public function getName()
    {
        return $this->name;
    }

    /**
* Get posts for this tag
*
* @return ArrayCollection
*/
    public function getPosts()
    {
        return $this->posts;
    }

    /**
* This method allows a class to decide how it will react when it is treated like a string
*
* @return string
*/
    public function __toString()
    {
        return $this->getName();
    }
}