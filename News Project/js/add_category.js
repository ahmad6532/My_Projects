 let add_cat = document.querySelector('#add_category');
        add_cat.addEventListener('click', function(e) {
            let new_cat =document.querySelector('#new_category');
            let new_cat_span = document.querySelector('#new_cat_span');
            if (new_cat.value == "") {
                new_cat_span.style.visibility = "visible";
                e.preventDefault();

            }else {
                return true;
            }
        })