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

  public static function readNumberOfVoters($branchname)
  {
    $numberVoters = new MVote();
    $result = $numberVoters->getTotalNumberOfVoters($branchname);

    return $result;
  }

  public static function readTotalAmmountPayment($branchname)
  {
    $amtPayment = new MVote();
    $result = $amtPayment->getTotalAmmountPayment($branchname);

    return $result;
  }

  public static function readDataBySearch($searchInput, $branchname) 
  {
    $dataBySearch = new MVote();
    $result = $dataBySearch->getDataBySearch($searchInput, $branchname);

    return $result;
  }
}