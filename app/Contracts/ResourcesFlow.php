<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface ResourcesFlow
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index();

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request);

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $model);

    /**
     * Display the specified resource.
     *
     * @param  \App\Models $model
     * @return \Illuminate\Http\Response
     */
    public function show($model);
}
