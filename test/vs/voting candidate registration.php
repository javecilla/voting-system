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
<title>GMCVS - Candidates Registration</title>
  <?php require_once __DIR__ . '/resources/api/links.inc.php'; ?>
 <style>
 #main_content, .img_container{
	 display: flex;
	 justify-content: center;
	 align-items: center;
	
 }
 </style>
</head>

<body style="background:url(resources/images/gmc-bg.png) no-repeat center center; ">
	<div id="main_content" style="min-height: 100vh">
		<div class="card vcr_card" style="width: 50%;">
		  	<div class="card-header">
		    	<h6 class="text-uppercase" id="tbl_title">Register new candidate</h6>
		  	</div>
		  	<div class="card-body">
		    	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method = 'post' 
		    		enctype ='multipart/form-data' autocomplete="off">
		    		<!-- category -->
		    		<label class="form-label"><b>Category:</b></label>
		    		<input type = 'search' list = 'category' name = 'txtcategory' id = 'txtcategory' 
		    		class="form-select mt-2" placeholder="--SELECT--" required>
					<datalist id = 'category'>
						<option value = 'Male Category'>
						<option value = 'Female Category'>
						<option value = 'Pride Category'>
					</datalist>

					<!-- candidate name -->
					<label class="form-label mt-2"><b>Candidate Name:</b></label>				
					<input type = 'text' name = 'txtname' id = 'txtname' class="form-control" 
					placeholder="Enter candidate name" required>

					<!-- upload photo -->
					<label class="form-label mt-2"><b>Candidate Photo:</b></label>	
					<input type = 'file' name = 'fileToUpload' id = 'fileToUpload' accept = 'image/*' onchange = 'loadFile(event)' class="form-control">

					<div class="img_container img-thumbnail">
						<img name = 'imgprofile' id = 'imgprofile' 
						src="resources/images/upload_default.png" width="200" />
					</div>
					<hr>
					<!-- buttons action -->
					<div class="btn_container">
						<button type = 'submit' name = 'btnsave' id = 'btnsave' value = 'Save Candidate' 
						class="btn btn-success" disabled>
							Save Candidate <i class="fa-solid fa-floppy-disk"></i>
						</button>&nbsp;
						<button type = 'button' name = 'btnview' id = 'btnview'
						class="btn btn-secondary" onclick = 'window.location.href = "voting view candidates.php";'>
							View Candidates <i class="fa-solid fa-eye"></i>
						</button>
					</div>
					<!-- hidden candidate id for updating info -->
					<input type="hidden" name="cid" id="cid"/>
		    	</form>
		  	</div>
		</div><!--card-->
	</div><!--end main div-->
</body>
</html>

<script>
var loadFile = function(event)
{
document.getElementById('imgprofile').src = URL.createObjectURL(event.target.files[0]);
if(document.getElementById('imgprofile').src != '')
{
document.getElementById('btnsave').disabled = false;	
}
};
</script>

<?php
if(isset($_POST['btnsave']))
{
	//retrieve data
	$category = mysqli_real_escape_string($cn, $_POST['txtcategory']);
	$name = mysqli_real_escape_string($cn, $_POST['txtname']);

	$imgName = $_FILES['fileToUpload']['name']; //admin
	// get the file ext of image
	$imgExt = pathinfo($imgName, PATHINFO_EXTENSION);
	//renaming the image name with random string
	$newImgName = uniqid("IMG-", true);
	//storing full image info in variable
	$imgprofile = $newImgName.'.'.strtolower($imgExt);
	//creating upload path on root directory
	$imgUploadPath = "resources/uploads/".$imgprofile;

	//check if condidate no/id is set then, query action willbe perform 
	//UPDATE
	if(isset($_POST['cid']) && !empty($_POST['cid'])) 
	{
		$candidateno = $_POST['cid'];

		//check if new file is selected
		if(!empty($_FILES['fileToUpload']['name'])) 
		{
			//move the uploaded file to the specified folder
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imgUploadPath);
        //update the candidate info and profile photo
        //$sql = mysqli_query($cn, "UPDATE voting_candidates SET category = '$category', candidate_name = '$name', img_profile = '$imgprofile' WHERE candidate_no = '$candidateno'");
        $sql = "UPDATE voting_candidates SET category = ?, candidate_name = ?, img_profile = ? WHERE candidate_no = ?";
        $stmt = $cn->prepare($sql);
        $stmt->bind_param("sssi", $category, $name, $imgprofile, $candidateno);
        $stmt->execute();
		} 
		else 
		{
			//update the candidate info without changing the profile photo
        	//$sql = mysqli_query($cn, "UPDATE voting_candidates SET category = '$category', candidate_name = '$name' WHERE candidate_no = '$candidateno'");
        	$sql = "UPDATE voting_candidates SET category = ?, candidate_name = ? WHERE candidate_no = ?";
        	$stmt = $cn->prepare($sql);
            $stmt->bind_param("ssi", $category, $name, $candidateno);
            $stmt->execute();
		}
		//sql response
		if($sql) 
		{
			echo "<script>
            alert('Candidate information updated successfully!');
            window.location.href = 'voting view candidates.php';
         </script>";
		} 
		else 
		{
			echo "<script>
           	alert('Something went wrong: Failed to update candidate information.');
         </script>";
		}

	} 
	else 
	{
		//if this candidate no is not set then the query action will be perform is
		//INSERT
		move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imgUploadPath);
		//$sql = mysqli_query($cn, "insert into voting_candidates (category, candidate_name, img_profile) values ('$category', '$name', '$imgprofile')");
		$stmt = $cn->prepare("INSERT INTO voting_candidates(category, candidate_name, img_profile) VALUES(?, ?, ?)");
	    $stmt->bind_param("sss", $category, $name, $imgprofile);
	    $stmt->execute();
		echo"<script>
			alert('Candidate information and file ". htmlspecialchars( basename( $_FILES['fileToUpload']['name'])). " has been saved and uploaded.')
			window.location.href = 'voting view candidates.php';
		</script>";
	}
	
}

//TO DISPLAY SPECIFIC DATA OF CANDIDATE 
if(isset($_GET['cid']) && $_GET['cid'] !== '')
{
	$candidateno = $_GET['cid'];
	//$sql = mysqli_query($cn, "select * from voting_candidates where candidate_no = '$candidateno'");
	//$row = mysqli_fetch_assoc($sql);
	$stmt = $cn->prepare("SELECT * FROM voting_candidates WHERE candidate_no = ?");
	$stmt->bind_param("i", $candidateno);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	echo"<script>
	document.getElementById('cid').value = '".$row['candidate_no']."';
	document.getElementById('txtcategory').value = '".$row['category']."';
	document.getElementById('txtname').value = '".$row['candidate_name']."';
	document.getElementById('imgprofile').src = 'resources/uploads/".$row['img_profile']."';

	document.getElementById('btnsave').disabled = false;
	document.getElementById('tbl_title').innerHTML = 'UPDATE CANDIDATE INFORMATION';
	</script>";
	
}

//TO DELETE EXISTING CANDIDATE
//TO DELETE CANDIDATE RECORD
if(isset($_POST['candidateID'])) {
	$stmt = $cn->prepare("DELETE FROM voting_candidates WHERE candidate_no = ?");
	$stmt->bind_param("i", $_POST['candidateID']);
	$stmt->execute();
	die;
}

?>