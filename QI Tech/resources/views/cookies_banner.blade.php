<style>
    .card {
        width: 300px;
        height: 230px;
        background-color: rgb(255, 255, 255);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px 30px;
        gap: 13px;
        position: relative;
        overflow: hidden;
        box-shadow: 2px 2px 20px rgba(0, 0, 0, 0.062);
    }

    .cookieSvg {
        width: 30px;
    }

    

    .cookieHeading {
        font-size: 1.2em;
        font-weight: 800;
        color: rgb(26, 26, 26);
        margin-bottom: 0px;
    }

    .cookieDescription {
        text-align: center;
        font-size: 13px;
        font-weight: 600;
        color: rgb(99, 99, 99);
    }

    .cookieDescription a {
        --tw-text-opacity: 1;
        color: rgb(59 130 246);
    }

    .cookieDescription a:hover {
        -webkit-text-decoration-line: underline;
        text-decoration-line: underline;
    }

    .buttonContainer {
        display: flex;
        gap: 20px;
        flex-direction: row;
    }

    .acceptButton {
        width: 80px;
        height: 30px;
        background-color: #2CAFA4;
        transition-duration: .2s;
        border: none;
        color: rgb(241, 241, 241);
        cursor: pointer;
        font-weight: 600;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px #b4ebe6, 0 2px 4px -1px #2CAFA4;
        transition: all .6s ease;
    }

    .declineButton {
        width: 80px;
        height: 30px;
        background-color: #dadada;
        transition-duration: .2s;
        color: rgb(46, 46, 46);
        border: none;
        cursor: pointer;
        font-weight: 600;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px #bebdbd, 0 2px 4px -1px #bebdbd;
        transition: all .6s ease;
    }

    .declineButton:hover {
        background-color: #ebebeb;
        box-shadow: 0 10px 15px -3px #bebdbd, 0 4px 6px -2px #bebdbd;
        transition-duration: .2s;
    }

    .acceptButton:hover {
        background-color: #31cdc0;
        box-shadow: 0 10px 15px -3px #b4ebe6, 0 4px 6px -2px #2CAFA4;
        transition-duration: .2s;
    }
    .backdrop {
        background: rgba(0, 0, 0, 0.122);
        position: fixed;
        width: 100%;
        height: 100vh;
        display: grid;
        place-items: center;
        z-index: 1000;
    }
</style>

<div class="backdrop cookie_card" style="display: none;">


    <div class="card cookie_card">
        <img src="{{asset('images/cookie.svg')}}" alt="" class="cookieSvg">
        
        <p class="cookieHeading">We use cookies.</p>
        <p class="cookieDescription">We use cookies to ensure that we give you the best experience on our website. <br><a
                href="{{ route('about_us') }}#tab-link-tab-2">Read cookies policies</a>.</p>
    
        <div class="buttonContainer">
            <button class="acceptButton" onclick="saveCookieStorage()">Allow</button>
            <button class="declineButton" onclick="declineCookie()">Decline</button>
        </div>
    </div>
    
    <script>
        
        document.addEventListener("DOMContentLoaded", function() {
                        if (!localStorage.getItem('cookieAccepted')) {

                $('.cookie_card').fadeIn();
            }
        });
    
        function saveCookieStorage() {
                localStorage.setItem('cookieAccepted', true);
    
            $('.cookie_card').fadeOut();
        }

        function declineCookie() {
            $('.cookie_card').fadeOut();
        }
    </script>
    