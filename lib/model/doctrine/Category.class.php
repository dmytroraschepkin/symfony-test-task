<?php

/**
 * Category
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    shop
 * @subpackage model
 * @author     Dmitriy
 */
class Category extends BaseCategory
{
  public function generateThumbnail($uploadedFileName = null)
  {
    if(!$uploadedFileName)
    {
      $uploadedFileName = $this->getImage();
    }
    $thumbnail = new sfThumbnail(sfConfig::get('app_category_thumbnail_width', 90), sfConfig::get('app_category_thumbnail_height', 90));
    $thumbnail->loadFile($this->getUploadRootDir() . DIRECTORY_SEPARATOR . $uploadedFileName);
    $thumbnail->save($this->getUploadRootDir(true) . DIRECTORY_SEPARATOR . $uploadedFileName);
  }

  public function removeThumbnail()
  {
    $thumbnailFileName = $this->getAbsoluteImagePath(true);
    if(file_exists($thumbnailFileName)){
      return unlink($thumbnailFileName);
    }

    // TODO: Log error since file not found
    return false;
  }

  public function getAbsoluteImagePath($thumbnail = false)
  {
    $result = $this->getImage();
    if(null !== $result)
    {
      $result = $this->getUploadRootDir($thumbnail) . '/' . $result;
    }
    return $result;
  }

  public function getWebImagePath($thumbnail = false)
  {
    $result = $this->getImage();
    if(null !== $result)
    {
      $result = $this->getUploadDir($thumbnail) . '/' . $result;
    }
    return $result;
  }

  public function getUploadRootDir($thumbnail = false)
  {
    return sfConfig::get('sf_web_dir') . $this->getUploadDir($thumbnail);
  }

  public function getUploadDir($thumbnail = false)
  {
    if($thumbnail)
    {
      return sfConfig::get('app_category_upload_dir_thumbnail', '/uploads/categories/thumbnail');
    }
    return sfConfig::get('app_category_upload_dir', '/uploads/categories');
  }
}
