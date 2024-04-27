document.addEventListener("DOMContentLoaded", function () {
    const body = document.getElementById('body');
    const li = document.querySelectorAll('.li');
    const btn = document.getElementById('btn');
    const port = document.getElementById('port');
    const hi = document.querySelector('.hi');
    const input = document.querySelectorAll('.input');
    var   bar = document.getElementById('3-bar');
    const menue_bar = document.getElementById('main-menue');
    const header= document.querySelector('.header');



// Menue bar for Mobile screens
    let x = 1;
    bar.addEventListener('click', function () {
        if (x) {
            menue_bar.style.display = "block";
        }
        else {
            menue_bar.style.display = "none";
        }
        x = !x;
    });

    // For black and White theme
    btn.addEventListener("click", function () {
        if (x) {

            body.style.backgroundColor = "white";
            body.style.color = "black";
            header.style.backgroundColor="white";
            port.style.color = "rgb(251, 90, 41)";
            hi.style.color = "#eda005";

            input.forEach(function (element) {
                element.style.backgroundColor = 'rgb(242,240,240)';
                element.style.color = "black";
            });
            li.forEach(function (object) {
                object.addEventListener("mouseover", function () {

                    object.style.backgroundColor = "rgb(235, 149, 21)";
                    object.style.color = "white";
                    object.style.boxShadow = "0px 0px 0px 0px transparent";
                    object.style.border = "1px solid transparent";
                    object.style.padding = "9px 19px";
                });
                object.addEventListener("mouseout", function () {

                    object.style.backgroundColor = "transparent";
                    object.style.color = "rgb(235, 149, 21)";
                    object.style.border = "1px solid transparent";
                });
            });


        }
        else {
            body.style.backgroundColor = "black";
            body.style.color = "white";
            port.style.color = "white";
            header.style.backgroundColor="black";

            hi.style.color = "yellow";
            input.forEach(function (element) {
                element.style.backgroundColor = 'rgb(237, 233, 233)';
                element.style.color = "black";
            });

            li.forEach(function (object) {
                object.addEventListener("mouseover", function () {

                    object.style.backgroundColor = "white";
                    object.style.color = "rgb(235, 149, 21)";
                    object.style.boxShadow = "0px 0px 40px 15px rgb(235, 149, 21)";
                    object.style.border = "1px solid white";
                    object.style.padding = "9px 19px";
                });
                object.addEventListener("mouseout", function () {

                    object.style.backgroundColor = "transparent";
                    object.style.color = "rgb(235, 149, 21)";
                    object.style.boxShadow = "0px 0px 0px 0px rgb(235, 149, 21)";
                    object.style.border = "1px solid transparent";
                    object.style.padding = "9px 19px";
                });
            });


        }
        x = !x;
    });


});

// Phone Number validation
document.getElementById("phone").addEventListener("input", function() {
    this.value = this.value.replace(/\D/g, ''); 
  });


//   Form submit validation
function formsubmit() {
    var name = document.getElementById("name");
    var email = document.getElementById("email");
    var phone = document.getElementById("phone");
    var message = document.getElementById("message");

    if (name.value.length <= 0) {
        name.style.border = "none";
        name.style.boxShadow = "0px 0px 11px 4px red";
        return false;
    }
    else if (email.value.length <= 0) {
        email.style.border = "none";
        email.style.boxShadow = "0px 0px 11px 4px red";
        return false;
    }
    else if (phone.value.length <= 0) {
        phone.style.border = "none";
        phone.style.boxShadow = "0px 0px 11px 4px red";
        return false;
    }
    else if (message.value.length <= 0) {
        message.style.border = "none";
        message.style.boxShadow = "0px 0px 11px 4px red";
        return false;
    }
    else {
        // return true;
    }
    return false;
}
