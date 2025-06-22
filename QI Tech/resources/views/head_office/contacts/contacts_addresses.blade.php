<a style="float: right;" type="button" class="btn primary-btn" data-bs-toggle="modal" data-bs-target="#addressModal">
    Add New Address
</a>



@if (!empty($new_contact_addresses))
<div class="d-flex flex-wrap">

    @foreach ($new_contact_addresses as $address)
        {{-- <tr>
                    <td>{{ $address->name }}</td>
                    <td>
                        <button class="badge badge-success badge-user border-0" data-bs-toggle="modal"
                            data-bs-target="#addressModal{{ $address->id }}">Edit</button>
                        <button class="badge bg-danger badge-sm remove-share border-0" data-bs-toggle="modal"
                            data-bs-target="#deleteAddressModal{{ $address->id }}">Remove</button>
                    </td>
                </tr> --}}










        <div class="card p-2 m-2 address-card"
            style="width: 18rem; border-radius:20px;box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -1px 0px inset; min-height: 15rem">
            <div class="d-flex justify-content-between align-items-center">
                <img style="width: 40px;height:40px;border-radius:50%; object-fit:cover; object-position:top"
                    src="{{ isset($address->avatar) && file_exists(public_path('v2/' . $address->avatar)) ? asset('v2/' . $address->avatar) : asset('images/svg/logo_blue.png') }}">
                <div class="">
                    <p class="fw-bold m-0 p-0" style="font-size: 20px">{{ $address->name }}</p>

                    <div class="d-flex gap-1">

                        <p class="m-0"
                            style="font-size: 12px; background:rgb(228, 223, 223) ;border-radius: 5px; padding: 2px 4px">
                            @if (isset($address->address_tag))
                                @if ($address->address_tag == 'current_address')
                                    Current address
                                @elseif ($address->address_tag == 'past_address')
                                    Past address
                                @elseif ($address->address_tag == 'work_address')
                                    Work address
                                @elseif ($address->address_tag == 'home_address')
                                    Home address
                                @endif
                            @endif


                        </p>

                    </div>

                </div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="dropdown-toggle" type="button" id="contactInfoDropDown" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <path
                        d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z"
                        stroke="#a9afb3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M19 13C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11C18.4477 11 18 11.4477 18 12C18 12.5523 18.4477 13 19 13Z"
                        stroke="#a9afb3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M5 13C5.55228 13 6 12.5523 6 12C6 11.4477 5.55228 11 5 11C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13Z"
                        stroke="#a9afb3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

                <div class="dropdown-menu animated--fade-in" aria-labelledby="contactInfoDropDown">
                    <a class="dropdown-item" href="{{ route('head_office.address.edit', $address->id) }}">Edit </a>
                    <a class="dropdown-item" data-bs-toggle="modal"
                        data-bs-target="#deleteAddressModal{{ $address->id }}">Delete</a>

                </div>



            </div>
            <div class="card-body">
                <div class="d-flex gap-2 flex-column">
                    @if (isset($address->address))
                        <div class="d-flex gap-2 align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z"
                                    stroke="#6693ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M12 22C16 18 20 14.4183 20 10C20 5.58172 16.4183 2 12 2C7.58172 2 4 5.58172 4 10C4 14.4183 8 18 12 22Z"
                                    stroke="#6693ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="fw-bold m-0 p-0" style="color: #c1c5c7">
                                {{ $address->address }}</p>
                        </div>
                    @endif
                </div>


            </div>

        </div>


        <div class="modal fade" id="deleteAddressModal{{ $address->id }}" tabindex="-1" role="dialog"
            aria-labelledby="deleteAddressModalLabel{{ $address->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" action="{{ route('head_office.contacts.delete_address', $address->id) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addressModalLabel">Delete address</h5>
                            <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h5>Are you sure you want to delete this address? All the linked
                                contacts will be
                                affected.</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <button class="btn btn-primary">Yes</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade" id="addressModal{{ $address->id }}" tabindex="-1" role="dialog"
            aria-labelledby="addressModalLabel{{ $address->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('head_office.contacts.create_address', $address->id) }}" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addressModalLabel">Edit Address</h5>
                            <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="d-flex align-items-center gap-2" style="width:200px;">
                                <label for="file" class="user-icon-circle">
                                    <img style="width: 50px;height:50px;border-radius:50%; border:0.5px solid gray; object-fit:cover; object-position:top"
                                        id="output"
                                        src="{{ isset($address->avatar) && file_exists(public_path('v2/' . $address->avatar)) ? asset('v2/' . $address->avatar) : asset('images/svg/logo_blue.png') }}">
                                </label>
                                <input type="hidden" name="image" id="base64_image" />
                                <input type="hidden" name="avatar" value="{{ $address->avatar }}">
                                <input id="file" type="file" class="d-none" accept=".png"
                                    onchange="loadFile(event)" />
                                <input type="name" id="name" name="name" placeholder="Name"
                                    class="form-control shadow-none" style="height:30px" required
                                    value="{{ $address->name }}">
                            </div>
                            <div class="mt-4">
                                <label style="margin: 0;font-size: 12px;" for="email">Address</label>
                                <input type="text" name="address" placeholder="Address" class="form-control"
                                    style="height:50px" required value="{{ $address->address }}">

                            </div>

                            <select class="form-select mt-4" aria-label="Tag the Address" name="address_tag">
                                <option value="current_address" @if ($address->address_tag == 'current_address') selected @endif>
                                    Current Address</option>
                                <option value="past_address" @if ($address->address_tag == 'past_address') selected @endif>
                                    Past Address</option>
                                <option value="work_address" @if ($address->address_tag == 'work_address') selected @endif>
                                    Work Address</option>
                                <option value="home_address" @if ($address->address_tag == 'home_address') selected @endif>
                                    Home Address</option>
                            </select>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif



<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('head_office.contacts.create_address') }}" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressModalLabel">Add New address</h5>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="d-flex align-items-center gap-2" style="width:200px;">
                        <label for="file" class="user-icon-circle">
                            <img style="width: 50px;height:50px;border-radius:50%; border:0.5px solid gray; object-fit:cover; object-position:top"
                                id="output">
                        </label>
                        <input type="hidden" name="image" id="base64_image" />
                        <input id="file" type="file" class="d-none" accept=".png"
                            onchange="loadFile(event)" />
                        <input type="name" id="name" name="name" placeholder="Name"
                            class="form-control shadow-none" style="height:30px" required>
                    </div>
                    <div class="mt-4">
                        <label style="margin: 0;font-size: 12px;" for="email">Address</label>
                        <input type="text" name="address" placeholder="Address" class="form-control"
                            style="height:50px" required>

                    </div>

                    <select class="form-select mt-4" aria-label="Tag the Address" name="address_tag">
                        <option value="current_address" selected>Current Address</option>
                        <option value="past_address">Past Address</option>
                        <option value="work_address">Work Address</option>
                        <option value="home_address">Home Address</option>
                    </select>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let loadFile = function(event) {
        let image = document.getElementById("output");
        image.src = URL.createObjectURL(event.target.files[0]);
        let file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = (evt) => {
            let result = evt.target.result;
            document.getElementById('base64_image').value = result;
        };
        reader.readAsDataURL(file);
    };
</script>
