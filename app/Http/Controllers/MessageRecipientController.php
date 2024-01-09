<?php

namespace App\Http\Controllers;

use App\Models\MessageRecipient;
use App\Http\Requests\StoreMessageRecipientRequest;
use App\Http\Requests\UpdateMessageRecipientRequest;

class MessageRecipientController extends Controller
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
     * @param  \App\Http\Requests\StoreMessageRecipientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRecipientRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MessageRecipient  $messageRecipient
     * @return \Illuminate\Http\Response
     */
    public function show(MessageRecipient $messageRecipient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MessageRecipient  $messageRecipient
     * @return \Illuminate\Http\Response
     */
    public function edit(MessageRecipient $messageRecipient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMessageRecipientRequest  $request
     * @param  \App\Models\MessageRecipient  $messageRecipient
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMessageRecipientRequest $request, MessageRecipient $messageRecipient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MessageRecipient  $messageRecipient
     * @return \Illuminate\Http\Response
     */
    public function destroy(MessageRecipient $messageRecipient)
    {
        //
    }
}
