<?php
declare(strict_types = 1);

namespace App\Views;

use App\Models\MRanking;
/**
 * 
 */
class VRanking extends MRanking
{
	public static function readRankingByBranch($branchname, $category)
	{
		$branchRanking = new MRanking();
		$result = $branchRanking->getCandidatesRanking($branchname, $category);
		return $result;
	}
}