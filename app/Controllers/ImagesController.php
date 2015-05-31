<?php

namespace Controllers;

use Models\Image;
use Components\FileDownloader;
use DateTime;

class ImagesController extends ControllerAbstract
{
    public function getRandomImage()
    {
        $imagesCount = $this->_app->db->createQueryBuilder('Models\Image')
            ->count()
            ->eagerCursor(true)
            ->getQuery()
            ->execute();
        
        $imagesCount--;
        
        $randomIndex = rand(0, $imagesCount);
        
        $image = $this->_app->db->createQueryBuilder('Models\Image')
            ->limit(1)
            ->skip($randomIndex)
            ->getQuery()
            ->getSingleResult();
        
        return $this->_respond(
            array(
                'id'        => $image->getId(),
                'name'      => $image->getName(),
                'filename'  => $image->getFilename(),
                'tags'      => $image->getTags()
            )
        );
    }
    
    public function getAllImages()
    {
        $images = $this->_app->db->getRepository('Models\Image')->findAllRecent();
        
        $imagesArray = array();
        foreach($images as $image) {
            $imagesArray[] = array(
                'id'        => $image->getId(),
                'name'      => $image->getName(),
                'filename'  => $image->getFilename(),
                'tags'      => $image->getTags()
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
            
            $fileDownloader = new FileDownloader();
            $downloadedFilename = $fileDownloader->download($url);
            
            if($downloadedFilename !== false) {
                $name = trim($params->name);
                $tags = explode(",", $params->tags);
                
                $newImage = new Image($name);
                
                foreach($tags as $tag) {
                    $newImage->addTag(trim($tag));
                }
                
                $newImage->setFilename($downloadedFilename);
                
                $newImage->setCreated(new DateTime());
                
                $this->_app->db->persist($newImage);
                $this->_app->db->flush();
                
                $imageArray = array(
                    'id'        => $newImage->getId(),
                    'name'      => $newImage->getName(),
                    'filename'  => $newImage->getFilename(),
                    'tags'      => $newImage->getTags()
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
            
            $tagsToUpdate = array();
            
            foreach($tags as $tag) {
                $tagToUpdate = trim($tag);
                if(!empty($tagToUpdate)) {
                    $tagsToUpdate[] = trim($tag);
                }
            }
            
            $image->updateTags($tagsToUpdate);
            
            $this->_app->db->persist($image);
            $this->_app->db->flush();
            
            return $this->_respond(array(
                'id'    => $image->getId(),
                'name'  => $image->getName(),
                'tags'  => $image->getTags()
            ));
        }
        
        return $this->_respond(array(), 404, "Image not found with ID: ".$id);
    }
    
    public function deleteImage($id)
    {
        $image = $this->_app->db->find('Models\Image', $id);
        if(!empty($image)) {
            $this->_app->db->remove($image);
            $this->_app->db->flush();
        }
        
        return $this->_respond();
    }
}