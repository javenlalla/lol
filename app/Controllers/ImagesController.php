<?php

namespace Controllers;

use Models\Image;
use Models\Tag;
use Components\FileDownloader;
use Components\ImageProcessor;
use DateTime;

class ImagesController extends ControllerAbstract
{
    public function getRandomImage()
    {
        //@TODO: Exclude NSWF Images
        $dql = "
            SELECT COUNT(b.id)
            FROM Models\Image b
        ";
        $query = $this->_app->db->createQuery($dql);
        $imagesCount = (int) $query->getSingleScalarResult();
        
        $randomIndex = rand(1, $imagesCount - 1);
        
        $dql = "
            SELECT b
            FROM Models\Image b
        ";

        $query = $this->_app->db->createQuery($dql);
        $query->setFirstResult($randomIndex);
        $query->setMaxResults(1);
        $image = $query->getArrayResult();
        return $this->_respond(
            $image[0]
        );
        
        
        // //@TODO: Exclude NSWF Images
        // $imagesCount = $this->_app->db->createQueryBuilder('Models\Image')
        //     ->count()
        //     ->eagerCursor(true)
        //     ->getQuery()
        //     ->execute();
        
        // $imagesCount--;
        
        // $randomIndex = rand(0, $imagesCount);
        
        // $image = $this->_app->db->createQueryBuilder('Models\Image')
        //     ->limit(1)
        //     ->skip($randomIndex)
        //     ->getQuery()
        //     ->getSingleResult();
        
        // return $this->_respond(
        //     array(
        //         'id'        => $image->getId(),
        //         'name'      => $image->getName(),
        //         'filename'  => $image->getFilename(),
        //         'tags'      => $image->getTags()
        //     )
        // );
    }
    
    public function getAllImages()
    {
        //@TODO Move getUser function to a helper class possibly. Should not be two lines of code to retrieve user.
        //@TODO Validate that a user object is found.
        // $userComponent = new \Components\User();
        // $user = $userComponent->getUser();
        
        $enableNsfwFlag = $this->_app->request->get('enableNsfw');
        $enableNsfw = false;
        if($enableNsfwFlag == 'true') {
            $enableNsfw = true;
        }
        
        // $images = $this->_app->db->getRepository('Models\Image')->findAllRecentByUserId($user->getId(), $enableNsfw);
        $images = $this->_app->db->getRepository('Models\Image')->findAllRecent($enableNsfw);
        
        $imagesArray = array();
        foreach($images as $image) {
            $imagesArray[] = array(
                'id'        => $image->getId(),
                'name'      => $image->getName(),
                'filename'  => $image->getFilename(),
                'compressed_filename' => $image->getCompressedFilename(),
                'nsfw'      => $image->getIsNsfw(),
                'tags'      => $image->getTagsArray()
            );
        }
        
        return $this->_respond($imagesArray);
    }
    
    public function createImage()
    {
        //@TODO: Add validation.
        $params = $this->_getRequestParams();
        
        if(!empty($params->name)) {
            $url = trim($params->url);
            
            //Download image.
            $fileDownloader = new FileDownloader();
            $downloadedFilename = $fileDownloader->download($url);
            
            
            if($downloadedFilename !== false) {
                // $userComponent = new \Components\User();
                // $user = $userComponent->getUser();
                
                //Get compressed version of image.
                $imageProcessor = new ImageProcessor();
                $compressedImageFilename = $imageProcessor->compressImage($downloadedFilename);
                
                $name = trim($params->name);
                $newImage = new Image($name);
                
                // $newImage->setUserId($user->getId());
                
                $tags = explode(",", $params->tags);
                foreach($tags as $tag) {
                    $tag = trim($tag);
                    if(!empty($tag)) {
                        $newImage->addTag(new Tag($tag));
                    }
                }
                
                $newImage->setFilename($downloadedFilename);
                $newImage->setCompressedFilename($compressedImageFilename);
                
                if($params->nsfw !== true) {
                    $newImage->setIsNsfw(0);
                } else {
                    $newImage->setIsNsfw(1);
                }
                
                $newImage->setCreated(new DateTime());
                
                $this->_app->db->persist($newImage);
                $this->_app->db->flush();
                
                $imageArray = array(
                    'id'        => $newImage->getId(),
                    'name'      => $newImage->getName(),
                    'filename'  => $newImage->getFilename(),
                    'compressed_filename' => $newImage->getCompressedFilename(),
                    'nsfw'      => $newImage->getIsNsfw(),
                    'tags'      => $newImage->getTagsArray()
                );
                
                return $this->_respond($imageArray);
            }
            
            return $this->_respond(array(), 500, "Unable to download file from " . $url . ".");
            
        }
        
        return $this->_respond(array(), 400, "Invalid name provided.");
    }
    
    public function updateImage($id)
    {
        $image = $this->_app->db->find('Models\Image', $id);
        $params = $this->_getRequestParams();
        
        if(!empty($image)) {
            //@TODO: Add validation
            $name = trim($params->name);
            
            $tags = explode(",", $params->tags);
            
            $image->setName($name);
            
            if($params->nsfw !== true) {
                $image->setIsNsfw(0);
            } else {
                $image->setIsNsfw(1);
            }
            
            // $tagsToUpdate = array();
            
            // foreach($tags as $tag) {
            //     $tagToUpdate = trim($tag);
            //     if(!empty($tagToUpdate)) {
            //         $tagsToUpdate[] = trim($tag);
            //     }
            // }
            
            $currentTags = $image->getTagsArray();
            $tagsNoChanges = array_intersect($tags, $currentTags);
            foreach($currentTags as $key => $value) {
                if(in_array($value, $tagsNoChanges)) {
                    unset($currentTags[$key]);
                }
            }
            
            foreach($tags as $key => $value) {
                if(in_array($value, $tagsNoChanges)) {
                    unset($tags[$key]);
                }
            }
            
            foreach($tags as $tag) {
                $tag = trim($tag);
                if(!empty($tag)) {
                    $image->addTag(new Tag($tag));
                }
            }
            
            //Loop through tags that should be removed.
            foreach($currentTags as $tag) {
                $criteria = \Doctrine\Common\Collections\Criteria::create()
                    ->where(\Doctrine\Common\Collections\Criteria::expr()->eq("tag", $tag))
                    ->setMaxResults(1)
                ;
                $tagFetch = $image->getTags()->matching($criteria);
                $image->removeTag($tagFetch[0]);
                $this->_app->db->remove($tagFetch[0]);
            }

            $this->_app->db->persist($image);
            $this->_app->db->flush();
            
            return $this->_respond(array(
                'id'    => $image->getId(),
                'name'  => $image->getName(),
                'nsfw'  => $image->getIsNsfw(),
                'tags'  => $image->getTagsArray()
            ));
        }
        
        return $this->_respond(array(), 404, "Image not found with ID: ".$id);
    }
    
    public function deleteImage($id)
    {
        //@TODO: Delete image file.
        $image = $this->_app->db->find('Models\Image', $id);
        if(!empty($image)) {
            $this->_app->db->remove($image);
            $this->_app->db->flush();
        }
        
        return $this->_respond();
    }
}