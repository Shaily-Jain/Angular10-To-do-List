<?php

namespace App\Http\Controllers;

use App\Models\todolist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Exception;
use \Illuminate\Database\QueryException;
use Response;

class TodolistController extends Controller
{
    public function index()
    {
        try
        {  
            $todolists = todolist::all();
            $categories = DB::table('category')->get();
            return view('home', compact('todolists','categories'));
        }
        catch(QueryException $e)
        {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger',$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try
        {   
            $data = $request->validate([
                'name' => 'required',
                'category_id' => 'required'
            ]);

            $category_name = DB::table('category')->select('category.name')->where('id',$request->category_id)->get();
            $dataitem = todolist::create([
                'name' => $request->name,
                'category_id' => $category_name[0]->name
            ]);
            return response()->json(array('status' => 'success', 'data' => $dataitem));
        }
        catch(QueryException $e)
        {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger',$e->getMessage());
        }
    }

    public function destroy(todolist $todolist)
    {
        try
        {
            $todoitem = $todolist->delete();
            return response()->json(array('status' => 'success', 'data' => $todoitem));

        }
        catch(QueryException $e)
        {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger',$e->getMessage());
        }
    }
}
