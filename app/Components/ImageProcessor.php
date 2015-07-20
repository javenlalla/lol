<?php

namespace Components;

class ImageProcessor extends ComponentAbstract
{
    const IMAGES_FOLDER_PATH = "i/";
    
    public function compressImage($imageFilename)
    {
        $filenameParts = $this->_getFilenameParts($imageFilename);
        
        //If image is a gif, get first frame and use that as thumb base.
        //Else, use current image as thumb base.
        if($filenameParts['extension'] == 'gif') {
            $imageBaseFilename = $this->_getGifFrame($filenameParts);
        } else {
            $imageBaseFilename = $this->_createImageBase($filenameParts);
        }
        
        $this->_compressImage($imageBaseFilename);
        
        return $imageBaseFilename;
        //convert 'QmAbdq.gif[0]' frame.jpg
    }
    
    /**
     * Retrieve file parts from filename.
     * 
     * @param $filename string Target filename to retrieve filename parts from.
     * @return array Array of filename parts.
     */
    private function _getFilenameParts($filename)
    {
        $parts = explode(".", $filename);
        
        return array(
            'filename'  =>  $filename,
            'name'      =>  $parts[0],
            'extension' =>  $parts[1],
        );
    }
    
    private function _getGifFrame($filenameParts)
    {
        $gifFrameFilename = $this->_getImageBaseName($filenameParts['name']) . ".jpg";
        
        $commandBase = "convert ".self::IMAGES_FOLDER_PATH."'%s[0]' ".self::IMAGES_FOLDER_PATH."%s";
        
        $command = sprintf($commandBase, $filenameParts['filename'], $gifFrameFilename);
        shell_exec($command);
        
        return $gifFrameFilename;
    }
    
    private function _createImageBase($filenameParts)
    {
        $imageBaseFilename = $this->_getImageBaseName($filenameParts['name']);
        $imageBaseFilename .= ".".$filenameParts['extension'];
        
        $src = self::IMAGES_FOLDER_PATH.$filenameParts['filename'];
        $dest = self::IMAGES_FOLDER_PATH.$imageBaseFilename;
        
        //@TODO Error Handling if 'copy' fails.
        copy($src, $dest);
        
        return $imageBaseFilename;
    }
    
    private function _getImageBaseName($name)
    {
        return $name."_b";
    }
    
    private function _compressImage($filename)
    {
        $filenamePath = self::IMAGES_FOLDER_PATH.$filename;
        $commandBase = "convert -resize 200x -strip -interlace Plane -gaussian-blur 0.05 -quality 80%% %s %s";
        
        $command = sprintf($commandBase, $filenamePath, $filenamePath);
        
        shell_exec($command);
    }
    
}