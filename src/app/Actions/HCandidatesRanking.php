<?php 
declare(strict_types = 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Views\VRanking;

define('READ', 'read');

if(isset($_GET['action']) && $_GET['action'] === READ) {
	$task = isset($_GET['task']) ? $_GET['task'] : '';
	switch($task) {
		case 'rankBybranch':
			$result = VRanking::readRankingByBranch($_GET['branchname'], $_GET['category']);
			if(is_array($result)) {
				foreach($result as $row):
					?>
						<tr>
		          <td scope="row" ><?=$row['cid']?></td>
		          <td scope="row" style="width: 120px;">
		            <img src="/src/app/Storage/candidates/<?=$row['imgname']?>.<?=$row['imgext']?>" 
		              alt="Candidate Photo" class="img-thumbnail img-responsive" 
		            />
		          </td>
		          <td><?=$row['cname']?></td>
		          <td>
		          	<?php if($row['category'] === "Lakan"): ?>
									<span class="badge bg-primary"><?=$row['category']?></span>
								<?php elseif($row['category'] === "Lakanbini"): ?>
									<span class="badge bg-secondary"><?=$row['category']?></span>
								<?php elseif($row['category'] === "Lakandyosa"): ?>
									<span class="badge bg-info"><?=$row['category']?></span>
								<?php endif; ?>
		          </td>
		          <td><span class="badge rounded-pill bg-success fs-1"><?= $row['total_vote_points'] ?></span></td>
		          <td><span class="badge rounded-pill bg-dark fs-1"><?= $row['total_number_of_voters'] ?></span></td>
		        </tr>
					<?php
				endforeach;
			} else {
				echo "<tr><td colspan='6'>No records found...</td></tr>";
			}
			break;
	}
}