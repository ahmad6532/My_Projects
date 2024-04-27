 let submit = document.querySelector('#submit');
        submit.addEventListener('click', function(e) {
            let email = document.querySelector('#email');
            let usespan = document.querySelector('#emailspan');
            let password = document.querySelector('#password');
            let passspan = document.querySelector('#passspan');

            if (email.value == "") {
                usespan.style.visibility = "visible";
                e.preventDefault();

            } else if (password.value == "") {
                passspan.style.visibility = "visible";
                e.preventDefault();

            } else {
                return true;
            }
        })