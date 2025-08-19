<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function index()
    {
        $logs = AdminLog::all();
        return $this->respondWithSuccess($logs);
    }

    public function show($id)
    {
        $log = AdminLog::find($id);
        if (!$log) {
            return $this->respondWithError('Log not found', 404);
        }
        return $this->respondWithSuccess($log);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'admin_id' => 'required|exists:admins,id',
            'action' => 'required|string|max:255',
            'created_at' => 'required|date',
        ]);

        $log = AdminLog::create($request->all());
        return $this->respondWithSuccess($log, 'Log created successfully');
    }

    public function update(Request $request, $id)
    {
        $log = AdminLog::find($id);
        if (!$log) {
            return $this->respondWithError('Log not found', 404);
        }

        $this->validateRequest($request, [
            'admin_id' => 'sometimes|required|exists:admins,id',
            'action' => 'sometimes|required|string|max:255',
            'created_at' => 'sometimes|required|date',
        ]);

        $log->update($request->all());
        return $this->respondWithSuccess($log, 'Log updated successfully');
    }

    public function destroy($id)
    {
        $log = AdminLog::find($id);
        if (!$log) {
            return $this->respondWithError('Log not found', 404);
        }

        $log->delete();
        return $this->respondWithSuccess(null, 'Log deleted successfully');
    }
}
