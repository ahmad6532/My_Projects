
@extends('layouts.admin.master')
@section('content')

<div class="row justify-content-center mt-2">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="user-content-detail">
                <div class="text-center">
                    <h1 class="text-heading_vt pb-4">Role & Permissions</h1>
                </div>
                <div class="d-flex justify-content-end">
                    <div class="px-1">
                        <a href="{{route('roles.list')}}"><button class="page-btn page-btn-outline roles-btn">Cancel</button></a>
                    </div>
            <form action="{{route('update.roles.permissions')}}" id="saveRoleForm" method="post">
                @csrf
                    <div class="px-1">
                        <button name="submit" type="submit" class="page-btn roles-btn">Update</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title">Role Name<span class="red" style="font-size:22px;">*</span></label>
                    <input type="text" autocomplete="off" required placeholder="Enter Role Name" value="{{$role->role_name}}" name="role_name" class="form-control">
                    @error('role_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <input type="hidden" name="id" style="min-width:250px" value="{{ old('id', $role->id) }}">
                </div>
                <div class="roles-table" style="overflow:auto;">
                    <table class="table" style="width:100%; border:1px solid #dee2e6;">
                        <tbody>
                            <tr>
                                <td><h1>Modules</h1></td>
                                <td colspan="4" style="border-left: 1px solid #dee2e6;"><h1>User Role Manager</h1></td>
                            </tr>
                            <tr class="roles-sub-head">
                                <td></td>
                                <td><p>All Access</p></td>
                                <td><p>Read Only</p></td>
                                <td><p>Write</p></td>
                                <td><p>Delete</p></td>
                            </tr>
                            @php($active_permission = [])
                            @foreach ($modules as $key => $item)
                                @php($module_name = strtolower(str_replace(' ', '-', $item->name)))
                                @php($disable = 0)
                                @php($all = $read = $write = $delete = 0)
                                @foreach ($rolePermissions as $permission)
                                    @if ($item->id == $permission['module_id'])
                                        @if ($module_name . '-all' == $permission['name'])
                                            {{-- @php(
                                                $disable =
                                                    ($role->status == 'default') ||
                                                    ($item->name == 'User Management')
                                                        ? 1
                                                        : 0
                                            ) --}}
                                            @php($all = 1)
                                        @elseif($module_name . '-read' == $permission['name'])
                                            @php($read = 1)
                                        @elseif($module_name . '-delete' == $permission['name'])
                                            @php($delete = 1)
                                        @elseif($module_name . '-write' == $permission['name'])
                                            @php($write = 1)
                                        @endif
                                    @endif
                                @endforeach
                                @if (in_array($item->id, $allowed_modules) || $role_id == 1)
                                    <tr>
                                        <?php
                                            if($item->id !== 18 &&
                                                $item->id !== 17 &&
                                                $item->id !== 16 &&
                                                $item->id !== 15 &&
                                                $item->id !== 10 &&
                                                $item->id !== 11 &&
                                                $item->id !== 6 &&
                                                $item->id !== 8 &&
                                                $item->id !== 19 && 
                                                $item->id !== 20 && 
                                                $item->id !== 21 && 
                                                $item->id !== 22 && 
                                                $item->id !== 23 && 
                                                $item->id !== 24 &&
                                                $item->id !== 25
                                            ){
                                                $style = '';
                                            }else{
                                                $style = 'padding-left:50px !important;';
                                            }
                                        ?>
                                        <td style="{{$style}}">{{ $item->name }}</td>
                                        <td>
                                            <label class="switch-container">
                                                <input type="checkbox" class="all_checkbox"
                                                    {{ $disable ? 'disabled' : '' }} id="all_{{ $item->id }}"
                                                    {{ $all ? 'checked' : '' }}
                                                    onclick="check('{{ $item->id }}')"
                                                    name="all[{{ $item->id }}]">
                                                @if ($disable)
                                                    <input type="checkbox" class="d-none"
                                                        {{ $all ? 'checked' : '' }}
                                                        name="all[{{ $item->id }}]">
                                                @endif
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        @if($item->id !== 5 &&
                                         $item->id !== 1 &&
                                         $item->id !== 2 &&
                                          $item->id !== 3 &&
                                          $item->id !== 25
                                          )
                                        <td><input type="radio" id="read_{{ $item->id }}"
                                                name="read[{{ $item->id }}]" {{ $read ? 'checked' : '' }}
                                                class="radio-size cursor-pointer {{ $all ? 'd-none' : '' }}">
                                        </td>
                                        <td><input type="radio" id="write_{{ $item->id }}"
                                                name="write[{{ $item->id }}]"
                                                {{ $write ? 'checked' : '' }}
                                                class="radio-size cursor-pointer {{ $all ? 'd-none' : '' }}">
                                        </td>
                                        @if($item->id !== 10 && $item->id !== 11 && $item->id !== 15 && $item->id !== 6 && $item->id !== 18)
                                            <td><input type="radio" id="delete_{{ $item->id }}"
                                                    name="delete[{{ $item->id }}]"
                                                    {{ $delete ? 'checked' : '' }}
                                                    class="radio-size cursor-pointer {{ $all ? 'd-none' : '' }}">
                                        @endif
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<script>
    function check(id) {
        if (id == 'all') {
            if ($('#all_checkd').is(':checked')) {
                for (let i = 1; i <= $('.all_checkbox').length; i++) {
                    if(!$('#all_'+i).attr('checked')){
                        $('#all_'+i).trigger('click')
                    }
                }

                $('.all_checkbox').attr('checked', true);
                $('.radio-size').css('display', 'none');
            } else {
                for (let i = 1; i <= $('.all_checkbox').length; i++) {
                    if($('#all_'+i).attr('checked')){
                        $('#all_'+i).trigger('click')
                    }
                }
                $('.all_checkbox').attr('checked', false);
                $('.radio-size').css('display', 'block');

            }
        } else {
            if (!$('#all_' + id).is(':checked')) {
                $('#all_' + id).attr('checked', false);
                $('#read_' + id).removeClass('d-none');
                $('#read_' + id).css('display', 'block');
                $('#delete_' + id).removeClass('d-none');
                $('#delete_' + id).css('display', 'block');
                $('#write_' + id).removeClass('d-none');
                $('#write_' + id).css('display', 'block');
                $('#read_' + id).attr('disabled', false);
                $('#delete_' + id).attr('disabled', false);
                $('#write_' + id).attr('disabled', false);
            } else {
                $('#all_' + id).attr('checked', true);
                $('#read_' + id).addClass('d-none');
                $('#read_' + id).attr('disabled', true);
                $('#delete_' + id).addClass('d-none');
                $('#write_' + id).addClass('d-none');
                $('#delete_' + id).attr('disabled', true);
                $('#write_' + id).attr('disabled', true);
            }
        }

        // start for Employee management
            var all2Checkbox = document.getElementById('all_2');//parent id
            var all_19_Checkbox = document.getElementById('all_19');//child
            var all_20_Checkbox = document.getElementById('all_20');//child
            var all_21_Checkbox = document.getElementById('all_21');//child
            var all_22_Checkbox = document.getElementById('all_22');//child
            var all_23_Checkbox = document.getElementById('all_23');//child
            var all_24_Checkbox = document.getElementById('all_24');//child

            //parent function
            function handleAll2CheckboxChange() {
                if (!all2Checkbox.checked) {
                    all_19_Checkbox.checked = false;
                    all_20_Checkbox.checked = false;
                    all_21_Checkbox.checked = false;
                    all_22_Checkbox.checked = false;
                    all_23_Checkbox.checked = false;
                    all_24_Checkbox.checked = false;
                }
            }
            all2Checkbox.addEventListener('change', handleAll2CheckboxChange);

            //child function
            function handleAll_19_CheckboxChange() {
                if (all_19_Checkbox.checked) {
                    all2Checkbox.checked = true;
                }
            }
            all_19_Checkbox.addEventListener('change', handleAll_19_CheckboxChange);
           
            //child function
            function handleAll_20_CheckboxChange() {
                if (all_20_Checkbox.checked) {
                    all2Checkbox.checked = true;
                }
            }
            all_20_Checkbox.addEventListener('change', handleAll_20_CheckboxChange);
            
            //child function
            function handleAll_21_CheckboxChange() {
                if (all_21_Checkbox.checked) {
                    all2Checkbox.checked = true;
                }
            }
            all_21_Checkbox.addEventListener('change', handleAll_21_CheckboxChange);
            
            //child function
            function handleAll_22_CheckboxChange() {
                if (all_22_Checkbox.checked) {
                    all2Checkbox.checked = true;
                }
            }
            all_22_Checkbox.addEventListener('change', handleAll_22_CheckboxChange);
            
            //child function
            function handleAll_23_CheckboxChange() {
                if (all_23_Checkbox.checked) {
                    all2Checkbox.checked = true;
                }
            }
            all_23_Checkbox.addEventListener('change', handleAll_23_CheckboxChange);
           
            //child function
            function handleAll_24_CheckboxChange() {
                if (all_24_Checkbox.checked) {
                    all2Checkbox.checked = true;
                }
            }
            all_24_Checkbox.addEventListener('change', handleAll_24_CheckboxChange);

        //end Employee management

        // start for time sheet
            var all3Checkbox = document.getElementById('all_3');//parent id
            var all_11_Checkbox = document.getElementById('all_11');//child daily
            var all_10_Checkbox = document.getElementById('all_10');//child monthly
            var all_15_Checkbox = document.getElementById('all_15');//child yearly

            function handleAll3CheckboxChange() {
                if (!all3Checkbox.checked) {
                    all_10_Checkbox.checked = false;
                    all_11_Checkbox.checked = false;
                    all_15_Checkbox.checked = false;
                }
            }
            all3Checkbox.addEventListener('change', handleAll3CheckboxChange);

            //child function
            function handleAll_10_CheckboxChange() {
                if (all_10_Checkbox.checked) {
                    all3Checkbox.checked = true;
                }
            }
            all_10_Checkbox.addEventListener('change', handleAll_10_CheckboxChange);

            //child function
            function handleAll_11_CheckboxChange() {
                if (all_11_Checkbox.checked) {
                    all3Checkbox.checked = true;
                }
            }
            all_11_Checkbox.addEventListener('change', handleAll_11_CheckboxChange);
            
            //child function
            function handleAll_15_CheckboxChange() {
                if (all_15_Checkbox.checked) {
                    all3Checkbox.checked = true;
                }
            }
            all_15_Checkbox.addEventListener('change', handleAll_15_CheckboxChange);
        //end time sheet

        // start for global setting
            var all5Checkbox = document.getElementById('all_5');//parent id
            var all_6_Checkbox = document.getElementById('all_6');//child theme setting
            var all_8_Checkbox = document.getElementById('all_8');//child smtp
            var all_16_Checkbox = document.getElementById('all_16');//child configuration
            var all_17_Checkbox = document.getElementById('all_17');//child appearance
            var all_18_Checkbox = document.getElementById('all_18');//child version

            // parent function
            function handleAll5CheckboxChange() {
                if (!all5Checkbox.checked) {
                    all_6_Checkbox.checked = false;
                    all_8_Checkbox.checked = false;
                    all_16_Checkbox.checked = false;
                    all_17_Checkbox.checked = false;
                    all_18_Checkbox.checked = false;
                }
            }
            all5Checkbox.addEventListener('change', handleAll5CheckboxChange);

            //child function
            function handleAll_6_CheckboxChange() {
                if (all_6_Checkbox.checked) {
                    all5Checkbox.checked = true;
                }
            }
            all_6_Checkbox.addEventListener('change', handleAll_6_CheckboxChange);

            //child function
            function handleAll_8_CheckboxChange() {
                if (all_8_Checkbox.checked) {
                    all5Checkbox.checked = true;
                }
            }
            all_8_Checkbox.addEventListener('change', handleAll_8_CheckboxChange);
           
            //child function
            function handleAll_16_CheckboxChange() {
                if (all_16_Checkbox.checked) {
                    all5Checkbox.checked = true;
                }
            }
            all_16_Checkbox.addEventListener('change', handleAll_16_CheckboxChange);
            
            //child function
            function handleAll_17_CheckboxChange() {
                if (all_17_Checkbox.checked) {
                    all5Checkbox.checked = true;
                }
            }
            all_17_Checkbox.addEventListener('change', handleAll_17_CheckboxChange);
            
            //child function
            function handleAll_18_CheckboxChange() {
                if (all_18_Checkbox.checked) {
                    all5Checkbox.checked = true;
                }
            }
            all_18_Checkbox.addEventListener('change', handleAll_18_CheckboxChange);
        //end for global setting
    }

    $("[type='radio']").on('click', function (e) {
        var previousValue = $(this).attr('previousValue');
        if (previousValue == 'true') {
            this.checked = false;
            $(this).attr('previousValue', this.checked);
        }
        else {
            this.checked = true;
            $(this).attr('previousValue', this.checked);
        }
    });
</script>
@endsection


