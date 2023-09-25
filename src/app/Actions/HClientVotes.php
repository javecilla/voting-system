<?php
declare(strict_types = 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Functions\RFunctions;
use App\Controllers\{CVote, CAuthentication};
use App\Views\VVote;

define('CREATE', 'create');
define('READ', 'read');
define('UPDATE', 'update');
define('DELETE', 'delete');

if(isset($_POST['action'])) {
	$response = [];
	$action = $_POST['action'];
	$gaction = $_POST['gaction'];
  $ctoken = $_POST['ctoken'];

	switch ($action) {
		case CREATE:
			$client = new CAuthentication();
  		$validateRecaptcha = $client->verifyRecaptha($gaction, $ctoken);
  		if($validateRecaptcha['success']) {
				$emailValidationResult = RFunctions::validateEmail($_POST['votersEmail']);
				if($emailValidationResult['success']) {
					$dataForm = [
						'sid' => $_POST['sid'],
						'amtPayment' => $_POST['amtPayment'],
						'votePoints' => $_POST['votePoints'],
						'referrenceNumber' => filter_input(INPUT_POST, 'referrenceNumber', FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_NO_ENCODE_QUOTES),
						'votersEmail' => $emailValidationResult['email']
					];
					$result = CVote::processVote($dataForm);
					$response = ['success' => true, 'message' => $result['message']];
				} else {
					$response = ['success' => false, 'message' => $emailValidationResult['message']];
				}
			} else {
  			$response = ['success' => false, 'message' => $validateRecaptcha['message']];
  		}
			break;

		case UPDATE:
			if(isset($_POST['task']) && $_POST['task'] === 'updatePayment') {
				$dataForm = [
					'vid' => $_POST['vid'],
					'amtPayment' => $_POST['amtPayment'],
					'votePoints' => $_POST['votePoints']
				];
				$result = CVote::updateVotePayment($dataForm);
			} else {
				$result = CVote::updateVoteStatus($_POST['vid'], $_POST['vstatus']);
			}

			$response = ['success' => true, 'message' => $result['message']];
			break;

		case DELETE:
			$result = CVote::deleteRecord($_POST['vid']);
			$response = ['success' => true, 'message' => $result['message']];
			break;
		
		default:
			// code...
			break;
	}
	
	header('Content-Type: application/json');
	echo json_encode($response);
}


if(isset($_GET['action']) && $_GET['action'] === READ && isset($_GET['task'])) {
	switch($_GET['task']) {
		case 'allRecords':
			// to fetch all candidate records/data
			$result = VVote::readAllData();
			foreach($result as $row): 
				$datetimeVoted = date("F j, Y, g:iA", strtotime($row['vote_datetime'])); 
				?> 
					<tr>
	          <td>00<?=$row['vid']?></td>
	          <td>00<?=$row['sid']?></td>
	          <td><?=$row['cid']?></td>
	          <td><span class="badge rounded-pill bg-primary">₱<?=$row['amt_payment']?></span></td>
	          <td><span class="badge rounded-pill bg-info"><?=$row['vote_points']?></span></td>
	          <td><?=$row['referrence_no']?></td>
	          <td><?=$row['voters_email']?></td>
	          <td>
	          	<!-- Votes pending -->
	          	<?php if($row['vote_status'] === 0): ?>
	          		<button class="btn btn-sm btn-warning"
		              type="button"
		              id="updateVoteStatus"
		              data-id="<?=$row['vid']?>"
		              data-value="1">
		              Pending
	            	</button>
	            <!-- Votes is verified -->
	          	<?php else: ?>
	          		<button class="btn btn-sm btn-success"
		              type="button"
		              id="updateVoteStatus"
		              data-id="<?=$row['vid']?>"
		              data-value="0">
		              Verified
	            	</button>
	          	<?php endif; ?>
	          </td>
	          <td><?=$datetimeVoted;?></td>
	          <td>
	            <button class="btn btn-danger btn-sm"
	           	 	type="button"
	            	id="removeVote"
		            data-id="<?=$row['vid']?>" 
	              data-bs-toggle="tooltip" 
	              data-bs-title="Delete this vote">
	              <i class="fas fa-user-minus"></i>&nbsp;
	            </button>
	            <button class="btn btn-warning btn-sm"
	           	 	type="button"
	            	id="updateVoteFormModal"
		            data-id="<?=$row['vid']?>" 
	              data-bs-toggle="tooltip" 
	              data-bs-title="Update this vote">
	              <i class="fa-solid fa-user-pen"></i>&nbsp;
	            </button>
	          </td>
	        </tr>
				<?php
			endforeach;
			break;

		case 'byId':
			$voteData = VVote::readDataById($_GET['vid']);
			?>
				<div class="row">
          <div class="col-md-6">
            <div class="form-floating">
              <select class="form-select" id="amountPayment" aria-label="Floating label select example">
                <option selected><?=$voteData['amt_payment']?></option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
              <label for="amountPayment">Amount of Payment</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input type="text" value="<?=$voteData['vote_points']?>" class="form-control" id="votePoints" style="pointer-events: none;" />
              <label for="votePoints">Equivalent Points</label>
            </div>
          </div>
          <input type="hidden" value="<?=$_GET['vid']?>" id="vidModal"/>
        </div>
			<?php
			break;
		
		case 'candidatesByVote':
			$result = VVote::readCandidateVotes();
			?>
				<select class="form-select" id="filterVotesByCandidate" aria-label="Floating label select example">
            <option value="" selected>--SELECT--</option>
            <?php
            	foreach($result as $data):
            		?><option value="<?=$data['sid']?>"><?=$data['cname']?></option><?php
            	endforeach;
            ?>
          </select>
         <label for="filterVotesByCandidate">Filter Votes by Candidate</label>

			<?php
			break;

		case 'voteRecordsBySID':
			$result = VVote::readDataBySID($_GET['sid']);
			foreach($result as $row): 
				$datetimeVoted = date("F j, Y, g:iA", strtotime($row['vote_datetime'])); 
				?> 
					<tr>
	          <td>00<?=$row['vid']?></td>
	          <td>00<?=$row['sid']?></td>
	          <td><?=$row['cid']?></td>
	          <td><span class="badge rounded-pill bg-primary">₱<?=$row['amt_payment']?></span></td>
	          <td><span class="badge rounded-pill bg-info"><?=$row['vote_points']?></span></td>
	          <td><?=$row['referrence_no']?></td>
	          <td><?=$row['voters_email']?></td>
	          <td>
	          	<!-- Votes pending -->
	          	<?php if($row['vote_status'] === 0): ?>
	          		<button class="btn btn-sm btn-warning"
		              type="button"
		              id="updateVoteStatus"
		              data-id="<?=$row['vid']?>"
		              data-value="1">
		              Pending
	            	</button>
	            <!-- Votes is verified -->
	          	<?php else: ?>
	          		<button class="btn btn-sm btn-success"
		              type="button"
		              id="updateVoteStatus"
		              data-id="<?=$row['vid']?>"
		              data-value="0">
		              Verified
	            	</button>
	          	<?php endif; ?>
	          </td>
	          <td><?=$datetimeVoted;?></td>
	          <td>
	            <button class="btn btn-danger btn-sm"
	           	 	type="button"
	            	id="removeVote"
		            data-id="<?=$row['vid']?>" 
	              data-bs-toggle="tooltip" 
	              data-bs-title="Delete this vote">
	              <i class="fas fa-user-minus"></i>&nbsp;
	            </button>
	          </td>
	        </tr>
				<?php
			endforeach;
			break;

		case 'byBranch':
			$result = VVote::readDataByBranch($_GET['branchname']);
			foreach($result as $row): 
				$datetimeVoted = date("F j, Y, g:iA", strtotime($row['vote_datetime'])); 
				?> 
					<tr>
	          <td>00<?=$row['vid']?></td>
	          <td>00<?=$row['sid']?></td>
	          <td><?=$row['cid']?></td>
	          <td><span class="badge rounded-pill bg-primary">₱<?=$row['amt_payment']?></span></td>
	          <td><span class="badge rounded-pill bg-info"><?=$row['vote_points']?></span></td>
	          <td><?=$row['referrence_no']?></td>
	          <td><?=$row['voters_email']?></td>
	          <td>
	          	<!-- Votes pending -->
	          	<?php if($row['vote_status'] === 0): ?>
	          		<button class="btn btn-sm btn-warning"
		              type="button"
		              id="updateVoteStatus"
		              data-id="<?=$row['vid']?>"
		              data-value="1">
		              Pending
	            	</button>
	            <!-- Votes is verified -->
	          	<?php else: ?>
	          		<button class="btn btn-sm btn-success"
		              type="button"
		              id="updateVoteStatus"
		              data-id="<?=$row['vid']?>"
		              data-value="0">
		              Verified
	            	</button>
	          	<?php endif; ?>
	          </td>
	          <td><?=$datetimeVoted;?></td>
	          <td>
	            <button class="btn btn-danger btn-sm"
	           	 	type="button"
	            	id="removeVote"
		            data-id="<?=$row['vid']?>" 
	              data-bs-toggle="tooltip" 
	              data-bs-title="Delete this vote">
	              <i class="fas fa-user-minus"></i>&nbsp;
	            </button>
	          </td>
	        </tr>
				<?php
			endforeach;
			break;

		case 'pendingVotes':
			$pendingVotes = VVote::readAllPendingVotes();
			?>
        <?php if($pendingVotes > 0): ?>
          <span class="pendingVote position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="countPendingVotesBalagtas">
            <?=$pendingVotes?>
           	<span class="visually-hidden">Pending votes</span>
           </span>
         <?php else: ?>
         <?php endif; ?>
				<?php
			break;

		case 'searchFilter':
			$searchInput = filter_input(INPUT_GET, 'searchQuery', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			$result = VVote::readDataBySearch($searchInput);
			if(is_array($result)) {
				foreach($result as $row): 
				$datetimeVoted = date("F j, Y, g:iA", strtotime($row['vote_datetime'])); 
				?> 
					<tr>
	          <td>00<?=$row['vid']?></td>
	          <td>00<?=$row['sid']?></td>
	          <td><?=$row['cid']?></td>
	          <td><span class="badge rounded-pill bg-primary">₱<?=$row['amt_payment']?></span></td>
	          <td><span class="badge rounded-pill bg-info"><?=$row['vote_points']?></span></td>
	          <td><?=$row['referrence_no']?></td>
	          <td><?=$row['voters_email']?></td>
	          <td>
	          	<!-- Votes pending -->
	          	<?php if($row['vote_status'] === 0): ?>
	          		<button class="btn btn-sm btn-warning"
		              type="button"
		              id="updateVoteStatus"
		              data-id="<?=$row['vid']?>"
		              data-value="1">
		              Pending
	            	</button>
	            <!-- Votes is verified -->
	          	<?php else: ?>
	          		<button class="btn btn-sm btn-success"
		              type="button"
		              id="updateVoteStatus"
		              data-id="<?=$row['vid']?>"
		              data-value="0">
		              Verified
	            	</button>
	          	<?php endif; ?>
	          </td>
	          <td><?=$datetimeVoted;?></td>
	          <td>
	            <button class="btn btn-danger btn-sm"
	           	 	type="button"
	            	id="removeVote"
		            data-id="<?=$row['vid']?>" 
	              data-bs-toggle="tooltip" 
	              data-bs-title="Delete this vote">
	              <i class="fas fa-user-minus"></i>&nbsp;
	            </button>
	          </td>
	        </tr>
				<?php
			endforeach;
			} else {
				echo "<tr><th colspan='9'>No records found.</th></tr>";
			}
			
			break;

		default:
			break;
	}
}
