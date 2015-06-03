<?php
namespace Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\DocumentRepository;
use DateTime;

class ImageRepository extends DocumentRepository
{
    public function findAllRecent($enableNsfw = false)
    {
        //db.Image.update({},{$set: {nsfw: 0}},{ multi: true });
        $qb = $this->createQueryBuilder('Models\Image');
        
        if($enableNsfw !== true) {
            $qb->field('nsfw')->equals(0);
        }
        
        return $qb
            ->sort('created', 'DESC')
            ->eagerCursor(true)
            ->getQuery()
            ->execute();
    }
}