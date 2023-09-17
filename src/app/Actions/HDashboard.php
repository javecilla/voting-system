<?php
declare(strict_types = 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Views\VDashboard;
use App\Views\VVote;

if(isset($_GET['action']) && $_GET['action'] === "read") {
	$task = isset($_GET['task']) ? $_GET['task'] : '';
	switch($task) {
		case 'getPendingVotes':
			$pendingVotes = VVote::readAllPendingVotes();
			echo !empty($pendingVotes) ? $pendingVotes : "0";
			break;

		case 'getNumberOfVoters':
			$numberOfVoters = VVote::readNumberOfVoters();
			echo !empty($numberOfVoters) ? $numberOfVoters : "0";
			break;

		case 'getTotalAmmountPayment':
			$result = VVote::readTotalAmmountPayment();
			echo !empty($result) ? $result[0]['total_ammount'] : "0";
			break;

		default:
			break;
	}
}