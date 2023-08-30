<?php
declare(strict_types = 1);

namespace App\Models;

/**
 * 
 */
interface IProccessData {
	public function createData();
	public function updateData();
	public function deleteData();
}