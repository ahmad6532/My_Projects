<div class="card p-2 m-2 contact-card"
    style="width: 18rem; border-radius:20px;box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -1px 0px inset; min-height: 15rem"
    data-group-ids="{{ $contact->contact_to_groups->pluck('group_id')->toJson() }}"
    data-tag-ids="{{ $contact->tag_to_contacts->pluck('tag_id')->toJson() }}"
    data-assigned-to-ids="{{ $contact->user_to_contacts->pluck('head_office_user_id')->toJson() }}"
    data-name="{{ $contact->name }}" data-selected-tab={{ $tab }}>
    <div class="position-relative">
        <input type="checkbox" name="" id="" value="{{ $contact->id }}">

        <div class="position-absolute top-0 end-0">
                @foreach($contact->matchingContactsAsContact1 as $match)
                @if($match->contact_2 !== $contact->id)
                    <div class="text-primary" style="font-size: 12px;font-weight:600" title="Matches with Contact" data-toggle="tooltip" data-bs-placement="left">
                        {{ $match->get_contact_2->name }} - {{ number_format($match->match, 2) }}%
                    </div>
                @endif
            @endforeach

            @foreach($contact->matchingContactsAsContact2 as $match)
                @if($match->contact_1 !== $contact->id)
                    <span class="text-info" style="font-size: 12px;font-weight:600" title="Matches with Contact" data-toggle="tooltip" data-bs-placement="left">
                        {{ $match->get_contact_1->name }} - {{ number_format($match->match, 2) }}%
                    </span>
                @endif
            @endforeach
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <img style="width: 40px;height:40px;border-radius:50%; object-fit:contain; object-position:top"
            src="{{ isset($contact->avatar) && file_exists(public_path('v2/' . $contact->avatar)) ? asset('v2/' . $contact->avatar) : asset('v2/images/user.jpg') }}">
        <div class="">
            <p class="fw-bold m-0 p-0" style="font-size: 20px">{{ $contact->name }}</p>
            @if (!empty($contact->tag_to_contacts))
                <div class="d-flex gap-1">
                    @foreach ($contact->tag_to_contacts as $tag_to_contact)
                        <p class="m-0"
                            style="font-size: 12px; background:rgb(228, 223, 223) ;border-radius: 5px; padding: 2px 4px">
                            {{ $tag_to_contact->contact_tag->tag_name }}</p>
                    @endforeach
                </div>
            @endif
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
            <a class="dropdown-item" href="{{ route('head_office.contacts.view', $contact->id) }}" target="_blank">View</a>
            <a class="dropdown-item" href="{{ route('head_office.contacts.edit', $contact->id) }}">Edit </a>
            <a class="dropdown-item" href="{{ route('head_office.contacts.delete_contact', $contact->id) }}">Delete</a>

        </div>



    </div>
    <div class="card-body">
        <div class="d-flex gap-2 flex-column">
            @if (isset($contact->date_of_birth))
                <div class="d-flex gap-2 align-items-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21 10H3M16 2V6M8 2V6M7.8 22H16.2C17.8802 22 18.7202 22 19.362 21.673C19.9265 21.3854 20.3854 20.9265 20.673 20.362C21 19.7202 21 18.8802 21 17.2V8.8C21 7.11984 21 6.27976 20.673 5.63803C20.3854 5.07354 19.9265 4.6146 19.362 4.32698C18.7202 4 17.8802 4 16.2 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V17.2C3 18.8802 3 19.7202 3.32698 20.362C3.6146 20.9265 4.07354 21.3854 4.63803 21.673C5.27976 22 6.11984 22 7.8 22Z"
                            stroke="#6693ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <p class="fw-bold m-0 p-0" style="color: #c1c5c7">
                        {{ Carbon\Carbon::parse($contact->date_of_birth)->format('Y-m-d') }}</p>
                </div>
            @endif
            @if (isset($contact->contacts_to_addresses) && count($contact->contacts_to_addresses) > 0)
                @foreach ($contact->contacts_to_addresses as $contacts_to_address)
                    @if (isset($contacts_to_address->new_contact_address->address_tag) &&
                            $contacts_to_address->new_contact_address->address_tag == 'current_address')
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
                                {{ $contacts_to_address->new_contact_address->address }}</p>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        @if (isset($contact))
        <div style="transform: scale(0.7);position: relative;right:-30px;">
            @livewire('contact-tags', ['contact_id' => $contact->id]) 
        </div>
        @endif

    </div>
    <div class="card-footer border-0">
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="d-flex gap-1" style="justify-self: flex-end">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M20 21C20 19.6044 20 18.9067 19.8278 18.3389C19.44 17.0605 18.4395 16.06 17.1611 15.6722C16.5933 15.5 15.8956 15.5 14.5 15.5H9.5C8.10444 15.5 7.40665 15.5 6.83886 15.6722C5.56045 16.06 4.56004 17.0605 4.17224 18.3389C4 18.9067 4 19.6044 4 21M16.5 7.5C16.5 9.98528 14.4853 12 12 12C9.51472 12 7.5 9.98528 7.5 7.5C7.5 5.01472 9.51472 3 12 3C14.4853 3 16.5 5.01472 16.5 7.5Z"
                        stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{-- <p class="">Profile</p> --}}
                <a class="m-0 p-0 fw-bold" style="color: black" href="{{ route('head_office.contacts.view', $contact->id) }}" target="_blank">Profile</a>

            </div>

            <a href="{{ route('head_office.contacts.favourite_contact', $contact->id) }}">
                <svg width="24" height="24" viewBox="0 0 24 24"
                    fill="{{ in_array($contact->id, $user_favourite_contacts->pluck('contact_id')->toArray()) ? '#f3bb23' : '#eae5e1' }}"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.2827 3.45332C11.5131 2.98638 11.6284 2.75291 11.7848 2.67831C11.9209 2.61341 12.0791 2.61341 12.2152 2.67831C12.3717 2.75291 12.4869 2.98638 12.7174 3.45332L14.9041 7.88328C14.9721 8.02113 15.0061 8.09006 15.0558 8.14358C15.0999 8.19096 15.1527 8.22935 15.2113 8.25662C15.2776 8.28742 15.3536 8.29854 15.5057 8.32077L20.397 9.03571C20.9121 9.11099 21.1696 9.14863 21.2888 9.27444C21.3925 9.38389 21.4412 9.5343 21.4215 9.68377C21.3988 9.85558 21.2124 10.0372 20.8395 10.4004L17.3014 13.8464C17.1912 13.9538 17.136 14.0076 17.1004 14.0715C17.0689 14.128 17.0487 14.1902 17.0409 14.2545C17.0321 14.3271 17.0451 14.403 17.0711 14.5547L17.906 19.4221C17.994 19.9355 18.038 20.1922 17.9553 20.3445C17.8833 20.477 17.7554 20.57 17.6071 20.5975C17.4366 20.6291 17.2061 20.5078 16.7451 20.2654L12.3724 17.9658C12.2361 17.8942 12.168 17.8584 12.0962 17.8443C12.0327 17.8318 11.9673 17.8318 11.9038 17.8443C11.832 17.8584 11.7639 17.8942 11.6277 17.9658L7.25492 20.2654C6.79392 20.5078 6.56341 20.6291 6.39297 20.5975C6.24468 20.57 6.11672 20.477 6.04474 20.3445C5.962 20.1922 6.00603 19.9355 6.09407 19.4221L6.92889 14.5547C6.95491 14.403 6.96793 14.3271 6.95912 14.2545C6.95132 14.1902 6.93111 14.128 6.89961 14.0715C6.86402 14.0076 6.80888 13.9538 6.69859 13.8464L3.16056 10.4004C2.78766 10.0372 2.60121 9.85558 2.57853 9.68377C2.55879 9.5343 2.60755 9.38389 2.71125 9.27444C2.83044 9.14863 3.08797 9.11099 3.60304 9.03571L8.49431 8.32077C8.64642 8.29854 8.72248 8.28742 8.78872 8.25662C8.84736 8.22935 8.90016 8.19096 8.94419 8.14358C8.99391 8.09006 9.02793 8.02113 9.09597 7.88328L11.2827 3.45332Z"
                        stroke="{{ in_array($contact->id, $user_favourite_contacts->pluck('contact_id')->toArray()) ? '#f3bb23' : '#eae5e1' }}"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

            </a>
        </div>
    </div>
</div>
