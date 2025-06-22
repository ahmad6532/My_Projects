
<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
    <label for="title" class=" control-label">Title</label>
    <div class="col-md-10">
        <input class="form-control" name="title" type="text" id="title" value="{{ old('title', optional($serviceMessage)->title) }}" minlength="1" maxlength="255" placeholder="Enter title here...">
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
    <label for="message" class=" control-label">Message</label>
    <div class="col-md-10">
        <textarea spellcheck="true"  class="form-control" name="message" cols="50" rows="10" id="message" minlength="1" maxlength="1000" required="true" placeholder="Enter message here...">{{ old('message', optional($serviceMessage)->message) }}</textarea>
        {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('send_to') ? 'has-error' : '' }}">
    <label for="send_to" class=" control-label">Send To</label>
    <div class="col-md-10">
{{--        <input class="form-control" name="send_to" type="text" id="send_to" value="{{ old('send_to', optional($serviceMessage)->send_to) }}" minlength="1" required="true" placeholder="Enter send to here...">--}}
        <select name="send_to[]" id="send_to" class="form-control" multiple="multiple">
            @foreach($receivers as $key=>$receiver)
                <option @if (in_array($receiver,optional($serviceMessage)->receiver_list ?? []) ) selected @endif>
                    {{$receiver}}
                </option>
            @endforeach
        </select>
        {!! $errors->first('send_to', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('countries') ? 'has-error' : '' }}">
    <label for="countries" class=" control-label">Countries</label>
    <div class="col-md-10">
{{--        <input class="form-control" name="countries" type="text" id="countries" value="{{ old('countries', optional($serviceMessage)->countries) }}" minlength="1" required="true" placeholder="Enter countries here...">--}}
        <select name="countries[]" id="countries" class="form-control" multiple>
            @foreach($countries as $key=>$country)
                <option @if (in_array($country,optional($serviceMessage)->country_list ?? []) ) selected @endif>
                {{$country}}
                </option>
            @endforeach
        </select>
        {!! $errors->first('countries', '<p class="help-block">:message</p>') !!}
    </div>
</div>


