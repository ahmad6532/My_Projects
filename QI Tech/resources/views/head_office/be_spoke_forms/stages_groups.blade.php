<h4><strong>Please add groups to organize your questions</strong></h4>
<form name="fields_form" method="post" action="{{route('head_office.be_spoke_forms_templates.stage_groups.save',$stage->id)}}">
    @csrf
    <input type="hidden" name="stage_id" value="@if(isset($stage)){{$stage->id}}@endif">
    <input type="hidden" name="form_id" value="@if(isset($stage)){{$stage->form_id}}@endif">

    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>Group Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @foreach($stage->groups as $group)
                <tr>
                    <td>
                        <input class="form-control" type="text" id="group_name[{{$group->id}}]"
                            value="{{$group->group_name}}" name="groups[{{$group->id}}]"
                            placeholder="Enter group name here" required>
                    </td>
                    <td calss="row_icons">
                        <a class="btn btn-danger delete_group"
                            href="{{route('head_office.be_spoke_forms_templates.form_group_delete',['id'=>$group->id,'_token'=>csrf_token()])}}"><i
                                class="fas fa-times"></i> Delete Group</a>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td>
                        <label for="stage_name">New Group Name</label>
                        <input class="form-control" type="text" id="group_name" name="group_name"
                            placeholder="Enter group name here">
                    </td>
                    <td calss="row_icons">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <button type="submit" name="submit" class="nav-link btn btn-info inline"><i class="fas fa-save"></i> Save Groups</button>
    </div>
    </div>
    <!-- End custom design -->
</form>