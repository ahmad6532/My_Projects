<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">

    <title>QR Code</title>
    <link href="{{ asset('v2/fonts/LitteraText/stylesheet.css') }}" rel="stylesheet">
    
    <style>
    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        background-color: #f5f5f5;
        font-family: "Littera Text";
        padding:0;
        
    }
    *{
        font-family: "Littera Text";
    }
    .wrapper {
        width: 100%;
        max-width: 330px;
        padding: 55px 110px;
        margin: auto;
        background:#2aafa4 !important;
        position:relative;
        -webkit-print-color-adjust: exact; 
        color-adjust: exact;
        display: grid;
        place-items: center;
        }
    .wrapper::before{
        content: '';
        position:absolute;
        top:25%;
        left:0;
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 198.5px 0 198.5px 343.8px;
        border-color: transparent transparent transparent #FFFFFF;
        transform: rotate(0deg);
        z-index:1;
    }
    .wrapper::after{
        content :'';
        position:absolute;
        top:25%;
        right:0;
        z-index:1;
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 198.5px 343.8px 198.5px 0;
        border-color: transparent #FFFFFF transparent transparent;
        transform: rotate(0deg);
    }
    .header, .foot{
        color:white;
        text-align:center;
    }
    .code{
        position: relative;
        background:black;
        color:white;
        padding: 14px;
        border-radius: 16px;
        width: 230px;
        margin: 0 auto;
        z-index:2;
    }
    .code p{
        font-size:39px;
        margin: 0;
        margin-top: 10px;
    }
    .center{
        text-align:center;
    }
    .header h2{
        font-size:30px;
        font-weight: normal;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .header h1{
        font-size:34px;
        font-weight:bold;
        margin-top: 2px;
    }
    .foot img{
        margin-top:0;
    }
    .small{
        font-size:12px;
        margin-bottom:0px;
    }
    .img-wrapper{
        margin: 0 auto;
        width:630px;
    }
    @media print{
        .img-wrapper{
            margin:0 !important;
            text-align:left;
        }
        .no-print{
            display: none;
        }
    }
    
    .btn {
    display: inline-block;
    font-weight: 400;
    color: #fff;
    text-align: center;
    vertical-align: middle;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.35rem;
    border:none
}
.btn-info {
        background-color: #2BAFA5 ;
    }
    .btn-info {
        color: #fff;
        background-color: #36b9cc;
        border-color: #36b9cc;
    }
    .btn-default {
    color: #fff;
    background-color: #b3b3b3;
    border-color: #b3b2b2;
}
button, select {
    text-transform: none;
}
button, input, optgroup, select, textarea {
    margin: 0;
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}
.btn:not(:disabled):not(.disabled) {
    cursor: pointer;
}
    </style>

<link href="{{route('location.color_css')}}" rel="stylesheet">
  </head>
  <!-- https://www.qrcode-monkey.com/ -->
  <body>
    <div class="wrapper bg-white" id="capture-area" style="background: white !important;">
        <div class=" text-center" >
            <img width="200px" src="{{$location->getBrandingAttribute()->logo}}" alt="">
        </div>
        <p class="m-0" style="font-size:2rem;text-align:center;color:#434343;">Scan to record <br> {{isset($form) ? 'Incident' : 'Near Miss'}}</p>
            <div class="code center">
            <img src="{{isset($form) ? $location->getFormQr($form->id) : $location->getQrCodeLinkAttribute()}}" width="230">
            <p>SCAN ME</p>
        </div>
        <div class=" center">
            <p style="font-size: 25px;margin-bottom:0;">{{$location->trading_name}}</p>
            <p style="font-size: 20px;margin-top:5px;">{{$location->full_address}}</p>
        </div>
    </div>
    <div class="no-print" style="display: flex;flex-direction:column;gap:0.5rem;margin-right:2rem;">
        <button id="download-btn" class="btn btn-info">Download</button>
        <button class="btn btn-info " onclick="javascript:print()">Print</button>
        <button class="btn btn-default " onclick="javascript:window.close()">Close</button>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="{{asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script>
        window.onload = function() {
    document.getElementById("download-btn").addEventListener("click", function() {
        const captureArea = document.getElementById("capture-area");
        if (captureArea) {
            html2canvas(captureArea, {
                scale: 5,
                onrendered: function(canvas) {
                    var imgData = canvas.toDataURL("image/png");
                    var link = document.createElement('a');
                    link.href = imgData;
                    link.download = 'Qi-Tech NearMiss.png';
                    link.click();
                }
            });
        } else {
            console.error("Capture area not found!");
        }
    });
};

    </script>

    <!-- <div class="img-wrapper">
        <img src="{{asset('images/qr-code-final.png')}}" height="700">
    </div> -->
</body>
</html>
