<?php

namespace App\Services;

use App\Providers\View;

class Router
{
  public static function handle($method, $path, $filename)
  {
    $cMethod = $_SERVER['REQUEST_METHOD'];
    $cURI = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    //specify register path 
    $registerPath = [
      '/'
    ];

    if($cMethod === $method) {
      if(in_array($cURI, $registerPath)) {
        if ($cURI === $path) {
          $title = self::getPageTitle($cURI); //change the page title based on the URI
          echo View::render($filename, $title); //render the page content based on the URI
          return true;
        } else {
         	return false;
        }
      } else {
        View::error();
        return false;
      }
    } else {
      return false;
   	}
  }

  //help method to determine the page title
  private static function getPageTitle($uri)
  {
    $pageTitle = '';
    switch ($uri) {
      case '/':
        $pageTitle .= 'Jerome Avecilla | Portfolio';
        break;
      default:
        $pageTitle .= 'Unknown'; 
        break;
    }
    return $pageTitle;
  }
}
