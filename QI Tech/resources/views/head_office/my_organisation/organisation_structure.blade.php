<div class="company-center-area content-custom-scroll" style="max-width: 80%;
  margin: auto;
  overflow-x: auto;
  padding-top: 3rem;">
    @if(!count($allGroups))
    <p class="text-muted font-italic">You do not have any tiers or groups created.</p>
    @endif

    <div class="row horizontal-full-width">
        <div class="col-1 organisation-levels">
            @for($i = 1; $i <= $maximumDepth; $i++) <div class="organisation-level" title="Click to edit level">
                <div class="level_counter text-danger">LV {{$i}}</div><span
                    class="level_name">{{$levels[$i]->level_name}}</span>
                <div class="action-bar card card-qi">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#edit_level_modal_{{$levels[$i]->id}}"
                        class="btn text-info"><i class="fa fa-edit"></i> Edit Level {{$i}}</a>
                </div>
        </div>

        <div class="modal fade" id="edit_level_modal_{{$levels[$i]->id}}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                       Edit level {{$i}}
                       <button type="button" class="btn-close" data-bs-dismiss="modal"
                       aria-label="Close"><span>&times;</span></button>
                   </button>
                   
                    </div>
                    <div class="modal-body">

                        <form method="post" action="{{route('head_office.organisation.save_level')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{$levels[$i]->id}}">
                            
                            <div class="organisation-structure-add-content">
                                <label class="inputGroup">New Name : 
                                    <input type="text" name="level_name" class="w-50" required
                                        @if($levels[$i]->level_name != 'Lv Name')
                                    value="{{$levels[$i]->level_name}}" @endif>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="save" value="Save" class="btn btn-info">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @endfor
    </div>
    <div class="col-11  pr-300">
        @include('head_office.my_organisation.horizontal-list',['groups' => $allGroups])
    </div>
</div>

<div class="modal fade" id="level_action_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>