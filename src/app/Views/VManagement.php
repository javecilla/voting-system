<?php
declare(strict_types = 1);
/**
 * 
 */
namespace App\Views;

use App\Models\MManagement;

// use App\Controllers\CManagement;

class VManagement extends MManagement
{
	public static function readAllData()
  {
    $allData = new MManagement();
    $result = $allData->getAllData();

    return $result;
  }

  public static function readDataById($csid)
  {
    $dataById = new MManagement();
    $result = $dataById->getCandidateData($csid);

    return $result;
  }

  public static function filterDataBySearch($inputQuery, $queryCategory)
  {
    $searchData = new MManagement();
    $result = $searchData->getDataBySearch($inputQuery, $queryCategory);

    return $result;
  }

  public static function getAllDataCategory($query) 
  {
    $categoryData = new MManagement();
    $result = $categoryData->getAllDataByCategory($query);

    return $result;
  }

  public static function getAllDataBranch($query) 
  {
    $branchData = new MManagement();
    $result = $branchData->getAllDataByBranch($query);

    return $result;
  }

  public static function getAllDataCategoryCard($squery) 
  {
    $branchData = new MManagement();
    $result = $branchData->getAllDataByCategoryCard($squery);

    return $result;
  }
}