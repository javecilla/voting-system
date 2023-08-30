<?php

namespace App\Providers;

class View 
{
 	public static function render($filename, $title)
  {
    $viewContent = self::renderContent($filename);
    $mainContent = file_get_contents(__DIR__ . '/../../build/content/main.php');
    $mainContent = str_replace('{{title}}', $title, $mainContent); 
    return str_replace('{{content}}', $viewContent, $mainContent);
  }

  public static function renderContent($filename) 
  {
    ob_start();
    require_once $filename;
    return ob_get_clean();
  }

  public static function error() 
  {
    return require_once __DIR__ . '/../Exceptions/pages/_404.html';
  }
}