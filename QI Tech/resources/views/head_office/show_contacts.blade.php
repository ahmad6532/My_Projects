
@foreach($contacts as $contact)
<tr>

    <td>
        {{$contact->first_name}}
    </td>
    <td>
        {{$contact->last_name}}
    </td>
    <td>
        {{$contact->date_of_birth->format(config('app.dateFormat'))}}
    </td>
    <td>
        @if(count($contact->contact_address))
            {{$contact->contact_address->last()->address->address}}
        @endif
    </td>
    <td>
        <a href="{{route('head_office.contact.view',$contact->id)}}" target="_blank">
            <svg width="15" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>  
        </a>
        <a href="#" data-bs-toggle="modal" class="" data-bs-target="#add_new_contact_{{$contact->id}}">
            <svg width="15" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 3.99998H6.8C5.11984 3.99998 4.27976 3.99998 3.63803 4.32696C3.07354 4.61458 2.6146 5.07353 2.32698 5.63801C2 6.27975 2 7.11983 2 8.79998V17.2C2 18.8801 2 19.7202 2.32698 20.362C2.6146 20.9264 3.07354 21.3854 3.63803 21.673C4.27976 22 5.11984 22 6.8 22H15.2C16.8802 22 17.7202 22 18.362 21.673C18.9265 21.3854 19.3854 20.9264 19.673 20.362C20 19.7202 20 18.8801 20 17.2V13M7.99997 16H9.67452C10.1637 16 10.4083 16 10.6385 15.9447C10.8425 15.8957 11.0376 15.8149 11.2166 15.7053C11.4184 15.5816 11.5914 15.4086 11.9373 15.0627L21.5 5.49998C22.3284 4.67156 22.3284 3.32841 21.5 2.49998C20.6716 1.67156 19.3284 1.67155 18.5 2.49998L8.93723 12.0627C8.59133 12.4086 8.41838 12.5816 8.29469 12.7834C8.18504 12.9624 8.10423 13.1574 8.05523 13.3615C7.99997 13.5917 7.99997 13.8363 7.99997 14.3255V16Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>    
        </a>
        <a href="{{route('head_office.contact.add_new_contact_delete',['id'=>$contact->id,'_token'=>csrf_token()])}}" class="delete">
            <svg width="15" style="color: white" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>    
        </a>
                @if( isset($c))
                    
                @include('head_office.edit_contact',['contact' => $contact,'c' => $c])
                @else
                @include('head_office.edit_contact',['contact' => $contact])
                @endif
 
    </td>
    
</tr>
@endforeach


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="{{asset('admin_assets/js/select2.min.js')}}"></script>
<script>
    jQuery(document).ready(function(e){
            $(document).on( "click", ".delete", function(e) {
                e.preventDefault();
                let href= $(this).attr('href');
                alertify.defaults.glossary.title = 'Alert!';
                alertify.confirm("Are you sure?", "Are you sure to delete this Contact",
                function(){
                    window.location.href= href;
                },function(i){
                });
            });
        });
        $('.select_2_custom_{{$counter}}').select2({
            dropdownParent: $(".select_2_modal .modal-content"),
            tags: true,
        });
            function initPlaces() {
            //var autocomplete = new google.maps.places.Autocomplete(document.getElementByClassName(''));
            var input = document.getElementsByClassName('free-type-address');
            for (let i = 0; i < input.length; i++) { var autocomplete=new google.maps.places.Autocomplete(input[i]);
                autocomplete.addListener('place_changed', function () { $(input[i]).trigger('change'); }); } }

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzxvYQxd1yHydcBFIRNOLQjcbQtThH6rI&amp;libraries=places&callback=initPlaces"></script>




