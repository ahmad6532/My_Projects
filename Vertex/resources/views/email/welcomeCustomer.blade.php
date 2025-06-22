<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@800&display=swap" rel="stylesheet">
    <title>Welcome</title>
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
            padding: 20px 46px;
        }
        .password-para p{
            font-size: 12px;
            line-height: 19px;
            font-weight: 400;
            color: #5E5E5E;
            margin-bottom: 10px;
        }
       
        .password-para p span{
            font-size: 12px;
            line-height: 19px;
            font-weight: 600;
            color: #5E5E5E;
            margin-bottom: 10px;
        }
      .para{
            padding: 0 46px;
        }
        .para p{
            font-size: 12px;
            line-height: 19px;
            font-weight: 400;
            color: #5E5E5E;
            margin-bottom: 5px;
        }
      .para p span{
            font-size: 12px;
            line-height: 19px;
            font-weight: 600;
            color: #5E5E5E;
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
      .visit-btn{
      	background-color:#028CC9;
      color:#ffffff !important;
      padding:8px 15px;
      border-radius:60px;
      font-size:12px;

      }
    </style>
</head>
<body>
<table class="main">
    <tbody>
    <tr>
        <td style="padding: 40px 50px 20px;">
            <table>
                <tbody  style="background-color: white;">
                    <tr>
                        <td style="border-bottom:1px solid rgb(211, 205, 205);padding:10px;">
                            <h2><img src="{{asset('assets/media/logo-letter-1.png')}}" style="max-width: 18%;"></h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td align="center" style="padding: 10px 0 10px;">
                                        <h2><img src="{{asset('assets/media/misc/welcome-email.png')}}" style="max-width: 18%;"></h2>
                                        <tr>
                                            <td  align="center" style="padding-bottom: 20px;">
                                                <h4>Welcome to Securegenic!</h4>
                                            </td>
                                        </tr>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="password-para">
                                        <h5 style="margin-bottom:10px;">Dear {{$name}}!</h5>
                                        <p>We're thrilled to have you join the Securgenic family! Welcome Onboard!<br/>
                                            <br/>
                                            To help you get started, we have created your account and generated your login credentials. Please find your account details below:<br/>
                                        </p>
                                        <p><span>User Name:</span> {{$username}}</p>
                                        <p><span>Password:</span> {{$password}}</p>
                                        <p>For security purposes, we strongly recommend changing your password as soon as possible.</p>
                                        <p>Click on the following link to access the login page
                                        <a href="https://mdm.securegenic.com/" target="_blank">https://mdm.securegenic.com/</a></p>
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
        <td style="padding-bottom: 60px;" align="center">
            <table>
                <tbody class="footer">
                <tr>
                    <td align="center">
                        <h3><img src="{{ asset('assets/media/logos/logo-letter-1.png')}}" style="max-width: 20%;"></h3>
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
