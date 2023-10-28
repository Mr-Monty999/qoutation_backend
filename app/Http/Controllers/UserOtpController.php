<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserOtpRequest;
use App\Http\Requests\UpdateUserOtpRequest;
use App\Models\UserOtp;

class UserOtpController extends Controller
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
     * @param  \App\Http\Requests\StoreUserOtpRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserOtpRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserOtp  $userOtp
     * @return \Illuminate\Http\Response
     */
    public function show(UserOtp $userOtp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserOtp  $userOtp
     * @return \Illuminate\Http\Response
     */
    public function edit(UserOtp $userOtp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserOtpRequest  $request
     * @param  \App\Models\UserOtp  $userOtp
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserOtpRequest $request, UserOtp $userOtp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserOtp  $userOtp
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserOtp $userOtp)
    {
        //
    }
}
