<?php
session_start();
require_once __DIR__ . '/resources/api/gmcdb.config.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>GMCVS - Candidate Information</title>
<?php require_once __DIR__ . '/resources/api/links.inc.php'; ?>
<style type="text/css">
	.readonly {
		user-select: none;
		pointer-events: none;
		cursor: default;
	}
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
	  	-webkit-appearance: none;
	  	margin: 0;
	}
	button#back_to_top_btn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  height: 50px;
  font-size: 17px;
  border: none;
  outline: none;
  background: #c97e8b!important; 
  color: white;
  opacity: 0.7;
  width: 50px;
  cursor: pointer;
  margin-bottom: 5px;
  border-radius: 50%;
  transition: 0.5s all ease;
}

	button#back_to_top_btn:hover {
  opacity: 1;
}

	.btn_submit {
		background: #c97e8b!important;
		color: #f3f3f3; 
		outline: none;
		border: none;
		cursor: pointer;
	}
</style>
</head>

<body>
  <!--Scroll back to top-->
	<button onclick="topFunction()" id="back_to_top_btn">
		<i class="fa fa-arrow-up"></i>
	</button>

	<div class="main_content">
		<div class="card-group">
			<div class="card mt-3">
				<div class="card-header bg-transparent">
				    <nav class="navbar navbar-light bg-light p-2 fixed-top">
        					  <a onclick="window.history.go(-1);" class="navbar-brand btn btn-light">
        					  	<i class="fa-solid fa-arrow-left"></i> <small>Back</small>
        					  </a>
        					  <form class="form-inline">
        					    <div class="input-group">
        					    	<input class="form-control bg-light" id="searchQ" type="search" placeholder="Search">
        							  <div class="input-group-prepend">
        							    <button class="btn btn-outline-light" type="button" id="button-addon1"><i class="fa-solid fa-magnifying-glass text-dark"></i></button>
        							  </div>
        							</div>
        					  </form>
        					</nav>
			  </div>
		  	<div class="card-body">
		  		<div class="candidate_card">
						<div class="card mb-3" style="width: 95%">
							<div class="candidate_info">
								<div class="row g-0 p-3">
									<?php
									if(isset($_GET['cid'])) {
										$stmt = $cn->prepare("SELECT * FROM voting_candidates WHERE candidate_no = ?");
										$stmt->bind_param("i", $_GET['cid']);
										$stmt->execute();
										$result1 = $stmt->get_result();
										
										if($result1->num_rows > 0) {
											$row1 = $result1->fetch_assoc();
										} else {
											echo "error";
										}
									}
									?>
									<div class="col-md-4 p-3">
								    	<center>
								    	  <img src="resources/uploads/<?=$row1['img_profile']?>" class="img-fluid card-img-top img-thumbnail"
								    	  width="200"/>
								    	</center>
								   </div>
								   <div class="col-md-8">
								      <small><b>Category</b> :</small>
								      <span class="form-control mt-2 mb-2"><?=$row1['category']?></span>
								      <small><b>Candidate Name</b> :</small>
								      <span class="form-control mt-2 mb-2"><?=$row1['candidate_name']?></span>
								      <input type="hidden" value="<?=$row1['candidate_name']?>" id="cname">
									  <!-- count total vote points for specific candidate -->
									   <?php
										$stmt = $cn->prepare("SELECT SUM(total_vote_points) AS total_vote_points
											FROM numberof_votes 
											WHERE candidate_id = ?");
										$stmt->bind_param("i", $_GET['cid']);
										$stmt->execute();
										$result2 = $stmt->get_result();
										$row2 = $result2->fetch_assoc();
										?>
										<small><b>Total Vote Points</b> :</small>
										<span class="form-control mt-2"><h5><?=$row2['total_vote_points'] ?? 0 ?></h5></span>

										<input type="hidden" id="prevSelectedCategory" value="<?=$_GET['category']?>"/>
								  	</div>
								</div>
							</div><!--candidate_info-->
							<hr/>
							<div class="paymentVote_info">
								<div class="row g-0 ">
									<div class="col-md-4 p-3">
										<center>
											<img src="resources/images/qrcode/qr_payment=50.jpg" class="img-fluid img-thumbnail" width="250" id="qrcode_img"/><br/><br/>
										</center>
									</div>
									<div class="col-md-8">
										<div class="row mb-2 p-3">
											<div class="col-md-8">
												<small><b>Select Amount of Payment</b> :</small>
								      		<select class="form-select mt-2 mb-2" id="amtPayment" onchange="amtPayment()">
								      		    <option value="20" selected>₱20.00</option>
								      			<option value="50">₱50.00</option>
								      			<option value="100">₱100.00</option>
								      		</select>
								      		<small><b>Vote Points</b> :</small>
								      		<input type="text" class="form-control mt-2 mb-2 readonly" id="votePoints" value="20" readonly />
											</div>
											<div class="col-md-8">
												<small><b>Referrence Number</b> :</small>
										  	<input type="text" class="inum form-control mt-2 mb-2" 
										  		placeholder="#############" maxlength="13" 
										    	id="referrenceno" oninput="validateReferrenceNo()" />
										    <button type="button" class="btn btn_submit mt-2 form-control text-white" 
										  		id="submit" onclick="onSubmitVote()" disabled>Submit</button>
											</div>
										</div>
								  </div>
								</div>
							</div>
							
							<div class="card-footer">
								<small class="text-muted">CID-000<?=$row1['candidate_no']?></small>
								<?php
								if(isset($_GET['cid'])) {
									//calcute/sum the total number of vote points for specific candidates
									$stmt = $cn->prepare("SELECT SUM(total_vote_points) AS total_vote_points
										FROM numberof_votes 
										WHERE candidate_id = ?");
									$stmt->bind_param("i", $_GET['cid']);
									$stmt->execute();
									$result3 = $stmt->get_result();
									$row3 = $result3->fetch_assoc();
								}
								?>
								<small class="text-muted float-end">
									<!-- coalescing operator-> if there is an vote from specific candidate then it will dipslay in row[total_vote_points] otherwise it will display 0 as default-->
									Total Vote Points: <?= $row3['total_vote_points'] ?? 0 ?>
								</small>
								<!-- hidden data -->
								<input type="hidden" id="toInsertAmtPayment" value="20" />
								<input type="hidden" id="datetime_vote"
									value="<?php echo date("M-d-Y")?> / <?php echo date(" h: i A");?>" />
							</div>
						</div><!--card-->
					</div><!--end candidate_card-->
		  	</div><!--end card-body-->
			</div><!--end main card-->
		</div><!--card-group-->
	</div><!--main content-->
	<script type="text/javascript">
		//selected amount of payment
		function amtPayment() {
			//alert("test");
			const selectedAmtPayment = document.getElementById('amtPayment').value;
			document.getElementById('toInsertAmtPayment').value = selectedAmtPayment;
			//console.log(selectedAmtPayment);

			//check the amt of payment->load qrcode img according to the amt of payment
			if(selectedAmtPayment === '20') {
			    document.getElementById('qrcode_img').src = 'resources/images/qrcode/qr_payment=20.jpg';
				document.getElementById('votePoints').value = 20;   
			}
			else if(selectedAmtPayment === '50') {
				document.getElementById('qrcode_img').src = 'resources/images/qrcode/qr_payment=50.jpg';
				document.getElementById('votePoints').value = 60;
			}
			else if(selectedAmtPayment === '100') {
				document.getElementById('qrcode_img').src = 'resources/images/qrcode/qr_payment=100.jpg';
				document.getElementById('votePoints').value = 150;
			}
		}

		//validate entered referrence no
	  	function validateReferrenceNo() {
	    	const inputRef = document.getElementById('referrenceno');
	    	const maxLength = 13;
	    	const submitBtn = document.getElementById('submit');
	    	//remove non-numeric characters from the input value
	    	const sanitizedValue = inputRef.value.replace(/\D/g, '');
	    	//update the input value with the sanitized value
	    	inputRef.value = sanitizedValue;

	    	//check if the sanitized value has a length of 13 characters
	    	if(sanitizedValue.length === maxLength) {
	      	submitBtn.disabled = false;
	    	} else {
	      	submitBtn.disabled = true;
	    	}
	  	}

		function isEmpty(field) {
			return field === '';
		}

		//submit payment and vote
		function onSubmitVote() {
			//alert("test");
			const cid = '<?=$_GET['cid'];?>';
			const cname = document.getElementById('cname').value;
			const amtPayment = document.getElementById('toInsertAmtPayment').value;
			const referrenceNo = document.getElementById('referrenceno').value;
			const datetime_vote = document.getElementById('datetime_vote').value;
			const votePoints = document.getElementById('votePoints').value;

			const prevBASE_URL = document.getElementById('prevSelectedCategory').value;
			//alert(votePoints);
			if(isEmpty(amtPayment)) {
				return false; //no action will perform
			} 
			else {
				//send ajax request to process data
				$.ajax({
				   method: "POST", //send request via http POST method
				   url: "resources/api/gmcvoting.contr.php", //file to be send
				   data: { //data to be process
				      isVoteCount: true,
				      candidateID: cid,
				      amtPayment: amtPayment,
				      referrenceNo: referrenceNo,
				      datetime_vote: datetime_vote,
				      votePoints: votePoints
				   },
				   success: function(response) { //if success
				      alert(response); 
				      // location.reload();
				      window.location.href = 'voting system.php?category=' + prevBASE_URL;
				   },
				   error: function(error) { //if error
				   	alert(error);
				   	location.reload();
				   }
				});
			}
		}

		// Get the Scroll back to top button
		let scroll_btn = document.getElementById("back_to_top_btn");

		// When the user scrolls down 20px from the top of the document, show the button
		window.onscroll = function() {scrollFunction()};

		function scrollFunction() {
		  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		    scroll_btn.style.display = "block";
		  } else {
		    scroll_btn.style.display = "none";
		  }
		}

		// When the user clicks on the button, scroll to the top of the document
		function topFunction() {
		  document.body.scrollTop = 0;
		  document.documentElement.scrollTop = 0;
		}
	</script>
</body>
</html>