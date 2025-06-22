@foreach($records as $record)
<tr>
    <td> @if(!$record->head_office_linked)<input type="checkbox" name="record_ids[]" onchange="import_incidents()"
            class="import_incidents" multiple="multiple" value="{{$record->id}}" {{$record->head_office_linked ?
        'checked' : ''}} /> @endif</td>
    <td>{{$record->createdDate()}}</td>
    <td>@php echo $record->head_office_linked ? 'Linked' : 'Not Linked' @endphp </td>
    <td>
        <a href="{{route('head_office.location.single_record', $record->id)}}">Preview</a>
    </td>
</tr>
@endforeach