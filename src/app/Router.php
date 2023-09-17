<?php
declare(strict_types = 1);

namespace App;

use App\Service;

class Router
{
  public static function handle($method, $path, $filename)
  {
    $cMethod = $_SERVER['REQUEST_METHOD'];
    $cURI = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $isAdmin = strpos($cURI, '/admin/') !== false;
    //specify register path 
    $registerPath = [
      '/admin/dashboard/',
      '/admin/voting-records/',
      '/admin/candidate-management/',
      '/admin/candidates-ranking/',
      '/buwan-ng-wikang-pambansa-2023-lakan-lakanbini-lakandyosa/candidates/'
    ];

    if($cMethod === $method) {
      if(in_array($cURI, $registerPath)) {
        if ($cURI === $path) {
          $title = self::getPageTitle($cURI); //change the page title based on the URI
          echo Service::render($filename, $title, $isAdmin);
          return true;
        } else {
         	return false;
        }
      } else {
        Service::error();
        return false;
      }
    } else {
      return false;
   	}
  }

  public static function testing($string) {
    echo $string;
  }

  //help method to determine the page title
  private static function getPageTitle($uri)
  {
    $pageTitle = 'VS | ';
    switch($uri) {
      case '/admin/dashboard/':
        $pageTitle .= 'Dashboard';
        break;

      case '/admin/voting-records/':
        $pageTitle .= 'Voting Records';
        break;

      case '/admin/candidate-management/':
        $pageTitle .= 'Candidate Management';
        break;

      case '/admin/candidates-ranking/':
        $pageTitle .= 'Candidates Rankings';
        break;

      case '/buwan-ng-wikang-pambansa-2023-lakan-lakanbini-lakandyosa/candidates/':
        $pageTitle .= 'Golden Minds Colleges';
        break;

      default:
        $pageTitle .= 'Unknown'; 
        break;
    }
    return $pageTitle;
  }
}

