<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Point
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PointRepository")
 */
class Point
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;
    

    /**
     * @ORM\ManyToOne(targetEntity="Map", inversedBy="points")
     */
    protected $map;


    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="points")
     */
    protected $location;


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
     * Set title
     *
     * @param string $title
     * @return Point
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set body
     *
     * @param string $body
     * @return Point
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set map
     *
     * @param \AppBundle\Entity\Map $map
     * @return Point
     */
    public function setMap(\AppBundle\Entity\Map $map = null)
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map
     *
     * @return \AppBundle\Entity\Map 
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set location
     *
     * @param \AppBundle\Entity\Location $location
     * @return Point
     */
    public function setLocation(\AppBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \AppBundle\Entity\Location 
     */
    public function getLocation()
    {
        return $this->location;
    }
}
