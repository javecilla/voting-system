<?php
session_start();
require_once __DIR__ . '/gmcdb.config.php';

/*------------------
# CODE ADDED
-----------------*/

//TO INSERT THE VOTES INFORMATION
if(isset($_POST['isVoteCount'])) {
	//retrieve and sanitize data
   $cid = intval($_POST['candidateID']);
   $amtPayment = intval($_POST['amtPayment']);
   $referrenceNo = filter_var($_POST['referrenceNo'], FILTER_SANITIZE_NUMBER_INT);
   $datetime_vote = $_POST['datetime_vote'];
   $votePoints = intval($_POST['votePoints']);

   //check reference number in database is already exist then, denied to submit the
   //vote if this entered reference number is exist in database
   $stmt = $cn->prepare("SELECT COUNT(*) AS countRefno
   	FROM numberof_votes 
   	WHERE candidate_id = ? AND referrence_no = ?");
   $stmt->bind_param("ii", $cid, $referrenceNo);
   $stmt->execute();
   $result = $stmt->get_result();
   $row = $result->fetch_assoc();
   $refno = $row['countRefno'];

   //start validate reference number
   if($refno == 0) { //entered reference number of user is not exist
   	$stmt = $cn->prepare("INSERT INTO numberof_votes(candidate_id, sub_amt_payment, referrence_no, date_time, sub_vote_points) VALUES(?, ?, ?, ?, ?)");
	   $stmt->bind_param("iiisi", $cid, $amtPayment, $referrenceNo, $datetime_vote, $votePoints);
	   $stmt->execute();
	   //check execution
	   if($stmt->affected_rows > 0) {
	   	echo "You successfully vote!";
	   } else {
	   	$errMsg = "Something went wrong: Failed to insert vote";
			$error = array('error' => 1, 'message' => $errMsg);
			echo json_encode($error);
			exit();
	   }
   } 
   else { 
   	//reference number is already exist
   	$errMsg = "Something went wrong: Failed cannot submit same reference no";
		$error = array('error' => 1, 'message' => $errMsg);
		echo json_encode($error);
		exit();
   }
}

//TO FETCH THE CANDIDATE LIST
if(isset($_GET['fetchCandidateData'])) {
	$stmt = $cn->prepare("SELECT * FROM voting_candidates ORDER BY candidate_no DESC");
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()) {
		?>
		<tr>
			<td><?=$row['candidate_no']?></td>
			<td><?=$row['category']?></td>
			<td><?=stripcslashes($row['candidate_name'])?></td>
			<td><img src="resources/uploads/<?=$row['img_profile']?>" class="img-thumbnail"/></td>
			<td>
				<a href='voting candidate registration.php?cid=<?=$row['candidate_no']?>' class='btn btn-warning text-white'>
					<i class='fa-solid fa-pen-to-square'></i> Edit
				</a>
			</td>
			<td>
				<input type = 'hidden' class='candidateID' value ='<?=$row['candidate_no']?>' />
				<button type = 'submit' class='btndelete btn btn-danger text-white'>
					<i class='fa-solid fa-trash'></i> Delete
				</button>
			</td>
		</tr>
		<?php
	}
}

//TO FETCH ALL RECORD depends on selected category
if(isset($_GET['selectedCategoryName'])) {
	$stmt = $cn->prepare("SELECT * FROM voting_candidates WHERE category = ?");
	$stmt->bind_param("s", $_GET['selectedCategoryName']);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()) {
		?>
		<tr>
			<td><?=$row['candidate_no']?></td>
			<td><?=$row['category']?></td>
			<td><?=stripcslashes($row['candidate_name'])?></td>
			<td><img src="resources/uploads/<?=$row['img_profile']?>" class="img-thumbnail"/></td>
			<td>
				<a href='voting candidate registration.php?cid=<?=$row['candidate_no']?>' class='btn btn-warning text-white'>
					<i class='fa-solid fa-pen-to-square'></i> Edit
				</a>
			</td>
			<td>
				<input type = 'hidden' class='candidateID' value ='<?=$row['candidate_no']?>' />
				<button type = 'submit' class='btndelete btn btn-danger text-white'>
					<i class='fa-solid fa-trash'></i> Delete
				</button>
			</td>
		</tr>
		<?php
	}
}

//TO UPDATE VOTE STATUS AND COUNT VOTES
if(isset($_GET['vid']) && isset($_GET['cid'])) {
	$vote_id = $_GET['vid'];
	$candidate_id = $_GET['cid'];
	
	//check if sub total vote points is set
	if(isset($_GET['svp'])) {
		$stmt = $cn->prepare("SELECT * FROM numberof_votes WHERE votes_id = ? AND candidate_id = ?");
		$stmt->bind_param("ii", $vote_id, $candidate_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		//add subvote ponts to the total vote point if this is verified
		$subvote_points = $_GET['svp'];
		$status = 1; //verified
		//0 + 200 = 200
		$verifiedAmt_payment = intval($row['amt_payment']) + intval($row['sub_amt_payment']);
		//0 + 250 = 250
		$updateVote_points = intval($row['total_vote_points']) + intval($subvote_points);
		
		//to update status and total vote points
		$stmt = $cn->prepare("UPDATE numberof_votes
			SET status = ?, amt_payment = ?, total_vote_points = ?
			WHERE votes_id = ? AND candidate_id = ?
		");
		$stmt->bind_param("iiiii", $status, $verifiedAmt_payment, $updateVote_points, $vote_id, $candidate_id);
		$stmt->execute();
		if($stmt->affected_rows > 0) {
			header("Location: ../../voting records.php");
		} else {
			echo "Failed!";
		}
	}
}

//TO DISPLAY ALL DATA IN DASHBOARD
if(isset($_GET['displayData'])) {
	?>
	<div class="col">
		<!-- dashboard card -->
		<div class="card">
			<div class="card-body">
				<?php
				//to count number of all voters
				$stmt = $cn->prepare("SELECT COUNT(*) AS total_voters FROM numberof_votes");
				$stmt->execute();
				$result = $stmt->get_result();
				$row1 = $result->fetch_assoc();
				?>
				<div class="d-flex align-items-center">
					<div>
						<div class="d-inline-flex align-items-center" id="cardTotalVoters">											
							<h2 class="text-dark mb-1 font-weight-medium" id="totalVoters">
								<?=$row1['total_voters'] ?? 0 ?>
							</h2>
						</div>
						<h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Voters</h6>
					</div>
					<div class="ms-auto mt-md-3 mt-lg-0">
						<span class="opacity-7 text-muted">
							<i class="fa-solid fa-users" style="font-size: 30px"></i>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card border-end">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium" id="totalVotePoints">
							---
						</h2>
						<h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Vote Points</h6>
					</div>
					<div class="ms-auto mt-md-3 mt-lg-0">
						<span class="opacity-7 text-muted">
							<i class="fa-solid fa-key" style="font-size: 30px"></i>
						</span>
					</div>
					<input type="hidden" value="default" id="selectedCandidateId" onchange="candidateNumVotes()"/>
				</div>
			</div>
		</div>
	</div><!--end col-->
	<div class="col">
		<div class="card border-end">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<?php
					$stmt = $cn->prepare("SELECT SUM(amt_payment) AS total_amtpayment FROM numberof_votes");
					$stmt->execute();
					$result = $stmt->get_result();
					$row2 = $result->fetch_assoc();
					//format the total amount with commas
					$formattedAmount = number_format($row2['total_amtpayment'], 0, '.', ',');
					?>
					<div>
						<h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
							<sup class="set-peso">₱</sup><span id="totalAmount"><?= $formattedAmount ?></span><span>.00</span>
						</h2>
						<h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Amount</h6>
					</div>
					<div class="ms-auto mt-md-3 mt-lg-0">
						<span class="opacity-7 text-muted">
							<i class="fa-solid fa-peso-sign" style="font-size: 30px"></i>
						</span>
					</div>
					<input type="hidden" value="default" id="selectedCandidateId" onchange="candidateNumVotes()"/>
				</div>
			</div>
		</div>
	</div><!--end col-->
	<?php
}

//TO DISPLAY ALL VOTE RECORDS IN TABLE ->voting records.php
if(isset($_GET['fetchData'])) {
	$stmt = $cn->prepare("SELECT nof.*, vc.*
		FROM numberof_votes nof
		INNER JOIN voting_candidates vc ON nof.candidate_id = vc.candidate_no
		ORDER BY nof.votes_id DESC
	");
	$stmt->execute();
	$result = $stmt->get_result();
	//check data 
	if($result->num_rows > 0) {
		//fetch all data found
		while($row = $result->fetch_assoc()) {
			?>
			<tr>
				<td><?=stripcslashes($row['candidate_name'])?></td>
				<td><?=$row['category']?></td>
				<td><?=$row['date_time']?></td>
				<td><span class="badge text-bg-light">₱<?=$row['sub_amt_payment']?>.00</span></td>
				<td><?=$row['referrence_no']?></td>
				<!-- status -->
				<td>
				<?php
				  	if($row['status'] == 0) {
				  		echo '<a href="resources/api/gmcvoting.contr.php?vid='.$row['votes_id'].'&cid='.$row['candidate_id'].'&svp='.$row['sub_vote_points'].'" class="badge text-bg-danger">Pending</a>';
				  	} 
				  	else {
				  		echo '<span class="badge rounded-pill text-bg-success">Verified</span>';
				  	}
				?>
				</td>
				<!-- points -->
				<td>
				<?php
				  	if($row['status'] == 0) {
				  		echo '<span class="badge rounded-pill text-bg-light p-2">'.$row['sub_vote_points'].'</span>';
				  	}
				  	else {
				  		echo '<span class="badge rounded-pill text-bg-secondary p-2">'.$row['sub_vote_points'].'</span>';
				  	}
				?>
				</td>
				<td>
					<input type="hidden" value="<?=$row['votes_id']?>" id="votesId">
					<button type="button" class="btn btn-danger btn-sm" id="removeVote">Remove</button>
				</td>
			</tr>
			<?php
		}
	}
	else { //no vote data found
		?>
		<tr><td colspan="7" class="text-center">No votes record has been found.</td></tr>
		<?php
	}
}



//TO FILTER VOTE RECORD(depends on the selected candidates)->voting records.php
if(isset($_GET['selectedCandidateId'])) {
	$candidateId = intval($_GET['selectedCandidateId']);
	try {	
		//to get vote records for selected candidate
		$stmt = $cn->prepare("SELECT nof.*, vc.*
			FROM numberof_votes nof
			INNER JOIN voting_candidates vc ON nof.candidate_id = vc.candidate_no 
			WHERE candidate_id = ?
			ORDER BY nof.date_time DESC
		");
		$stmt->bind_param("i", $candidateId);
		$stmt->execute();
		$result = $stmt->get_result();
		//check data 
		if($result->num_rows > 0) {
			//fetch all data found
			while($row = $result->fetch_assoc()) {
				?>
				<tr>
					<td><?=stripcslashes($row['candidate_name'])?></td>
					<td><?=$row['category']?></td>
					<td><?=$row['date_time']?></td>
					<td><span class="badge text-bg-light">₱<?=$row['sub_amt_payment']?>.00</span></td>
					<td><?=$row['referrence_no']?></td>
					<!-- status -->
					<td>
					<?php
						if($row['status'] == 0) {
							echo '<a href="resources/api/gmcvoting.contr.php?vid='.$row['votes_id'].'&cid='.$row['candidate_id'].'&svp='.$row['sub_vote_points'].'" class="badge text-bg-danger">Pending</a>';
						} 
						else {
							echo '<span class="badge rounded-pill text-bg-success">Verified</span>';
						}
					?>
					</td>
					<!-- points -->
					<td>
					<?php
						if($row['status'] == 0) {
							echo '<span class="badge rounded-pill text-bg-light p-2">'.$row['sub_vote_points'].'</span>';
						}
						else {
							echo '<span class="badge rounded-pill text-bg-secondary p-2">'.$row['sub_vote_points'].'</span>';
						}
					?>
					</td>
					<td>
					    <input type="hidden" value="<?=$row['votes_id']?>" id="votesId">
					    <button type="button" class="btn btn-danger btn-sm" id="removeVote">Remove</button>
				    </td>
				</tr>
				<?php
			}
		}
		
		//to count total number of voter for selected candidates
		$stmt = $cn->prepare("SELECT COUNT(*) AS total_votersCandidate FROM numberof_votes WHERE candidate_id = ?");
		$stmt->bind_param("i", $candidateId);
		$stmt->execute();
		$result1 = $stmt->get_result();
		$row1 = $result1->fetch_assoc();
		
		//to count total vote points for selected candidates
		$stmt = $cn->prepare("SELECT SUM(total_vote_points) AS total_vote_pointsCandidates FROM numberof_votes WHERE candidate_id = ?");
		$stmt->bind_param("i", $candidateId);
		$stmt->execute();
		$result2 = $stmt->get_result();
		$row2 = $result2->fetch_assoc();
		
		//to sum total amount of payment for selected candidates
		$stmt = $cn->prepare("SELECT SUM(amt_payment) AS total_amtpaymentCandidate FROM numberof_votes WHERE candidate_id = ?");
		$stmt->bind_param("i", $candidateId);
		$stmt->execute();
		$result3 = $stmt->get_result();
		$row3 = $result3->fetch_assoc();
			
		
		//format the total amount with commas
		?>
		<script>
			var totalVoter = <?=$row1['total_votersCandidate'] ?? 0 ?>;
			var totalVoterPoints = <?=$row2['total_vote_pointsCandidates'] ?? 0 ?>;
			var totalAmount = <?=$row3['total_amtpaymentCandidate'] ?? 0 ?>;
			document.getElementById('totalVoters').innerHTML = totalVoter;
			document.getElementById('totalVotePoints').innerHTML = totalVoterPoints;
			document.getElementById('totalAmount').innerHTML = totalAmount;
		</script>
		<?php
	} catch(Exception $e) {
		echo "An error occured: " . $e->getMessage();
	}
}

//TO SEARCH FILTER VOTE RECORDS->voting records.php
if(isset($_GET['filterValue'])) {
	$searchValue = filter_var($_GET['filterValue'], FILTER_SANITIZE_STRING);
	$stmt = $cn->prepare("SELECT nof.*, vc.*
		FROM numberof_votes nof
		INNER JOIN voting_candidates vc ON nof.candidate_id = vc.candidate_no
		WHERE CONCAT(vc.candidate_name, ' ', vc.category, ' ', nof.sub_amt_payment, ' ', nof.referrence_no, ' ', nof.sub_vote_points)
		LIKE '%$searchValue%'
	");

	$stmt->execute();
	$result = $stmt->get_result();
	//check data 
	if($result->num_rows > 0) {
		//fetch all data found
		while($row = $result->fetch_assoc()) {
			?>
			<tr>
				<td><?=stripcslashes($row['candidate_name'])?></td>
				<td><?=$row['category']?></td>
				<td><?=$row['date_time']?></td>
				<td><span class="badge text-bg-light">₱<?=$row['sub_amt_payment']?>.00</span></td>
				<td><?=$row['referrence_no']?></td>
				<!-- status -->
				<td>
				<?php
				  	if($row['status'] == 0) {
				  		echo '<a href="resources/api/gmcvoting.contr.php?vid='.$row['votes_id'].'&cid='.$row['candidate_id'].'&svp='.$row['sub_vote_points'].'" class="badge text-bg-danger">Pending</a>';
				  	} 
				  	else {
				  		echo '<span class="badge rounded-pill text-bg-success">Verified</span>';
				  	}
				?>
				</td>
				<!-- points -->
				<td>
				<?php
				  	if($row['status'] == 0) {
				  		echo '<span class="badge rounded-pill text-bg-light p-2">'.$row['sub_vote_points'].'</span>';
				  	}
				  	else {
				  		echo '<span class="badge rounded-pill text-bg-secondary p-2">'.$row['sub_vote_points'].'</span>';
				  	}
				?>
				</td>
				<td>
					<input type="hidden" value="<?=$row['votes_id']?>" id="votesId">
					<button type="button" class="btn btn-danger btn-sm" id="removeVote">Remove</button>
				</td>
			</tr>
			<?php
		}
	}
	else { //no matching record has found
		?>
		<tr><td colspan="7" class="text-center">No matching Record has been found.</td></tr>
		<?php
	}
}


#TO VALIDATE LOGIN USER CREDENTIALS AGAINTS DATABASE
  if(isset($_POST['isLogin']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $uname = filter_var($_POST['uname'], FILTER_SANITIZE_STRING);
    $pword = filter_var($_POST['pword'], FILTER_SANITIZE_STRING);

    //start validate login request
    try {
      	if(empty($uname) || empty($pword)) {
        	//echo "Empty fields";
        	header("Location: ../../voting login.php?error=empty_fields");
     		exit();
      	} 
		else {
        	$stmt = $cn->prepare("SELECT * FROM login WHERE username = ? LIMIT 1");
        	$stmt->bind_param("s", $uname);
        	$stmt->execute();
        	$result = $stmt->get_result();
        	//validate login credentials against database
        	if($result->num_rows > 0) {
        		$row = $result->fetch_assoc();
            	if($pword == $row['password']) {
              		$_SESSION['uname'] = $uname;
              		header("Location: ../../voting records.php");
              		exit();
	            } else {
	              	//echo "Invalid password!";
	            	header("Location: ../../voting login.php?error=invalid_password");
	            	exit();
	     			exit();
	            }
        	} else {
            	//echo "Invalid username!";
            	header("Location: ../../voting login.php?error=invalid_username");
            	exit();
        	}
    	}    
    } catch (Exception $e) {
      echo "An error occured: " . $e->getMessage();
    }
}

//TO DELETE VOTES RECORD IN TABLE->voting record.php
if(isset($_POST['deleteVoteSet'])) {
	$votesId = $_POST['voteid'];
	$stmt = $cn->prepare("DELETE FROM numberof_votes WHERE votes_id = ?");
	$stmt->bind_param("i", $votesId);
	$stmt->execute();
	exit();
}