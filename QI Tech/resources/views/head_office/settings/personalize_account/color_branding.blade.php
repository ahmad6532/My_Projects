<h3 class="text-info h3 font-weight-bold">Colour Branding</h3>
<form action="{{ route('head_office.update_head_office_branding') }}" target="_blank" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="color_scheme">Color Scheme</label>
        <input type="color" id="color_scheme" class="" value="{{ $headOffice->bg_color_code }}" name="bg_color_code" title="Color Scheme" required>
    </div>
    <div class="form-group">
        <label for="logo">Upload Logo (Leave Blank to keep unchanged)</label>
        <input type="file" id="logo" name="logo_file" class="form-control" title="logo">
    </div>
    <div class="form-group">
        <label for="font">Select Font</label>
        <select name="font" id="font" class="form-control" name="font">
            <option>Arial</option>
            <option>sans-serif</option>
            <option>Times New Roman</option>
            <option>fantasy</option>
            <option>Montserrat</option>

        </select>
    </div>
    {{-- <div class="form-group">
        <label for="background">Upload User Login page background (Leave Blank to remove)</label>
        <input type="file" id="background" name="bg_file" class="form-control" title="background">
    </div> --}}

    <div class="form-group">
        <button class="btn btn-info" type="submit" name="preview_btn" value="preview">Preview</button>
        <button class="btn btn-info" type="submit" name="submit"> Update</button>
    </div>
</form>
