<div x-data="{ open: false, selectedStartedRule: @entangle('selectedStartedRule'), selectedEmailType: @entangle('selectedEmailType') }" id="case-live-wrapper">
    <style>
        [hidden] {
            display: none !important;
        }

        .msa-wrapper {
            width: 100%;
            position: relative;
        }

        .msa-wrapper:focus-within .input-presentation {
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .msa-wrapper>* {
            display: block;
            width: 100%;
        }

        .msa-wrapper .input-presentation {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
            min-height: 40px;
            padding: 6px 40px 6px 12px;
            border: 1px solid rgba(0, 0, 0, 0.3);
            font-size: 1rem;
            border-radius: 10px;
            position: relative;
            cursor: pointer;
        }

        .msa-wrapper .input-presentation .placeholder {
            font-weight: 400;
            color: #6e707e;
            background: transparent
        }

        .msa-wrapper .input-presentation:after {
            content: '';
            border-top: 6px solid black;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            right: 14px;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .msa-wrapper .input-presentation.active {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        .msa-wrapper .input-presentation .tag-badge {
            background-color: #2BAFA5;
            padding-left: 14px;
            padding-right: 28px;
            color: white;
            border-radius: 14px;
            position: relative;
        }

        .msa-wrapper .input-presentation .tag-badge span {
            font-size: 16px;
            line-height: 27px;
        }

        .msa-wrapper .input-presentation .tag-badge button {
            display: inline-block;
            padding: 0;
            -webkit-appearance: none;
            appearance: none;
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, .8);
            font-size: 12px;
            position: absolute;
            right: 0px;
            padding-right: 10px;
            padding-left: 5px;
            cursor: pointer;
            line-height: 26px;
            height: 26px;
            font-weight: 600;
        }

        .msa-wrapper .input-presentation .tag-badge button:hover {
            background-color: rgba(255, 255, 255, .2);
            color: white;
        }

        .msa-wrapper ul {
            position: absolute;
            width: 100%;
            background: white;
            box-shadow: 0 0 15px rgba(255, 255, 255, .06);
            border: 1px solid rgba(0, 0, 0, 0.3);
            font-size: 1rem;
            margin: 0;
            padding: 0;
            border-top: none;
            list-style: none;
            border-bottom-right-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .msa-wrapper ul li {
            padding: 6px 12px;
            text-transform: capitalize;
            cursor: pointer;
        }

        .msa-wrapper ul li:hover {
            background: #2BAFA5;
            color: white;
        }

        .select2-container {
    width: 100% !important;
}
    </style>
    <div style="min-height: 200px;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <h4 class="h4">When Started</h4>
                <div wire:loading class="loading-wrape" style="width:30px;height:30px;">
                    <div class="loader-small" style="top: unset;"></div>
                </div>
            </div>
            <button type="button" class="btn" x-on:click="open = !open" wire:click="toggleComplete('s')"><i class="fa-solid fa-plus"></i></button>
        </div>
        @if (!empty($startedTableData['started']))
            <div class="custom-scroll" style="max-height: 200px;overflow-y:auto;">
                <table id="dataTable-case" class="table new-table table-responsive table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            {{-- <th>Title</th> --}}
                            <th>Condition</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="all_locations ">
                        @foreach ($startedTableData['started'] as $index => $rule)
                            <tr wire:key="start_{{ $index }}">
                                {{-- <td>{{ $rule['condition_title'] }}</td> --}}
                                <td>
                                    @if ($rule['condition_type'] == 1)
                                        Add user profile to this case
                                    @elseif($rule['condition_type'] == 2)
                                        Add specific user to this case
                                    @elseif($rule['condition_type'] == 3)
                                        Send Email
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center align-items-center mx-auto"
                                        style="width: fit-content;">
                                        <button x-on:click="open = true" type="button" class="btn p-0 px-2"
                                            title="edit this condition"
                                            wire:click='editConditon({{ $index }},"s")'>
                                            <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn p-0 px-2" title="Remove this action"
                                            wire:click="deleteConditon({{ $index }},'s')">
                                            <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="h6 text-muted text-left m-0"> There are no rules created</p>

        @endif
    </div>

    <div style="min-height: 200px;">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="h4">When Completed</h4>
            <button type="button" class="btn" x-on:click="open = !open" wire:click="toggleComplete('c')"><i class="fa-solid fa-plus"></i></button>
        </div>
        @if (!empty($startedTableData['completed']))
        <div class="custom-scroll" style="max-height: 200px;overflow-y:auto;">
            <table id="dataTable-case2" class="table new-table table-responsive table-bordered " style="width:100%">
                <thead>
                    <tr>
                        {{-- <th>Title</th> --}}
                        <th>Condition</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="all_locations ">
                    @foreach ($startedTableData['completed'] as $index => $rule)
                        <tr wire:key="complete_{{ $index }}">
                            {{-- <td>{{ $rule['condition_title'] }}</td> --}}
                            <td>
                                @if ($rule['condition_type'] == 1)
                                    Remove user profile to this case
                                @elseif($rule['condition_type'] == 2)
                                    Remove specific user to this case
                                @elseif($rule['condition_type'] == 3)
                                    Send Email
                                @else
                                    Unknown
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center align-items-center mx-auto"
                                    style="width: fit-content;">
                                    <button x-on:click="open = true" type="button" class="btn p-0 px-2"
                                        title="edit this condition"
                                        wire:click='editConditon({{ $index }},"c")'>
                                        <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="btn p-0 px-2" title="Remove this action"
                                        wire:click="deleteConditon({{ $index }},'c')">
                                        <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="h6 text-muted text-left m-0"> There are no rules created</p>

    @endif
    </div>



    <div class="backdrop" id="backdropMain" x-show="open" x-transition
        x-on:click="open = false; selectedStartedRule = 0;$wire.set('title', '');$wire.set('addUser', '1');$wire.set('message', '');$wire.set('allowPrevStage', false);$wire.set('allowFutureStage', false);$wire.set('ruleIndex', null);$wire.set('users', [])">
        <div class="card custom-scroll" @click.stop
            style="min-width: 50%;max-height:500px;overflow-y:scroll;position: relative;overflow-x:hidden">
            <div wire:loading class="loading-wraper">
                <div class="loader-container" style="position: relative;display:grid;place-items:center;z-index:20;">
                    <div class="loader" style="top: unset;"></div>
                </div>
            </div>
            <div class="card-header">
                Add Rules
            </div>
            <div class="card-body">
                <form wire:submit.prevent="{{ isset($ruleIndex) ? 'updateRecord' : 'saveRecord' }}" id="formmain">
                    {{-- <div class="">
                        <label for="ruleTitle">Rule Title</label>
                        <input id="ruleTitle" wire:model.defer='title' name="rule_title" type="text"
                            class="form-control custom-case-input" placeholder="Enter rule name" required>
                        @error('selectedStartedRule')
                            <small class="form-text text-danger">Please fill all the fields</small>
                        @enderror
                    </div> --}}

                    <div class="">
                        <label for="ruleConditon">Rule Condition</label>
                        <select wire:model='selectedStartedRule' x-model='selectedStartedRule'
                            class="form-select brand-btn" id="ruleConditon" name="rule_conditon">
                            <option value='0'>Please choose a rule</option>
                            <option value="1">{{ isset($ruleType) && $ruleType == 'c' ? 'Remove' : 'Add' }} user
                                profile to this case </option>
                                <option value="2">{{ isset($ruleType) && $ruleType == 'c' ? 'Remove' : 'Add' }}
                                    specific user to this case </option>
                            <option value="3">Send Email </option>
                        </select>
                    </div>


                    <div class="mt-2" x-show='selectedStartedRule == 1 || selectedStartedRule == 2'
                        x-transition.opacity>

                        <hr style="border: 1.5px solid #949495;width:80%;margin-top:1.5rem;" class="mx-auto">

                        <div class="users" x-show='selectedStartedRule == 2' >
                            <label class="inputGroup">
                                Select Users:
                                <select style="width: 100% !important;" wire:model='users' class="select_2 w-100 w-auto selectedUsers form-select" data-user="user"
                                    name="users[]" multiple="multiple">
                                    @foreach ($user->users as $u)
                                        <option value="{{ $u->user->id }}">{{ $u->user->name }}</option>
                                    @endforeach
                                </select>
                            </label>

                        </div>
                        <div class="profiles" x-show='selectedStartedRule == 1'>

                            <label class="inputGroup">
                                Select Profiles:
                                <select class="select_2 w-100 w-auto selectedProfiles" wire:model='users'
                                    data-user="profiles" name="profiles[]" multiple="multiple"
                                    style="display: flow-root;">
                                    @foreach ($user->head_office_user_profiles as $userEach)
                                        <option
                                            {{ isset($userProfiles) && in_array($userEach->id, $userProfiles) ? 'selected="selected"' : '' }}
                                            value="{{ $userEach->id }}">
                                            {{ $userEach->profile_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>

                        </div>

                        <div class="mb-1" x-show='selectedStartedRule == 1' x-transition.opacity>
                            <label for="add-user" class="fw-bold">Add Users</label>
                            <select class="form-select brand-btn" id="add-user" name="add-user"
                                wire:blur="$set('addUser', $event.target.value)">
                                <option value="1" {{ isset($addUser) && $addUser == 1 ? 'selected' : '' }}>All users with this profile </option>
                                <option value="2" {{ isset($addUser) && $addUser == 2 ? 'selected' : '' }}>Single user randomly
                                </option>
                            </select>
                        </div>

                        <div class="form-flex justify-content-between">
                            <label for="prev-started">Allow to view previous stages:</label>
                            <input hidden class="" type="checkbox" id="prev-started">
                            <div class="btn-wrap">
                                <button type="button"
                                    class="btn btn-outline-secondary live-btn {{ $allowPrevStage ? 'active' : '' }}"
                                    onclick="$('#prev-started').prop('checked',true)"
                                    wire:click="$set('allowPrevStage', true)">Yes</button>
                                <button type="button"
                                    class="btn btn-outline-secondary live-btn {{ $allowPrevStage ? '' : 'active' }}"
                                    onclick="$('#prev-started').prop('checked',false)"
                                    wire:click="$set('allowPrevStage', false)">No</button>
                            </div>
                        </div>
                        <div class="form-flex justify-content-between">
                            <label for="future-started">Allow to view future stages:</label>
                            <input hidden class="" type="checkbox" id="future-started">
                            <div class="btn-wrap">
                                <button type="button"
                                    class="btn btn-outline-secondary live-btn {{ $allowFutureStage ? 'active' : '' }}"
                                    onclick="$('#future-started').prop('checked',true)"
                                    wire:click="$set('allowFutureStage', true)">Yes</button>
                                <button type="button"
                                    class="btn btn-outline-secondary live-btn {{ $allowFutureStage ? '' : 'active' }}"
                                    onclick="$('#future-started').prop('checked',false)"
                                    wire:click="$set('allowFutureStage', false)">No</button>
                            </div>
                        </div>

                        
                    </div>

                    <div class="mt-2" x-show='selectedStartedRule == 3' x-transition>
                        <hr style="border: 1.5px solid #949495;width:80%;margin-top:1.5rem;" class="mx-auto">
                        <label for="" class="fw-bold">Send Email to:</label>
                        <select x-model='selectedEmailType' class="form-select brand-btn">
                            <option value="1">Specific User</option>
                            <option value="2">User Profile</option>
                            <option value="3">{{$is_external ? 'Responder Email' : 'Location Email' }}</option>
                        </select>

                        <div class="mb-2">

                            <div class="users " x-show='selectedEmailType == 1'>
                                <label class="inputGroup">
                                    Select Users:
                                    <select class="select_2 w-100 w-auto selectedUsers" wire:model='users' name="usersEmail[]"
                                        multiple="multiple">
                                        @foreach ($user->users as $u)
                                            <option value="{{ $u->user->id }}">{{ $u->user->name }}</option>
                                        @endforeach
                                    </select>
                                </label>

                            </div>
                            <div class="profiles" x-show='selectedEmailType == 2'>

                                <label class="inputGroup">
                                    Select Profiles:
                                    <select class="select_2 w-100 w-auto selectedProfiles" wire:model='users' name="profilesEmail[]"
                                        multiple="multiple" style="display: flow-root;">
                                        @foreach ($user->head_office_user_profiles as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->profile_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>

                            </div>
                            <div x-show='selectedEmailType == 3'>
                                <input class="text-muted form-control custom-case-input mt-2" readonly disabled style="background: #dee2e6;" value="{{$is_external ? "Responder's Email (User)" : "Location reported from" }}"  />
                            </div>


                        </div>

                        <div wire:ignore>
                            <textarea spellcheck="true"  wire:model="message" class="rich-text-area tinymce " id="email-rich" name="email_text_then"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-info mt-3 "
                        style="margin-left: calc(100% - 60px);">Save</button>
                </form>
            </div>
        </div>
    </div>
    <button wire:click="$refresh" style="display: none;"></button>
</div>

@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            tinymce.init({
                promotion: false,
                branding:false,
                selector: '#email-rich',
                font_formats:"Littera Text",
                content_style: "body { font-family: 'Littera Text', sans-serif; }",
                skin: false,
                content_css: false,
                forced_root_block: false,
                setup: function(editor) {
                    editor.on('init change', function() {
                        editor.save();
                    });
                    editor.on('blur', function(e) {
                        @this.set('message', editor.getContent());
                    });
                }
            });

        });
        Livewire.on('emailUpdate', message => {
            tinymce.get('email-rich').setContent(message);
        });
        $(document).ready(function($) {
            // $(".select_2").select2();
            
            

            $('.selectedUsers').on('select2:select', function(e) {
                @this.set('users', $(this).select2('val'));
            });
            $('.selectedUsers').on('select2:unselect', function(e) {
                @this.set('users', $(this).select2('val'));
            });
            $('.selectedProfiles').on('select2:select', function(e) {
                @this.set('users', $(this).select2('val'));
            });
            $('.selectedProfiles').on('select2:unselect', function(e) {
                @this.set('users', $(this).select2('val'));
            });

            Livewire.on('updateUserSelected', (values, type) => {
                if (type === 'profile') {
                    $('.selectedProfiles').val(values).trigger('change');
                    @this.set('users', $('.selectedProfiles').select2('val'));
                } else if (type === 'user') {
                    $('.selectedUsers').val(values).trigger('change');
                    @this.set('users', $('.selectedUsers').select2('val'));
                }
            })




            


            Livewire.hook('message.processed', (message, component) => {
                $(".select_2").select2();
            })

            Livewire.on('formSubmitted', () => {
                // Reset form fields after successful submission
                document.getElementById('formmain').reset();
                document.getElementById('backdropMain').click();
            });


        });
        
        function InitializeTable(){
            let table;
            if ( table !== undefined) {
                        table.destroy();
                        }

            table = new DataTable('#dataTable-case', {
                paging: false,
                info: false,
                language: {
                    search: ""
                },
                'columnDefs': [{
                    "select": 'multi',
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': '',
                }],
            });
        }
    </script>
@endpush
