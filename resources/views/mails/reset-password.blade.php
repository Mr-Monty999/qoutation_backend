@extends('mails.layouts.layout1')
@section('content')
    <div
        style="
                        margin: 0;
                        margin-top: 70px;
                        padding: 92px 30px 115px;
                        background: #ffffff;
                        border-radius: 30px;
                        text-align: center;
                    ">
        <div style="width: 100%; max-width: 489px; margin: 0 auto">
            <h3 style="">
                {{ trans('messages.Reset Password') }}
            </h3>

            <h1
                style="
                                margin: 0;
                                font-size: 24px;
                                font-weight: 500;
                                color: #1f1f1f;
                            ">
                {{ trans('messages.your otp is') }}
            </h1>
            {{-- <p
                style="
                                margin: 0;
                                margin-top: 17px;
                                font-size: 16px;
                                font-weight: 500;
                            ">
                Hey Tomy,
            </p> --}}
            <p
                style="
                                margin: 0;
                                margin-top: 60px;
                                font-size: 40px;
                                font-weight: 600;
                                letter-spacing: 25px;
                                color: #0d6efd;
                            ">
                {{ $data['code'] }}
            </p>
        </div>
    </div>
@endsection
