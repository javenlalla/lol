<?php
namespace Models;

use Doctrine\ORM\EntityRepository;

class ImageRepository extends EntityRepository
{
    public function findAllRecent($enableNsfw = false)
    {
        $dql = "
            SELECT b, t
            FROM Models\Image b
                LEFT JOIN b.tags t
        ";
        
        if($enableNsfw !== true) {
            $dql .= " WHERE b.is_nsfw = 0";
        }
        
        $dql .= " ORDER BY b.created DESC";

        $query = $this->getEntityManager()->createQuery($dql);
        // $query->setMaxResults(30);
        // $images = $query->getResult();
        
        return $query->getResult();
        
        echo json_encode($images);
        
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