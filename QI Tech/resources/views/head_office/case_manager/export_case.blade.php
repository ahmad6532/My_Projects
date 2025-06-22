@extends('layouts.head_office_app')
@section('title', 'Case Manager')

@section('sidebar')
@include('layouts.company.sidebar')
@endsection

@section('content')
<div id="content" class="content-custom-scroll">
@php
  use Carbon\Carbon;
@endphp
    <h5 class="h3 text-center">Transfer Cases</h5>
    <button class="btn btn-info d-flex " style="margin-left: auto;" data-bs-toggle="modal" data-bs-target="#case-modal">Add Cases</button>
    
    <div>
      @if (count($links) !=0)
        
      
      <table class="mt-4 dataTable-case table table-responsive table-bordered mx-auto rounded dataTable w-100">
        <thead>
          <th>Sr.</th>
          <th>Company</th>
          <th>Case Id's</th>
          <th>created At</th>
          <th>expire At</th>
          <th>Actions</th>
        </thead>
        <tbody class="text-center">
          @foreach ($links as $link)
          <tr>
          <td>{{$link->id}}</td>
          <td>{{$head_office->company_name}}</td>
          <td>{{implode(',', json_decode($link->case_ids,true))}}</td>
          <td>{{$link->created_at->format('d/m/Y h:i A')}}</td>
          <td>{{Carbon::parse($link->link_expiry)->format('d/m/Y h:i A')}}</td>
          <td>
            <div class="wrap">
              <span class="hidden-span" hidden>{{$link->link_token}}</span>
            <!-- Button to copy text -->
            <button  class="copy-button btn" title="{{$link->link_token}}"><i class="fas fa-copy"></i> Copy</button>
            </div>
          </td>
        </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <h5 class="text-center my-3">No Links found! 🙂</h5>
      @endif
    </div>
</div>



  
  <!-- Modal -->
  <div class="modal fade modal-xl" id="case-modal" tabindex="-1" aria-labelledby="case-modal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" style="width: fit-content;">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Select Cases <span class="badge bg-info" id="case-counter">{{isset($case_ids) ? count(explode(',', $case_ids[0])) : '0'}}</span></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table id="dataTable-case" class="dataTable-case table table-responsive table-bordered mx-auto rounded dataTable w-100">
            <thead>
              <tr>
                <th><input type="checkbox" name="" id="dataTable-select-all" ></th>
                <th>Case Id</th>
                <th>Date</th>
                <th>Status</th>
                <th>Type</th>
                <th>Location</th>
                <th>Reporter</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($cases as $case)
                
              <tr>
                <td class="caseid" data-case-id="{{$case->id}}"></td>
                <td class="text-center">{{$case->id}}</td>
                <td>{{$case->created_at->format(config('app.dateFormat'))}}</td>
                <td class="text-center"><span class="badge bg-info">{{$case->status}}</span></td>
                <td class="text-center">{{$case->incident_type}}</td>
                <td class="text-center">{{$case->location_name}}</td>
                <td class="text-center">{{$case->reported_by}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="link_btn">Generate link</button>
        </div>
      </div>
    </div>
  </div>

  <form hidden action="{{route('case_manager.generate_transfer_links')}}" method="POST" id="link_form">
        @csrf
        <input type="text" hidden name="case_ids[]" id="case_ids_input" value="">
  </form>


@endsection
@section('case_manager_tabs')

@endsection


@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')

<script src="{{asset('admin_assets/js/view_case.js')}}"></script>
<script src="{{asset('js/alertify.min.js')}}"></script>

<script>
  $(document).ready(function(){
    $("#case-modal").modal('show')
  

    let table = new DataTable('#dataTable-case',{
            paging: false,
            // info: false,
            language: {
                search: ""
            },
            'columnDefs': [{
                "select": 'multi',
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': '',
                'render': function(data, type, full, meta) {
                    return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(
                        data).html() + '">';
                }
            }],
        });

        $('#dataTable-select-all').on('click', function() {
            var rows = table.rows({
                'search': 'applied'
            }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        let checkedData = [];
        table.on('change', 'input', function() {
          checkedData = [];
          let rowData = table.column(0).nodes().filter(function(value, index) {
              let inputElement = $(value).find('input');
              return inputElement.prop('checked');
          });

          rowData.map(element => {
            checkedData.push($(element).data('case-id'))
          });
          $('#case_ids_input').val(checkedData);

          if(rowData.length > 0){
            $('#case-counter').fadeIn();
            $('#case-counter').text(rowData.length)
          }else{
            $('#case-counter').text('0')
            $('#case-counter').fadeOut();
          }
      });

      let case_ids = @json($case_ids)[0].split(',');
      $('#case_ids_input').val(case_ids);
      const rows = $('.caseid');
      case_ids.forEach(function(id) {
        $(`td[data-case-id="${id}"] input`).attr('checked',true)
      });
      
      $('#link_btn').on('click',function() {
        $('#link_form').submit();
      })


  })
    
    $('.copy-button').click(function(element){
      var text = $(this).parent().find('.hidden-span').text();
        
        // Create a temporary input element
        var input = $('<input>').val(text).appendTo('body').select();
        document.execCommand('copy');
        input.remove();
        alertify.success('Link copied!')
    });

  
</script>

@if(Session::has('success'))
    <script>
        alertify.success("{{ Session::get('success') }}");
    </script>
@elseif(Session::has('error'))
<script>
    alertify.success("{{ Session::get('error') }}");
</script>
@endif
@endsection