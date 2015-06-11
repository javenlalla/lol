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
    
    public function findAllRecentByUserId($userId, $enableNsfw = false)
    {
        $qb = $this->createQueryBuilder('Models\Image');
        
        if($enableNsfw !== true) {
            $qb->field('nsfw')->equals(0);
        }
        
        $qb->field('userId')->equals(new \MongoId($userId));
        
        return $qb
            ->sort('created', 'DESC')
            ->eagerCursor(true)
            ->getQuery()
            ->execute();
            
        // $qb = $this->createQueryBuilder('Models\User');
        //     // ->select('images.name');
        
        // // if($enableNsfw !== true) {
        // //     $qb->field('nsfw')->equals(0);
        // // }
        // // echo $userId;
        // $qb->field('Image.$id')->equals('5578be24d835375a0f8b4568');
        
        // return $qb
        //     // ->sort('images.created', 'DESC')
        //     ->eagerCursor(true)
        //     ->getQuery()
        //     ->execute();
        
        //db.Image.update({},{$set: {nsfw: 0}},{ multi: true });
        $qb = $this->createQueryBuilder('Models\Image');
        
        if($enableNsfw !== true) {
            $qb->field('nsfw')->equals(0);
        }
        
        $qb->field('user_id')->equals($userId);
        
        return $qb
            ->sort('created', 'DESC')
            ->eagerCursor(true)
            ->getQuery()
            ->execute();
    }
}