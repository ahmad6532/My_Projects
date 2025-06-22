<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div style="width: 100%; height: auto;">
        
            <img src="{{ $head_office_user->headOffice->company_logo }}" style="border: none; max-width: 100%; max-height: 100%; object-fit: contain;" />
   
    </div>
    <p class="header">Hey there!</p>
    <p><strong>{{ $head_office_user->headOffice->company_name }}</strong>has added you as a {{ $profile }}</p>
    <p class="footer">Best Regards,<br>{{ $head_office_user->headOffice->company_name }}</p>
</body>
</html>