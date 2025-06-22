<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@800&display=swap" rel="stylesheet">
        <title>New Employee Added</title>
        <style>
            body{
	 	        margin: 0;
                font-family: 'Barlow', sans-serif;
                height: 100vh;
	        }
            a{
                text-decoration: none;
                cursor: pointer;
                color: #0092c2;
            }
            p{
                color: black;
                margin: 0;
                padding: 0;
            }
            h1,h2,h3,h4,h5,h6{
                margin: 0;
                padding: 0;
            }
            table{
                border-collapse: collapse;
            }
            .main{
                margin: 0 auto;
                background-color: #EAF0F3;
            }
            td{
                padding: 0;
            }
            h1{
                font-size: 22px;
                font-weight: 600;
                line-height: 28px;
                color: #000000;
            }
            h3{
                font-size: 19px;
                font-weight: 600;
                line-height: 23px;
                color: #5E5E5E;
                margin-bottom: 8px;
            }
            img{
                display: block;
                max-width: 100%;
            }
            .password-para{
                padding: 0 70px;
            }
            .password-para p{
                font-size: 18px;
                line-height: 22px;
                font-weight: 400;
                color: #5E5E5E;
                margin-bottom: 20px;
            }
            button{
                font-size: 10px;
                font-weight: 700;
                line-height: 14px;
                color: #FFFFFF;
                background: #3490EC;
                padding: 10px 40px;
                border: 1px solid #3490EC;
                border-radius: 50px;
                margin-bottom: 20px;
            }
            .thanku-para{
                padding: 0 46px;
            }
            .thanku-para p{
                font-size: 18px;
                line-height: 22px;
                font-weight: 400;
                color: #5E5E5E;
                margin-bottom: 40px;
            }
            .red{
                color: #C53534 !important;
                letter-spacing: 2px;
                font-weight: 600 !important;
                margin-bottom: 30px !important;
                margin-top: 5px;
            }
            .footer p{
                font-size: 14px;
                line-height: 18px;
                font-weight: 400;
                color: #000000;

            }
        </style>
    </head>
    <body>
        @php($logo=\App\Models\Setting::where(['key'=>'App_Logo'])->first()->value)
        @php($business_name=\App\Models\Setting::where(['key'=>'App_Name'])->first()->value)
        <table style="width: 100%;" height="100%">
            <tr>
                <td valign="middle">
                    <table class="main" width="550" align="center">
                        <tbody>
                            <tr>
                                <td align="center" style=" padding: 20px 0;">
                                    <h1>New Employee Added</h1>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px 50px 20px;">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table style="background-color: white; width: 100%;">
                                                        <tr>
                                                            <td align="center" style="padding: 20px 0 20px;">
                                                                <h2><img src="{{ asset('assets/admin/img/'.$logo) }}" style="max-width: 15%;"></h2>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="password-para" align="left">
                                                                <h3>Dear {user_name}</h3>
                                                                <p>To activate your {{$business_name}} Account, please verify your email address.</p>
                                                                <p>Your account will not be created until your email address is confirmed.</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="password-para" align="center">
                                                                <h1>Verification Code</h1>
                                                                <p class="red">{{$otp}}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="password-para" style="padding: 0 70px;">
                                                                <p style="color:#BB1015; font-weight:700;">{{$business_name}} Team</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="password-para" style="padding: 0 70px;">
                                                            <p style="font-weight:700;">Email: {{$email}}</p>
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="password-para" style="padding: 0 70px;">
                                                            <p style="font-weight:700;">Phone: {{$admin_phone}}</p>
                                                        </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 20px;" align="center">
                                    <table style="width: 100%;">
                                        <tbody class="footer">
                                            <!-- <tr>
                                                <td align="center">
                                                    <h3><img src="{{ asset('assets/admin/img/Paymo-logo.png') }}" style="max-width: 20%;"></h3>
                                                </td>
                                            </tr> -->
                                            <tr>
                                                <td align="center">
                                                    <p>Copyright © 2022. All Rights Reserved.</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>