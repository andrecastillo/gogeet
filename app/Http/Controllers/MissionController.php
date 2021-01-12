<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MissionController extends Controller
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
     * Show the main view for missions.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        return view('missions')->with('title', 'Missions');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $missions = Mission::where(['user_id'=>Auth::user()->id])->get();

        return response()->json([
            'missions' => $missions,
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required|max:256',
            'description' => 'string|nullable',
            'due_date' => 'date|nullable',
        ]);

        $mission = Mission::create([
            'name' => request('name'),
            'description' => request('description'),
            'user_id' => Auth::user()->id,
            'due_date' => request('due_date')
        ]);

        return response()->json([
            'missions' => $mission,
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
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Mission $mission)
    {
        $this->validate($request, [
            'name'        => 'string|required|max:256',
            'description' => 'string|nullable',
            'due_date' => 'date|nullable'
        ]);

        $mission->name = request('name');
        $mission->description = request('description');
        $mission->due_date = request('due_date');
        $mission->save();

        return response()->json([
            'message' => 'Mission updated successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Mission $mission)
    {
        $mission->delete();

        return response()->json([
            'message' => 'Mission deleted successfully!'
        ], 200);
    }
}
