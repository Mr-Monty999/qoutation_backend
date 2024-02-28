<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ env('APP_NAME') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
</head>

<body
    style="
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            font-size: 14px;
        ">
    <div
        style="
                max-width: 680px;
                margin: 0 auto;
                padding: 45px 30px 60px;
                background: #f4f7ff;
                /* background-image: url(https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661497957196_595865/email-template-background-banner);
                background-repeat: no-repeat;
                background-size: 800px 452px;
                background-position: top center; */
                font-size: 14px;
                color: #434343;
            ">
        <header>
            <table style="width: 100%">
                <tbody>
                    <tr style="height: 0">
                        <td
                            style="
                                    text-align: center;
                                    background-color: #0d6efd;
                                    padding: 13px;
                                    border-radius: 5px;
                                ">
                            <img alt="" src="{{ asset(env('LOCAL_LOGO')) }}" height="30px" />
                        </td>
                        <!-- <td style="text-align: right">
                                <span
                                    style="
                                        font-size: 16px;
                                        line-height: 30px;
                                        color: #ffffff;
                                    "
                                    >12 Nov, 2021</span
                                >
                            </td> -->
                    </tr>
                </tbody>
            </table>
        </header>

        <main>
            @yield('content')

            <!-- <p
                    style="
                        max-width: 400px;
                        margin: 0 auto;
                        margin-top: 90px;
                        text-align: center;
                        font-weight: 500;
                        color: #8c8c8c;
                    "
                >
                    Need help? Ask at
                    <a
                        href="mailto:archisketch@gmail.com"
                        style="color: #499fb6; text-decoration: none"
                        >archisketch@gmail.com</a
                    >
                    or visit our
                    <a
                        href=""
                        target="_blank"
                        style="color: #499fb6; text-decoration: none"
                        >Help Center</a
                    >
                </p> -->
        </main>

        <footer
            style="
                    width: 100%;
                    max-width: 490px;
                    margin: 20px auto 0;
                    text-align: center;
                    border-top: 1px solid #e6ebf1;
                ">
            <!-- <p
                    style="
                        margin: 0;
                        margin-top: 40px;
                        font-size: 16px;
                        font-weight: 600;
                        color: #434343;
                    "
                >
                    Taseer
                </p> -->
            <!-- <p style="margin: 0; margin-top: 8px; color: #434343">
                    Address 540, City, State.
                </p> -->
            <div style="margin: 0; margin-top: 16px;text-align: center;">
                {{ trans('messages.best regards', [], 'ar') }} : {{ env('APP_NAME', [], 'ar') }}
                <br />
                <a href="{{ env('FRONTEND_URL') }}" target="_blank">{{ env('FRONTEND_URL') }}</a>
                <br />
                Mobile: +966547813233
            </div>
            <p style="margin: 0; margin-top: 16px; color: #434343;text-align: center;">
                Copyright © 2022 {{ env('APP_NAME', [], 'ar') }}. All rights
                reserved.
            </p>
        </footer>
    </div>
</body>

</html>
