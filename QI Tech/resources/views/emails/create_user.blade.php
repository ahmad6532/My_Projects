<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #f6f6f6;
        font-family: Arial, sans-serif;
        text-align: center;
    }
  
    .email-container {
        max-width: 650px;
        margin: 0 auto;
        background-color: #ffffff;
        padding: 40px 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
  
    .logo {
        margin-bottom: 20px;
    }
  
    .logo img {
        width: 150px; 
    }
  
    p {
        font-size: 28px;
        color: #333333;
        margin-bottom: 20px;
    
    }
  
    p.greeting, p.message {
        font-size: 18px;
        color: #666666;
        margin: 10px 0;
    }
  
    .btn {
    display: inline-block;
    background-color: #7cdceb;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 16px; 
    margin-top: 25px;
}
  
    .image-below {
        width: 100%;
        max-width: 180px;
        margin: 20px auto;
    }
  
    .footer {
        font-size: 12px;
        color: #888888;
        margin-top: 25px;
        line-height: 1.5;
    }
  </style>
  
  <div class="email-container">
    <div class="logo">
        <img src="{{ asset('images/svg/logo_blue.png') }}" alt="Logo" title="Logo"> 
    </div>
    
    <p style="font size 26px">You're ready!</p>
    <p class="greeting">Hello {{$ho_request->first_name}}</p>
    <p class="message">Your company account is set up and ready to go!</p>
  
    <a href="{{route('create.headOfficeUser.request',$token)}}" class="btn">Sign In</a>
    <br>
    <img src="{{ asset('images/login-cloud2.png') }}" alt="cloud" class="image-below">
  
    <p class="footer">
        This is an automated email. This inbox is not monitored.<br>
        Copyright &copy; 2024 Qi-Tech
    </p>
  </div>
  