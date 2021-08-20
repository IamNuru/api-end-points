<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Todolist;
use Illuminate\Http\Request;

class TodoController extends Controller
{

    //get all todos
    public function todos($id){
        $todos = Todo::with('todolist')->where('todolist_id', $id)->get();

        return response()->json($todos);
    }



    //get all todos
    public function show($id){
        $todo = Todo::with('todolist')->where('todolist_id', $id)->first();

        return response()->json($todo);
    }



    //store todo item to database
    public function store(Request $request, $id){
        $request->validate([
            'title' => 'required|string|min:2|max:20',
            'deadline' => 'nullable|date|after_or_equal:today',
        ]);
        $confirmTodolist = Todolist::find($id);
        if(!$confirmTodolist){
            return response()->json(['message'=>"We couldn't find related todolist"]);
        }
        $todo = new Todo();
        $todo->todolist_id = $id;
        $todo->title = $request->title;
        $todo->deadline = $request->deadline;
        $todo->save();

        return response()->json(['message'=>'Todo Item succesfully Added', 'data' => $todo]);
    }


    //update todo item to database
    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|string|min:2|max:20',
            'deadline' => 'nullable|date|after_or_equal:today',
            'todolist' => 'required|integer'
        ],[
            'todolist.required' => 'Please select a todolist',
            'todolist.integer' => 'Invalid todolist selected',
        ]);
        $todo = Todo::where('id', $id)->first();
        $todo->todolist_id = $request->todolist;
        $todo->title = $request->title;
        $todo->deadline = $request->deadline;
        $todo->update();

        return response()->json(['message' =>'Todo Item succesfully Updated', 'data' => $todo]);
    }

    
    //update todo item to database
    public function updatestatus(Request $request, $id){
        $request->validate([
            'status' => 'required|integer'
        ],[
            'status.integer' => 'Something went wrong'
        ]);
        $todo = Todo::where('id', $id)->first();
        $todo->completed = !$request->status;
        $todo->update();

        return response()->json(['message' =>'Status Updated', 'data' => $todo]);
    }


    public function destroy($id){
        $todo = Todo::destroy($id);

        return response()->json(['message' => 'Todo Item succesfully Deleted', 'data' => $todo]);
    }
}
