<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRemarkRequest;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\TaskRemark;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * @group Tasks
     * Display task lists of a project
     *
     *  @bodyParam id int required The id of the project
     *
     * @response {
     *   "tasks": [
     *     {
     *       "id": 1,
     *       "project_id": 1,
     *       "title": "Variety media",
     *       "description": "Hai variety media",
     *       "status": "completed",
     *       "created_at": "2025-03-05T16:19:18.000000Z",
     *       "updated_at": "2025-03-05T16:21:35.000000Z",
     *       "remarks": [
     *         {
     *           "id": 1,
     *           "task_id": 1,
     *           "remark": "remark 1",
     *           "date": "2025-10-13",
     *           "created_at": "2025-03-05T16:26:44.000000Z",
     *           "updated_at": "2025-03-05T16:26:44.000000Z"
     *         }
     *       ]
     *     }
     *   ]
     * }
     *
     */

    public function index(Project $project)
    {
        $tasks = Task::where('project_id', $project->id)
                            ->with('remarks')
                            ->orderbyDesc('created_at')
                            ->get();
     
        return response()->json(['tasks' => $tasks]);
    }

    /**
     * @group Tasks
     * Create a new Task for a project
     *
     * @bodyParam project_id int required The id of the project.
     * @bodyParam title string required.
     * @bodyParam description string required.
     * @bodyParam status string required
     *
      * @response {
     *      "message": "Task successfully added!",
     *      "data": {
     *          "project_id": "1",
     *          "title": "Variety media",
     *          "description": "This is 4th task",
     *          "status": "pending",
     *          "updated_at": "2025-03-05T10:54:34.000000Z",
     *          "created_at": "2025-03-05T10:54:34.000000Z",
     *          "id": 1
     *      }
     * }
     */
    public function store(StoreTaskRequest $request)
    {
        $validatedData = $request->validated();

        $task = Task::create($validatedData);

        return response()->json([
            'message' => 'Task successfully added!',
            'data' => $task
        ]);
    }

    /**
     * @group Tasks
     * Create a new remark for a task
     *
     * @bodyParam task_id int required The id of the task.
     * @bodyParam remark string required.
     * @bodyParam date date required.
     *
      * @response {
     *      "message": "Task Remark successfully added!",
     *      "data": {
     *          "remark": "remark 1",
     *          "date": "2025-10-13",
     *          "updated_at": "2025-03-05T10:54:34.000000Z",
     *          "created_at": "2025-03-05T10:54:34.000000Z",
     *          "id": 1
     *      }
     * }
     */
    public function addRemarks(StoreTaskRemarkRequest $request, Task $task)
    {
        $validatedData = $request->validated();
        $validatedData['task_id'] = $task->id;

        $remark = TaskRemark::create($validatedData);

        return response()->json([
            'message' => 'Task Remark successfully added!',
            'data' => $remark
        ]);
    }

    /**
     * @group Tasks
     * Show a Task
     *
     * @bodyParam task_id int required The id of the task.
     * @bodyParam project_id int required The id of the project.
     * @bodyParam title string required.
     * @bodyParam description string required.
     * @bodyParam status string required
     *
      * @response {
     *      "message": "Task retrieved successfully!",
     *      "data": {
     *          "id": 1,
     *          "project_id": "1",
     *          "title": "Variety media",
     *          "description": "This is 4th task",
     *          "status": "completed",
     *          "updated_at": "2025-03-05T10:54:34.000000Z",
     *          "created_at": "2025-03-05T10:54:34.000000Z",
     *          "remarks": []
     *      }
     * }
     */
    public function show(Task $task)
    {
        $task->load('remarks');

        return response()->json([
            'message' => 'Task retrieved successfully!',
            'data' => $task
        ]);
    }

    /**
     * @group Tasks
     * Update a Task
     *
     * @bodyParam task_id int required The id of the task.
     * @bodyParam project_id int required The id of the project.
     * @bodyParam title string required.
     * @bodyParam description string required.
     * @bodyParam status string required
     *
      * @response {
     *      "message": "Task updated successfully!",
     *      "data": {
     *          "id": 1,
     *          "project_id": "1",
     *          "title": "Variety media",
     *          "description": "This is 4th task",
     *          "status": "completed",
     *          "updated_at": "2025-03-05T10:54:34.000000Z",
     *          "created_at": "2025-03-05T10:54:34.000000Z",
     *      }
     * }
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {

        $validatedData = $request->validated();
        $task->update($validatedData);

        return response()->json([
            'message' => 'Task updated successfully!',
            'data' => $task
        ]);

    }

   /**
     * @group Tasks
     * Remove or delete a task 
     *
     * @bodyParam id int required The id of the task
     *
     * @response {
     *      "message": "Task deleted successfully!",
     * }
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully!'
        ]);
    }
}
