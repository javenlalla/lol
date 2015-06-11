<?php
namespace Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use DateTime;

/** @ODM\Document(repositoryClass="UserRepository") */
class User
{
    /** @ODM\Id */
    private $id;

    /** @ODM\String */
    private $name;
    
    /** @ODM\String */
    private $gid;
    
    /** @ODM\String */
    private $token;
    
    /** @ODM\Date */
    private $created;
    
    /** @ODM\ReferenceMany(targetDocument="Image") */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getGid()
    {
        return $this->gid;
    }
    
    public function setGid($gid)
    {
        $this->gid = $gid;
    }
    
    public function getToken()
    {
        return $this->token;
    }
    
    public function setToken($token)
    {
        $this->token = $token;
    }
    
    public function getCreated()
    {
        return $this->created;
    }
    
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
    }
    
    public function getImages()
    {
        return $this->images;
    }
    public function addImage(Image $image)
    {
        $this->images[] = $image;
    }
}