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
            <h1
                style="
                                margin: 0;
                                font-size: 24px;
                                font-weight: 500;
                                color: #1f1f1f;
                            ">
                {{ $data['subject'] }}
            </h1>
            <div style="width: 100%; max-width: 489px; margin: 0 auto">
                <div style="margin: 0;font-weight: bold;font-size:25px;color:black">
                    {{ trans('messages.url') }}:</div>
                <div style="margin: 0;font-weight:bold;font-size:20px;color:black">
                    <a href="{{ env('FRONTEND_URL') . '/user/quotations/' . $data['quotation_id'] . '/replies/my-reply' }}"
                        target="_blank">
                        {{ env('FRONTEND_URL') . '/user/quotations/' . $data['quotation_id'] . '/replies/my-reply' }}</a>
                </div>

            </div>
        </div>
    </div>
@endsection
