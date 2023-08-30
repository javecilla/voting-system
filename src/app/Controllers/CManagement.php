<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Models\MManagement;
use App\Views\VManagement;
/**
 * 
 */
class CManagement extends MManagement
{
	public static function createData($dataForm)
	{
		$cdata = new MManagement();
		$result = $cdata->createCandidate($dataForm);
		return $result;
	}

	public static function updateData($dataForm)
	{
		$udata = new MManagement();
		$result = $udata->updateCandidate($dataForm);
		return $result;
	}

	public static function deleteData($thisId) 
	{
		$ddata = new MManagement();
		$result = $ddata->deleteCandidate($thisId);
		return $result;
	}


}