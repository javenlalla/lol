<?php
namespace Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\DocumentRepository;
use DateTime;

class ImageRepository extends DocumentRepository
{
    public function findAllRecent()
    {
        return $this->createQueryBuilder('Models\Image')
            ->sort('created', 'DESC')
            ->eagerCursor(true)
            ->getQuery()
            ->execute();
    }
}