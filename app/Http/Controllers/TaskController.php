<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Tasks;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Tasks::orderBy('priority')->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!empty($request->input('task_name'))){
            $task = new Tasks();
            $task->task_name = $request->input('task_name');
            $task->priority = $request->input('priority');
            $task->save();
            return redirect('/tasks/create')->withSuccess('Task created successfully');
        } else {
            return redirect()->back()->withError('Task name is required');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //dd($id);
        $task = Tasks::where('id', $id)->first();
        if($task == null){
            return redirect()->back()->withError('Task Not Found');
        } else {
            return view('tasks.edit', compact('task'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Tasks::where('id', $id)->first();
        if($task != null & !empty($request->input('taks_name'))){
            $task->task_name = $request->input('task_name');
            $task->priority = $request->input('priority');
            $task->save();
            return redirect('/tasks/create')->withSuccess('Task updated successfully');
        } else {
            return redirect()->back()->withError('Some Error Occured');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Tasks::where('id', $id)->first();
        if($task != Null){
            $task->delete();
            $response = array('status'=>true, 'msg'=>'Task Deleted Successfully.');
        } else {
            $response = array('status'=>false, 'msg'=>'Record Not Found');
        }
        return json_encode($response);
    }

    public function reorder(Request $request){
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'distinct'],
        ]);

        $ids = $validated['order'];
        DB::transaction(function () use ($ids) {
            // Lock and retrieve the tasks that can be reordered.
            $tasks = Tasks::whereIn('id', $ids)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Reject missing or unauthorized task IDs.
            if ($tasks->count() !== count($ids)) {
                throw ValidationException::withMessages([
                    'order' => 'One or more tasks are invalid.',
                ]);
            }
            foreach ($ids as $index => $id) {
                $tasks[$id]->update([
                    'priority' => $index + 1,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Task order changed successfully.',
        ]);
    }
}