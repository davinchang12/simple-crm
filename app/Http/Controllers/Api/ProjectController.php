<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->paginate(5);
        return new ProjectResource(true, 'Projects data list', $projects);
        
        // $projects = Project::all();
        // return ProjectResource::collection($projects);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'deadline' => 'required',
            'user_id' => 'required',
            'client_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->user_id);
        $client = Client::find($request->client_id);

        if (!$user || !$client) {
            return response()->json([
                "message" => "User or client data not found."
            ], 422);
        }

        $project = Project::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully added new project.',
            'data' => [$project->toArray()]
        ]);
    }
}
