<div class="d-flex flex-column gap-2">
    <div class="card w-50 mt-3 mx-auto">
        <div class="card-body">
                <h6 class="h6">Import Incidents</h6>
                <div class="d-flex align-items-center gap-2 ">
                    <input class="form-control" type="text" name="link" id="form-link">
                    <button class="btn btn-info" id="import-btn">Import</button>
                </div>
        </div>
    </div>
    <div class="card w-75 mt-3 mx-auto" id="case-card" style="display: none;">
        <div class="card-body">
            <table id="dataTable-case"  class="dataTable-case table table-responsive table-bordered mx-auto rounded dataTable">
                <thead>
                    <th></th>
                    <th>Case Id</th>
                    <th>Company</th>
                    <th>location</th>
                    <th>Incident Type</th>
                    <th>status</th>
                </thead>
                <tbody>
                    <tr></tr>
                    <tr></tr>
                    <tr></tr>
                    <tr></tr>
                    <tr></tr>
                </tbody>
            </table>
            <button class="btn btn-info" style="margin-left: auto;" id="add-btn">Add</button>
        </div>
    </div>

    <form action="{{route('case_manager.copy_cases')}}" method="POST" id="add-form">
        @csrf
        <input type="text" hidden name="location" value="{{$location->id}}">
        <input type="text" hidden name="case_ids[]" value="" id="case_ids">
    </form>
</div>

<script>
    $('#import-btn').on('click',function(){
        const data = {
            route: "{{route('case_manager.import_cases')}}",
            location: @json($location->id),
            _token: "{{csrf_token()}}",
            link: $('#form-link').val()
        }

        if($('#form-link').val().trim() == ''){
            alertify.error('Please provide valid token!');
        }else{
            $.ajax({
                url: '{{ route("case_manager.import_cases") }}',
                method: 'POST',
                data: data,
                success: function(response) {
                    
                        const cases = response[0];
                        $('#dataTable-case tbody').empty()
                        $('#case-card').fadeIn()
                        cases.forEach(function(caseData) {
                            var row = `<tr>
                                <td><input class="checks-btn" type="checkbox" data-id='${caseData.id}'/></td>
                                <td>${caseData.id}</td>
                                <td>${response[1]}</td>
                                <td>${caseData.location_name}</td>
                                <td>${caseData.incident_type}</td>
                                <td>${caseData.status}</td>
                            </tr>`;
                            $('#dataTable-case tbody').append(row);
                        });
                    
                },
                error: function(xhr, status, error) {
                    if(xhr.status == 404){
                        alertify.error('Link Expired!');
                    }else{
                        alertify.error('Please provide valid token!');

                    }
                    // Handle error response
                }
            });
        }
    });

    $('#add-btn').on('click',function(){
        case_ids = [];
        let inputs = $('tbody input:checked');
        inputs.each((index,input) => {
            case_ids.push($(input).data('id'))
        })

        $('#case_ids').val(case_ids);
    
    $('#add-form').submit()

    })


</script>