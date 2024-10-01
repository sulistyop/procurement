<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
	public function index()
	{
		$activityLogs = ActivityLog::with('user')->orderBy('created_at', 'desc')
			->get()
			->map(function ($activityLog) {
				$activityLog->data = $activityLog->model::find($activityLog->model_id);
				return $activityLog;
			});
		
		return view('activity_logs.index', compact('activityLogs'));
	}
}