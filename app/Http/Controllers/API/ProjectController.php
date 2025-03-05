<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * @group Projects
     * Display Project list
     *
      * @response {
     *   "projects": {
     *     "current_page": 1,
     *     "data": [
     *       {
     *         "id": 1,
     *         "user_id": 1,
     *         "title": "New Project 4",
     *         "description": "This is 4th project",
     *         "created_at": "2025-03-05T16:05:21.000000Z",
     *         "updated_at": "2025-03-05T16:05:21.000000Z",
     *         "tasks": []
     *       }
     *     ],
     *     "first_page_url": "http://127.0.0.1:8000/api/project-list?page=1",
     *     "from": 1,
     *     "last_page": 1,
     *     "last_page_url": "http://127.0.0.1:8000/api/project-list?page=1",
     *     "links": [
     *       {
     *         "url": null,
     *         "label": "« Previous",
     *         "active": false
     *       },
     *       {
     *         "url": "http://127.0.0.1:8000/api/project-list?page=1",
     *         "label": "1",
     *         "active": true
     *       },
     *       {
     *         "url": null,
     *         "label": "Next »",
     *         "active": false
     *       }
     *     ],
     *     "next_page_url": null,
     *     "path": "http://127.0.0.1:8000/api/project-list",
     *     "per_page": 10,
     *     "prev_page_url": null,
     *     "to": 1,
     *     "total": 1
     *   }
     * }
     */
    public function index()
    {
        $projects = Project::where('user_id', auth()->user()->id)
                            ->with('tasks.remarks')
                            ->orderbyDesc('created_at')
                            ->paginate(10);

        return response()->json(['projects' => $projects]);
    }

    /**
     * @group Projects
     * Create a new project
     *
     * @bodyParam title string required.
     * @bodyParam description string required.
     *
      * @response {
     *      "message": "Project successfully added!",
     *      "data": {
     *          "title": "New Project 4",
     *          "description": "This is 4th project",
     *          ""user_id": 1,
     *          "updated_at": "2025-03-05T10:54:34.000000Z",
     *          "created_at": "2025-03-05T10:54:34.000000Z",
     *          "id": 1
     *      }
     * }
     */
    public function store(StoreProjectRequest $request)
    {
        
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->user()->id;

        $project = Project::create($validatedData);

        return response()->json([
            'message' => 'Project successfully added!',
            'data' => $project
        ]);
    }

    /**
     * @group Projects
     * Display a project
     *
     * @bodyParam id int required The id of the project
     *
      * @response {
     *      "message": "Project retrieved successfully!",
     *      "data": {
     *          "id": 1,
     *          "title": "New Project 4",
     *          "description": "This is 4th project",
     *          ""user_id": 1,
     *          "updated_at": "2025-03-05T10:54:34.000000Z",
     *          "created_at": "2025-03-05T10:54:34.000000Z",
     *      }
     * }
     */
    public function show(Project $project)
    {
        $project->load('tasks');

        return response()->json([
            'message' => 'Project retrieved successfully!',
            'data' => $project
        ]);
    }

    /**
     * @group Projects
     * Update a  project
     *
     * @bodyParam title string required.
     * @bodyParam description string required.
     *
      * @response {
     *      "message": "Project updated successfully!",
     *      "data": {
     *          "title": "New Project 4",
     *          "description": "This is 4th project",
     *          ""user_id": 1,
     *          "updated_at": "2025-03-05T10:54:34.000000Z",
     *          "created_at": "2025-03-05T10:54:34.000000Z",
     *          "id": 1
     *      }
     * }
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {

        $validatedData = $request->validated();
        $project->update($validatedData);

        return response()->json([
            'message' => 'Project updated successfully!',
            'data' => $project
        ]);

    }

    /**
     * @group Projects
     * Remove or delete a project 
     *
     * @bodyParam id int required The id of the project
     *
     * @response {
     *      "message": "Project deleted successfully!",
     * }
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully!'
        ]);
    }
}
