<div class="modal fade select_2_modal" @if (isset($contact)) id="add_new_contact_{{$contact->id}}" @else id="add_new_contact" @endif
    tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form method="post" @if(isset($contact))
                action="{{route('head_office.contact.add_new_contact',$contact->id)}}" @else
                action="{{route('head_office.contact.add_new_contact')}}" @endif>
                @csrf
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        @if(isset($contact)) Edit @else Create @endif New Contact
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body organisation-structure-add-content">

                    <label class="inputGroup">Title :
                        <input type="text" name="title" value="{{optional($contact)->title}}" class="">
                    </label>
                    <label class="inputGroup">First Name :
                        <input type="text" name="first_name" value="{{optional($contact)->first_name}}">
                    </label>
                    <label class="inputGroup">Middle Name :
                        <input type="text" name="middle_name" value="{{optional($contact)->middle_name}}">
                    </label>
                    <label class="inputGroup">Last Name :
                        <input type="text" name="last_name" value="{{optional($contact)->last_name}}">
                    </label>
                    <label class="inputGroup">Date of birth :
                        <input type="date" name="date_of_birth" @if(isset($contact))
                            value="{{optional($contact)->date_of_birth->format('Y-m-d')}}" @endif >
                    </label>
                    <label class="inputGroup">Address :
                        <input type="text" name="address"
                            value="@if(isset($contact) && $contact->single_address) {{$contact->single_address->address->address}} @endif"
                            class="free-type-address pac-target-input">
                    </label>
                    <label class="inputGroup">Prefession :
                        <input type="text" name="profession"
                            value="@if(isset($contact) && $contact->profession) {{$contact->profession}} @endif"
                            >
                    </label>
                    <label class="inputGroup">Company :
                        <input type="text" name="company"
                            value="@if(isset($contact) && $contact->company) {{$contact->company}} @endif"
                            >
                    </label>
                    <label class="inputGroup">Website :
                        <input type="text" name="website"
                            value="@if(isset($contact) && $contact->website) {{$contact->website}} @endif"
                            >
                    </label>
                    <label class="inputGroup">Registration No. :
                        <input type="text" name="registration_no"
                            value="@if(isset($contact) && $contact->registration_no) {{$contact->registration_no}} @endif"
                            >
                    </label>
                  
                    <label class="inputGroup">Email Address :
                        <select name="email_address[]" multiple @if(isset($contact)) class="select_2_custom_{{$counter}}" @else class="select_2_custom" @endif class="select_2_custom" id="">
                            @if(isset($contact) && $contact->emails)
                            @foreach ($contact->emails as $email)
                            <option value="{{$email}}" selected> {{$email}} </option>
                            @endforeach
                            @endif
                        </select>
                    </label>
                    {{-- <input type="text" name="email_address" value="{{optional($contact)->email_address}}"
                        > --}}

                    <label class="inputGroup">Telephone No. :
                        {{-- <input type="text" name="telephone_no" value="{{optional($contact)->telephone_no}}"
                            > --}}
                        <select name="telephone_no[]" multiple @if(isset($contact)) class="select_2_custom_{{$counter}}" @else class="select_2_custom" @endif id="">
                            @if(isset($contact) && $contact->telephones)
                            @foreach ($contact->telephones as $telephone)
                            <option value="{{$telephone}}" selected> {{$telephone}} </option>
                            @endforeach
                            @endif
                        </select>
                    </label>
                    <label class="inputGroup">Gender :
                        <select name="gender">
                            <option value="Male" @if (isset($contact) && $contact->gender == 'Male') selected
                                @endif>Male</option>
                            <option value="Female" @if (isset($contact) && $contact->gender == 'Female') selected
                                @endif>Female</option>
                        </select>
                    </label>
                    <label class="inputGroup">Notes :
                        {{-- <input type="text" name="telephone_no" value="{{optional($contact)->telephone_no}}"
                            > --}}
                        <textarea spellcheck="true"  name="note" id="">@if (isset($contact)) {{$contact->note}} @endif</textarea>
                    </label>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>