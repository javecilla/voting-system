<?php
session_start();
require_once __DIR__ . '/resources/api/gmcdb.config.php';
//check user is not logged in, prevent access to the system 
if(!isset($_SESSION['uname'])) {
  header('Location: voting login.php?failed=' . urlencode('empty-field-access-denied'));
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>GMCVS - Candidates Management</title>
  <?php require_once __DIR__ . '/resources/api/links.inc.php'; ?>
  <style>
	#main_content {
		display: flex;
		justify-content: center;
		align-items: center;
		min-height: 70vh;
	}
  </style>
</head>

<body onload="getAllCandidateList()">
	<div id="main_content">
		<div class="card vvc_card" style="width: 80%!important;">
		  	<div class="card-header p-3">
		  		<div class="row">
		  			<div class="col-6">
		  				<h6 class="text-uppercase">Candidate Records</h6>
		  			</div>
		  			<div class="col-6">
		  				<button class="btn btn-primary float-end"
		  					onclick="window.location.href='voting records.php'">
		  					Vote Records <i class="fa-solid fa-arrow-right"></i> 
		  				</button>
		  			</div>
		  		</div>
		  	</div>
		  	<div class="card-body">
		  		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method = 'post'>
			  		<div class="card-title">
						<small><b>Filter by Category</b> :</small>
						<select class="form-select" id="selectedCategoryName" onchange="selectedCategory()">
							<option value="default" selected>All Records</option>
			  				<?php
							$stmt = $cn->prepare("SELECT DISTINCT category FROM voting_candidates ");
							$stmt->execute();
							$result = $stmt->get_result();
							while($row = $result->fetch_assoc()) {
								?>
								<option value="<?=$row['category']?>"><?=$row['category']?></option>
								<?php
							}
							?>
							
						</select>
			  		</div>
			  		<div class="card_table">
			  			<table class="table table-bordered">
			  				<thead>
			  					<tr>
			  						<th style="width: 5%;">CID</th>
			  						<th style="width: 30%;">Category</th>
			  						<th>Candidate Name</th>
			  						<th style="width: 13%;">Image Profile</th>
			  						<th colspan="2" class="text-center" style="width: 19%;">
			  							<button type="button" onclick = 'window.location.href = "voting candidate registration.php";' class="btn btn-success">
			  								<i class="fa-solid fa-plus"></i> New Candidate
			  							</button>
			  						</th>
			  					</tr>
			  				</thead>
			  				<tbody id="candidateManagement">
								<!--data fetch by api ajax request-> api/gmcvoting.contr-->
			  				</tbody>
			  			</table>
			  		</div>
			  	</form>
		  	</div>

		</div>
	</div>
</body>
</html>

<script type="text/javascript">
	function getAllCandidateList() {
		$.ajax({
			method: "GET",
			url: "resources/api/gmcvoting.contr.php",
			dataType: "html",
			data: { fetchCandidateData: true },
			success: function(data) {
				$('#candidateManagement').html(data);
			}
		});
	}
	
	function selectedCategory() {
		//alert("test");
		var selectedCategoryName = document.getElementById('selectedCategoryName').value;
		//alert(selectedCategoryName);
		if(selectedCategoryName === 'default') {
			getAllCandidateList();
		} else {
			$.ajax({
				method: "GET",
				url: "resources/api/gmcvoting.contr.php",
				dataType: "html",
				data: { selectedCategoryName: selectedCategoryName },
				success: function(result) {
					$('#candidateManagement').html(result);
				}
			});
		}
	}
	

	//request to delete candidate
	$(document).ready(function() {
		$("#candidateManagement").on("click", ".btndelete", function(e) {
			e.preventDefault();
			//alert("test");
			var candidateID = $(this).closest("tr").find(".candidateID").val();
			//alert(candidateID);
			Swal.fire({
			  title: 'Delete this candidate?',
			  text: "You won't be able to recover this.",
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes, delete it'
			}).then((response) => {
			  if(response.isConfirmed) {
			  	$.ajax({
						method: "POST",
						url: "voting candidate registration.php",
						data: { candidateID: candidateID },
						success: function(result) {
							Swal.fire({
								title: 'Deleted!',
					      text: 'Candidate Deleted Successfully.',
					      icon:'success'
							}).then((deleted) => {
								location.reload();
							});	
						}
					});
			    
			  }
			})
			
		});
	});
</script>