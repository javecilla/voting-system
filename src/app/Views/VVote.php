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
  public static function readCandidateVotes()
  {
    $data = new MVote();
    $result = $data->getCandidateVotes();
    return $result;
  }

  public static function readDataBySID($sid)
  {
    $dataBySid = new MVote();
    $result = $dataBySid->getDataBySid($sid);
    return $result;
  }

  public static function readDataById($vid) 
  {
    $dataById = new MVote();
    $result = $dataById->getDataById($vid);
    return $result;
  }

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

  public static function readAllPendingVotes()
  {
  	$pendingVotes = new MVote();
    $result = $pendingVotes->getTotalPendingVotes();

    return $result;
  }

  public static function readNumberOfVoters()
  {
    $numberVoters = new MVote();
    $result = $numberVoters->getTotalNumberOfVoters();

    return $result;
  }

  public static function readTotalAmmountPayment()
  {
    $amtPayment = new MVote();
    $result = $amtPayment->getTotalAmmountPayment();

    return $result;
  }

  public static function readDataBySearch($searchInput) 
  {
    $dataBySearch = new MVote();
    $result = $dataBySearch->getDataBySearch($searchInput);

    return $result;
  }
}