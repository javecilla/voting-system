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
      '/admin/candidate-management/',
      '/admin/voting-records/',
      '/select-campus/',
      '/candidates/sta-maria/',
      '/candidates/balagtas/'
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

      case '/admin/candidate-management/':
        $pageTitle .= 'Candidate Management';
        break;

      case '/admin/voting-records/':
        $pageTitle .= 'Voting Records';
        break;

      case '/select-campus/':
        $pageTitle .= 'Golden Minds Colleges';
        break;

      case '/candidates/sta-maria/':
        $pageTitle .= 'Sta. Maria';
        break;

      case '/candidates/balagtas/':
        $pageTitle .= 'Balagtas';
        break;

      default:
        $pageTitle .= 'Unknown'; 
        break;
    }
    return $pageTitle;
  }
}

