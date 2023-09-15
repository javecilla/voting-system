<?php
declare(strict_types = 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Functions\RFunctions;
use App\Controllers\CManagement;
use App\Views\VManagement;

//constants for actions
define('CREATE', 'create');
define('READ', 'read');
define('UPDATE', 'update');
define('DELETE', 'delete');

if(isset($_POST['action'])) {
	switch ($_POST['action']) {
		case CREATE:
			//validate the image uploaded
			if(isset($_FILES['imageUpload']) && !empty($_FILES['imageUpload']['name'])) {
				$imageValidationResult = RFunctions::validateImage($_FILES['imageUpload']);
				if($imageValidationResult['success']) {
					$dataForm = [
						'imgtmp' => $imageValidationResult['imgtmp'],
						'imgname' => $imageValidationResult['imgname'],
						'imgext' => $imageValidationResult['imgext'],
						'category' => $_POST['selectedCategory'],
						'sbranch' => $_POST['selectedBranch'],
						'cname' => filter_input(INPUT_POST, 'candidateName', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
						'cnumber' => filter_input(INPUT_POST, 'candidateNo', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)
					];
					$createRequestResult = CManagement::createData($dataForm);
					if($createRequestResult['success']) {
						$response = ['success' => true, 'message' => $createRequestResult['message']];
					} else {
						$response = ['success' => false, 'message' => $createRequestResult['message']];
					}
				} else {
					$response = ['success' => false, 'message' => $imageValidationResult['message']];
				}
			} else {
				$response = ['success' => false, 'message' => 'Please select the image/file!'];
			}
			break;

		case UPDATE:
			//Update the candidate data with a file/image
			if(!empty($_FILES['imageUploadModal']['name'])) {
    		$imageValidationResult = RFunctions::validateImage($_FILES['imageUploadModal']);
    		if($imageValidationResult['success']) {
        	$dataForm = [
            'imgtmp' => $imageValidationResult['imgtmp'],
            'imgname' => $imageValidationResult['imgname'],
            'imgext' => $imageValidationResult['imgext'],
            'category' => $_POST['selectedCategoryModal'],
            'sbranch' => $_POST['selectedBranchModal'],
            'cname' => filter_input(INPUT_POST, 'candidateNameModal', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'cnumber' => filter_input(INPUT_POST, 'candidateNoModal', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'csid' => $_POST['candidateSID']
        	];

	        $updateWithImageResult = CManagement::updateData($dataForm);
	        $response = ['success' => true, 'message' => $updateWithImageResult['message']];
    		} else {
    			$response = ['success' => false, 'message' => $imageValidationResult['message']];
    		}
			} 
			//Update the candidate data without a file/image
			else {
    		$dataForm = [
	        'category' => $_POST['selectedCategoryModal'],
	        'sbranch' => $_POST['selectedBranchModal'],
	        'cname' => filter_input(INPUT_POST, 'candidateNameModal', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
	        'cnumber' => filter_input(INPUT_POST, 'candidateNoModal', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
	        'csid' => $_POST['candidateSID']
    		];
		    $updateWithNOImageResult = CManagement::updateData($dataForm);
		    $response = ['success' => true, 'message' => $updateWithNOImageResult['message']];
			}
			break;

		case DELETE:
			$requestDeleteResult = CManagement::deleteData($_POST['sid']);
			if($requestDeleteResult['success']) {
				$response = ['success' => true, 'message' => $requestDeleteResult['message']];
			} else {
				$response = ['success' => false, 'message' => $requestDeleteResult['message']];
			}
			break;
		
		default:
			break;
	}
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
}

if(isset($_GET['action']) && isset($_GET['category'])) {
	switch($_GET['category']) {
		case 'all':
			// to fetch all candidate records/data
			$result = VManagement::readAllData();
			foreach($result as $row):
				?>
					<tr>
					  <th scope="row" style="width: 80px;">00<?=$row['sid']?></th>
					  <th scope="row" style="width: 80px;"><?=$row['cid']?></th>
					  <td scope="row" style="width: 110px;">
					    <img src="/src/app/Storage/candidates/<?=$row['imgname']?>.<?=$row['imgext']?>" 
					    alt="Candidate Photo" class="img-thumbnail img-responsive" />
					  </td>
					  <td scope="row"><?=$row['cname']?></td>
					  <td scope="row">
					    <?php if($row['category'] === "Lakan"): ?>
								<span class="badge bg-primary"><?=$row['category']?></span>
							<?php elseif($row['category'] === "Lakanbini"): ?>
								<span class="badge bg-success"><?=$row['category']?></span>
							<?php elseif($row['category'] === "Lakandyosa"): ?>
								<span class="badge bg-info"><?=$row['category']?></span>
							<?php endif; ?>
					  </td>
					  <td scope="row" style="width: 130px;" class="text-center">
						  <!-- Candidate identifier -->
						  <input type="hidden" value="<?=$row['sid']?>" class="sid"/>
						  <input type="hidden" value="<?=$row['cid']?>" class="cid"/>	

						  <div class="dropdown">
						    <button type="button" class="btn " data-bs-toggle="dropdown" aria-expanded="false">
						      <i class="fas fa-ellipsis-h fs-3"></i>
						    </button>
						    <ul class="dropdown-menu">
						      <li>
						        <button type="button" class="btn dropdown-item viewCandidate" data-action="view">
						          <i class="fas fa-eye"></i> View
						        </button>
						      </li>
						      <li>
						        <button type="button" class="btn dropdown-item editCandidate" data-action="edit">
						          <i class="fas fa-edit"></i> Update
						        </button>
						      </li>
						      <li>
						        <button type="button" class="btn dropdown-item" id="deleteDataCandidate">
						          <i class="fas fa-trash"></i> Delete
						        </button>
						      </li>
						    </ul>
						  </div>
					 	</td>
					</tr>

					<?php
				
			endforeach;	
			break;
		
		case 'byId':
			// fetch candidate data by id
			$mode = isset($_GET['mode']) ? $_GET['mode'] : 'view';
			$csid = $_GET['sid'];
			$candidate = VManagement::readDataById($csid);
			//$totalVotePoints = VManagement::countVotePoints($csid);
			if(isset($_GET['purpose']) && $_GET['purpose'] === "clientSide") { //client side modal
				?> 
					<input type="hidden" id="vSID" value="<?=$candidate['sid']?>" />
					<div class="card cardAuto">
						<div class="row g-0 p-1"> 
							<div class="col-md-5 mb-3 noEvents">
								<center>
	      					<img src="/src/app/Storage/candidates/<?=$candidate['imgname']?>.<?=$candidate['imgext']?>" 
				        	alt="Candidate Photo" class="img-fluid rounded-start" width="350" />
				        </center>
    					</div>
    					<div class="col-md-7 noEvents"> 
    						<div class="row mt-2 g-2">
    							<div class="col-md-8">
    								<div class="form-floating">
								      <input class="form-control" id="candidateName" value="<?=$candidate['cname']?>"> 
								      <label for="candidateName">Candidate name</label>
							    	</div>
    							</div>
    							<div class="col-md-4">
    								<div class="form-floating">
								      <input class="form-control" id="candidateNumber" value="<?=$candidate['cid']?>"> 
								      <label for="candidateNumber">Candidate no</label>
							    	</div>
    							</div>
								</div>
								<div class="row g-1 mb-2 mt-2">
    							<ul class="list-group">
					          <li class="list-group-item d-flex justify-content-between align-items-start">
					            <div class="ms-2 me-auto">
					              <div class="fw-bold">Current Votes: </div><small>(Total number of votes)</small>
					            </div>
					           	<span class="badge badgeClient rounded-pill" id="numberOfVotes">
					           		<?=$candidate['total_number_of_voters'] > 0 ? $candidate['total_number_of_voters'] : '0';?>
					           	</span>
					          </li>
					        </ul> 
  							</div>
	  						<div class="row g-1 mt-2">
	  							<ul class="list-group">
						        <li class="list-group-item d-flex justify-content-between align-items-start">
						          <div class="ms-2 me-auto">
						            <div class="fw-bold">Current Points: </div><small>(Total of vote points)
						            &nbsp;&nbsp;&nbsp;&nbsp;</small>
						          </div>
						          <span class="badge badgeClient rounded-pill" id="votePoints">
						          	<?=$candidate['total_vote_points'] > 0 ? $candidate['total_vote_points'] : '0';?>
						          </span>
						        </li>
						      </ul> 
	  						</div>
    					</div>
						</div>
						<div class="row g-0 p-1">
	  					<div class="instruction alert alert-warning d-flex align-items-center" role="alert">
								<small class="text-justify">
									For a smooth and precise voting experience, kindly adhere to the following guidelines:
									<ol>
										<li><b>Choose Payment Amount</b>: Begin by selecting the desired payment amount.</li>
										<li><b>Scan QR Code</b>: Scan the provided QR code with your device's camera.</li>
										<li><b>Enter Refference Number</b>: Enter the reference number from GCash.</li>
										<li><b>Email Address</b>: Provide your valid email address.</li>
										<li><b>Submit Vote</b>: Click the "Submit Vote" button to confirm.</li>
									</ol>
									Your cooperation in following these steps ensures a seamless voting process.
								</small>
							</div>
							<div class="votingInput">
								<div class="row g-0 p-1">
								 	<div class="col-md-8 p-2">
								 		<div class="row">
								 			<div class="col-md-6 mb-2">
								 				<div class="form-floating">
										      <select class="form-select" id="selectPayment">
										        <option value="" selected>--SELECT--</option>
										        <option value="10">₱10</option>
										        <option value="50">₱50</option>
										        <option value="100">₱100</option>
										      </select>
									      	<label for="selectPayment">Select Amount of payment</label>
									    	</div>
								 			</div>
								 			<div class="col-md-6 mb-2">
								 				<div class="form-floating">
      										<input type="text" class="form-control noEvents" value="" id="equivalentVotePoints" />
      										<label for="equivalentVotePoints">Equivalent Vote Points</label>
    										</div>
								 			</div>
								 			<div class="col-md-6 mb-2">
								 				<div class="form-floating">
      										<input type="number" class="form-control" 
      											value=""
      											maxlength="13" 
      											inputmode="numeric" 
      											id="referrenceNumber" 
      										/>
      										<label for="referrenceNumber">Enter Referrence Number</label>
    										</div>
								 			</div>
								 			<div class="col-md-6 mb-2">
								 				<div class="form-floating">
      										<input type="text" class="form-control" 
      											value="" 
      											id="votersEmail"
      											autocomplete="off"
      											aria-label="Enter your Email" 
      											list="emailSuggestion"
      										/>
      										<datalist id="emailSuggestion">
										        <option value="javecilla@gmail.com">
										        <option value="info@goldenmindsbulacan.com">
										        <option value="admission@goldenmindsbulacan.com">
										      </datalist>
      										<label for="votersEmail">Enter your Email</label>
    										</div>
								 			</div>
								 		</div>
								 	</div>
									<div class="col-md-4 p-2 noEvents mb-2">
										<div class="form-control mb-2">
								      <small>QR Code Image Preview</small>
								    </div>
								    <div class="card cardAuto">
								    	<img src="" alt="..." id="qrCodeImage" 
								      	class="img-card-top" />
								    </div>
									</div>
								</div>
							</div>
		  			</div>
					</div>
					
				<?php
			} else { //admin side modal
				?>
			 	<div class="card mb-3 cardAuto">
  				<div class="row g-0 p-1">
    				<div class="col-md-4">
      				<img src="/src/app/Storage/candidates/<?=$candidate['imgname']?>.<?=$candidate['imgext']?>" 
			        	alt="Candidate Photo" class="img-fluid rounded-start file-upload-image-modal" width="350" />
			        	<input type="hidden" value="<?=$candidate['sid']?>" id="csidModal"/>
    				</div>
				    <div class="col-md-8 ml-3">
				    	<div class="row mb-1">
						    <?php if ($mode === 'edit'): ?>
						    	<label class="col-sm-4 col-form-label">Browse File:</label>
						    	<div class="col-sm-8 mt-2">
						    		<input class="file-upload-input-modal" type='file' id="image_upload_modal"
                      onchange="uploadImage(this);" accept="image/jpeg, jpg, png"
                      style="display: none;" 
                    />
					          <button class="btn btn-light form-control browse_file_modal"
					            onclick="$('.file-upload-input-modal').click()">
				        			<i class="fa-solid fa-image"></i> Change Photo
				        		</button>
				        		<button type="button" onclick="removeImageModal('#image_upload_modal')" 
			                class="btn btn-danger form-control displayNone" >
			                <i class="fa-solid fa-minus"></i>&nbsp; 
			                <span class="image_upload_name_modal"></span>
			               </button>
			        		</div>
  							</div>
				      <?php endif; ?>
				      <script>
				      	function removeImageModal(input) {
								  // Restore original photo in the edit form
								  <?php if ($mode === 'edit'): ?>
								    $('.file-upload-image-modal').attr('src', '/src/app/Storage/candidates/<?=$candidate['imgname']?>.<?=$candidate['imgext']?>');
								    $('#image_upload_modal').val('');
								    $('.browse_file_modal').show();
								    $('.remove_file_modal').hide();
								  <?php endif; ?>
								}
				      </script>
						    
				      <div class="row mb-1">
	    					<label class="col-sm-4 col-form-label">Candidate Name:</label>
						    <div class="col-sm-8 mt-2">
						    	<?php if ($mode === 'edit'): ?>
				            <input type="text" class="form-control" id="candidateNameModal" value="<?=$candidate['cname']?>">
				          <?php else: ?>
				            <h6 class="text-uppercase"><strong><?=$candidate['cname']?></strong></h6>
				          <?php endif; ?>
						    </div>
  						</div>
  						<div class="row mb-1">
	    					<label class="col-sm-4 col-form-label">Candidate No:</label>
						    <div class="col-sm-8 mt-2">
								  <?php if ($mode === 'edit'): ?>
						        <input type="text" class="form-control" id="candidateNoModal" value="<?=$candidate['cid']?>">
		  						<?php else: ?>
  									<h6 class="text-uppercase"><strong><?=$candidate['cid']?></strong></h6>
				      		<?php endif; ?>
				      	</div>
  						</div>
  						<div class="row mb-1">
	    					<label class="col-sm-4 col-form-label">Category:</label>
						    <div class="col-sm-8 mt-2">
						    	<?php if ($mode === 'edit'): ?>
				            <select class="form-select" id="selectCategoryModal">
                      <option value="<?=$candidate['category']?>" selected><?=$candidate['category']?></option>
                      <option value="Lakan">Lakan</option>
                      <option value="Lakanbini">Lakanbini</option>
                      <option value="Lakandyosa">Lakandyosa</option>
                    </select>
				        	<?php else: ?>
				          	<h6 class="text-uppercase"><strong><?=$candidate['category']?></strong></h6>
				        	<?php endif; ?>
						    </div>
  						</div>
  						<div class="row mb-1">
	    					<label class="col-sm-4 col-form-label">School Branch:</label>
						    <div class="col-sm-8 mt-2">
						    	<?php if ($mode === 'edit'): ?>
				            <select class="form-select" id="selectBranchModal">
                      <option value="<?=$candidate['sbranch']?>" selected><?=$candidate['sbranch']?></option>
                      <option value="Golden Minds Colleges - Sta.Maria">Golden Minds Colleges - Sta.Maria</option>
                      <option value="Golden Minds Colleges - Balagtas">Golden Minds Colleges - Balagtas</option>
                    </select>
				        	<?php else: ?>
				          	<h6 class="text-uppercase"><strong><?=$candidate['sbranch']?></strong></h6>
				        	<?php endif; ?>
						    </div>
  						</div>
  						<?php if ($mode === 'edit'): ?>
  						<?php else: ?>
  						<div class="row mb-2 mt-2">
  							<div class="d-flex col-md-6">
					        <ul class="list-group">
					          <li class="list-group-item d-flex justify-content-between align-items-start">
					            <div class="ms-2 me-auto">
					              <div class="fw-bold">Current Votes: </div><small>(Total number of votes)</small>
					            </div>
					           	<span class="badge filterList rounded-pill" id="numberOfVotes">
					           		<?=$candidate['total_number_of_voters'] > 0 ? $candidate['total_number_of_voters'] : '0';?>
					           	</span>
					          </li>
					        </ul> 
					      </div>
					      <div class="d-flex col-md-6">
					        <ul class="list-group">
					          <li class="list-group-item d-flex justify-content-between align-items-start">
					            <div class="ms-2 me-auto">
					              <div class="fw-bold">Current Points: </div><small>(Total of vote points)
					              &nbsp;&nbsp;&nbsp;&nbsp;</small>
					            </div>
					           	<span class="badge filterList rounded-pill" id="votePoints">
					           		<?=$candidate['total_vote_points'] > 0 ? $candidate['total_vote_points'] : '0';?>
					           	</span>
					          </li>
					        </ul> 
					      </div>
  						</div>
  						<?php endif; ?>		
				    </div>
				  </div>
				</div>
			<?php
			}
			
			break;

		case 'searchFilter':
			$seachQuery = filter_input(INPUT_GET, 'searchQuery', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$result = VManagement::filterDataBySearch($seachQuery, null, null);
				if(is_array($result)) {
					foreach($result as $searchData):
						?>
							<tr>
					      <th scope="row" style="width: 80px;">00<?=$searchData['sid']?></th>
					      <th scope="row" style="width: 80px;"><?=$searchData['cid']?></th>
					      <td scope="row" style="width: 110px;">
					        <img src="/src/app/Storage/candidates/<?=$searchData['imgname']?>.<?=$searchData['imgext']?>" 
					        	alt="Candidate Photo" class="img-thumbnail img-responsive" />
					      </td>
					      <td scope="row"><?=$searchData['cname']?></td>
					      <td scope="row">
					      	<?php if($searchData['category'] === "Lakan"): ?>
									    <span class="badge bg-primary"><?=$searchData['category']?></span>
									<?php elseif($searchData['category'] === "Lakanbini"): ?>
									    <span class="badge bg-success"><?=$searchData['category']?></span>
									<?php elseif($searchData['category'] === "Lakandyosa"): ?>
									    <span class="badge bg-info"><?=$searchData['category']?></span>
									<?php endif; ?>
					      </td>
					     	<td scope="row" style="width: 130px;" class="text-center">
					     		<!-- Candidate identifier -->
					     		<input type="hidden" value="<?=$searchData['sid']?>" class="sid"/>
					        <input type="hidden" value="<?=$searchData['cid']?>" class="cid"/>	

					        <div class="dropdown">
					          <button type="button" class="btn " data-bs-toggle="dropdown" aria-expanded="false">
					            <i class="fas fa-ellipsis-h fs-3"></i>
					          </button>
					          <ul class="dropdown-menu">
					            <li>
					              <button type="button" class="btn dropdown-item viewCandidate" data-action="view">
					                <i class="fas fa-eye"></i> View
					              </button>
					            </li>
					            <li>
					              <button type="button" class="btn dropdown-item editCandidate" data-action="edit">
					                <i class="fas fa-edit"></i> Update
					              </button>
					            </li>
					            <li>
					              <button type="button" class="btn dropdown-item" id="deleteDataCandidate">
					                <i class="fas fa-trash"></i> Delete
					              </button>
					            </li>
					         	</ul>
					        </div>
					      </td>
					    </tr>
						<?php
					endforeach;
				} else {
					echo "<tr><th colspan='6'>No records found.</th></tr>";
				}
			break;

		case 'searchFilterTwo':
			$inputSearch = filter_input(INPUT_GET, 'inputSearch', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			$result = VManagement::filterDataBySearch($inputSearch, $_GET['candidateCategory'], $_GET['candidateBranch']);
			if(is_array($result)) {
				foreach($result as $row):
					?>
					<div class="col">
						<div class="card mb-3">
						  <div class="row g-0 ">
						    <div class="col-md-4">
						    	<img src="/src/app/Storage/candidates/<?=$row['imgname']?>.<?=$row['imgext']?>" 
										alt="Candidate Photo" class="img-fluid rounded-start" 
									/>
						    </div>
						    <div class="col-md-8">
						      <div class="card-body">
						      	<span class="card-text">
						      		<span class="badge mb-2" style="background: #24382e;">
						      			<span class="text-uppercase">CID</span>: <?=$row['cid']?>
						      		</span>
						        	<?php if($row['category'] === "Lakan"): ?>
												<span class="badge bg-success"><?=$row['category']?></span>
											<?php elseif($row['category'] === "Lakanbini"): ?>
												<span class="badge" style="background: #8eaf91;"><?=$row['category']?></span>
											<?php elseif($row['category'] === "Lakandyosa"): ?>
												<span class="badge" style="background: #4d8881;"><?=$row['category']?></span>
											<?php endif; ?>
						        </span>
						        <h5 class="card-title"><?=$row['cname']?></h5>
						        <input type="hidden" class="candidateVoteSid" value="<?=$row['sid']?>" />
						        <input type="hidden" class="candidateCategory" value="<?=$row['category']?>"/>
						        <input type="hidden" class="candidateBranch" value="<?=$row['sbranch']?>"/>
										<button type="button" data-id="<?=$row['sid']?>" class="voteBtn btn btn-light mt-2" style="background-color: #f3e3d3!important;">
										    <i class="fas fa-thumbs-up"></i>&nbsp;Vote
										</button>
						      </div>
						    </div>
						  </div>
					</div>
					</div>
				<?php
				endforeach;
			} else {
					echo "<h4>No records found.</h4>";
				}
			break;

		case 'byCategoryFilter':
			$result = VManagement::getAllDataCategory($_GET['target']);
			foreach($result as $row):
				?>
					<tr>
					  <th scope="row" style="width: 80px;">00<?=$row['sid']?></th>
					  <th scope="row" style="width: 80px;"><?=$row['cid']?></th>
					  <td scope="row" style="width: 110px;">
					    <img src="/src/app/Storage/candidates/<?=$row['imgname']?>.<?=$row['imgext']?>" 
					    alt="Candidate Photo" class="img-thumbnail img-responsive" />
					  </td>
					  <td scope="row"><?=$row['cname']?></td>
					  <td scope="row">
					    <?php if($row['category'] === "Lakan"): ?>
								<span class="badge bg-primary"><?=$row['category']?></span>
							<?php elseif($row['category'] === "Lakanbini"): ?>
								<span class="badge bg-success"><?=$row['category']?></span>
							<?php elseif($row['category'] === "Lakandyosa"): ?>
								<span class="badge bg-info"><?=$row['category']?></span>
							<?php endif; ?>
					  </td>
					  <td scope="row" style="width: 130px;" class="text-center">
						  <!-- Candidate identifier -->
						  <input type="hidden" value="<?=$row['sid']?>" class="sid"/>
						  <input type="hidden" value="<?=$row['cid']?>" class="cid"/>	

						  <div class="dropdown">
						    <button type="button" class="btn " data-bs-toggle="dropdown" aria-expanded="false">
						      <i class="fas fa-ellipsis-h fs-3"></i>
						    </button>
						    <ul class="dropdown-menu">
						      <li>
						        <button type="button" class="btn dropdown-item viewCandidate" data-action="view">
						          <i class="fas fa-eye"></i> View
						        </button>
						      </li>
						      <li>
						        <button type="button" class="btn dropdown-item editCandidate" data-action="edit">
						          <i class="fas fa-edit"></i> Update
						        </button>
						      </li>
						      <li>
						        <button type="button" class="btn dropdown-item" id="deleteDataCandidate">
						          <i class="fas fa-trash"></i> Delete
						        </button>
						      </li>
						    </ul>
						  </div>
					 	</td>
					</tr>
				<?php
			endforeach;	
			break;

		case 'byBranchFilter':
			$result = VManagement::getAllDataBranch($_GET['target']);
			foreach($result as $row):
				?>
					<div class="col">
						<div class="card mb-3" data-aos="fade-up">
						  <div class="row g-0 ">
						    <div class="col-md-4">
						    	<img src="/src/app/Storage/candidates/<?=$row['imgname']?>.<?=$row['imgext']?>" 
										alt="Candidate Photo" class="img-fluid rounded-start" 
									/>
						    </div>
						    <div class="col-md-8">
						      <div class="card-body">
						      	<span class="card-text">
						      		<span class="badge mb-2" style="background: #24382e;">
						      			<span class="text-uppercase">CID</span>: <?=$row['cid']?>
						      		</span>
						        	<?php if($row['category'] === "Lakan"): ?>
												<span class="badge bg-success"><?=$row['category']?></span>
											<?php elseif($row['category'] === "Lakanbini"): ?>
												<span class="badge" style="background: #8eaf91;"><?=$row['category']?></span>
											<?php elseif($row['category'] === "Lakandyosa"): ?>
												<span class="badge" style="background: #4d8881;"><?=$row['category']?></span>
											<?php endif; ?>
						        </span>
						        <h5 class="card-title"><?=$row['cname']?></h5>
						        <input type="hidden" class="candidateVoteSid" value="<?=$row['sid']?>" />
						        <input type="hidden" class="candidateCategory" value="<?=$row['category']?>"/>
						        <input type="hidden" class="candidateBranch" value="<?=$row['sbranch']?>"/>
										<button type="button" data-id="<?=$row['sid']?>" class="voteBtn btn btn-light mt-2" style="background-color: #f3e3d3!important;">
										    <i class="fas fa-thumbs-up"></i>&nbsp;Vote
										</button>
						      </div>
						    </div>
						  </div>
					</div>
					</div>
				<?php
			endforeach;	
			break;	

		case 'byCategoryBranchFilter':
			$result = VManagement::getAllDataCategoryBranch($_GET['fcategory'], $_GET['scategory']);
			foreach($result as $row):
				if(isset($_GET['purpose']) && $_GET['purpose'] === 'clientSide') { //render it in client side
					?>
						<div class="col">
							<div class="card mb-3">
							  <div class="row g-0 ">
							    <div class="col-md-4">
							    	<img src="/src/app/Storage/candidates/<?=$row['imgname']?>.<?=$row['imgext']?>" 
											alt="Candidate Photo" class="img-fluid rounded-start" 
										/>
							    </div>
							    <div class="col-md-8">
							      <div class="card-body">
							      	<span class="card-text">
							      		<span class="badge mb-2" style="background: #24382e;">
							      			<span class="text-uppercase">CID</span>: <?=$row['cid']?>
							      		</span>
							        	<?php if($row['category'] === "Lakan"): ?>
													<span class="badge bg-success"><?=$row['category']?></span>
												<?php elseif($row['category'] === "Lakanbini"): ?>
													<span class="badge" style="background: #8eaf91;"><?=$row['category']?></span>
												<?php elseif($row['category'] === "Lakandyosa"): ?>
													<span class="badge" style="background: #4d8881;"><?=$row['category']?></span>
												<?php endif; ?>
							        </span>
							        <h5 class="card-title"><?=$row['cname']?></h5>
							        <input type="hidden" class="candidateVoteSid" value="<?=$row['sid']?>" />
							        <input type="hidden" class="candidateCategory" value="<?=$row['category']?>"/>
							        <input type="hidden" class="candidateBranch" value="<?=$row['sbranch']?>"/>
											<button type="button" data-id="<?=$row['sid']?>" class="voteBtn btn btn-light mt-2" style="background-color: #f3e3d3!important;">
											    <i class="fas fa-thumbs-up"></i>&nbsp;Vote
											</button>
							      </div>
							    </div>
							  </div>
							</div>
						</div>
					<?php
				} else { //render by admin side
					?>
						<tr>
						  <th scope="row" style="width: 80px;">00<?=$row['sid']?></th>
						  <th scope="row" style="width: 80px;"><?=$row['cid']?></th>
						  <td scope="row" style="width: 110px;">
						    <img src="/src/app/Storage/candidates/<?=$row['imgname']?>.<?=$row['imgext']?>" 
						    alt="Candidate Photo" class="img-thumbnail img-responsive" />
						  </td>
						  <td scope="row"><?=$row['cname']?></td>
						  <td scope="row">
						    <?php if($row['category'] === "Lakan"): ?>
									<span class="badge bg-primary"><?=$row['category']?></span>
								<?php elseif($row['category'] === "Lakanbini"): ?>
									<span class="badge bg-success"><?=$row['category']?></span>
								<?php elseif($row['category'] === "Lakandyosa"): ?>
									<span class="badge bg-info"><?=$row['category']?></span>
								<?php endif; ?>
						  </td>
						  <td scope="row" style="width: 130px;" class="text-center">
							  <!-- Candidate identifier -->
							  <input type="hidden" value="<?=$row['sid']?>" class="sid"/>
							  <input type="hidden" value="<?=$row['cid']?>" class="cid"/>	

							  <div class="dropdown">
							    <button type="button" class="btn " data-bs-toggle="dropdown" aria-expanded="false">
							      <i class="fas fa-ellipsis-h fs-3"></i>
							    </button>
							    <ul class="dropdown-menu">
							      <li>
							        <button type="button" class="btn dropdown-item viewCandidate" data-action="view">
							          <i class="fas fa-eye"></i> View
							        </button>
							      </li>
							      <li>
							        <button type="button" class="btn dropdown-item editCandidate" data-action="edit">
							          <i class="fas fa-edit"></i> Update
							        </button>
							      </li>
							      <li>
							        <button type="button" class="btn dropdown-item" id="deleteDataCandidate">
							          <i class="fas fa-trash"></i> Delete
							        </button>
							      </li>
							    </ul>
							  </div>
						 	</td>
						</tr>
					<?php
				}
				
				
			endforeach;	
			break;

		default:
			break;
	}	
}
