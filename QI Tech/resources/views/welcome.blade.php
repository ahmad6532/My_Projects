
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>QI-Tech</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="QI-Tech System" name="description" />
        <meta content="Khuram Nawaz Khayam" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="favicon.ico">

    </head>

    <body style="text-align: center">
        <img src="{{ asset('/images/loading-gif.gif') }}">
  
        <script>
            window.location.href='/app.html';

            setTimeout(() => {
                alert('Error loading System. Please refresh your web browser!');
            }, 10000);
        </script>

    </body>
</html>
