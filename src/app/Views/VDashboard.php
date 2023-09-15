<?php
declare(strict_types = 1);

namespace App\Views;

use App\Models\MDashboard;
/**
 * 
 */
class VDashboard extends MDashboard
{
	public static function readAllTotalPendingVotes()
	{
		$allPendingVotes = new MDashboard();
		$result = $allPendingVotes->getAllPendingVotes();
		return $result;
	}
}