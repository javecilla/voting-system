<?php
declare(strict_types = 1);
/**
 * 
 */
namespace App\Views;

use App\Models\MVote;
// use App\Controllers\CManagement;

class VVote extends MVote
{
	public static function readAllData()
  {
    $allData = new MVote();
    $result = $allData->getAllData();

    return $result;
  }

  public static function readDataByBranch($branchname)
  {
  	$dataByBranch = new MVote();
    $result = $dataByBranch->getDataByBranch($branchname);

    return $result;
  }

  public static function readPendingVotes($branchname)
  {
  	$pendingVotes = new MVote();
    $result = $pendingVotes->getTotalPendingVotes($branchname);

    return $result;
  }
}