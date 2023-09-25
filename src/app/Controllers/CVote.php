<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Models\MVote;
/**
 * 
 */
class CVote extends MVote
{
  

  public static function processVote($data)
  {
  	$create = new MVote(); 
  	$result = $create->voteCount($data);
  	return $result;
  }

  public static function updateVotePayment($data) 
  {
    $update = new MVote(); 
    $result = $update->setPayment($data);
    return $result;
  }

  public static function updateVoteStatus($vid, $vstatus)
  {
    $update = new MVote(); 
    $result = $update->setStatus($vid, $vstatus);
    return $result;
  }

  public static function deleteRecord($vid)
  {
    $delete = new MVote(); 
    $result = $delete->deleteVote($vid);
    return $result;
  }
}