<?php
declare(strict_types = 1);
/**
 * 
 */
namespace App\Models;

class Database 
{
  private $dbhost = 'localhost';
  private $dbuname = 'root';
  private $dbpword = '';
  private $dbname = 'gmcbulac_db_vs';
  
  protected function db()
  {
    try {
      $dsn = "mysql:host={$this->dbhost};dbname={$this->dbname}";
      $pdo = new \PDO($dsn, $this->dbuname, $this->dbpword);
      $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Set error mode
      return $pdo;
    } catch (\PDOException $e) {
      echo "Connection error: " . $e->getMessage();
      return null;
    }
  }
}