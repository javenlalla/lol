<?php
namespace Models;

/**
 * @Entity @Table(name="image_tags")
 **/
class Tag
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    private $id;

    // /**
    //  * @Column(type="integer")
    //  * @var int
    //  */
    // private $image_id;
    
    /**
     * @Column(type="string")
     * @var string
     */
    private $tag;
    
    /**
     * @ManyToOne(targetEntity="Image", inversedBy="tags")
     **/
    private $image;
    
    public function __construct($tag = null)
    {
        if(!empty($tag)) {
            $this->setTag(trim($tag));
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTag()
    {
        return $this->tag;
    }
    
    public function setTag($tag)
    {
        $this->tag = $tag;
    }
    
    // public function getImageId()
    // {
    //     return $this->image_id;
    // }
    
    // public function setImageId($image_id)
    // {
    //     $this->image_id = (int) $image_id;
    // }
    
    public function getImage()
    {
        return $this->image;
    }
    
    public function setImage(Image $image = null)
    {
        $this->image = $image;
    }
    
}