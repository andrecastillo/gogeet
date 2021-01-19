<?php

namespace App\Http\Controllers;

use App\Models\Principle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrincipleController extends Controller
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
     * Show the main view for principles.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        return view('principles')->with('title', 'Principles');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $principles = Principle::where(['user_id'=>Auth::user()->id])->get();

        return response()->json([
            'principles' => $principles,
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
            'name' => 'string|required|max:256',
            'description' => 'string|nullable'
        ]);

        $principle = Principle::create([
            'name' => request('name'),
            'description' => request('description'),
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'principles' => $principle,
            'message' => 'Success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($principle_id)
    {
        return response()->json([
            'principle' => Principle::findOrFail($principle_id)
        ], 200);
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
    public function update(Request $request, Principle $principle)
    {
        $this->validate($request, [
            'name'        => 'string|required|max:256',
            'description' => 'string|nullable'
        ]);

        $principle->name = request('name');

        $principle->description = request('description');

        $principle->save();

        return response()->json([
            'message' => 'Principle updated successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Principle $principle)
    {
        $principle->delete();

        return response()->json([
            'message' => 'Principle deleted successfully!'
        ], 200);
    }
}
