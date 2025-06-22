<script>
   // jQuery all form scripting here.
    jQuery(document).ready(function(e){
        $(document).on( "click", ".delete_form", function(e) {
            e.preventDefault();
            let href= $(this).attr('href');
            alertify.confirm("Are you sure?", "Are you sure to delete this form? This will also delete all stages of this form.",
            function(){
                window.location.href= href;
            },function(i){
            });
        });

        $(".delete_stage" ).on( "click", function(e) {
            e.preventDefault();
            let href= $(this).attr('href');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", "Are you sure to delete this stage? This will also delete all questions of this stage.",
            function(){
                window.location.href= href;
            },function(i){
            });
        });
        $(".delete_task" ).on( "click", function(e) {
            e.preventDefault();
            let href= $(this).attr('href');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", "Are you sure to delete this task?",
            function(){
                window.location.href= href;
            },function(i){
            });
        });
        $(document).on('click','.delete_group',function(e){
            e.preventDefault();
            let href= $(this).attr('href');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", "Are you sure to delete this group? This will also delete all questions of this group.",
            function(){
                window.location.href= href;
            },function(i){
            });
        });

        $(document).on('click','.delete_question',function(e){
            e.preventDefault();
            let href= $(this).attr('href');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", "Are you sure to delete this question? This will also delete all actions of this question.",
            function(){
                window.location.href= href;
            },function(i){
            });
        });
        $(document).on('click','.delete_action',function(e){
            e.preventDefault();
            let href= $(this).attr('href');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", "Are you sure to delete this condition?",
            function(){
                window.location.href= href;
            },function(i){
            });
        });
        

        $('.toggle_ajax_model').on('click',function(e){
            let href = $(this).attr('data-href');
            $.ajax({
               // method: "POST",
                url: href,
                //data: { name: "John", location: "Boston" }
            }).done(function (msg) {
                    $('.modal-body').html(msg);
                    hideNotRequiredFields();
            });
        });

        
        $(document).on('click','.options .plus',function(e){
            let html = $('.options .clone_row').html();
            $('.options').append(html);
            $('.minus').show();
            $('.plus').hide();
            $('.clone_row .minus').hide();
            $('.clone_row .plus').show();
        });

        $(document).on('click','.options .minus',function(e){
            $(this).closest('.input-group').remove();
        });

        $(document).on('change','.new_field_type',function(e){
            hideNotRequiredFields();
            $('#field_minimum').val('');
            $('#field_maximum').val('');
        });


        function hideNotRequiredFields(form = false){
            let totalField = [
                'min',
                'max',
                'options',
                'multi_select',
                'select_loggedin_user',
                'address_specific',
                'select_loggedin_user_changed'
            ];

            // Check if form fields are defined
            if(typeof form_fields == undefined || typeof form_fields == 'undefined'){
                return; // the page has no form fields
            }
            let type = $('.new_field_type').find(':selected').val();
            var toShowFields = form_fields[type]['required_fields'];
            let min_max_type = 'number';
            switch (type) {
                case 'text':
                    min_max_type = 'number';
                    break;
                case 'number':
                    min_max_type = 'number';
                    break;
                case 'date':
                    min_max_type = 'date';
                    break;
                case 'time':
                    min_max_type = 'time';
                    break;
                case 'textarea':
                    min_max_type = 'number';
                    break;
                case 'textarea':
                    min_max_type = 'number';
                    break;
                default:
                    break;
            }
            // Change min max field type
            $('#field_minimum').attr('type',min_max_type);
            $('#field_maximum').attr('type',min_max_type);
            
            totalField.forEach(function(item){
                if(toShowFields.includes(item)){
                    $('.'+item).show();
                }else{
                    $('.'+item).hide();
                }
            });

            // User Specific
            if($('.new_field_type').find(':selected').val() == 'user'){
                if($('.select_loggedin_user > input').is(':checked')){
                    $('.select_loggedin_user_changed').show();
                }else{
                    $('.select_loggedin_user_changed').hide();
                }
            }
           
        }
        $(document).on('change','.select_loggedin_user',function(e){
            if($('.new_field_type').find(':selected').val() == 'user'){
                if($('.select_loggedin_user > input').is(':checked')){
                    $('.select_loggedin_user_changed').show();
                }else{
                    $('.select_loggedin_user_changed').hide();
                }
            } 
        });
        hideNotRequiredFields();
        $(document).on('change','.address_type',function(e){
            if($(this).find(':selected').val() == 'locations'){
                $('.address_select_loggedIn').show();
            }else{
                $('.address_select_loggedIn').hide();
            }
        });


        $(document).on('change','.action_type',function(e){
            let href = $(this).attr('data-href');
            let value= $(this).find(':selected').val();
            let question_id = $('#question_id').val();
            let condition_id =$('#condition_id').val();
            $.ajax({
                method: "GET",
                url: href,
                data: { type: value, question_id: question_id ,condition_id:condition_id }
            }).done(function (msg) {
                    $('.action_details').html(msg);
                    showBothRequiredSelectBox();
                    initTinyMce();
            });
        });
        $(document).on('click','.delete-email-attachment',function(e){
            e.preventDefault();
            let href = $(this).attr('href');
            $.ajax({
                method: "GET",
                url: href,
            }).done(function (msg) {
                $('.delete-email-attachment').remove();
                $('.preview-email-attachment').remove();
                // alertify();
            });
        });

        // Fix to show two fields when INBETWEEN is selected as value
        $(document).on('change','#if_value',function(e){
            let value= $(this).find(':selected').val();
            if(value == 'between'){
                $('.condition_value_2').show();
            }else{
                $('.condition_value_2').hide();
            }
        });

        $(document).on('change','.email_type',function(e){
            let value= $(this).find(':selected').val();
            if(value == 'free_type_email'){
                $('.free_type_email').show();
            }else{
                $('.free_type_email').hide();
            }
            
            if(value == 'user_selected_in_question_x'){
                $('.user_select_question_x').show();
            }else{
                $('.user_select_question_x').hide();
            }
        });
        jQuery(document).on('change','.5_why_required',function(e){
            showBothRequiredSelectBox();
        });
        jQuery(document).on('change','.fish_bone_required',function(e){
            showBothRequiredSelectBox();
        });
        jQuery(document).on('change','.case_close_checkbox',function(e){
            if(jQuery('.case_close_checkbox').is(':checked')){
                jQuery('.case_close_checkbox_msg').show();
            }else{
                jQuery('.case_close_checkbox_msg').hide()
            }
        });

        jQuery(document).on('click','.stagePrevButton',function(e){
            e.preventDefault();
            let currentStage = parseInt($('.progress-bar-list.active').attr('data-stage'));
            $("#progressbar li").eq(currentStage-1).removeClass("active");
            $("#progressbar li").eq(currentStage-2).addClass("active");

            stagesDisplay();
        });
        jQuery(document).on('click','.stageNextButton',function(e){
            e.preventDefault();
            let switchStage = true;
            let currentStage = parseInt($('.progress-bar-list.active').attr('data-stage'));
            $('.stages.stage_data_'+currentStage).find('input').each(function(index,element){
                if($(element)[0].checkValidity() == false){
                    $(element).closest('form')[0].reportValidity();
                    switchStage = false;
                }
            });
            $('.stages.stage_data_'+currentStage).find('select').each(function(index,element){
                if($(element)[0].checkValidity() == false){
                    $(element).closest('form')[0].reportValidity();
                    switchStage = false;
                }
            });
            $('.stages.stage_data_'+currentStage).find('textarea').each(function(index,element){
                if($(element)[0].checkValidity() == false){
                    $(element).closest('form')[0].reportValidity();
                    switchStage = false;
                }
            });

            if(switchStage){
                $("#progressbar li").eq(currentStage-1).removeClass("active");
                $("#progressbar li").eq(currentStage).addClass("active");
                stagesDisplay();
            }
           
        });

        jQuery(document).on('click','.risk-matrix a.value ',function(e){
            e.preventDefault();
            let value= jQuery(this).attr("data-value");
            $(this).closest('.risk-matrix').find('.risk-matrix-value').val(value).trigger('change');
            // Remove selected class
            $(this).closest('.risk-matrix').find('a.value.selected').removeClass('selected');
            $(this).addClass('selected');
        });
        
        $(document).on('change','.form_question',function(e){
            let id = $(this).attr('name');
            id = id.split("_").pop();
            processConditions(56);
        });

        var DmdOptions = {
            adjustWidth: false,
		    url: function (phrase) {
			    return '/location/bespokeforms/form/dmds/?query=' + phrase + '&format=json';
		},
		    getValue: 'description',
            list: {
                match: {
                    enabled: false
                },
			maxNumberOfElements: 30,
		    },
	    };
        try{
            $('.drug-field').easyAutocomplete(DmdOptions);
        }catch(e){
            //console.log(e);
        }
        initTinyMce();
        showBothRequiredSelectBox();
        stagesDisplay();
        
    });


    
    function showBothRequiredSelectBox(){
        if(jQuery('.5_why_required').is(':checked') && jQuery('.fish_bone_required').is(':checked')){
            jQuery('.both_whys_required').show();
        }else{
            jQuery('.both_whys_required').hide();
        }
    }   


    function initTinyMce(){
        if(typeof tinymce == 'undefined'){
            return;
        }
        $('.tinymce').tinymce({
        height: 500,
        menubar: true,
        automatic_uploads: true,
        plugins: [
           'advlist','autolink','lists','link','image','charmap','preview','anchor','searchreplace','visualblocks'
           ,'fullscreen','insertdatetime','media','table','help','wordcount'
        ],
        toolbar: 'undo redo | a11ycheck casechange blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist checklist outdent indent | removeformat | code image media link table help',
        images_upload_url: '/forms/attachment/upload',
      });
    }
   // let currentStage = 1;
    function stagesDisplay(){
        let stagesCount =  $('#form_stages').val();
        let lengthOfEachStage = parseFloat(100/stagesCount).toFixed();
        let currentStage = parseInt($('.progress-bar-list.active').attr('data-stage'));
        var percent = lengthOfEachStage * currentStage;
		percent = percent.toFixed();
		$(".progress-bar").css("width", percent + "%")
        $(".progress-bar-list").css("width", lengthOfEachStage + "%");

        
        // $('.stages.stage_data_'+currentStage).find('select').removeAttr('required');
        // $('.stages.stage_data_'+currentStage).find('textarea').removeAttr('required');

        if(currentStage == stagesCount){
            $('.stageNextButton').hide();
            $('.formSubmitButton').show();
        }else{
            $('.stageNextButton').show();
            $('.formSubmitButton').hide();
        }

        if(currentStage == 1){
            $('.stagePrevButton').hide();
        }else{
            $('.stagePrevButton').show();
        }

        $('.stage_name_top').text($('.progress-bar-list.active').text());

        $('.stages').hide();
        $('.stages.stage_data_'+currentStage).show();
    }

function initPlaces() {
    //var autocomplete = new google.maps.places.Autocomplete(document.getElementByClassName(''));
    var input = document.getElementsByClassName('free-type-address');
    for (let i = 0; i < input.length; i++) {
        var autocomplete = new google.maps.places.Autocomplete(input[i]);
        autocomplete.addListener('place_changed', function () {
        $(input[i]).trigger('change');
        });
    }
}

function processConditions(id){
    if(typeof conditions[id] == 'undefined'){
        return;
    }
    
    let cons = conditions[id];
    cons.forEach(function(condition){
        let type = condition.question_type;  
        if(type == 'text' || type == 'textarea' || type == 'dm+d'|| type == 'address'){
            /* Word detected */
            from_detect = $('#question_'+id).val();
            console.log(from_detect);
            to_detect = condition.condition_value;
            if(from_detect.includes(to_detect)){
                processAction(id,condition);
            }
            return false;
        }

        if(type == 'number' || type == 'age' || type == '5x5_risk_matrix'){
            if(type == 'age'){
                user_value = parseFloat(getAge($('#question_'+id).val()));
            }else{
                user_value = parseFloat($('#question_'+id).val());
            }
            if(type == '5x5_risk_matrix'){
                user_value = $('#question_'+id).val();
                let arr = user_value.split("-");
                user_value = arr[1];
            }
            value = parseFloat(condition.condition_value);
            value_1 = parseFloat(condition.condition_value_2);
            
            switch(condition.condition_if_value){
                case 'greater_then':
                    if(user_value > value){
                        processAction(id,condition);
                    }
                    break;
                case 'less_then':
                    if(user_value < value){
                        processAction(id,condition);
                    }
                    break;
                case 'between':
                    if(user_value > value && user_value < value_1  ){
                        processAction(id,condition);
                    }
                    break;
                case 'equal_to':
                    if(user_value == value){
                        processAction(id,condition);
                    }
                    break;
            }
            return false;
         }

         if(type == 'date'){
            user_value = Date.parse($('#question_'+id).val());
            number_of_days = parseInt(condition.condition_value);
            repored_date = Date.now();
            
            date_value_is_greater = false;
            if(repored_date < user_value){
                date_value_is_greater = true;
            }

            difference_in_days = 0;
            difference_in_days = Math.floor((repored_date - user_value) /(1000*60*60*24));
            difference_in_days = parseInt(Math.abs(difference_in_days));
            switch(condition.condition_if_value){
                case 'less_then':
                    if(date_value_is_greater == false && difference_in_days <= number_of_days){
                        processAction(id,condition);
                    }
                    break;
                case 'greater_then':
                    if(date_value_is_greater == true && difference_in_days >= number_of_days){
                        processAction(id,condition);
                    }
                    break;
                }
                return false;
        }

        if(type == 'radio' || type == 'checkbox' || type == 'select' ){
            user_values = [];
            if(type == 'checkbox' || type=="radio"){
                $('.question_'+id+' input:checked').each(function(){
                    user_values.push($(this).val());
                });
            }
            if(type=="select"){
                $('.question_'+id+' option:selected').each(function(){
                    user_values.push($(this).val());
                });
            }
            to_check_values = condition.condition_value;
            to_check_values.forEach(function(val){
                if($.inArray(val,user_values ) !== -1){
                    processAction(id,condition);
                }
            });  
            return false;
        }




    });

}
function getAge(dateString) {
    var today = new Date();
    var birthDate = new Date(dateString);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}

function processAction(id, condition){
    switch(condition.condition_action_type){
        case 'hide_section':
            $('.group_'+condition.condition_action_value).hide();
            $('.group_'+condition.condition_action_value).find('input').removeAttr('required');
            $('.group_'+condition.condition_action_value).find('select').removeAttr('required');
            $('.group_'+condition.condition_action_value).find('textarea').removeAttr('required');
        break;
        case 'hide_question':
            $('.question_'+condition.condition_action_value).hide();
            $('.question_'+condition.condition_action_value).find('input').removeAttr('required');
            $('.question_'+condition.condition_action_value).find('select').removeAttr('required');
            $('.question_'+condition.condition_action_value).find('textarea').removeAttr('required');
        break;
        
        case 'show_question':
            $('.question_'+condition.condition_action_value).show();
        break;
    }
}


</script>
