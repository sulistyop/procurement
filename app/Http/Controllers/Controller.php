<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
	public function setLogActivity($action, $modelInstance = null)
	{
		$activityLog = new ActivityLog();
		$activityLog->user_id = Auth::id();
		$activityLog->action = $action;
		$activityLog->model = get_class($modelInstance);
		if($modelInstance->id) {
			$activityLog->model_id = $modelInstance->id;
		}
		$activityLog->save();
	}
}
