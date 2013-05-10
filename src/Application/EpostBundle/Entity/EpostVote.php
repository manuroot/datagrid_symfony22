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
use FOS\CommentBundle\Entity\Vote as BaseVote;

/**
 * @ORM\Table(name="epost_vote")
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class EpostVote extends BaseVote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Comment of this vote
     *
     * @var Comment
     * @ORM\ManyToOne(targetEntity="Application\EpostBundle\Entity\EpostCommentsThread")
     */
    protected $comment;
}