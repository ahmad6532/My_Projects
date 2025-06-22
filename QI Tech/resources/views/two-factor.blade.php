<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QI tech</title>

    <link rel="stylesheet" href="{{ asset('/css/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/loader.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('/css/colors.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('v2/fonts/LitteraText/stylesheet.css') }}">
    <link href="{{ asset('admin_assets/css/style.css') }}" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            /* background: linear-gradient(90deg, #2FC597, #1A92AF); */
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            font-family: "Littera Text", sans-serif !important;
        }

        section {
            display: flex;
            align-items: center;
            flex-direction: column;
            gap: 0.8rem;
            width: 50vw;
            min-width: 350px;
            background-color: white;
            border-radius: 8px;
            box-shadow: rgba(71, 75, 73, 0.255) 0px 0px 26px 0px;
            padding-top: 1.5rem;
            padding-bottom: 3rem;
            margin: 0 auto;
            position: relative;
        }


        .title {
            font-size: 2rem;
            font-weight: bold;
            color: #343434;
        }

        p {
            color: #707070;
            font-size: 16px;
            font-weight: 500;
        }

        .email {
            font-weight: 600;
            color: #505050;
        }

        .sub-info {
            font-size: 14px;
            text-align: left;
        }

        .inputs-wrapper {
            width: fit-content;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-block: 1rem;
        }

        input {
            width: 60px;
            aspect-ratio: 1/1;
            text-align: center;
            border-radius: 7px;
            border: none;
            background: #eceafa;
            margin: 0 10px;
            font-size: 26px;
            font-weight: bolder;
            transition: 0.2s ease-in-out;
        }

        input:focus {
            background: #e0e4e2;
            outline: none;
            transition: 0.5s ease-in-out;
        }

        button {
            /* width: 150px; */
            max-width: 350px;
            width: 100%;
            letter-spacing: 2px;
            margin-top: 24px;
            padding: 12px 16px;
            border-radius: 4px;
            border: none;
            font-size: 18px;
            font-weight: 500;
            /* background: linear-gradient(90deg, #1B93AE, #31C498); */
            background: #CF9E3F;
            color: white;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        button:hover {
            opacity: 0.8;
            transition: 0.2s ease-in-out;
        }

        button:disabled {
            background: linear-gradient(90deg, #757c7c, #a3b1ad);
        }

        .logo {
            width: 130px;
            margin-block: 1.5rem;
        }

        .resend-btn {
            color: #2cafa4;
            text-decoration: none;
            font-weight: 800;
            font-size: 14px;
        }

        .link-wrapper {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .link-wrapper>p {
            font-size: 14px;
        }

        #countdown {
            margin-top: 2rem;
            font-size: 16px;
            font-weight: bolder;
            color: #343434;
            opacity: 0;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25%,
            75% {
                transform: translateX(-10px);
            }

            50% {
                transform: translateX(10px);
            }
        }


        .wrong-otp input {
            animation: shake 0.3s cubic-bezier(0.215, 0.610, 0.355, 1);
            outline: 1px solid rgb(208, 50, 50);
        }

        .error-msg {
            position: absolute;
            position: absolute;
            bottom: 167px;
            font-weight: 600;
            color: #e45a5a;
        }

        .error-msg2 {
            position: absolute;
            position: absolute;
            bottom: 167px;
            font-weight: 600;
            color: #e45a5a;
        }

        .b-red input {
            outline: 1px solid rgb(208, 50, 50);
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding-inline: 3rem;
            padding-bottom: 3rem;
        }

        .back-button-login {
            text-decoration: none;
        }

        .help-btn {
            text-decoration: none;
            font-family: "Littera Text", sans-serif !important;
            color: #2cafa4;
            letter-spacing: 0.04rem;
            background: none;
            padding: 0;
            width: fit-content;
            font-size: 15px;
            font-weight: 400;
            margin: 0;
            /* color: white; */
            /* padding:  6px 12px; */
            /* border-radius: 7px; */
            /* box-shadow: 0 0 12px rgba(0, 0, 0, 0.253); */
        }

        .alertify .ajs-footer .ajs-buttons.ajs-primary {
            text-align: center;
        }
        .loader-bg{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;  
            display: grid;
            place-items: center;
            background: rgba(29, 29, 29, 0.456);
            z-index:99;
        }
    </style>
</head>

<body>
    <img class="logo" src="{{ $logo }}" alt="logo">
    <div class="loader-bg" id="loader" style="display: none;">
        <div class="bar-loader"></div>
    </div>
    <section>
        <div class="nav">
            <a class="back-button-login mr-4" href="{{ route('otp.logout') }}">
                <span class="back">← </span> Back
            </a>
            <button id="help-btn" class="help-btn">Help</button>
            <div id="msg" hidden>{{ isset($msg) ? $msg : 'QI tech support line details' }}</div>
        </div>
        <div class="title">Verification Code</div>
        <p class="sub-title">{{$location_admin == false ? 'Enter the verification code we sent to' : 'The verification code has been sent to your administrator.'}}</p>
        @if ($location_admin == false)
            <p class="email">{{ $user->email }}</p>
        @endif
        <div class="inputs-wrapper">
            <p class="sub-info">Type 6 digit security code</p>
            <div id='inputs'>
                <input id='input1' type='text' maxLength="1" class="digit-input" />
                <input id='input2' type='text' maxLength="1" class="digit-input" />
                <input id='input3' type='text' maxLength="1" class="digit-input" />
                <input id='input4' type='text' maxLength="1" class="digit-input" />
                <input id='input5' type='text' maxLength="1" class="digit-input" />
                <input id='input6' type='text' maxLength="1" class="digit-input" />
            </div>
        </div>
        <div class="link-wrapper">
            <p>Didn't receive the verification code?</p>
            <a class="resend-btn" id="resend-link" href="{{ $errors->has('error') ? '#' : route('otp.renew') }}">Resend
                Code</a>
        </div>
        <p class="error-msg" {{ $time == 0 ? '' : 'hidden' }}>{{ $time == 0 ? '' : 'Wrong Code!' }}</p>
        <p class="error-msg2" @if ($errors->has('error')) @else hidden @endif>
            {{ $errors->first('error', 'Too many Code requests, please try again after 1 minute') }}
        </p>
        <button id="verify-btn" disabled onclick="submitOtp()">Verify</button>
        <div style="{{ $time != 0 ? '' : 'display:none;' }}" id="countdown">{{ $time }}</div>
        <div id="expire-msg"
            style="font-weight:500;margin-top:2rem;font-size: 14px;color:#e45a5a;{{ $time == 0 ? '' : 'display:none;' }}">
            Verification code expired! <a style="color:#e45a5a;" href="{{ route('otp.renew') }}">Request new code</a>
        </div>
    </section>



    <script src="{{ asset('/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('/js/alertify.min.js') }}"></script>

    <!-- @if (isset($sent) || old('sent'))
        <script>
            alertify.alert('Verification Code has been sent to your Email Address!').set({
                title: "Email Sent!"
            });
        </script>
    @endif -->

    <script>
        $('#help-btn').on('click', function() {
            let msg = $('#msg').text();
            alertify.alert(msg).set({
                title: "Help!"
            });
        });

        $(document).ready(function() {
            $('#input1').focus();
            // handle singl paste
            $('.digit-input').on('input', function() {
                var $this = $(this);
                var val = $this.val();

                if (/^\d$/.test(val)) {
                    $this.next('.digit-input').focus();

                    if ($this.is('#input6')) {
                        $this.blur();
                    }
                } else {
                    $this.val('');
                }
            });

            // handle paste event
            $('#input1').on('paste', function(event) {
                var $this = $(this);

                var pastedValue = event.originalEvent.clipboardData.getData('text');

                // If pasted value contains 1 to 4 digits
                if (/^\d{1,6}$/.test(pastedValue)) {
                    // Split the value into individual digits
                    var digits = pastedValue.split('');

                    $('.digit-input').each(function(index) {
                        if (digits[index] !== undefined) {
                            $(this).val(digits[index]);
                        } else {
                            $(this).val(
                            ''); 
                        }
                    });

                    $this.next('.digit-input').focus();
                } else {
                    $this.val('');
                }
            });

        });

        const inputsWrapper = document.getElementById('inputs');
        const inputs = ["input1", "input2", "input3", "input4", "input2", "input6"];
        const countdownElement = document.getElementById('countdown');

        inputs.map((id) => {
            const input = document.getElementById(id);
            addListener(input);
        });

        function addListener(input) {
    input.addEventListener("input", () => {
        const code = parseInt(input.value);
        if (code >= 0 && code <= 9) {
            const n = input.nextElementSibling;
            if (n) n.focus();
        } else {
            input.value = "";
        }
        checkFulfillment();
    });

    input.addEventListener("keydown", (event) => {
        const key = event.key;
        if (key === "Backspace" && input.value === "") {
            const prev = input.previousElementSibling;
            if (prev) prev.focus();
        }
    });
}

        const verifyBtn = document.getElementById('verify-btn');
        let fulfilledInputs = 0;

        function checkFulfillment() {
            fulfilledInputs = 0;

            inputs.forEach((id) => {
                const input = document.getElementById(id);
                if (input.value !== "") {
                    fulfilledInputs++;
                }
            });

            if (fulfilledInputs === inputs.length) {
                verifyBtn.removeAttribute("disabled");
                submitOtp();
            } else {
                verifyBtn.setAttribute('disabled', 'true');
            }
        }

        async function submitOtp() {
            if (fulfilledInputs === 6) {
                const otpValue1 = document.getElementById('input1').value;
                const otpValue2 = document.getElementById('input2').value;
                const otpValue3 = document.getElementById('input3').value;
                const otpValue4 = document.getElementById('input4').value;
                const otpValue5 = document.getElementById('input5').value;
                const otpValue6 = document.getElementById('input6').value;
                verifyBtn.setAttribute('disabled', 'true');
                try {
                    $('#loader').fadeIn();
                    const response = await fetch("/submit-otp", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            otp: `${otpValue1}${otpValue2}${otpValue3}${otpValue4}${otpValue5}${otpValue6}`
                        }),
                    });

                    if (response.status === 410) {
                        $('#loader').fadeOut();
                        countdownElement.style.visibility = 'hidden';
                        inputsWrapper.classList.add("b-red");
                        document.querySelector('.error-msg').removeAttribute('hidden');
                        document.querySelector('.error-msg').innerText = 'Too many tries! Refresh the Code.';
                        document.getElementById('input1').setAttribute('disabled', 'true')
                        document.getElementById('input2').setAttribute('disabled', 'true')
                        document.getElementById('input3').setAttribute('disabled', 'true')
                        document.getElementById('input4').setAttribute('disabled', 'true')
                        document.getElementById('input5').setAttribute('disabled', 'true')
                        document.getElementById('input6').setAttribute('disabled', 'true')


                    }
                    if (response.status === 498) {
                        $('#loader').fadeOut();
                        countdownElement.style.visibility = 'hidden';
                        inputsWrapper.classList.add("b-red");
                        document.querySelector('.error-msg').removeAttribute('hidden');
                        document.querySelector('.error-msg').innerText = 'Code expired! Refresh the Code.';
                        $('#countdown').hide();
                        document.querySelector('#expire-msg').style.display = 'block';
                        document.getElementById('input1').setAttribute('disabled', 'true')
                        document.getElementById('input2').setAttribute('disabled', 'true')
                        document.getElementById('input3').setAttribute('disabled', 'true')
                        document.getElementById('input5').setAttribute('disabled', 'true')
                        document.getElementById('input6').setAttribute('disabled', 'true')
                    }
                    if (!response.ok && response.status !== 410) {
                        $('#loader').fadeOut();
                        verifyBtn.removeAttribute('disabled');
                        document.querySelector('.error-msg').removeAttribute('hidden');
                        inputsWrapper.classList.add("wrong-otp");
                        setTimeout(function() {
                            inputsWrapper.classList.remove("wrong-otp");
                            document.getElementById('input1').value = ''
                            document.getElementById('input2').value = ''
                            document.getElementById('input3').value = ''
                            document.getElementById('input4').value = ''
                            document.getElementById('input5').value = ''
                            document.getElementById('input6').value = ''
                            $('#input1').focus();
                        }, 500);
                        throw new Error('Network response was not ok');
                    }
                    const data = await response.json();
                    if (response.ok) {
                        document.querySelector('.error-msg').setAttribute('hidden', 'true');
                        setTimeout(function() {
                            window.location.href = `${data.url}`
                        }, 5000);
                    }
                    console.log(data.message);
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        }

        let timeLeft = countdownElement.innerText;



        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            const formattedTime = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            countdownElement.innerText = formattedTime;
            countdownElement.style.opacity = 1;
            timeLeft--;

            // Check if the timer has reached zero
            if (timeLeft < 0) {
                clearInterval(timerInterval);
                $('#countdown').hide();
                        document.querySelector('#expire-msg').style.display = 'block';
            }
        }
        const timerInterval = setInterval(updateTimer, 1000);
    </script>



</body>

</html>
