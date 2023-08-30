<?php
session_start();
require_once __DIR__ . '/resources/api/gmcdb.config.php';
//check user is not logged in, prevent access to the system 
// if(!isset($_SESSION['uname'])) {
//   header('Location: voting login.php?failed=' . urlencode('empty-field-access-denied'));
//   exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>GMCVS Vote Records | Dashboard</title>
  <?php require_once __DIR__ . '/resources/api/links.inc.php'; ?>
<style type="text/css">
	.badge {
		text-decoration: none;
	}
	.head_content, .main_content {
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>
</head>
<body onload="getAllVoteRecords(), getAllDataDashboard()">
	<header class="head_content">
		<div class="row" style="width: 100%!important;">
			<div class="col">
				<div class="card mt-3 p-2">
					<div class="card-header bg-transparent">
						<div class="row mb-2" id="cardDashboard">
							<!--data fetch via api ajax-->
						</div><!--end row dashboard-->
					</div><!--end card header-->
					<div class="row mt-4">
			  			<div class="col-4">
			  				<button class="btn btn-primary"
			  					onclick="window.location.href='voting view candidates.php'">
			  					<i class="fa-solid fa-arrow-left"></i> Candidate Management
			  				</button>
			  			</div>
			  			<div class="col-4">
			  				<div class="row g-1">
			  					<div class="col-sm-5">
			  						<label for="" class="col-form-label float-end">Filter by Candidates: </label>
			  					</div>
			  					<div class="col-sm-7">
									<!--To display unique candidate name-->
			  						<select id="selectedCandidate" onchange="selectedCandidate()" class="form-select">
			  							<option value="default" selected>All Records</option>
			  							<?php
			  							$stmt = $cn->prepare("SELECT DISTINCT nof.candidate_id, vc.candidate_name
			  								FROM numberof_votes nof
			  								INNER JOIN voting_candidates vc ON nof.candidate_id = vc.candidate_no
			  							");
			  							$stmt->execute();
			  							$result = $stmt->get_result();
			  							if($result->num_rows > 0) {
			  								while($row = $result->fetch_assoc()) {
			  									?>
			  									<option value="<?=$row['candidate_id']?>">
			  										<?=$row['candidate_name']?>
			  									</option>
			  									<?php
			  								}
			  							} else { echo "No votes record has found!"; }
			  							?>
			  						</select>
			  					</div>
			  				</div>
			  				
			  			</div>
			  			<div class="col-4">
			  				<div class="input-group mb-3">
							  	<input type="text" id='txtSearch' onkeyup="searchQueryFilter()" 
							  	class="form-control" placeholder="I am looking for..." autocomplete="off">
							  	<button class="input-group-text btn btn-secondary">
							  		<i class="fa-solid fa-magnifying-glass"></i> Search
							  	</button>
							</div>
			  			</div>
		  			</div>
				</div><!--end first card-->
			</div><!--end first column-->
		</div><!--end first row-->
	</header>
	<section class="main_content">
		<div class="row" style="width: 100%!important;">
			<div class="col">
				<div class="card mt-1 p-2">
				    <div class="table-responsive">
    					<table class="table table-bordered">
        		  			<thead>
        		  				<tr>
        		  					<th>Candidate Name</th>
        		  					<th>Category</th>
        		  					<th>Date and Time</th>
        		  					<th>Amount</th>
        		  					<th>Referrence No.</th>
        		  					<th>Status</th>
        		  					<th>Points</th>
        		  				</tr>
        		  			</thead>
        		  			<tbody id="tbodyVoteRecords">
        		  				<!-- data fetch by api ajax request -->
        		  			</tbody>
    		  		    </table>
		  		    </div>
				</div>
			</div>
		</div>

	</section><!--end main content-->
	<script type="text/javascript">
		function isEmpty(field) {
			return field === '';
		}
		
		//fetch all data of dashboard
		function getAllDataDashboard() {
			$.ajax({
				method: "GET",
				url: "resources/api/gmcvoting.contr.php",
				dataType: "html",
				data: { displayData: true },
				success: function(allDataDashboard) {
					$('#cardDashboard').html(allDataDashboard);
				}
			});
		}
		
		//fetch all data of vote records in table
		function getAllVoteRecords() {
			$.ajax({
				method: "GET",  //send request via GET method
				url: "resources/api/gmcvoting.contr.php", //file to be send
				dataType: "html", //set html as datatype
				data: { 
					fetchData: true //data to be retrieve in server
				},
				success: function(allVoteRecords) { //if success
					$('#tbodyVoteRecords').html(allVoteRecords); //fetch all data in table
				}
			});
		}

		//selection filter by candidates
		function selectedCandidate() {
			//alert("test");
			var selectedCandidateId = document.getElementById('selectedCandidate').value;
			//alert(selectedCandidateId);
			var getTotal = document.getElementById('selectedCandidateId').value = selectedCandidateId;
			
			//check if selected filter is default
			if(selectedCandidateId === 'default') {
				//then call the getAllVoteRecords, it will display the default data in table
				getAllVoteRecords();
				getAllDataDashboard();
			} 
			else {
				$.ajax({
					method: "GET",
					url: "resources/api/gmcvoting.contr.php",
					dataType: "html",
					data: { selectedCandidateId: selectedCandidateId },
					success: function(response) { 
						$('#tbodyVoteRecords').html(response);
					}
				});
						
			}	
		}

		//search filter vote records
		function searchQueryFilter() {
			var filterValue = document.getElementById('txtSearch').value;
			//console.log(filterValue);

			//check if search field is empty
			if(isEmpty(filterValue)) { 
				//then call the getAllVoteRecords, it will display the default data in table
				getAllVoteRecords();
			} 
			else { //search query is set
				$.ajax({
					method: "GET",
					url: "resources/api/gmcvoting.contr.php",
					dataType: "html",
					data: {
						filterValue: filterValue
					}, 
					success: function(response) {
						$('#tbodyVoteRecords').html(response);
					}
				});
			}
		}
		
	    // to delete existing votes
		$(document).ready(function() {
			$('#tbodyVoteRecords').on('click', '#removeVote', function(e) {
				e.preventDefault();
				//to get vote id
				const voteid = $(this).closest('tr').find('#votesId').val();
				//alert(voteid);

				Swal.fire({
				  title: 'Delete this vote record?',
				  text: "You won't be able to recover this.",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it'
				}).then((response) => {
					if(response.isConfirmed) { //if confirm then send ajax request to server to delete votes
						$.ajax({
							method: "POST",
							url: "resources/api/gmcvoting.contr.php",
							data: {
								deleteVoteSet: true,
								voteid: voteid
							},
							success: function(result) {
								Swal.fire({
									title: 'Deleted!',
						      text: 'Vote Records Deleted Successfully.',
						      icon:'success'
								}).then((deleted) => {
									location.reload();
								});	
							}
						});
					} else { //no action will perform
						return false;
					}
				});
				
			})
		});
	</script>
</body>
</html>