<?php
// src/Blogger/BlogBundle/Twig/Extensions/BloggerBlogExtension.php

namespace Application\EpostBundle\Twig\Extensions;

class ApplicationEpostExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'created_ago' => new \Twig_Filter_Method($this, 'createdAgo'),
        );
    }

    public function createdAgo(\DateTime $dateTime)
    {
        $delta = time() - $dateTime->getTimestamp();
        if ($delta < 0)
            throw new \Exception("createdAgo is unable to handle dates in the future");

        $duration = "";
        if ($delta < 60)
        {
            // Seconds
            $time = $delta;
            $duration = $time . " seconde" . (($time > 1) ? "s" : "") ;
        }
        else if ($delta <= 3600)
        {
            // Mins
            $time = floor($delta / 60);
            $duration = $time . " minute" . (($time > 1) ? "s" : "") ;
        }
        else if ($delta <= 86400)
        {
            // Hours
            $time = floor($delta / 3600);
            $duration = $time . " heure" . (($time > 1) ? "s" : "") ;
        }
        else
        {
            // Days
            $time = floor($delta / 86400);
            $duration = $time . " jour" . (($time > 1) ? "s" : "") ;
        }

        return $duration;
    }

    public function getName()
    {
        return 'application_epost_twig_extension';
    }
}
