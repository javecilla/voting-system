<?php
session_start();
$cn = new mysqli('localhost', 'root', '', 'gmcbulac_db_gmc');
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <title>GMCVS Voting</title>
  <?php require_once __DIR__ . '/resources/api/links.inc.php'; ?>

	<style type="text/css">
	.category_card {
		border-left: 6px solid gray;
	}
	.category_card:hover {
		border-left: 6px solid #BA7B0B;
		cursor: pointer;
	}
	.anchor_card {
		text-decoration: none;
	}

	.auth-box {
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.gmc_name {
		font-family: Cinzel;
	  font-weight: 545;
	/*  color: #dea92a !important;*/
	color: #f3f3f3;
	}
	.btncategory {
  background: #BA7B0B!important; 
    font-family: Cinzel;
    font-size: 25px;
    color: #f3f3f3;

}

	</style>
</head>


<body>
	<a href="voting login.php" class="btn btn-light"><span class="text-white">Login</span></a>
	<div class="main-wrapper" >

		<!--Scroll back to top-->
		<button onclick="topFunction()" id="back_to_top_btn">
			<i class="fa fa-arrow-up"></i>
		</button>
    	
    	<div class="voting_wrapper d-flex no-block justify-content-center align-items-center position-relative"
      	style="background:url(resources/images/gmc-bg.png) no-repeat center center; ">

      		<div class="auth-box row" >
      			<div class="col-lg-8 col-md-4">
      				<?php
      				//check if category is set then btt btn will show
			        if(isset($_GET['category'])) {
			           	?>
			            <button type="button" class="btncategory btn btn-flat mb-2 form-control" 
			            onclick="window.location.href='voting system.php'">
			            	<i class="fa fa-arrow-left"></i> Candidate Category
			            </button>
			            <?php
			        }
			        ?>
      			</div>
        		<div class="col-lg-8 col-md-4 area ">

          			<div>
			          	<div class="text-center img-thumbnail bg-transparent form-control " >
			              	<img src="resources/images/gmc_logo.png" alt="GMC Logo" width="140">
			              	<h1 class="text-uppercase gmc_name p-3">
								<span class="capital-letter">S</span><small>ta. Maria</small><br/> 
								<span class="capital-letter">T</span><small>een</small>
								<span class="capital-letter">M</span><small>odel</small>
								<span class="capital-letter">S</span><small>earch</small><br/>
								<span class="capital-letter">V</span><small>oting</small> 
								<span class="capital-letter">S</span><small>ystem</small> 
							</h1>			            	
			            </div>
          			</div>           
		          	<ul class="circles">
			            <li></li>
			            <li></li>
			            <li></li>
			            <li></li>
			            <li></li>
			            <li></li>
			            <li></li>
			            <li></li>
			            <li></li>
			            <li></li>
		          	</ul>
        		</div>
				<div class="col-lg-8 col-md-4 bg-white ml-5 mb-1" >
		          	<div class="row">		   
			          	<?php
	        			//check if category is set or selected, then 
	        			//it will display all candidates from specific category
			          	if(isset($_GET['category'])) {
			          		$_SESSION['sCategory'] = $_GET['category'];
							$stmt = $cn->prepare("SELECT * FROM voting_candidates WHERE category = ?");
							$stmt->bind_param("s", $_GET['category']);
							$stmt->execute();
							$result = $stmt->get_result();
							while($row = $result->fetch_assoc()) {
								$_SESSION['cid'] = $row['candidate_no'];
								?>
								<div class="col-lg-3 col-md-6">
									<div class="card ">
	                                   	<img class="card-img-top img-fluid" src="resources/uploads/<?=$row['img_profile']?>" alt="Candidate Image"/>
	                                    <div class="card-body text-center">
	                                    	<?php
	                                    	$stmt = $cn->prepare("SELECT SUM(total_vote_points) AS total_vote_points
	                                    		FROM numberof_votes
	                                    		WHERE candidate_id = ?");
	                                    	$stmt->bind_param("i", $_SESSION['cid']);
	                                    	$stmt->execute();
	                                    	$resulta = $stmt->get_result();
	                                    	$currentVotePoints = $resulta->fetch_assoc();
	                                    	?>
	                                        <h6 class="card-title">Current Vote Points: </h6>
	                                        <span class="card-text form-control mb-2 text-center">
	                                        	<h4><?= $currentVotePoints['total_vote_points'] ?? 0 ?></h4>
	                                        </span>
	                                    </div>
	                                    <div class="card-footer">
	                                    	<small class="text-muted" style="margin-left:30px;">CID-000<?=$_SESSION['cid']?></small>
											<a href="voting candidate information.php" class="btn btn-success float-end" style="margin-right:30px;">Vote <i class="fa-regular fa-heart"></i></a>
	                                    </div>
	                                </div>
								</div>
	                           <?php
							}
						} 
						else {
							$stmt = $cn->prepare("SELECT DISTINCT category FROM voting_candidates");
							$stmt->execute();
							$result1 = $stmt->get_result();
							while($candidateCategory = $result1->fetch_assoc()) {
							?>
			                	<a href="voting system.php?category=<?=$candidateCategory['category']?>" class="anchor_card mt-2">
				                  	<div class="card border-end p-2 category_card ">
										<div class="card-body p-5">
											<div class="d-flex align-items-center">
												<div>
													<h5 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
													<?=$candidateCategory['category']?>
													</h5>
													<!--get the total candidate in specific category-->
													<?php
														$stmt = $cn->prepare("SELECT COUNT(*) AS totalcandidates
															FROM voting_candidates 
															WHERE category = ?
														");
														$stmt->bind_param("s", $candidateCategory['category']);
														$stmt->execute();
														$result2 = $stmt->get_result();
														$row2 = $result2->fetch_assoc();
													?>
													<small class="text-muted font-weight-normal mb-0 w-100 text-truncate">
														Total Candidates: <?=$row2['totalcandidates']?>
													</small>
												</div>
												<div class="ms-auto mt-md-3 mt-lg-0">
													<span class="opacity-7 text-muted">
														<i class="fa-solid fa-users" style="font-size: 30px"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!--end category card-->
				                </a>
		      				<?php
							}
						}
	        		?>
        		</div>
        	</div><!--col-8-->
    		</div><!--End login box-->
    	</div>
  	</div>
  	<!-- core js file -->
  	<script type="text/javascript">
  	//loader
	$(".preloader ").fadeOut();
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