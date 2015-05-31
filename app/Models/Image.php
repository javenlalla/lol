<?php
namespace Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use DateTime;

/** @ODM\Document(repositoryClass="ImageRepository") */
class Image
{
    /** @ODM\Id */
    private $id;

    /** @ODM\String */
    private $name;
    
    /** @ODM\String */
    private $filename;
    
    /** @ODM\Int */
    private $nsfw;
    
    /** @ODM\Collection */
    private $tags = array();
    
    /** @ODM\Date */
    private $created;

    public function __construct($name)
    {
        $this->name = $name;
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
    
    public function getFilename()
    {
        return $this->filename;
    }
    
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
    
    public function getNsfw()
    {
        return $this->nsfw;
    }
    
    public function setNsfw($nsfw)
    {
        $nsfw = (int) $nsfw;
        if($nsfw > 0) {
            $this->nsfw = 1;
        } else {
            $this->nsfw = 0;
        }
    }
    
    public function getTags()
    {
        return $this->tags;
    }
    
    public function addTag($tag)
    {
        if(!in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }
    }
    
    public function updateTags(array $tags)
    {
        //Remove tags not in update array.
        foreach($this->tags as $index => $currentTag) {
            if(!in_array($currentTag, $tags)) {
                unset($this->tags[$index]);
            }
        }
        
        $this->tags = array_values($this->tags);
        
        //Add tags in update array.
        foreach($tags as $tag) {
            $this->addTag($tag);
        }
    }
    
    public function removeTag($tag)
    {
        foreach($this->tags as $index => $currentTag) {
            if($currentTag == $tag) {
                array_slice($this->tags, $index, 1);
            }
        }
    }
    
    public function getCreated()
    {
        return $this->created;
    }
    
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
    }
}