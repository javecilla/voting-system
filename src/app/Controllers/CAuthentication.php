<?php
declare(strict_types = 1);

namespace App\Controllers;

/* [This Class will Handle authentication and reCAPTCHA verification for various systems.] */
class CAuthentication 
{
  private $serverSideToken = "6LcyI_snAAAAAL6BpXOHb7qmR-p9SdNptYvPiIJe";

  /* [Verifies a reCAPTCHA token against Google's API.] */
  public function verifyRecaptha($responseAction, $clientSideToken)
  {
    $curlData = ['secret' => $this->serverSideToken, 'response' => $clientSideToken];

    // Validate token using Google API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
   	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curlData));
   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $curlResponse = curl_exec($ch);
    $captchaResponse = json_decode($curlResponse, true);

    // Check reCAPTCHA response
    if($captchaResponse['success'] === true
     	&& $captchaResponse['action'] === $responseAction
     	&& $captchaResponse['score'] >= 0.5
     	&& $captchaResponse['hostname'] === $_SERVER['SERVER_NAME']) {
      return ['success' => true, 'message' => 'Validated'];
    } else {
      return ['success' => false, 'message' => 'You are not human.'];
    }
  }
}
	