<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the main view for goals.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        return view('goals');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $goals = Goal::where(['user_id'=>Auth::user()->id])->get();

        return response()->json([
            'goals' => $goals,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'due_date' => 'date'
        ]);

        $goal = Goal::create([
            'name' => request('name'),
            'description' => request('description'),
            'user_id' => Auth::user()->id,
            'due_date' => request('due_date')
        ]);

        return response()->json([
            'goals' => $goal,
            'message' => 'Success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Goal $goal)
    {
        $this->validate($request, [
            'name'        => 'required|max:256',
            'description' => 'required',
            'due_date' => 'date',
        ]);

        $goal->name = request('name');

        $goal->description = request('description');

        $goal->due_date = request('due_date');

        $goal->save();

        return response()->json([
            'message' => 'Goal updated successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Goal $goal)
    {
        $goal->delete();

        return response()->json([
            'message' => 'Goal deleted successfully!'
        ], 200);
    }
}
