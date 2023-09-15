<?php
declare(strict_types = 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Views\VDashboard;
use App\Views\VVote;

if(isset($_GET['action']) && $_GET['action'] === "read") {
	$task = isset($_GET['task']) ? $_GET['task'] : '';
	switch($task) {
		case 'getPendingVotes':
			$branch = isset($_GET['branchname']) ? $_GET['branchname'] : '';
			$pendingVotes = VVote::readPendingVotes($branch);
			echo !empty($pendingVotes) ? $pendingVotes : "No data found";
			break;

		case 'getNumberOfVoters':
			$branch = isset($_GET['branchname']) ? $_GET['branchname'] : '';
			$numberOfVoters = VVote::readNumberOfVoters($branch);
			echo !empty($numberOfVoters) ? $numberOfVoters : "No data found";
			break;

		case 'getTotalAmmountPayment':
			$branch = isset($_GET['branchname']) ? $_GET['branchname'] : '';
			$result = VVote::readTotalAmmountPayment($branch);
			echo !empty($result) ? $result[0]['total_ammount'] : "No data found";
			break;

		default:
			break;
	}
}