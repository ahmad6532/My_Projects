 let submit = document.querySelector('#add_post');
        submit.addEventListener('click', function(e) {
            let title = document.querySelector('#title');
            let titlespan = document.querySelector('#titlespan');
            let author = document.querySelector('#author');
            let authorspan = document.querySelector('#authorspan');
            let description = document.querySelector('#description');
            let descriptionspan = document.querySelector('#descriptionspan');
            let category = document.querySelector('#category');
            let categoryspan = document.querySelector('#categoryspan');
            let image = document.querySelector('#image');
            let imagespan = document.querySelector('#imagespan');

            if (title.value == "") {
                titlespan.style.visibility = "visible";
                e.preventDefault();

            } else if (author.value == "") {
                authorspan.style.visibility = "visible";
                e.preventDefault();

            } else if (description.value == "") {
                descriptionspan.style.visibility = "visible";
                e.preventDefault();

            } else if (category.value == "Select Category") {
                categoryspan.style.visibility = "visible";
                e.preventDefault();

            } else if (image.value == "") {
                imagespan.style.visibility = "visible";
                e.preventDefault();

            } else {
                return true;
            }
        })