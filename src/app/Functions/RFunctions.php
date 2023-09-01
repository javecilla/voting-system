<?php
declare(strict_types = 1);

namespace App\Functions;

class RFunctions 
{
  /**
  * This function will handle the image/file upload
  * validate and identify which folder the image
  * upload will go to
  */
	public static function validateImage(array $image)
  {
    $imgName = $image['name'];
    $imgSize = $image['size'];
    $imgTMPname = $image['tmp_name'];
    $error = $image['error'];
    if ($error !== UPLOAD_ERR_OK) {
      return ['success' => false, 'message' => 'Failed to upload the image.'];
    }
    $imgExt = pathinfo($imgName, PATHINFO_EXTENSION);
    $imgExtLC = strtolower($imgExt);
    $validImgExt = ['jpg', 'png', 'jpeg'];
    if (!in_array($imgExtLC, $validImgExt)) {
      return ['success' => false, 'message' => 'Invalid image! Please upload the image with the following format: [jpg, png, jpeg].'];
    }
    $newImgName = 'IMG-' . date('Y-m-d') . '-' . uniqid();

    return ['success' => true, 'imgname' => $newImgName, 'imgext' => $imgExtLC, 'imgtmp' => $imgTMPname];
  }

  public static function validateEmail(string $email) 
  {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      //email is valid
      return ['success' => false, 'message' => 'Invalid email address! Please enter a valid email address.'];
    }
    //email is valid
    return ['success' => true, 'email' => $email];
  }

}