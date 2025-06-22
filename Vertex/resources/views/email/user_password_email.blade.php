<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@800&display=swap" rel="stylesheet">
    <title>Forgot</title>
    <style>
        body{
            margin: 0;
            font-family: 'Barlow', sans-serif;
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
            width: 100%;
        }
        .main{
            width: 600px;
            margin: 0 auto;
            background-color: #EAF0F3;
        }
        td{
            padding: 0;
        }
        h1{
            font-size: 27px;
            font-weight: 600;
            line-height: 30px;
        }
        img{
            display: block;
            max-width: 100%;
        }
        .password-para{
            padding: 0 46px;
        }
        .password-para p{
            font-size: 15px;
            line-height: 19px;
            font-weight: 400;
            color: #5E5E5E;
            margin-bottom: 40px;
        }
        button{
            font-size: 10px;
            font-weight: 700;
            line-height: 14px;
            color: #FFFFFF;
            background: linear-gradient(0deg, #3490EC, #3490EC), #FF743C;
            padding: 10px 40px;
            border: 1px solid #3490EC;
            border-radius: 50px;
            margin-bottom: 20px;
        }
        .thanku-para{
            padding: 0 46px;
        }
        .thanku-para p{
            font-size: 15px;
            line-height: 19px;
            font-weight: 400;
            color: #5E5E5E;
            margin-bottom: 40px;
        }
        .footer p{
            font-size: 12px;
            line-height: 16px;
            font-weight: 400;
            color: #000000;

        }
    </style>
</head>
<body>
<table class="main">
    <tbody>
    <tr>
        <td align="center" style=" padding: 30px 0;">
            <h1>Create Your Password</h1>
        </td>
    </tr>
    <tr>
        <td style="padding: 0px 20px 20px;">
            <table>
                <tbody>
                <tr>
                    <td>
                        <table style="background-color: white;">
                            <tr>
                                <td align="center" style="padding: 10px 0 20px;">
                                    <h2><img src="{{ asset('assets/images/theme/' . $setting[16]['value']) }}" style="max-width: 15%;"></h2>
                                </td>
                            </tr>
                            <tr>
                                <td class="password-para" align="center">
                                    <p>We have recently received a Password for your  account.
                                         please use below OTP</p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <h2>{{$user}}</h2>
                                    <h2>{{$email}}</h2>
                                    <h2>{{$password}}</h2>
                                </td>
                            </tr>
                            <tr>
                                <td class="thanku-para" align="center">
                                    <p>If you did not request this change, you do not need to do anything. Thank You,</p>
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
        <td style="padding-bottom: 60px;">
            <table>
                <tbody class="footer">
                <tr>
                    <td align="center">
                        <h3><img src="{{ asset('assets/images/unity_login_side_logo.png') }}" style="max-width: 20%;"></h3>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <p>Copyright © <?php echo date('Y');?>. All Rights Reserved.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
