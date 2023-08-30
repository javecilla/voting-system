<?php
session_start();
require_once __DIR__ . '/resources/api/gmcdb.config.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <title>GMCVS Voting</title>
  <?php require_once __DIR__ . '/resources/api/links.inc.php'; ?>

	<style type="text/css">
	.category_card {
		border-left: 6px solid #a9455c;
	}
	.category_card:hover {
		border-left: 6px solid #c97e8b;
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


	.badge {
		border-radius: 50%!important;
		opacity: .9;
		color: #f3f3f3;
		background: #c97e8b;
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
.current_vote_points {
	border-left: 1px soilid #c97e8b;
}
	.btn_vote {
		background: #c97e8b!important;
		color: #f3f3f3; 
	}

	.btn_vote:hover {
		opacity: 0.8;
		color: #fff;
	}

	.header_text {
		font-family: Cinzel;
		font-size: 25px;
		font-weight: 530;
	    color: #f3f3f3;
	}
	.category_set {
		font-family: Cinzel;
		font-size: 25px;
		font-weight: 560;
	    color: #a9455c;
	}
	.main-wrapper {
		min-height: 100vh;
	}
	.area{
   background: #ad4a5d!important;  
   background: -webkit-linear-gradient(to left, #0A4780, #0C5DA8, #1F79CD); 
   backdrop-filter: blur(20px) saturate(168%);   
}
	</style>
</head>

<body>
	<div class="main-wrapper" >
  		<!--Scroll back to top-->
		<button onclick="topFunction()" id="back_to_top_btn"><i class="fa fa-arrow-up"></i></button>
    	<div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
      	style="background:url(resources/images/gmc-bg.png) no-repeat center center;" >
      	<div class="auth-box row " >
      	    <?php
      		if(isset($_GET['category'])) {
      			?>
      			<nav class="navbar navbar-light bg-light p-2 fixed-top">
					<a href="voting system.php" class="navbar-brand btn btn-light">
					  	<i class="fa-solid fa-arrow-left"></i> <small>Back</small>
					</a>
					<form class="form-inline">
					   <div class="input-group">
					    	<input class="form-control bg-light" type="search" placeholder="Search">
							  <div class="input-group-prepend">
							    <button class="btn btn-outline-light" type="button" id="button-addon1">
							    	<i class="fa-solid fa-magnifying-glass text-dark"></i>
							    </button>
							</div>
						</div>
					 </form>
				</nav>
      			<?php
      		}
      		?>
					<div class="area">
		          <div class="mt-5 mb-2 text-center">
		          	<a onclick="location.reload();">
			          				<img src="resources/images/VS Logo edited.png" alt="GMC Logo" width="150">
			          			</a>
		            <h3 class="text-white header_text">Sta. Maria Teen Model Search</h3>
		            <h3 class="text-white header_text">Voting System</h3>
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
	          	<div class="p-3">
	          		<div class="alert alert-danger" >
  											<h4 class="mt-3 text-center mb-2">
			            		<span class="category_set" id="categorySet">Select Category for Candidate</span>
			            	</h4>	
									</div>
			          
	            
			        	<div class="row">		   
				        <?php
		        		//check if category is set or selected, then 
		        		//it will display all candidates from specific category
				         if(isset($_GET['category'])) {
							$stmt = $cn->prepare("SELECT * FROM voting_candidates WHERE category = ?");
							$stmt->bind_param("s", $_GET['category']);
							$stmt->execute();
							$result = $stmt->get_result();
							while ($row = $result->fetch_assoc()) {
								$_SESSION['sCategory'] = $_GET['category'];
								?>
								<script type="text/javascript">
								    document.getElementById('categorySet').innerHTML='<?= $_SESSION['sCategory'] ?>';
								</script>
								  <div class="col-lg-4 col-md-8">
								            <div class="card">
								                <img class="card-img-top img-fluid" src="resources/uploads/<?= $row['img_profile'] ?>"
								                     alt="Candidate Image"/>
								                <div class="card-body text-center">
								                    <h6 class="card-title">Current Vote Points: </h6>
								                    <?php
								                    $stmt = $cn->prepare("SELECT SUM(total_vote_points) AS total_vote_points
								                            FROM numberof_votes
								                            WHERE candidate_id = ?");
								                    $stmt->bind_param("i", $row['candidate_no']);
								                    $stmt->execute();
								                    $resulta = $stmt->get_result();
								                    $currentVotePoints = $resulta->fetch_assoc();
								                    ?>
								                    <span class="card-text form-control mb-2 text-center current_vote_points">
								                        <h4><?= $currentVotePoints['total_vote_points'] ?? 0 ?></h4>
								                    </span>
								                </div>
								                <div class="card-footer">
								                    <small class="text-muted">
								                        CID-000<?= $row['candidate_no'] ?>
								                    </small>
								                    <a href="voting candidate information.php?cid=<?= $row['candidate_no'] ?>&category=<?= $_GET['category'] ?>"
								                       class="btn btn_vote btn-sm float-end">
								                        Vote <i class="fa-regular fa-heart"></i>
								                    </a>
								                </div>
								            </div>
								        </div>
								        <?php
								    }
									} 
									else {
										//TO SELECT CANDIDATE CATEGORY
										$stmt = $cn->prepare("SELECT DISTINCT category FROM voting_candidates");
										$stmt->execute();
										$result1 = $stmt->get_result();
										while($candidateCategory = $result1->fetch_assoc()) {
										?>

				              <a href="voting system.php?category=<?=$candidateCategory['category']?>" class="anchor_card mt-1">
					              <div class="card border-end p-2 category_card ">
													<div class="card-body p-3">
														<div class="d-flex align-items-center">
															<div>
																<!-- to get total number of unverified votes -->
																<?php
																$stmt = $cn->prepare("SELECT COUNT(*) AS totalUnverifiedVotes 
																	FROM numberof_votes nofv
																	INNER JOIN voting_candidates vc ON nofv.candidate_id = vc.candidate_no
																	WHERE vc.category = ? AND nofv.status = 0");
																$stmt->bind_param("s", $candidateCategory['category']);
																$stmt->execute();
																$result3 = $stmt->get_result();
																$row3 = $result3->fetch_assoc();
																?>
																<h5 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
																	<?=$candidateCategory['category']?>
																	<span class="badge"><?=$row3['totalUnverifiedVotes'] ?? 0?></span>
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
																<!-- <a href="#" class="badge text-bg-warning">Unverified Vote: </a> -->
																<span class="opacity-7 text-muted">
																	<?php
																	if($candidateCategory['category'] == 'Female Category') {
																		echo '<i class="fa-solid fa-venus" style="font-size: 37px"></i>';
																	} else if($candidateCategory['category'] == 'Pride Category') {
																		echo '<i class="fa-solid fa-transgender" style="font-size: 37px"></i>';
																	} else if($candidateCategory['category'] == 'Male Category') {
																		echo '<i class="fa-sharp fa-solid fa-mars" style="font-size: 37px"></i>';
																	}
																	?>
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
          	</div>
        	
      	</div>
    	</div><!--End login box-->
  	</div>
  	<a href="voting login.php" class="btn btn-light"><span class="text-white">Login</span></a>
  	<script type="text/javascript">
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