

<h4>Head Office : {{$case->case_head_office->name()}}</h4>

<h4>Case ID : {{$case->id}}</h4>
<h4>Date Of Incident : {{$case->link_case_with_form->created_at}}</h4>
<h4>Branch : {{$case->link_case_with_form->location->short_name()}}</h4>
<h4>Incident Type : {{$case->link_case_with_form->form->name}}</h4>
<h5>{{ $heading }}</h5>
<p>{{ $msg }}</p>
<br />
<p>
    This is an automated email. Please don't reply.
</p>
<p>
    Copyright &copy; {{ \Carbon\Carbon::now()->year }} {{ env('APP_NAME') }} 
</p>