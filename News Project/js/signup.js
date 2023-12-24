 let submit = document.querySelector('#submit');
        submit.addEventListener('click', function(e) {

            let name = document.querySelector('#name');
            let usespan = document.querySelector('#namespan');
            let email = document.querySelector('#email');
            let emailspan = document.querySelector('#emailspan');
            let password = document.querySelector('#password');
            let passspan = document.querySelector('#passspan');
            let cpassword = document.querySelector('#cpassword');
            let cpassspan = document.querySelector('#cpassspan');

            if (name.value == "") {
                usespan.style.visibility = "visible";
                e.preventDefault();

            } else if (email.value == "") {
                emailspan.style.visibility = "visible";
                e.preventDefault();

            } else if (password.value == "") {
                passspan.style.visibility = "visible";
                e.preventDefault();

            } else if (cpassword.value == "") {
                cpassspan.style.visibility = "visible";
                e.preventDefault();

            } else {
                return true
            }

        })