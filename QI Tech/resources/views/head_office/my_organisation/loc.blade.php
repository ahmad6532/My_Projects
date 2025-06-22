@foreach ($locations as $loc)
    <tr class="loc"
        @if ($loc->location->is_deleted == true) data-deleted="true"
        @elseif ($loc->location->is_archived == true)
            data-archived='true'
        @else
            data-live='true' 
        @endif

        data-location_type="{{isset($loc->location->location_type) ? $loc->location->location_type->name : ''}}"
        data-location_sub_type="{{isset($loc->location->pharmacy_type) ? $loc->location->pharmacy_type->name : ''}}"
        data-country="{{$loc->location->country}}"
        data-tags="{{$loc->location->location_tag->pluck('name')->implode(', ')}}"
        data-groups="{{count($loc->groups) == 0 ? 'Unassigned' : $loc->groups->pluck('group.group')->implode(', ')}}"
        data-status = "@if ($loc->location->is_deleted == true) Deleted
        @elseif ($loc->location->is_archived == true)
            Archived
        @elseif ($loc->location->is_active == false) Inactive @else Live @endif"
        >
        <td></td>
        <td >
            <p class="d-none">{{$loc->location->registration_no}}</p>
            <div style="width: 100% !important;" class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunction(this)'>
                <input style="background: transparent;" onblur="blurFunction(this)" data-column='username-{{ $loc->location_id }}' type="text"
                    value="{{ $loc->location->username }}" readonly>
                <span style="display:none;"></span>

                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunction('username-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text" onclick="copyFunction('username-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div>
            </div>
            @if (isset($loc->location->location_type))
                <div class="border border-2 p-1 mt-2 rounded" style="width: fit-content;font-size:10px;">
                    {{$loc->location->location_type->name}}
                </div>
            @endif
        </td>

        <td>
            <div style="font-size: 14px;" class="d-flex flex-column gap-1">
                

                @if ($loc->location->is_active)
                <span class="text-success d-flex align-items-center gap-1">
                    <span style="width:4px;height:4px;" class="bg-success rounded-circle d-flex"></span>
                    live
                </span>
                @elseif($loc->location->is_archived)
                    <span class="text-warning d-flex align-items-center gap-1">
                        <span style="width:4px;height:4px;" class="bg-warning rounded-circle d-flex"></span>
                        Archived
                    </span>
                @elseif($loc->location->is_deleted)
                <span class="text-danger d-flex align-items-center gap-1">
                    <span style="width:4px;height:4px;" class="bg-danger rounded-circle d-flex"></span>
                    Deleted
                </span>
                    
                @endif

                @if ($loc->groups->count() == 0)
                    <span class="text-danger d-flex align-items-center gap-1">
                        <span style="width:4px;height:4px;" class="bg-danger rounded-circle d-flex"></span>
                        Unassigned
                    </span>
                @endif

                @if ($loc->location->is_active == false)
                    <span class="text-danger">Inactive</span>
                @endif

            </div>
        </td>

        <td>
            <div class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunction(this)'>
                <input onblur="blurFunction(this)" data-column="location_code-{{ $loc->location_id }}" type="text"
                    value="{{ $loc->location->location_code }}" readonly>
                <span style="display:none;"></span>
                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunction('location_code-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text"
                        onclick="copyFunction('location_code-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div>

            </div>
            <div class="main-wrapper-loc  resizing-input my-1" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunction(this)'>
                <input onblur="blurFunction(this)" data-column="registered_company_name-{{ $loc->location_id }}"
                    type="text" value="{{ $loc->location->registered_company_name }}" readonly>
                <span style="display:none;"></span>
                <div class="btn-wrapper-loc">
                    <button title="click to edit"
                        onclick="editFunction('registered_company_name-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text"
                        onclick="copyFunction('registered_company_name-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div>

            </div>
            @php
                $address = $loc->location->address_line1;
                if (!empty($loc->location->address_line2)) {
                    $address .= ', ' . $loc->location->address_line2;
                }
                if (!empty($loc->location->address_line3)) {
                    $address .= ', ' . $loc->location->address_line3;
                }
            @endphp
            
            <div style="display:flex;" class="main-wrapper-loc resizing-input" onmouseenter="hoverFunction(this)"
                onmouseleave="leaveHoverFunction(this)">
                <input style="font-weight: bold;" onblur="blurFunction(this)"
                    data-column="address_line1-{{ $loc->location_id }}" type="text"
                    value="{{ $address }}" readonly>
                <span style="display:none;"></span>
                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunction('address_line1-{{ $loc->location_id }}')">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                    <button title="click to copy text" onclick="copyFunction('address_line1-{{ $loc->location_id }}')">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>
            </div>


            </div>


            {{-- <div style="display:flex;" class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunction(this)'>
                <input style="font-weight: bold;" onblur="blurFunction(this)"
                    data-column="address_line1-{{ $loc->location_id }}" type="text"
                    value="{{ $loc->location->address_line2 }}" readonly>
                <span style="display:none;"></span>
                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunction('address_line1-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text"
                        onclick="copyFunction('address_line1-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div>

            </div> --}}

            {{-- <div style="display:flex;" class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunction(this)'>
                <input style="font-weight: bold;" onblur="blurFunction(this)"
                    data-column="address_line1-{{ $loc->location_id }}" type="text"
                    value="{{ $loc->location->address_line3 }}" readonly>
                <span style="display:none;"></span>
                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunction('address_line1-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text"
                        onclick="copyFunction('address_line1-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div>

            </div> --}}

            {{-- <div style="display:flex;" class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunction(this)'>
                <input style="font-weight: bold;" onblur="blurFunction(this)"
                    data-column="address_line1-{{ $loc->location_id }}" type="text"
                    value="{{ $loc->location->OrganizationCode }}" readonly>
                <span style="display:none;"></span>
                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunction('address_line1-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text"
                        onclick="copyFunction('address_line1-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div> --}}

            {{-- </div>

            <div style="display:flex;" class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunction(this)'>
                <input style="font-weight: bold;" onblur="blurFunction(this)"
                    data-column="address_line1-{{ $loc->location_id }}" type="text"
                    value="{{ $loc->location->country }}" readonly>
                <span style="display:none;"></span>
                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunction('address_line1-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text"
                        onclick="copyFunction('address_line1-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div>

            </div> --}}

            {{-- <div style="display:flex;" class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunction(this)'>
                <input style="font-weight: bold;" onblur="blurFunction(this)"
                    data-column="address_line1-{{ $loc->location_id }}" type="text"
                    value="{{ $loc->location->county }}" readonly>
                <span style="display:none;"></span>
                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunction('address_line1-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text"
                        onclick="copyFunction('address_line1-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div>

            </div> --}}

            <div style="display:flex;" class="main-wrapper-loc my-1  resizing-input" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunction(this)'>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 7L10.1649 12.7154C10.8261 13.1783 11.1567 13.4097 11.5163 13.4993C11.8339 13.5785 12.1661 13.5785 12.4837 13.4993C12.8433 13.4097 13.1739 13.1783 13.8351 12.7154L22 7M6.8 20H17.2C18.8802 20 19.7202 20 20.362 19.673C20.9265 19.3854 21.3854 18.9265 21.673 18.362C22 17.7202 22 16.8802 22 15.2V8.8C22 7.11984 22 6.27976 21.673 5.63803C21.3854 5.07354 20.9265 4.6146 20.362 4.32698C19.7202 4 18.8802 4 17.2 4H6.8C5.11984 4 4.27976 4 3.63803 4.32698C3.07354 4.6146 2.6146 5.07354 2.32698 5.63803C2 6.27976 2 7.11984 2 8.8V15.2C2 16.8802 2 17.7202 2.32698 18.362C2.6146 18.9265 3.07354 19.3854 3.63803 19.673C4.27976 20 5.11984 20 6.8 20Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg> 
                <input style="font-weight: bold;" onblur="blurFunction(this)"
                data-column="email-{{ $loc->location_id }}" type="text"
                    value="{{ $loc->location->email }}" readonly>
                <span style="display:none;"></span>
                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunction('address_line1-{{ $loc->location_id }}')"><i
                        class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text"
                        onclick="copyFunction('email-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div>

            </div>
            <div style="display:flex;" class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunction(this)'>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.38028 8.85335C9.07627 10.303 10.0251 11.6616 11.2266 12.8632C12.4282 14.0648 13.7869 15.0136 15.2365 15.7096C15.3612 15.7694 15.4235 15.7994 15.5024 15.8224C15.7828 15.9041 16.127 15.8454 16.3644 15.6754C16.4313 15.6275 16.4884 15.5704 16.6027 15.4561C16.9523 15.1064 17.1271 14.9316 17.3029 14.8174C17.9658 14.3864 18.8204 14.3864 19.4833 14.8174C19.6591 14.9316 19.8339 15.1064 20.1835 15.4561L20.3783 15.6509C20.9098 16.1824 21.1755 16.4481 21.3198 16.7335C21.6069 17.301 21.6069 17.9713 21.3198 18.5389C21.1755 18.8242 20.9098 19.09 20.3783 19.6214L20.2207 19.779C19.6911 20.3087 19.4263 20.5735 19.0662 20.7757C18.6667 21.0001 18.0462 21.1615 17.588 21.1601C17.1751 21.1589 16.8928 21.0788 16.3284 20.9186C13.295 20.0576 10.4326 18.4332 8.04466 16.0452C5.65668 13.6572 4.03221 10.7948 3.17124 7.76144C3.01103 7.19699 2.93092 6.91477 2.9297 6.50182C2.92833 6.0436 3.08969 5.42311 3.31411 5.0236C3.51636 4.66357 3.78117 4.39876 4.3108 3.86913L4.46843 3.7115C4.99987 3.18006 5.2656 2.91433 5.55098 2.76999C6.11854 2.48292 6.7888 2.48292 7.35636 2.76999C7.64174 2.91433 7.90747 3.18006 8.43891 3.7115L8.63378 3.90637C8.98338 4.25597 9.15819 4.43078 9.27247 4.60655C9.70347 5.26945 9.70347 6.12403 9.27247 6.78692C9.15819 6.96269 8.98338 7.1375 8.63378 7.4871C8.51947 7.60142 8.46231 7.65857 8.41447 7.72538C8.24446 7.96281 8.18576 8.30707 8.26748 8.58743C8.29048 8.66632 8.32041 8.72866 8.38028 8.85335Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <input style="font-weight: bold;" onblur="blurFunction(this)"
                data-column="telephone_no-{{ $loc->location_id }}" type="text"
                    value="{{ $loc->location->telephone_no }}" readonly>
                <span style="display:none;"></span>
                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunction('address_line1-{{ $loc->location_id }}')"><i
                        class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text"
                        onclick="copyFunction('telephone_no-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div>

            </div>
            @if ($loc->location->ods_name)
                <div style="display:flex;" class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                    onmouseleave='leaveHoverFunction(this)'>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 17H16M11.0177 2.764L4.23539 8.03912C3.78202 8.39175 3.55534 8.56806 3.39203 8.78886C3.24737 8.98444 3.1396 9.20478 3.07403 9.43905C3 9.70352 3 9.9907 3 10.5651V17.8C3 18.9201 3 19.4801 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4801 21 18.9201 21 17.8V10.5651C21 9.9907 21 9.70352 20.926 9.43905C20.8604 9.20478 20.7526 8.98444 20.608 8.78886C20.4447 8.56806 20.218 8.39175 19.7646 8.03913L12.9823 2.764C12.631 2.49075 12.4553 2.35412 12.2613 2.3016C12.0902 2.25526 11.9098 2.25526 11.7387 2.3016C11.5447 2.35412 11.369 2.49075 11.0177 2.764Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>                    
                    <input style="font-weight: bold;" onblur="blurFunction(this)"
                    data-column="ods_name-{{ $loc->location_id }}" type="text"
                        value="{{ $loc->location->ods_name }}" readonly>
                    <span style="display:none;"></span>
                    <div class="btn-wrapper-loc">
                        <button title="click to edit" onclick="editFunction('ods_name-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-pen-to-square"></i></button>
                        <button title="click to copy text"
                            onclick="copyFunction('ods_name-{{ $loc->location_id }}')"><i
                                class="fa-regular fa-copy"></i></button>
                    </div>

                </div>
            @endif
            {{-- <div class="main-wrapper-loc align-items-start" style="flex-direction: column" onmouseenter="hoverFunction(this)"
                onmouseleave='leaveHoverFunctionBoth(this)'>
                <div class="btn-wrapper-loc">
                    <button title="click to edit" onclick="editFunctionBoth('email-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-pen-to-square"></i></button>
                    <button title="click to copy text" onclick="copyFunctionBoth('email-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                </div>
                <div class="main-wrapper-loc d-flex align-items-center gap-1">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 7L10.1649 12.7154C10.8261 13.1783 11.1567 13.4097 11.5163 13.4993C11.8339 13.5785 12.1661 13.5785 12.4837 13.4993C12.8433 13.4097 13.1739 13.1783 13.8351 12.7154L22 7M6.8 20H17.2C18.8802 20 19.7202 20 20.362 19.673C20.9265 19.3854 21.3854 18.9265 21.673 18.362C22 17.7202 22 16.8802 22 15.2V8.8C22 7.11984 22 6.27976 21.673 5.63803C21.3854 5.07354 20.9265 4.6146 20.362 4.32698C19.7202 4 18.8802 4 17.2 4H6.8C5.11984 4 4.27976 4 3.63803 4.32698C3.07354 4.6146 2.6146 5.07354 2.32698 5.63803C2 6.27976 2 7.11984 2 8.8V15.2C2 16.8802 2 17.7202 2.32698 18.362C2.6146 18.9265 3.07354 19.3854 3.63803 19.673C4.27976 20 5.11984 20 6.8 20Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>                        
                    <input onblur="specBlur(this)" data-column="email-{{ $loc->location_id }}" type="text"
                        value="{{ $loc->location->email }}" readonly>
                        <div class="btn-wrapper-loc">
                            <button title="click to copy text"
                                onclick="copyFunction('email-{{ $loc->location_id }}')"><i
                                    class="fa-regular fa-copy"></i></button>
                        </div>
                </div>

                <div class="d-flex align-items-center gap-1 mt-1">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.38028 8.85335C9.07627 10.303 10.0251 11.6616 11.2266 12.8632C12.4282 14.0648 13.7869 15.0136 15.2365 15.7096C15.3612 15.7694 15.4235 15.7994 15.5024 15.8224C15.7828 15.9041 16.127 15.8454 16.3644 15.6754C16.4313 15.6275 16.4884 15.5704 16.6027 15.4561C16.9523 15.1064 17.1271 14.9316 17.3029 14.8174C17.9658 14.3864 18.8204 14.3864 19.4833 14.8174C19.6591 14.9316 19.8339 15.1064 20.1835 15.4561L20.3783 15.6509C20.9098 16.1824 21.1755 16.4481 21.3198 16.7335C21.6069 17.301 21.6069 17.9713 21.3198 18.5389C21.1755 18.8242 20.9098 19.09 20.3783 19.6214L20.2207 19.779C19.6911 20.3087 19.4263 20.5735 19.0662 20.7757C18.6667 21.0001 18.0462 21.1615 17.588 21.1601C17.1751 21.1589 16.8928 21.0788 16.3284 20.9186C13.295 20.0576 10.4326 18.4332 8.04466 16.0452C5.65668 13.6572 4.03221 10.7948 3.17124 7.76144C3.01103 7.19699 2.93092 6.91477 2.9297 6.50182C2.92833 6.0436 3.08969 5.42311 3.31411 5.0236C3.51636 4.66357 3.78117 4.39876 4.3108 3.86913L4.46843 3.7115C4.99987 3.18006 5.2656 2.91433 5.55098 2.76999C6.11854 2.48292 6.7888 2.48292 7.35636 2.76999C7.64174 2.91433 7.90747 3.18006 8.43891 3.7115L8.63378 3.90637C8.98338 4.25597 9.15819 4.43078 9.27247 4.60655C9.70347 5.26945 9.70347 6.12403 9.27247 6.78692C9.15819 6.96269 8.98338 7.1375 8.63378 7.4871C8.51947 7.60142 8.46231 7.65857 8.41447 7.72538C8.24446 7.96281 8.18576 8.30707 8.26748 8.58743C8.29048 8.66632 8.32041 8.72866 8.38028 8.85335Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        
                    <!-- <span style="display:none;"></span> -->
                    <input onblur="specBlur(this)" data-column="telephone_no-{{ $loc->location_id }}" type="text"
                        value="{{ $loc->location->telephone_no }}" readonly>
                        <button title="click to copy text"
                        onclick="copyFunction('telephone_no-{{ $loc->location_id }}')"><i
                            class="fa-regular fa-copy"></i></button>
                    <!-- <span style="display:none;"></span> -->
                </div>
            </div> --}}

        </td>

        <td>
            <div class="d-flex align-items-center gap-1 loc-groups-wrapper">

                @if (count($loc->groups))
                    <div @if (count($loc->groups) > 1) style="display:flex;align-items:center;flex-wrap:wrap;width:144px;" @endif
                        class="">
                        @foreach ($loc->groups as $assignment)
                            @php
                                $origin = [];
                                $currentGroup = $assignment->group;
                                while ($currentGroup->parent) {
                                    $origin[] = $currentGroup->parent->group;
                                    $currentGroup = $currentGroup->parent;
                                }
            
                                $origin = array_reverse($origin);
                                $origin[] = $assignment->group->group;
                                $originMessage = $origin ?  implode(' → ', $origin) : 'No origin';
                            @endphp
                            <p class="btn group-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $originMessage }}">{{ $assignment->group->group }}
                                <a title="Remove Group"
                                    href="{{ route('head_office.organisation.delete_group', ['id'=>$assignment->id,'_token'=>csrf_token()]) }}"
                                    data-msg="Are you sure you want to remove this assignment?"
                                    class="text-danger delete_button float-right"><i class="fa fa-xmark"></i></a>
                            </p>
                        @endforeach
                    </div>
                @endif
                <p style="margin-top: 10px;">
                    <button data-bs-toggle="modal" data-bs-target='#group_assing_modal'
                        class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center mx-auto">
                        <i class="fa fa-plus"></i>
                    </button>
                </p>
            
                <div class="d-none">
                    <div class="inside-content">
                        <div class="card-body">
                            <p><strong>Location: </strong> {{ $loc->location->name() }}</p>
                            <form method="post"
                            action="{{ route('head_office.organisation.assign_groups_save', $loc->id) }}">
                                @csrf
                                <input type="hidden" name="location_id" value="{{ $loc->id }}">
                                <p>Please select a group/tier</p>
                                @include('head_office.my_organisation.tree-list', ['groups' => $allGroups])
                                <input type="submit" name="save" value="Save" class="btn btn-info">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </td>
        <td>
            @livewire('location-tags-manager', ['loc_id' => $loc->location->id])
        </td>

        <td>
            <div>
                <div>
                    <p class="mb-1 " style="font-size: 12px;font-weight: 500;">Access</p>
                    <select class="locationAccessDropdown form-select custom-input"
                        style="font-size: 12px; white-space: nowrap;width:100px;">
                        <option
                            value="{{ route('location.access.update', ['id' => $loc->location->id, 'access' => 'anyone','_token' => csrf_token()]) }}"
                            {{ !$loc->location->is_access ? 'selected' : '' }}>
                            Anyone
                        </option>
                        <option
                            value="{{ route('location.access.update', ['id' => $loc->location->id, 'access' => 'selected_users','_token' => csrf_token()]) }}"
                            {{ $loc->location->is_access ? 'selected' : '' }}>
                            Selected Users
                        </option>
                    </select>
                </div>

                <div>
                    <p class="mb-1 mt-2 " style="font-size: 12px;font-weight: 500;">Two factor Authentication</p>
                    <div class="d-flex align-items-center gap-1">
                        <select id="otpStatusDropdown" 
                        class="otpSecurityDropdown form-select custom-input form-select-sm" 
                        onchange="location = this.value" 
                        style="width:110px;font-size: 12px;">
                    <option value="{{ route('otp.loc.security', ['id' => $loc->location_id, 'action' => 'enable', '_token' => csrf_token()]) }}"
                        {{ $loc->location->otp && $loc->location->otp->isEnabled ? 'selected' : '' }}>
                        Enable
                    </option>
                    <option value="{{ route('otp.loc.security', ['id' => $loc->location_id, 'action' => 'disable', '_token' => csrf_token()]) }}"
                        {{ $loc->location->otp && $loc->location->otp->isEnabled ? '' : 'selected' }}>
                        Disable
                    </option>
                </select>
                
                @if ($loc->location->otp && $loc->location->otp->isEnabled)
                    <select id="otpEmailDropdown" 
                            class="otpSecurityDropdown form-select custom-input form-select-sm" 
                            onchange="location = this.value" 
                            style="width:100px;font-size: 12px;">
                        <option value="{{ route('otp.loc.email', ['id' => $loc->location_id, 'action' => 'enable', '_token' => csrf_token()]) }}"
                            {{ $loc->location && $loc->location->is_email == false ? 'selected' : '' }}>
                            Admin Only
                        </option>
                        <option value="{{ route('otp.loc.email', ['id' => $loc->location_id, 'action' => 'disable', '_token' => csrf_token()]) }}"
                            {{ $loc->location && $loc->location->is_email == true ? 'selected' : '' }}>
                            To Location Email Address
                        </option>
                    </select>
                @endif
                @if ($loc->location->otp && $loc->location->otp->isEnabled)
                <select id="otpEmailDropdown" 
                class="otpSecurityDropdown form-select custom-input form-select-sm" 
                onchange="location = this.value" 
                style="width:87px;font-size: 12px;">
            <option value="#" disabled>
                Via Microsoft Authenticator (comming soon)
            </option>
            <option value="{{ route('otp.loc.email', ['id' => $loc->location_id, 'action' => 'enable', '_token' => csrf_token()]) }}" 
                {{ $loc->location && $loc->location->is_email == false ? 'selected' : '' }}>
                Via Email
            </option>
     </select>
                @endif
                
                    

                    </div>
                    @if ($loc->location->otp && $loc->location->otp->isEnabled)
                        <div class="input-wrapper" >
                            <p class="verification-code-btn" style="margin:0; margin-top:3px;cursor: pointer">Verification Codes</p>
                            @isset($loc->location->otp)
                            <div class="d-none">
                                <table class="new-table" style="width:100%;">
                                    <thead>
                                        <th>Date & time</th>
                                        <th>Verification Code</th>
                                        <th>Details</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                        @if ($loc->location->getOtpLogs->count() == 0)
                                            <tr>
                                                <td colspan="4" class="text-center">No data found</td>
                                            </tr>
                                        @else
                                            @foreach ($loc->location->getOtpLogs->sortByDesc('created_at') as $log )
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y h:i A') }}</td>
                                                    <td>{{ $log->otp }}</td>
                                                    <td>
                                                        <ul>
                                                            <li>{{$log->ip}}</li>
                                                            <li>{{$log->os}}</li>
                                                            <li>{{$log->device}}</li>
                                                        </ul>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($log->expires_at)->isPast() ? 'Expired' : 'Successful' }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            @endisset
                        </div>
                    @endif
                </div>

                <div>
                    <p class="mb-1 mt-2" style="font-size: 12px;font-weight: 500;">Password Reset</p>
                    <select class="passwordResetDropdown form-select custom-input"
                        style="font-size: 12px; white-space: nowrap;width:100px;">
                        <option
                            value="{{ route('location.password_admin.update', ['id' => $loc->id, 'access' => 'anyone','_token' => csrf_token()]) }}"
                            {{ !$loc->location->is_admin_password ? 'selected' : '' }}>
                            Admin Only
                        </option>
                        <option
                            value="{{ route('location.password_admin.update', ['id' => $loc->id, 'access' => 'selected_users','_token' => csrf_token()]) }}"
                            {{ $loc->location->is_admin_password ? 'selected' : '' }}>
                            From Location
                        </option>
                    </select>
                </div>
            </div>
        </td>
        <td >
            @if (count($headOffice->organisationSettings) != 0)
                <select style="white-space: nowrap;font-size:12px;" name="organisation_setting"
                    id="organisation_setting" class="organisation_setting_select form-select custom-input">
                    <option value="" {{ !isset($loc->org_settings()->id) ? 'selected' : '' }}>Not Assigned
                    </option>
                    @foreach ($headOffice->organisationSettings as $setting)
                        <option
                            value="{{ route('head_office.organisation_settings_update', ['setting_id' => $setting->id, 'location_id' => $loc->id]) }}"
                            onclick="event.stopPropagation();"
                            {{ isset($loc->org_settings()->id) && $loc->org_settings()->id == $setting->id ? 'selected' : '' }}>
                            {{ $setting->name }}
                        </option>
                    @endforeach
                </select>
            @endif
        </td>
        <td>
            <div class="d-flex flex-column gap-2 mb-2">
                <a target="_blank" class="border border-2 text-secondary rounded p-2" style="font-size:12px;"
                    href="{{ route('head_office.color_branding_get', ['id' => $loc->location_id,'_token'=>csrf_token()]) }}">
                    Access Remotely 
                </a>
                <a target="_blank" class="border border-2 text-secondary rounded p-2" style="font-size:12px;"
                    href="{{ route('head_office.location_page_view', ['id' => $loc->id,'_token'=>csrf_token()]) }}">
                    Edit & View
                </a>
            </div>

            <div class="dropdown">
                <button class="no-arrow btn shadow-none border-0  dropdown-toggle" type="button"
                    id="dropdownMenuButton{{ $loc->id }}" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton{{ $loc->id }}">
                    <a href="{{ route('head_office.location.import_location_incidents_preview', $loc->location->id) }}"
                        class="dropdown-item" title="Assign Groups">Import Incidents</a>
                     
                     {{-- <a target="_blank" class="dropdown-item" style="color: #34BFAF;"
                        href="{{ route('head_office.color_branding_get', ['id' => $loc->location_id, '_token' => csrf_token()]) }}">
                        Remotely Access
                     </a> --}}
                     
                     @if ($head_office_user->get_permissions() !== null )
                         @if ($head_office_user->get_permissions()->super_access)
                             <a href="#" data-show_link="{{ $loc->location->is_admin_password }}" 
                                data-id="{{ $loc->id }}" 
                                data-link='{{ route('head_office.location.password_reset_link', ['id' => $loc->location_id, '_token' => csrf_token()]) }}'
                                class="dropdown-item password-reset-btn" data-bs-toggle='modal' data-bs-target='#password_modal'
                                data-bs-placement='left' title="Send password reset email to location account">Password Reset</a>
                         @elseif (in_array($loc->id, isset($head_office_user->assigned_locations) ? json_decode($head_office_user->assigned_locations, true) : []))
                             <a href="#" data-show_link="{{ $loc->location->is_admin_password }}" 
                                data-id="{{ $loc->id }}" 
                                data-link='{{ route('head_office.location.password_reset_link', ['id' => $loc->location_id, '_token' => csrf_token()]) }}'
                                class="dropdown-item password-reset-btn" data-bs-toggle='modal' data-bs-target='#password_modal'
                                data-bs-placement='left' title="Send password reset email to location account">Password Reset</a>
                         @endif
                     @endif
                     
                     @if ($loc->location->is_active)
                         <a href="javascript:void(0);" class="dropdown-item text-danger" 
                            onclick="toggleStatus('{{ route('head_office.toggleLocationStatus', $loc->location->id) }}')">
                            Deactivate
                         </a>
                     @else
                         <a href="javascript:void(0);" class="dropdown-item text-success" 
                            onclick="toggleStatus('{{ route('head_office.toggleLocationStatus', $loc->location->id) }}')">
                            Make Live
                         </a>
                     @endif
                     
                </div>
            </div>
        </td>
    </tr>
@endforeach
<script>
    function toggleStatus(url) {
        if (confirm('Are you sure you want to change the location status?')) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token
                },
                body: JSON.stringify({}) // You can send extra data if needed
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload the page to reflect status change
                } else {
                    alert('Status change failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
</script>


<script>
    function copyFunction(inputId) {
        var copyText = document.querySelector(`[data-column="${inputId}"]`);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
    }

    function copyFunctionBoth(inputId) {
        var copyText = document.querySelector(`[data-column="${inputId}"]`);
        var siblingInput = copyText.nextElementSibling;
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);

        siblingInput.select();
        siblingInput.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(siblingInput.value);
    }

    function editFunctionBoth(inputId) {
        var inputElement = document.querySelector(`[data-column="${inputId}"]`);
        var siblingInput = inputElement.nextElementSibling;
        $(inputElement).focus().removeAttr('readonly');
        $(siblingInput).focus().removeAttr('readonly');
    }

    function editFunction(inputId) {
        var inputElement = document.querySelector(`[data-column="${inputId}"]`);
        $(inputElement).focus().removeAttr('readonly');
    }

    function blurFunction(element) {

        var [columnName, locId] = $(element).data('column').split('-');
        var inputValue = $(element).val();
        var _token = $('#tokenLoc').val();
        var route = $('#routeLoc').val();
        var data = {
            location_id: locId,
            column: columnName,
            value: inputValue,
            _token: _token
        };
        if (!$(element).prop('readonly')) {
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }
        $(element).attr('readonly', true)
    }

    function hoverFunction(element) {
        $(element).find('.btn-wrapper-loc').stop(true, true).css('visibility', 'visible');
    }

    function leaveHoverFunction(element) {
        $(element).find('.btn-wrapper-loc').stop(true, true).css('visibility', 'hidden');
    }


    function leaveHoverFunctionBoth(element) {

        $(element).find('.btn-wrapper-loc').css('visibility', 'hidden');
        $(element).find('input').attr('readonly', true);
    }

    function specBlur(element) {
        var [columnName, locId] = $(element).data('column').split('-');
        var inputValue = $(element).val();
        var _token = $('#tokenLoc').val();
        var route = $('#routeLoc').val();
        var data = {
            location_id: locId,
            column: columnName,
            value: inputValue,
            _token: _token
        };
        $.post(route, data)
            .then(function(response) {
                if (response.result) {
                    $(element).val(response.value);
                }
            })
            .catch(function(error) {
                console.log(error);
            })
    }

    $(document).ready(function() {
        $('input').each(function() {
            const length = $(this).val().length;
            $(this).css('min-width', (length + 1) + 'ch');

            $(this).on('input', function() {
                const newLength = $(this).val().length;
                $(this).css('min-width', (newLength + 1) + 'ch');
            });

            $(this).hover(
                function() {
                    $(this).css('border', '1px solid gray');
                },
                function() {
                    $(this).css('border', '');
                }
            );
        });
    });
    var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    var tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
</script>
