

        let submit = document.querySelector('#submit');
        submit.addEventListener('click', function() {

            let email = document.querySelector('#email');
            let emailspan = document.querySelector('#emailspan');


            if (email.value == "") {
                emailspan.style.visibility = "visible";
            }

        })
    