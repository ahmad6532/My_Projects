
<div class="row custom-group " ng-if="c.is_group" >
    <div class="col-md-12 mt-2">
        <span class="btn btn-danger btn-sm float-right" ng-click="s.collection.splice(collection_index,1)"><i class="fas fa-times"></i></span>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label for="group_name">Name</label>
            <input type="text" class="form-control" name="@{{s.name + c.name + $index}}" placeholder="Enter the name of group" ng-model="c.name"  required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group text-center">
            <label for="can_add_more">User Can Add More</label>
            <input type="checkbox" id="can_add_more" ng-model="c.can_add_more" class="form-control">
        </div>
    </div>
    <hr class="bg-dark w-50 text-center">
    <div class="ml-1 col-md-12" ng-init="g=c;">
        <div ng-repeat="c in g.fields track by $index">
            <div ng-init="collection = g.fields;"></div> 
            @include('location.be_spoke_forms.field')
            </div>
        <div class="col-md-12 mb-2">
            <button type="button" class="btn btn-info" ng-click="add_new_input_field($index,g)">+ Add New Field to Group</button>
        </div>

    </div>
</div>