<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Todolist;
use Illuminate\Http\Request;

class TodolistController extends Controller
{
    //get all todolists
    public function todolists($id){
        $todos = Todolist::with('todos')->where('owner_id', $id)
        ->latest()->get();

        return response()->json($todos);
    }

    //get all todos
    public function show($id){
        $todolist= Todolist::with('todos')->where('id',$id)->first();

        return response()->json($todolist);
    }

    //store todolist to database
    public function store(Request $request){
        $request->validate([
            'todolistName' => 'required|string|min:4|max:50',
            'ownerId' => 'required|integer'
        ]);
        $owner = Owner::where('id', $request->ownerId)->first();
        if ($owner) {
            $todolist = Todolist::create([
                'name' => $request->todolistName,
                'owner_id' => $request->ownerId,
            ]);
        return response()->json(['message' => 'Todolist succesfully Created', 'data' => $todolist]);

        }else{
            return response()->json(['message' => 'Something went wrong']);
        }
        
        /* $todolist = $owner->todolists()->create([
            'name' => $request->todolistName,
        ]); */

    }


    //update todolistitem to database
    public function update(Request $request, $id){
        $request->validate([
            'todolistName' => 'required|string|min:4|max:50',
        ]);
        $todolist= Todolist::where('id', $id)->first();
        $todolist->name = $request->todolistName;
        $todolist->update();

        return response()->json(['message' => 'TodoList succesfully Updated', 'data' => $todolist]);
    }


    public function destroy($id){
        $todolist= Todolist::destroy($id);

        return response()->json(['message' => 'TodoList succesfully Deleted', 'data' => $todolist]);
    }
}
