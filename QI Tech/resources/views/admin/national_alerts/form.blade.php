
<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
    <label for="title" class="">Title</label>
    <div class="col-md-10">
        <input class="form-control" name="title" type="text" id="title" value="{{ old('title', optional($nationalAlert)->title) }}" minlength="1" maxlength="255" placeholder="Enter title here...">
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('alert_type') ? 'has-error' : '' }}">
    <label for="alert_type" class="">Alert Type</label>
    <div class="col-md-10">
        <input class="form-control" name="alert_type" type="text" id="alert_type" value="{{ old('alert_type', optional($nationalAlert)->alert_type) }}" minlength="1" placeholder="Enter alert type here...">
        {!! $errors->first('alert_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('summary') ? 'has-error' : '' }}">
    <label for="summary" class="">Summary</label>
    <div class="col-md-10">
        <input class="form-control" name="summary" type="text" id="summary" value="{{ old('summary', optional($nationalAlert)->summary) }}" minlength="1" placeholder="Enter summary here...">
        {!! $errors->first('summary', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('class') ? 'has-error' : '' }}">
    <label for="class" class="">Class</label>
    <div class="col-md-10">
        <select name="class" id="class" class="form-control">
            <option value="1" title="Class 1 requires immediate recall, because the product poses a serious or life threatening risk to health." {{ old('class', optional($nationalAlert)->class)==1 ? 'selected':'' }}>Class 1: Immediate Recall</option>
            <option value="2" title="Class 2 specifies a recall within 48 hours, because the defect could harm the patient but is not life threatening." {{ old('class', optional($nationalAlert)->class)==1 ? 'selected':'' }}>Class 2: Recall within 48 hours</option>
            <option value="3" title="Class 3 requires action to be taken within 5 days because the defect is unlikely to harm patients and is being carried out for reasons other than patient safety." {{ old('class', optional($nationalAlert)->class)==1 ? 'selected':'' }}>Class 3: Action within 5 days</option>
            <option value="4" title="Class 4 alerts advise caution to be exercised when using the product, but indicate that the product poses no threat to patient safety." {{ old('class', optional($nationalAlert)->class)==1 ? 'selected':'' }}>Class 4: Information Only</option>
        </select>
        {!! $errors->first('class', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('patient_level_recall') ? 'has-error' : '' }}">
    <div class="mb-2">
    <label for="patient_level_recall" class="">Patient Level Recall if Yes
        <input class="form-control-sm"  name="patient_level_recall" type="checkbox" id="patient_level_recall" {{ old('patient_level_recall', optional($nationalAlert)->patient_level_recall)==true ?'checked': '' }}>
    </label>
    <div class="col-md-10">
        {!! $errors->first('patient_level_recall', '<p class="help-block">:message</p>') !!}
    </div>
    </div>
</div>

<div class="col-md-10">
<div class="custom-file {{ $errors->has('alert_documents') ? 'has-error' : '' }}">

        <label for="formFileMultiple" class="custom-file-label">Upload File</label>
        <input class="custom-file-input" type="file" id="formFileMultiple" name="alert_documents[]" multiple="multiple" />
        {!! $errors->first('alert_documents', '<p class="help-block">:message</p>') !!}

</div>
</div>

@section('scripts')
    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var files = Array.from(this.files)
            var fileName = files.map(f =>{return f.name}).join(" , ")
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
@endsection