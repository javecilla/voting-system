<?php
declare(strict_types = 1);

namespace App\Views;

use App\Models\MRanking;
/**
 * 
 */
class VRanking extends MRanking
{
	public static function rankingCandidates($category) 
	{
		$ranking = new MRanking();
		$result = $ranking->getCandidatesRanking($category);
		return $result;
	}
}