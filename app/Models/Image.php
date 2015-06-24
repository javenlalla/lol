<?php
namespace Models;

// use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @Entity(repositoryClass="ImageRepository") @Table(name="images")
 **/
class Image
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @Column(type="string")
     * @var string
     */
    private $name;
    
    /**
     * @Column(type="string")
     * @var string
     */
    private $filename;
    
    /**
     * @Column(type="integer")
     * @var int
     */
    private $is_nsfw;
    
    // /** @ODM\ObjectId */
    // private $userId;
    
    // /** @ODM\Collection */
    // private $tags = array();
    
    /**
     * @Column(type="datetime")
     * @var DateTime
     */
    private $created;
    
    /**
     * @OneToMany(targetEntity="Tag", mappedBy="image", orphanRemoval=true, cascade={"persist", "remove"})
     **/
    private $tags;

    public function __construct($name)
    {
        $this->name = $name;
        $this->tags = new ArrayCollection();
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
    
    public function getIsNsfw()
    {
        return $this->is_nsfw;
    }
    
    public function setIsNsfw($nsfw)
    {
        $nsfw = (int) $nsfw;
        if($nsfw > 0) {
            $this->is_nsfw = 1;
        } else {
            $this->is_nsfw = 0;
        }
    }
    
    // public function getUserId()
    // {
    //     return $this->userId;
    // }
    
    // public function setUserId($userId)
    // {
    //     $this->userId = $userId;
    // }
    
    public function getTags()
    {
        return $this->tags;
    }
    
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
        $tag->setImage($this);
    }
    
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
        $tag->setImage(null);
    }
    
    public function getTagsArray()
    {
        // var_dump($this->id, $this->tags->count());
        $tags = array();
        foreach($this->tags as $tag) {
            $tags[] = $tag->getTag();
        }
        
        return $tags;
    }
    
    // public function addTag($tag)
    // {
    //     if(!in_array($tag, $this->tags)) {
    //         $this->tags[] = $tag;
    //     }
    // }
    
    public function updateTags(array $tags)
    {
        //Remove tags not in update array.
        // foreach($this->tags as $index => $currentTag) {
        //     if(!in_array($currentTag, $tags)) {
        //         unset($this->tags[$index]);
        //     }
        // }
        
        // $this->tags = array_values($this->tags);
        
        //Add tags in update array.
        foreach($tags as $tag) {
            $newTag = new Tag($tag);
            $newTag->setImageId($this->id);
            $this->tags->add(new Tag($tag));
            
            //Old
            // $this->addTag($tag);
        }
    }
    
    // public function removeTag($tag)
    // {
    //     foreach($this->tags as $index => $currentTag) {
    //         if($currentTag == $tag) {
    //             array_slice($this->tags, $index, 1);
    //         }
    //     }
    // }
    
    public function getCreated()
    {
        return $this->created;
    }
    
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
    }
}