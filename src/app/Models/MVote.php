<?php
declare(strict_types = 1);

namespace App\Models;
/**
 * [https://javecilla.vercel.app/] [https://github.com/javecilla/]
 *
 * This Class Base Model will handle all database transaction 
 * from candidate management and only interact with database.
 * perform Logic for Votes and Database
 */
class MVote extends Database
{
	private array $response = [];
	private int $pending = 0; //set the status to 0 as pending vote
	private int $verfied = 1;
	private string $datetime;

	public function __construct() {
	  $this->datetime = date('Y-m-d H:i:s'); //date('F j, Y, g:iA');
	}

	/* [To insert submitted votes]*/
	protected function voteCount(array $data)
	{
		//check if the submitted referrence number exists
		if($this->referrenceExist($data['sid'], $data['referrenceNumber'])) {
      $this->response = ['success' => false, 'message' => 'Failed to submit vote: cannot submit same reference no'];
    } else {
    	try {
	    	$stmt = $this->db()->prepare("INSERT INTO votes (sid, amt_payment, vote_points, referrence_no, voters_email,vote_status, vote_datetime)
	    		VALUES (:sid, :payment, :points, :rnumber, :email, :status, :vdatetime)");
	    	$dataForm = [
	    		':sid' => $data['sid'],
	    		':payment' => $data['amtPayment'],
	    		':points' => $data['votePoints'],
	    		':rnumber' => $data['referrenceNumber'],
	    		':email' => $data['votersEmail'],
	    		':status' => $this->pending,
	    		':vdatetime' => $this->datetime
	    	];
	    	foreach($dataForm as $key => $value) {
	    		$stmt->bindParam($key, $dataForm[$key], \PDO::PARAM_STR);
	    	}

	    	if(!$stmt->execute()) {
					$this->response = ['success' => false, 'message' => 'Something went wrong: Failed to submit vote ' . $stmt->errorInfo()[2]];
				} 

	    	$this->response = ['success' => true, 'message' => 'Your vote has been successfully submitted. Thank you for participating; your vote is greatly appreciated and makes a difference. <br/><br/> <small>Kindly be informed that the vote counting process may require a brief moment as it includes a verification step for the team. Once this verification is completed, the vote count will commence.</small>'];

    	} catch (\PDOException $e) {
    		$this->response = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    	}
    }

		return $this->response;
	}

	/* [To update votes status]*/
	protected function setStatus($vid, $status)
	{
		$stmt = $this->db()->prepare("UPDATE votes SET vote_status = :vstatus WHERE vid = :vid LIMIT 1");
		$stmt->bindParam(':vstatus', $status, \PDO::PARAM_INT);
		$stmt->bindParam(':vid', $vid, \PDO::PARAM_INT);
		if(!$stmt->execute()) {
			$this->response = ['success' => false, 'message' => 'Failed to update status: ' . $stmt->errorInfo()[2]];
		}

		$this->response = ['success' => true, 'message' => 'Vote Verified Successfully!'];

		return $this->response;
	}

	/* [To get all votes records]*/
	protected function getAllData()
	{
		$stmt = $this->db()->prepare("SELECT v.*, c.* 
			FROM votes v
			INNER JOIN candidate c ON v.sid = c.sid
			ORDER BY vote_status ASC");
		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
		return $result !== false ? $result : null;
	}

	protected function getDataBySearch($inputQuery, $branchname = "")
	{
		$sql = "SELECT v.*, c.* 
		FROM votes v 
		INNER JOIN candidate c ON v.sid = c.sid
		WHERE CONCAT(v.referrence_no) LIKE :inputQuery";
		//check if this category is set or null
		if($branchname !== "") {
		  //hindi siya null, ibig sabihin si branch and category ay may laman
		  //then this query condition will add
		  $sql .= " AND c.sbranch = :branchname";
		}

		$stmt = $this->db()->prepare($sql);
		$stmt->bindValue(':inputQuery', '%' . $inputQuery . '%', \PDO::PARAM_STR);

		if($branchname !== "") {
		  $stmt->bindParam(':branchname', $branchname, \PDO::PARAM_STR);
		}

		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return $result !== false ? $result : null;
	}

	/*[To get data records by branch]*/
	protected function getDataByBranch($branchname)
	{
		$stmt = $this->db()->prepare("SELECT v.*, c.* 
			FROM votes v 
			INNER JOIN candidate c ON v.sid = c.sid
			WHERE c.sbranch = :branch
			ORDER BY v.vote_datetime DESC
		");
		$stmt->bindParam(':branch', $branchname, \PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
		return $result !== false ? $result : null;
	}

	/*[To get pending votes by branch]*/
	protected function getTotalPendingVotes($branchname)
	{
		$sql = "
			SELECT COUNT(*)
			FROM votes v
			INNER JOIN candidate c ON v.sid = c.sid
			WHERE v.vote_status = :status";

			if(!empty($branchname)) {
				$sql .= " AND c.sbranch = :branch";
			}

			$stmt = $this->db()->prepare($sql);
			$stmt->bindParam(':status', $this->pending, \PDO::PARAM_INT);

			if(!empty($branchname)) {
				$stmt->bindParam(':branch', $branchname, \PDO::PARAM_STR);
			}

			$stmt->execute();
			$count = $stmt->fetchColumn();
			return $count; 
	}

	protected function getTotalNumberOfVoters($branchname)
	{
		$sql = "
			SELECT COUNT(*)
			FROM votes v
			INNER JOIN candidate c ON v.sid = c.sid";

			if(!empty($branchname)) {
				$sql .= " AND c.sbranch = :branch";
			}

			$stmt = $this->db()->prepare($sql);
			if(!empty($branchname)) {
				$stmt->bindParam(':branch', $branchname, \PDO::PARAM_STR);
			}

			$stmt->execute();
			$count = $stmt->fetchColumn();
			return $count;
	}

	protected function getTotalAmmountPayment($branchname)
	{
		$sql = "
			SELECT 
			SUM(v.amt_payment) AS total_ammount
			FROM votes v
			INNER JOIN candidate c ON v.sid = c.sid
			WHERE v.vote_status = :status";

			if(!empty($branchname)) {
				$sql .= " AND c.sbranch = :branch";
			}

			$stmt = $this->db()->prepare($sql);
			$stmt->bindParam(':status', $this->verfied, \PDO::PARAM_INT);

			if(!empty($branchname)) {
				$stmt->bindParam(':branch', $branchname, \PDO::PARAM_STR);
			}

			$stmt->execute();
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			return $result;
	}


	

	/*[To delete records]*/
	protected function deleteVote($vid)
	{
		$stmt = $this->db()->prepare("DELETE FROM votes WHERE vid = :vid LIMIT 1");
		$stmt->bindParam(':vid', $vid, \PDO::PARAM_INT);
		if(!$stmt->execute()) {
			$this->response = ['success' => false, 'message' => 'Failed to delete vote: ' . $stmt->errorInfo()[2]];
		}

		$this->response = ['success' => true, 'message' => 'Vote Deleted successfully!'];

		return $this->response;
	}

	/* [to check referrence number submitted exist for that specific candidate] */
	private function referrenceExist($sid, $referrenceNumber)
	{
		$stmt = $this->db()->prepare("SELECT COUNT(*)
		 	FROM votes v
		 	INNER JOIN candidate c ON v.sid = c.sid
		 	WHERE c.sid = :sid 
		 	AND v.referrence_no = :referrence_no
		");

	 	$stmt->bindParam(':sid', $sid, \PDO::PARAM_INT);
	 	$stmt->bindParam(':referrence_no', $referrenceNumber, \PDO::PARAM_INT);
	 	$stmt->execute();
	 	$count = $stmt->fetchColumn();

	 	return $count > 0;
	}
}