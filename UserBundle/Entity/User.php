<?php
// src/Acme/UserBundle/Entity/User.php

namespace App\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=255, nullable=true)
     */
    protected $facebookId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="facebookAccessToken", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @var text
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    protected $bio;

    /**
     * @var birthday
     * @Assert\Date()
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    protected $birthday;

    /**
     * @ORM\Column(name="locale", type="string", length=5, nullable=true)
     *
     */
    protected $locale;
    
    /**
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;


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
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the full name of the user (first + last name)
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastname();
    }

    /**
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        if( !$this->getUsername() ) $this->setUsername($facebookId);
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookAccessToken
     * @return void
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return User
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    
        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    
        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }


    /**
     * @return \String 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Page
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
     * @return Page
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
     * Set isMagazine
     *
     * @param boolean $isMagazine
     * @return User
     */
    public function setIsMagazine($isMagazine)
    {
        $this->isMagazine = $isMagazine;
    
        return $this;
    }

    /**
     * Get isMagazine
     *
     * @return boolean 
     */
    public function getIsMagazine()
    {
        return $this->isMagazine;
    }
    
    public function getIsMagazineLabel()
    {
        if($this->isMagazine){
            return 'profile.show.magazine_true';
        }
        return 'profile.show.magazine_false';
    }

    /**
     * @ORM\Column(name="isMagazine", type="boolean", nullable=true)
     */
    private $isMagazine = true;
    
    public function serialize()
    {
        return serialize(array($this->facebookId, parent::serialize()));
    }

    public function unserialize($data)
    {
        list($this->facebookId, $parentData) = unserialize($data);
        parent::unserialize($parentData);
    }
    
    /**
     * @param Array
     */
    public function setFBData($fbdata)
    {
        
        if(!$this->getUsername()){
            if(isset($fbdata['username'])){
               $this->setUsername($fbdata['username']); 
            }
        }
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
            $this->addRole('ROLE_FACEBOOK');
        }
        if(!$this->getFirstname()){
            if (isset($fbdata['first_name'])) {
                $this->setFirstname($fbdata['first_name']);
            }
        }
        if(!$this->getLastname()){
            if (isset($fbdata['last_name'])) {
                $this->setLastname($fbdata['last_name']);
            }
        }
        if(!$this->getEmail()){
            if (isset($fbdata['email'])) {
                $this->setEmail($fbdata['email']);
            }
        }
        if(!$this->getBio()){
            if (isset($fbdata['bio'])) {
                $this->setBio($fbdata['bio']);
            }
        }
        if(!$this->getBirthday()){
            if (isset($fbdata['birthday'])) {
                $this->setBirthday( new \DateTime(preg_replace('/(.*)\/(.*)\/(.*)/', '$3-$1-$2', $fbdata['birthday'])) );
            }
        }
    }
    

}