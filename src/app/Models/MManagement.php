<?php
declare(strict_types = 1);

namespace App\Models;
/**
 * [https://javecilla.vercel.app] [https://github.com/javecilla]
 *
 * This Class Base Model will handle all database transaction 
 * from candidate management and only interact with database.
 */
class MManagement extends Database
{
	private array $response = [];
	private int $status = 1; // Status 1 indicates verified votes

	/* [to create new candidate record] */
	protected function createCandidate(array $data)
	{
		//check if the cid already exists
		if($this->candidateExist($data['cnumber'], $data['category'], $data['sbranch'])) {
      $this->response = ['success' => false, 'message' => 'Candidate is already exists! You cannot create new candidate with the same candidate number, category and branch/campus.'];
    } else {
			try {
				//start insert to db
				$stmt = $this->db()->prepare("INSERT INTO candidate (cid, cname, category, sbranch, imgname, imgext)
					VALUES (:cid, :cname, :category, :sbranch, :imgname, :imgext)");
				$eData = [
					':cid' => $data['cnumber'],
					':cname' => $data['cname'],
					':category' => $data['category'],
					':sbranch' => $data['sbranch'],
					':imgname' => $data['imgname'],
					':imgext' => $data['imgext']
				];
				foreach($eData as $key => $value) {
					$stmt->bindParam($key, $eData[$key], \PDO::PARAM_STR);
				}
				if(!$stmt->execute()) {
					$this->response = ['success' => false, 'message' => 'Failed to upload: ' . $stmt->errorInfo()[2]];
				} 
				//if statement execute success, start upload the submitted image to specify folder
				$imgUploadPath = '../Storage/candidates/' . $data['imgname'] . '.' . $data['imgext'];
				if(!move_uploaded_file($data['imgtmp'], $imgUploadPath)) {
	    		$this->response = ['success' => false, 'message' => 'Failed to move the uploaded file!'];
	    	}

	    	$this->response = ['success' => true, 'message' => 'Uploaded successfully!'];

			} catch (\PDOException $e) {
				$this->response = ['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()];
			}	
		}
		return $this->response;
	}

	/* [to get all candidates records] */
	protected function getAllData() 
	{
		$stmt = $this->db()->prepare("
			SELECT * 
			FROM candidate 
			ORDER BY sid DESC");
		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
		return $result !== false ? $result : null;
	}

	/* [to get all candidates records for category] */ 
	protected function getAllDataByCategory($category) 
	{
		$stmt = $this->db()->prepare("SELECT * FROM candidate WHERE category = :category ORDER BY sid DESC");
		$stmt->bindParam(':category', $category, \PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
		return $result !== false ? $result : null;
	}

	protected function getAllDataByBranch($sbranch)
	{
		$stmt = $this->db()->prepare("SELECT * FROM candidate WHERE sbranch = :sbranch ORDER BY sid DESC");
		$stmt->bindParam(':sbranch', $sbranch, \PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
		return $result !== false ? $result : null;
	} 

	/* [to get all candidates records for specific branch and category] */ 
	protected function getAllDataByCategoryCard($squery) 
	{
		$stmt = $this->db()->prepare("SELECT * FROM candidate WHERE category = :category ORDER BY sid DESC");
		$stmt->bindParam(':category', $squery, \PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
		return $result !== false ? $result : null;
	} 

	/* [to get candidates data by id] */
	protected function getCandidateData($thisId)
	{
		$stmt = $this->db()->prepare("
			SELECT c.*, 
      SUM(v.vote_points) AS total_vote_points,
      COUNT(*) AS total_number_of_voters
      FROM candidate c
      INNER JOIN votes v ON c.sid = v.sid
      WHERE c.sid = :sid AND v.vote_status = :status
		");
		$stmt->bindParam(':sid', $thisId, \PDO::PARAM_INT);
		$stmt->bindParam(':status', $this->status, \PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		
		return $result !== false ? $result : null;
	}

	/* [to get candidates data by search filter] */	
	protected function getDataBySearch($inputQuery, $category = null)
	{
		$sql = "SELECT * FROM candidate WHERE CONCAT(cname, ' ', cid) LIKE :inputQuery";
		//check if this category is set or null
		if($category !== null) {
		  //hindi siya null, ibig sabihin si sbranch and category ay may laman
		  //then this query condition will add
		  $sql .= " AND category = :category";
		}

		$stmt = $this->db()->prepare($sql);
		$stmt->bindValue(':inputQuery', '%' . $inputQuery . '%', \PDO::PARAM_STR);

		if($category !== null) {
		  $stmt->bindParam(':category', $category, \PDO::PARAM_STR);
		}

		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return $result !== false ? $result : null;
	}


	/* [to update candidate records] */
	protected function updateCandidate(array $data)
	{
    $sql = "UPDATE candidate SET cid = :cid,
      cname = :cname,
      category = :category,
      sbranch = :sbranch";
    //set image update flag to flase, means the default update with no img
    $imgUpdate = false; 

    if(isset($data['imgname']) && isset($data['imgname'])) {
      $sql .= ",
        imgname = :imgname,
        imgext = :imgext";
      //if there is an image set make imgupdate flag set to true
      $imgUpdate = true;
    }

    $sql .= " WHERE sid = :sid LIMIT 1";
    $stmt = $this->db()->prepare($sql);

    $stmt->bindParam(':cid', $data['cnumber'], \PDO::PARAM_STR);
    $stmt->bindParam(':cname', $data['cname'], \PDO::PARAM_STR);
    $stmt->bindParam(':category', $data['category'], \PDO::PARAM_STR);
    $stmt->bindParam(':sbranch', $data['sbranch'], \PDO::PARAM_STR);
    $stmt->bindParam(':sid', $data['csid'], \PDO::PARAM_INT);
    //i bind ang img param if there is an image set
    if($imgUpdate) {
      $stmt->bindParam(':imgname', $data['imgname'], \PDO::PARAM_STR);
      $stmt->bindParam(':imgext', $data['imgext'], \PDO::PARAM_STR);
    }

    if(!$stmt->execute()) {
      $this->response = [
      	'success' => false, 'message' => 
      	'Failed to update: ' . ($imgUpdate ? '(With-Image) ' : '(No-Image) ') . implode(", ", $stmt->errorInfo())
      ];
    } else {
      $this->response = ['success' => true, 'message' => 'Candidate updated successfully!']; 
      if($imgUpdate) {
        $imgUploadPath = '../Storage/candidates/' . $data['imgname'] . '.' . $data['imgext'];
        if(!move_uploaded_file($data['imgtmp'], $imgUploadPath)) {
          $this->response = ['success' => false, 'message' => 'Failed to move the uploaded file!'];
        }
      }
    }
    return $this->response;
	}

	/* [to delete candidate records] */
	protected function deleteCandidate($thisId)
	{
		try {
			$stmt = $this->db()->prepare("DELETE FROM candidate WHERE sid = :sid LIMIT 1");
			$stmt->bindParam(':sid', $thisId, \PDO::PARAM_INT);
			if(!$stmt->execute()) {
				$this->response = ['success' => false, 'message' => 'Failed to delete: ' . $stmt->errorInfo()[2]];
			}
			$this->response = ['success' => true, 'message' => 'Candidate record deleted successfully!'];
		} catch (\PDOException $e) {
			$this->response = ['success' => false, 'message' => "Something went wrong " . $e->getMessage()];
		}
		return $this->response;
	}

	/* [to check if a specific cid combined with category exists in the database] */
	private function candidateExist($cid, $category, $sbranch)
	{
	 	$stmt = $this->db()->prepare("SELECT COUNT(*) 
	 		FROM candidate WHERE cid = :cid 
	 		AND category = :category
			AND sbranch = :sbranch
		");
	 	$stmt->bindParam(':cid', $cid, \PDO::PARAM_STR);
	 	$stmt->bindParam(':category', $category, \PDO::PARAM_STR);
	 	$stmt->bindParam(':sbranch', $sbranch, \PDO::PARAM_STR);
	 	$stmt->execute();
	 	$count = $stmt->fetchColumn();

	 	return $count > 0;
	}

}