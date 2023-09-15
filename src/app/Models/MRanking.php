<?php
declare(strict_types = 1);

namespace App\Models;
/**
 * This class handles database interactions related to candidate rankings.
 * It retrieves candidate rankings by branch, including total vote points
 * and the total number of voters for each candidate.
 *
 * @link https://javecilla.vercel.app/
 * @link https://github.com/javecilla/
 */
class MRanking extends Database
{
	private int $status = 1; // Status 1 indicates verified votes

	public function __contruct() {}

	/*[Get the candidates ranking by specified branch]*/
	protected function getCandidatesRanking($branchname, $category)
	{
    $sql = "
      SELECT c.*, 
      SUM(v.vote_points) AS total_vote_points,
      COUNT(*) AS total_number_of_voters
      FROM candidate c
      LEFT JOIN votes v ON c.sid = v.sid
      WHERE c.sbranch = :branchname AND v.vote_status = :status";

    //check if category is not empty, then add the condition to the query
    if(!empty($category)) {
      $sql .= " AND c.category = :category";
    }

    $sql .= "
      GROUP BY c.cid, c.cname, c.category, c.sbranch, c.imgname, c.imgext
      ORDER BY total_vote_points DESC";

    $stmt = $this->db()->prepare($sql);
    $stmt->bindParam(':branchname', $branchname, \PDO::PARAM_STR);
    $stmt->bindParam(':status', $this->status, \PDO::PARAM_INT);

    // Bind the category parameter if it's not empty
    if(!empty($category)) {
        $stmt->bindParam(':category', $category, \PDO::PARAM_STR);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $result;
	}


}