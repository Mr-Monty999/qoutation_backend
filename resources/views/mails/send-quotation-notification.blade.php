<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>{{ $data['subject'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <style type="text/css">
        /**
   * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
   */
        @media screen {
            /* @font-face {
                font-family: "Source Sans Pro";
                font-style: normal;
                font-weight: 400;
                src: local("Source Sans Pro Regular"), local("SourceSansPro-Regular"),
                    url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format("woff");
            }

            @font-face {
                font-family: "Source Sans Pro";
                font-style: normal;
                font-weight: 700;
                src: local("Source Sans Pro Bold"), local("SourceSansPro-Bold"),
                    url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format("woff");
            } */
        }

        /**
   * Avoid browser level font resizing.
   * 1. Windows Mobile
   * 2. iOS / OSX
   */
        body,
        table,
        td,
        a {
            -ms-text-size-adjust: 100%;
            /* 1 */
            -webkit-text-size-adjust: 100%;
            /* 2 */
        }

        /**
   * Remove extra space added to tables and cells in Outlook.
   */
        table,
        td {
            mso-table-rspace: 0pt;
            mso-table-lspace: 0pt;
        }

        /**
   * Better fluid images in Internet Explorer.
   */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /**
   * Remove blue links for iOS devices.
   */
        a[x-apple-data-detectors] {
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
            text-decoration: none !important;
        }

        /**
   * Fix centering issues in Android 4.4.
   */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }

        body {
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /**
   * Collapse table borders to avoid space between cells.
   */
        table {
            border-collapse: collapse !important;
        }

        a {
            color: #1a82e2;
        }

        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }

        h1,
        h3,
        p {
            text-align: center;

        }
    </style>
    @if (app()->getLocale() == 'ar')
        <style>
            * {
                direction: rtl
            }
        </style>
    @endif
</head>

<body style="background-color: #e9ecef">
    <!-- start preheader -->
    {{-- <div class="preheader"
        style="
        display: none;
        max-width: 0;
        max-height: 0;
        overflow: hidden;
        font-size: 1px;
        line-height: 1px;
        color: #fff;
        opacity: 0;
      ">
        A preheader is the short summary text that follows the subject line when
        an email is viewed in the inbox.
    </div> --}}
    <!-- end preheader -->

    <!-- start body -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- start logo -->
        <tr>
            <td align="center" bgcolor="#e9ecef">
                <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
        <td align="center" valign="top" width="600">
        <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                    <tr>
                        <td align="center" valign="top" style="padding: 36px 24px">
                            <a href="{{ asset(env('LOCAL_LOGO')) }}" target="_blank" style="display: inline-block">
                                <img src="{{ asset(env('LOCAL_LOGO')) }}" alt="Logo" border="0"
                                    style="
                      display: block;
                      width: 100px;

                    " />
                            </a>
                        </td>
                    </tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
            </td>
        </tr>
        <!-- end logo -->

        <!-- start hero -->
        <tr>
            <td align="center" bgcolor="#e9ecef">
                <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
        <td align="center" valign="top" width="600">
        <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                    <tr>
                        <td align="left" bgcolor="#ffffff"
                            style="
                  padding: 36px 24px 0;
                  font-family: 'Cairo', sans-serif;
                  border-top: 3px solid #d4dadf;
                ">
                            <h1
                                style="
                    margin: 0;
                    font-size: 35px;
                    font-weight: 700;
                    letter-spacing: -1px;
                    line-height: 48px;
                  ">
                                {{ $data['subject'] }}
                            </h1>
                        </td>
                    </tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
            </td>
        </tr>
        <!-- end hero -->

        <!-- start copy block -->
        <tr>
            <td align="center" bgcolor="#e9ecef">
                <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
        <td align="center" valign="top" width="600">
        <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                    <!-- start copy -->
                    <tr>
                        <td align="left" bgcolor="#ffffff"
                            style="
                  padding: 24px;
                  font-family: 'Cairo', sans-serif;
                  font-size: 16px;
                  line-height: 24px;
                ">
                            <div style="text-align: center">
                                <div>
                                    <div style="margin: 0;font-weight: bold;font-size:25px;color:black">
                                        {{ trans('messages.supplier phone') }}:</div>
                                    <div style="margin: 0;font-weight: bold;font-size:20px; direction: ltr;color:black">
                                        +{{ $data['supplier_phone'] }}
                                    </div>
                                </div>
                                <br>
                                <div>
                                    <div style="margin: 0;font-weight: bold;font-size:25px;color:black">
                                        {{ trans('messages.supplier email') }}:</div>
                                    <div style="margin: 0;font-weight: bold;font-size:20px;color:black">
                                        {{ $data['supplier_email'] }}
                                    </div>
                                </div>
                                <br>
                                <div>
                                    <div style="margin: 0;font-weight: bold;font-size:25px;color:black">
                                        {{ trans('messages.quotation title') }}:</div>
                                    <div style="margin: 0;font-weight: bold;font-size:20px;color:black">
                                        {{ $data['quotation_title'] }}
                                    </div>
                                </div>
                                <br>
                                <div>
                                    <div style="margin: 0;font-weight: bold;font-size:25px;color:black">
                                        {{ trans('messages.quotation price') }}:</div>
                                    <div style="font-weight: bold;font-size:20px;color:black">
                                        {{ number_format($data['quotation_price'], 2) }}
                                        {{ trans('messages.SAR') }}</div>
                                </div>
                                <br>
                                <div>
                                    <div style="margin: 0;font-weight: bold;font-size:25px;color:black">
                                        {{ trans('messages.quotation description') }}:</div>
                                    <div style="margin: 0;font-weight:bold;font-size:20px;color:black">
                                        {{ $data['quotation_description'] }}</div>
                                </div>
                                <br>
                                <div>
                                    <div style="margin: 0;font-weight: bold;font-size:25px;color:black">
                                        {{ trans('messages.url') }}:</div>
                                    <div style="margin: 0;font-weight:bold;font-size:20px;color:black">
                                        {{ env('FRONTEND_URL') . '/user/services/' . $data['service_id'] }}</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- end copy -->

                    <!-- start button -->
                    {{-- <tr>
                        <td align="left" bgcolor="#ffffff">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" bgcolor="#ffffff" style="padding: 12px">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center" bgcolor="#1a82e2" style="border-radius: 6px">
                                                    <div
                                                        style="
                                display: inline-block;
                                padding: 16px 36px;
                                font-family: 'Cairo', sans-serif;
                                font-size: 20px;
                                color: #ffffff;
                                text-decoration: none;
                                border-radius: 6px;
                                font-weight: bold;
                                letter-spacing: 5px;
                              ">

                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr> --}}
                    <!-- end button -->

                    <!-- start copy -->
                    <!-- <tr>
              <td
                align="left"
                bgcolor="#ffffff"
                style="
                  padding: 24px;
                  font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif;
                  font-size: 16px;
                  line-height: 24px;
                "
              >
                <p style="margin: 0">
                  If that doesn't work, copy and paste the following link in
                  your browser:
                </p>
                <p style="margin: 0">
                  <a href="https://blogdesire.com" target="_blank"
                    >https://blogdesire.com/xxx-xxx-xxxx</a
                  >
                </p>
              </td>
            </tr> -->
                    <!-- end copy -->

                    <!-- start copy -->
                    <tr>
                        <td align="left" bgcolor="#ffffff"
                            style="
                  padding: 24px;
                  font-family: 'Cairo', sans-serif;
                  font-size: 16px;
                  line-height: 24px;
                  border-bottom: 3px solid #d4dadf;
                ">
                            <p style="margin: 0">
                                {{ trans('messages.best regards', [], 'ar') }} : {{ env('APP_NAME', [], 'ar') }}
                                <br />
                                <a href="{{ env('FRONTEND_URL') }}" target="_blank">{{ env('FRONTEND_URL') }}</a>
                                <br>
                                Mobile: +966547813233
                            </p>
                        </td>
                    </tr>
                    <!-- end copy -->
                </table>
                <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
            </td>
        </tr>
        <!-- end copy block -->

        <!-- start footer -->
        <tr>
            <td align="center" bgcolor="#e9ecef" style="padding: 24px">
                <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
        <td align="center" valign="top" width="600">
        <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                    <!-- start permission -->
                    <!-- <tr>
              <td
                align="center"
                bgcolor="#e9ecef"
                style="
                  padding: 12px 24px;
                  font-family: 'Cairo', sans-serif;
                  font-size: 14px;
                  line-height: 20px;
                  color: #666;
                "
              >
                <p style="margin: 0">
                  You received this email because we received a request for
                  [type_of_action] for your account. If you didn't request
                  [type_of_action] you can safely delete this email.
                </p>
              </td>
            </tr> -->
                    <!-- end permission -->

                    <!-- start unsubscribe -->
                    <!-- <tr>
                        <td align="center" bgcolor="#e9ecef"
                            style="padding: 12px 24px;font-family: 'Cairo', sans-serif;font-size: 14px; line-height: 20px; color: #666;">
                            <p style="margin: 0;">To stop receiving these emails, you can <a
                                    href="https://www.blogdesire.com" target="_blank">unsubscribe</a> at any time.</p>
                            <p style="margin: 0;">Paste 1234 S. Broadway St. City, State 12345</p>
                        </td>
                    </tr> -->
                    <!-- end unsubscribe -->
                </table>
                <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
            </td>
        </tr>
        <!-- end footer -->
    </table>
    <!-- end body -->
</body>

</html>
