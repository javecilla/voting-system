<?php
declare(strict_types = 1);

namespace App\Models;
/**
 * 
 */
class MDashboard extends Database
{
	private int $status = 0; //pending

	protected function getAllPendingVotes()
	{
		$stmt = $this->db()->prepare("
			SELECT c.*, 
			COUNT(*) AS total_pending_votes
			FROM candidate c
			INNER JOIN votes v ON c.sid = v.sid
			WHERE v.vote_status = :status
		");
    $stmt->bindParam(':status', $this->status, \PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $result;
	}
}