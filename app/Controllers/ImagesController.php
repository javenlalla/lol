<?php

namespace Controllers;

use Models\Image;
use Components\FileDownloader;

class ImagesController extends ControllerAbstract
{
    public function getAllImages()
    {
        $images = $this->_app->db->getRepository('Models\Image')->findAll();
        
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
            $name = trim($params->name);
            $url = trim($params->url);
            $tags = explode(",", $params->tags);
            
            $newImage = new Image($name);
            
            foreach($tags as $tag) {
                $newImage->addTag(trim($tag));
            }
            
            $fileDownloader = new FileDownloader();
            $fileDownloader->download($url);
            die;
            
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