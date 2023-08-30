<?php
declare(strict_types = 1);

namespace App;

class Service 
{
  public static function render($filename, $title, $isAdmin)
  {
    $viewContent = self::renderContent($filename);
    $mainContent = file_get_contents(__DIR__ . '/../../build/admin/content/main.php');
    $mainContent = str_replace('{{title}}', $title, $mainContent); 
    if($isAdmin) {
      return str_replace('{{contentAdmin}}', $viewContent, $mainContent);
    } else {
      return str_replace('{{contentUser}}', $viewContent, $mainContent);
    }
  }

  public static function renderContent($filename) 
  {
    ob_start();
    require_once $filename;
    return ob_get_clean();
  }

  public static function error() 
  {
    $errorPath = require_once __DIR__ . '/Exceptions/_404.php';
    return $errorPath;
  }
}

