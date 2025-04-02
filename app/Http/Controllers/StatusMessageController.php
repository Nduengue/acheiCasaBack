<?php

namespace App\Http\Controllers;

use App\Models\StatusMessage;
use App\Http\Requests\StoreStatusMessageRequest;
use App\Http\Requests\UpdateStatusMessageRequest;

class StatusMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatusMessageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StatusMessage $statusMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatusMessage $statusMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusMessageRequest $request, StatusMessage $statusMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatusMessage $statusMessage)
    {
        //
    }
}
