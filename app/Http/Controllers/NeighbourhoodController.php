<?php

namespace App\Http\Controllers;

use App\Models\Neighbourhood;
use App\Http\Requests\StoreNeighbourhoodRequest;
use App\Http\Requests\UpdateNeighbourhoodRequest;

class NeighbourhoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreNeighbourhoodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNeighbourhoodRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Neighbourhood  $neighbourhood
     * @return \Illuminate\Http\Response
     */
    public function show(Neighbourhood $neighbourhood)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Neighbourhood  $neighbourhood
     * @return \Illuminate\Http\Response
     */
    public function edit(Neighbourhood $neighbourhood)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNeighbourhoodRequest  $request
     * @param  \App\Models\Neighbourhood  $neighbourhood
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNeighbourhoodRequest $request, Neighbourhood $neighbourhood)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Neighbourhood  $neighbourhood
     * @return \Illuminate\Http\Response
     */
    public function destroy(Neighbourhood $neighbourhood)
    {
        //
    }
}
