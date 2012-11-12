<?php

namespace App\UploadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * App\UploadBundle\Entity\Image
 *
 * @ORM\Table(name="upload_image")
 * @ORM\Entity
 */
class Image
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User")
    */
    protected $createdUser;
    
    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;
    
    /**
     * @var string $body
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=64, nullable=true)
     */
    private $type;

    /**
     * @var string $size
     *
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    private $size;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    public $uploadpath;
    
    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;


    public static function getUploadRootDir()
    {
        return __DIR__.'/../../../../web';
    }
    public static function getUploadDir()
    {
        return '/uploads/documents';
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
     * Set title
     *
     * @param string $title
     * @return Image
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
     * @return Image
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
     * Set type
     *
     * @param string $type
     * @return Image
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return Image
     */
    public function setSize($size)
    {
        $this->size = $size;
    
        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set uploadpath
     *
     * @param string $uploadpath
     * @return Image
     */
    public function setUploadpath($uploadpath)
    {
        $this->uploadpath = $uploadpath;
    
        return $this;
    }

    /**
     * Get uploadpath
     *
     * @return string 
     */
    public function getUploadpath()
    {
        return $this->uploadpath;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Image
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Image
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdUser
     *
     * @param \App\UserBundle\Entity\User $createdUser
     * @return Image
     */
    public function setCreatedUser(\App\UserBundle\Entity\User $createdUser = null)
    {
        $this->createdUser = $createdUser;
    
        return $this;
    }

    /**
     * Get createdUser
     *
     * @return \App\UserBundle\Entity\User 
     */
    public function getCreatedUser()
    {
        return $this->createdUser;
    }
}