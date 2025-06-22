<div ng-app="BespokeFormApp">
    <script src="{{ asset('js/angular.min.js') }}"></script>
    <!-- ngIntlTellInput for phone field in ng angular -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
      <!-- Select 2-->
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/fuse.js@7.0.0"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUXawG1l_BPsK3wkmBs7n9mLPaJgznrTU&sensor=false&libraries=places"></script>
      <script src="scripts/external/vs-google-autocomplete.js"></script>
      <script src="{{asset('/tinymce/tinymce.min.js')}}"></script>
      <script src="{{asset('/tinymce/tinymce-jquery.min.js')}}"></script>
    <script src="{{ asset('bespoke_form_v3/app/ngIntlTelInput.js') }}"></script>
    <script src="{{ asset('bespoke_form_v3/scripts/external/select.min.js') }}"></script>
    <script src="{{ asset('bespoke_form_v3/scripts/external/angular-sanitize.min.js') }}"></script>
    <script src="{{ asset('bespoke_form_v3/scripts/external/angular-sanitize.min.js') }}"></script>
    <script src="{{ asset('bespoke_form_v3/scripts/external/vs-google-autocomplete.js') }}"></script>
    <script src="{{ asset('bespoke_form_v3/scripts/external/angular-tooltips.min.js') }}"></script>
    <script src="{{ asset('bespoke_form_v3/scripts/external/angular-tooltips.min.js') }}"></script>
    <link rel="stylesheet" href="{{asset('bespoke_form_v3/styles/selectize.default.css')}}">

    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('bespoke_form_v3/styles/main.css') }}"> --}}
    {{-- <script src="{{ asset('bespoke_form_v3/scripts/services/DataService.js') }}"></script> --}}
{{-- <script src="{{ asset('bespoke_form_v3/scripts/services/UIService.js') }}"></script> --}}

<!-- Classes for App Logic -->
<script src="{{ asset('bespoke_form_v3/scripts/classes/Section.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Page.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Form.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/Dummy.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/TextInput.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/NumberInput.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/DateInput.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/TimeInput.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/RadioInput.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/CheckboxInput.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/EmailInput.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/TextAreaInput.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/SelectInput.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/DMD.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/Drugs.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/locations_select.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/PhoneInput.js') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/TextBlock.js?v=1') }}"></script>
<script src="{{ asset('bespoke_form_v3/scripts/classes/Field_Types/Address.js') }}"></script>
<script src="{{asset('bespoke_form_v3/scripts/classes/Condition.js')}}"></script>
    <script src="{{asset('bespoke_form_v3/scripts/classes/Involvement.js')}}"></script>
    <script src="{{asset('bespoke_form_v3/scripts/classes/InvolvementField.js')}}"></script>
    {{-- <script src="{{ asset('bespoke_form_v3/scripts/services/UIService.js') }}"></script> --}}

    <style>
    .elements-area {
    max-width: 800px;
    /* min-height: 80vh; */
    /* padding-top: 4rem !important; */
    }
    .elements-area{
  background: white;
    border-radius: 5px;
    box-shadow: 3px 8px 24px 1px #00000012;
  max-height: 85vh;
  overflow-y: auto;
}
.custom-scroll::-webkit-scrollbar {
  width: 5px !important;
  height: 5px;
  transition: 0.2s ease-in-out;
}

.custom-scroll::-webkit-scrollbar-thumb {
  border-radius: 15px;
  background: #249b91;
}

.custom-scroll::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px #249b9120;
  border-radius: 10px;
}
.ui-select-focusser {
  display: none;
}

.vmp-select input {
  display: none !important;
}

.selectize-control.single .selectize-input,
.selectize-dropdown.single {
  width: 100%;
}

.select-field-remote {
  position: relative;
}
.select-field-remote .spinner-border {
  position: absolute;
  z-index: 10;
  right: 32px;
  width: 20px !important;
  height: 20px;
  top: 5px;
}
  #preview-controller-div input{
    pointer-events: none !important;
    cursor: not-allowed !important;
  }
    </style>
    
        

    <div ng-controller="MyController"  id="preview-controller-div" ng-strict-di>
      
  
      <div class="wrapper d-flex flex-column" style="background-color: @{{form.bg_color}};color: @{{data.colors.text}}">
    
  
  
      <div class="mx-auto" style="max-width: 800px;">
          <h1 ng-style="{'font-size': form.font_size + 'px', 'color': form.text_color}" class="form-title-input">@{{form_title}}</h1>
      </div>
  
     
      
          <!-- Cover Photo -->
          <img ng-if="data.cover" class="cover" ng-src="data.cover" ng-style="data.cover">
  
          <div class="elements-area container mt-4 custom-scroll pb-4 pt-2" style="padding-top: 1rem !important;">
              <!-- Logo -->
              <img ng-if="data.logo" class="logo" ng-src="data.logo">
  
              <!-- Group (OR Section)-->
  
              <!-- Field with label+input -->
  
              <!-- Stage (OR Page)-->
              <div class="row stage-container" style="min-height: 70vh;">
                  <div class="col-lg-12" ng-hide="stage.is_nhs_hidden">
  
                      <div ng-repeat="section in stage.items | orderBy : 'order' track by section.tracker" ng-if="showTaskSummary == false">
                          <div ng-if="section.type == 'section' && !section.hide" class="group-item row">
                              <div class="col-lg-12">
  
                                  <div class="field-item"
                                      ng-repeat="field in section.items | orderBy : 'order' track by field.tracker">
                                      <ng-include src="'views/templates/field_preview.html'"></ng-include>
                                  </div>
                              </div>
                          </div>
                          <div ng-if="section.type == 'field'" ng-init="field = section" >

                            <div class="row">
                                <div class="col-lg-11 field-preview" ng-hide="field.hide">
                                  <div class="form_group">
                                    <div class="input-container">
                                      <label  ng-style="{'font-size': field.input.font_size + 'px', 'color': field.input.text_color}" ng-show="field.is_label_hidden == false" ng-if="!(field.input.hyperlink && field.input.hyperlink.applied)"
                                        class="default-label @{{field.sub_type == 'block' ? 'tb-' + field.input.component : ''}}"
                                        ng-class="{'highlight-text': field.name === 'Check your answers before submitting'}">
                                        @{{field.name}}
                                      </label>
                                      <a  ng-style="{'font-size': field.input.font_size + 'px', 'color': field.input.text_color}" ng-if="field.input.hyperlink && field.input.hyperlink.applied"
                                        class="default-label @{{field.sub_type == 'block' ? 'tb-' + field.input.component : ''}}"
                                        href="@{{field.input.hyperlink.value}}" target="blank">@{{field.name}}</a>
                              
                                      <span title="Required" class="requierd-span" ng-if="field.input.required">*</span>
                                      <svg title="@{{field.tooltip}}" ng-if="field.tooltip && field.tooltip.trim().length > 0" class="mb-1" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        
                              
                                      <div ng-show="field.sub_label" ng-bind-html="field.sub_label | trust"></div>
                                      <br />
                                      <div ng-if="field.sub_type == 'input_field'">
                                        <div ng-if="field.input.category == 1">
                              
                                          <div>
                                            <input  ng-if="field.input.type != 'textarea' && field.input.type != 'phone' && field.input.type != 'date'" type="@{{field.input.type}}"
                                              class="form-control" placeholder="@{{field.input.placeholder}}" ng-model="field.input.value"
                                              ng-change="field.input.valueChanged()" ng-blur="field.input.valueChanged()" ng-disabled="true" />
                                              <!-- Date input -->
                                            <input  ng-if="field.input.type == 'date'" type="@{{field.input.show_day ? 'date' : (field.input.show_month == true ? 'month' : 'number')}}"
                                              class="form-control" placeholder="@{{field.input.placeholder}}" ng-model="field.input.value"
                                              ng-change="field.input.valueChanged()" ng-blur="field.input.valueChanged()" ng-disabled="true" />
                              
                                            <div class="d-flex justify-content-between align-items-center" style="color: #6c757d;font-size: 14px;">
                                              <div ng-if="field.input.min_number.value">
                                                <span>Min: @{{field.input.min_number.value}}</span>
                                              </div>
                                              <div ng-if="!field.input.min_number.value"></div>
                                              <span ng-if="field.input.max_number.value">
                                                @{{field.input.value ? field.input.value.toString().length : 0}} /
                                                @{{field.input.max_number.value || 'No limit'}}
                                              </span>
                                            </div>
                                          </div>
                              
                                          <input ng-if="field.input.type == 'phone'" type="text" class="form-control"
                                            placeholder="@{{field.input.placeholder}}" default-country-code="@{{ field.input.iso_code }}" ng-model="field.input.value" ng-intl-tel-input
                                            ng-change="field.input.valueChanged()" ng-disabled="true" />
                                          <div>
                                            <textarea ng-if="field.input.type === 'textarea'" class="form-control"
                                              placeholder="@{{field.input.placeholder}}" ng-model="field.input.value"
                                              ng-change="field.input.valueChanged()" ng-disabled="true"></textarea>
                                            <div class="d-flex justify-content-between align-items-center" style="color: #6c757d;font-size: 14px;">
                                              <div ng-if="field.input.min_characters.value">
                                                <span>Min: @{{field.input.min_characters.value}}</span>
                                              </div>
                                              <div ng-if="!field.input.min_characters.value"></div>
                                              <span ng-if="field.input.max_characters.value">
                                                @{{field.input.value ? field.input.value.length : 0}} /
                                                @{{field.input.max_characters.value || 'No limit'}}
                                              </span>
                                            </div>
                                          </div>
                                        </div>
                              
                              
                                      </div>
                              
                                      <div ng-if="field.input.category == 4">
                                        <div ng-if="field.input.type == 'address'" >
                                          <input ng-if="field.input.type == 'address'" type="text" class="form-control"
                                            placeholder="@{{field.input.placeholder}}" ng-model="field.input.value" vs-google-autocomplete
                                            ng-change="field.input.valueChanged()" ng-disabled="true" />
                                          
                                        </div>
                                    </div>
                              
                                      <div ng-if="field.input.category == 2">
                                        <div ng-if="field.input.type != 'select' && (o.is_hide == undefined || o.is_hide == false) " class="form-check"
                                          ng-repeat="o in field.input.options track by $index">
                                          <input ng-if="field.input.type != 'radio'" class="form-check-input" type="@{{field.input.type}}"
                                            name="r@{{field.id}}_radio" id="r@{{field.id}}_radio_@{{$index}}" value="@{{o.code ? o.code : o.val}}"
                                            ng-model="o.checked" ng-change="field.input.valueChanged(o)" ng-checked="field.input.value.includes(o.code ?? o.val)" ng-disabled="true" />
                              
                                            <input ng-if="field.input.type == 'radio'" class="form-check-input" type="@{{field.input.type}}"
                                            name="r@{{field.id}}_radio" id="r@{{field.id}}_radio_@{{$index}}" value="@{{o.code ? o.code : o.val}}"
                                            ng-model="field.input.value" 
                                            ng-change="field.input.valueChanged(o)" 
                                            ng-checked="(field.input.default_answer.applied === false && (field.input.default_answer.value === (o.code || o.val))) || (field.input.value === (o.code || o.val))"
                                             />
                                     
                              
                                          <label class="form-check-label" for="r@{{field.id}}_radio_@{{$index}}">
                                            @{{(o.label == '') || (o.label == undefined) ? o.val : o.label}}
                                          </label>
                                          <div ng-show="o.example">
                                            <a href="javascript:void(0)" ng-click="o.show_example = !o.show_example">@{{o.example_title}}</a><br />
                                            <p ng-show="o.show_example" style="font-size: 14px;" ng-bind-html="o.example | newLineToBr | trust"></p>
                                          </div>
                                        </div>
                              
                                        <div ng-show="field.input.type == 'select'">
                              
                                          <div ng-show="!field.input.multi_select" class="select-field-remote">
                                            <!-- Single Select with Remote Options -->
                                            <div class="spinner-border text-secondary" ng-show="field.input.isSearching" role="status">
                                              <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <ui-select ng-model="field.input.value" search-enabled theme="selectize"
                                              ng-if="field.input.remote_options" ng-disabled="true"  ng-change="field.input.valueChanged(field.input.value)">
                                              <ui-select-match placeholder="Select an option">
                                                <span ng-bind="$select.selected.text"></span>
                                              </ui-select-match>
                                              <ui-select-choices repeat="option as option in field.input.options track by $index"
                                                refresh="field.input.refreshOptions($select.search)" refresh-delay="300">
                                                <span ng-bind="option.text"></span>
                                              </ui-select-choices>
                                            </ui-select>
                              
                                            <!-- Single Select without Remote Options -->
                                            <ui-select ng-model="field.input.value" search-enabled theme="selectize"
                                              ng-if="!field.input.remote_options" ng-disabled="true"  ng-change="field.input.valueChanged(field.input.value)">
                                              <ui-select-match placeholder="Select an option">
                                                <span ng-bind="$select.selected.val"></span>
                                              </ui-select-match>
                                              <ui-select-choices repeat="option as option in field.input.options track by $index">
                                                <span ng-bind="option.val"></span>
                                              </ui-select-choices>
                                              <ui-select-no-choice ng-if="!field.input.options.length">
                                                No Information found
                                              </ui-select-no-choice>
                                            </ui-select>
                                          </div>
                              
                                          <div ng-show="field.input.multi_select">
                                            <!-- Multi Select with Remote Options -->
                                            <ui-select multiple ng-model="field.input.value" theme="selectize" search-enabled
                                              ng-if="field.input.remote_options" ng-disabled="true"  ng-change="field.input.valueChanged(field.input.value)">
                                              <ui-select-match placeholder="Select options">
                                                <span ng-bind="$item.text"></span>
                                              </ui-select-match>
                                              <ui-select-choices repeat="option.id as option in field.input.options track by $index"
                                                refresh="field.input.refreshOptions($select.search)" refresh-delay="300">
                                                <span ng-bind="option.text"></span>
                                              </ui-select-choices>
                                              <ui-select-no-choice ng-if="!field.input.options.length">
                                                <span ng-show="field.input.isSearching">Loading...</span>
                                                <span ng-show="!field.input.isSearching">No Information found</span>
                                              </ui-select-no-choice>
                                            </ui-select>
                              
                                            <!-- Multi Select without Remote Options -->
                                            <ui-select multiple ng-model="field.input.value" theme="selectize" search-enabled
                                              ng-if="!field.input.remote_options" ng-disabled="true"  ng-change="field.input.valueChanged(field.input.value)">
                                              <ui-select-match placeholder="Select options">
                                                <span ng-bind="$item.val"></span>
                                              </ui-select-match>
                                              <ui-select-choices repeat="option as option in field.input.options track by $index">
                                                <span ng-bind="option.val"></span>
                                              </ui-select-choices>
                                              <ui-select-no-choice ng-if="!field.input.options.length">
                                                No Information found
                                              </ui-select-no-choice>
                                            </ui-select>
                                          </div>
                                        </div>
                              
                              
                              
                              
                              
                              
                              
                                      </div>
                                      <div ng-if="field.input.category == 3">
                                        <div ng-if="(field.input.type == 'dmd' || field.input.type == 'dmd_drugs')  ">
                                          <div>
                                            <p class="mb-1" style="color: #6F777B;">Start typing and select the relevant medicine from the list.</p>
                                            <ui-select ng-model="field.input.value.vtm.vtm_id"
                                              on-select="field.input.valueChanged('id', $select.selected)" theme="selectize"
                                              ng-disabled="true" spinner-enabled="true" spinner-class="ui-select-spin" ng-disabled="true">
                                              <ui-select-match>
                                                <span ng-bind="$select.selected.label"></span>
                                                <ui-select-remove ng-click="field.input.valueChanged('vtm', null)">&times;</ui-select-remove>
                                              </ui-select-match>
                                              <ui-select-choices repeat="option.id as option in field.input.options track by $index"
                                                refresh="field.input.refreshAddresses($select.search)" refresh-delay="300">
                                                <span ng-bind="option.label"></span>
                                              </ui-select-choices>
                                            </ui-select>
                                          </div>
                                          <div ng-if="field.input.type !='dmd_drugs'">
                                            <p class="mb-1" style="color: #6F777B;">Then select the relevant product from the list.</p>
                                            <ui-select class="vmp-select" ng-model="field.input.value.vmp.vp_id"
                                              on-select="field.input.valueChanged('vmp', $select.selected)" theme="selectize" search-enabled="false"
                                              ng-disabled="true" ng-disabled="true">
                                              <ui-select-match>
                                                <span ng-bind="$select.selected.label"></span>
                                              </ui-select-match>
                                              <ui-select-choices repeat="option.id as option in field.input.vmp_options track by $index">
                                                <span ng-bind="option.label"></span>
                                              </ui-select-choices>
                                            </ui-select>
                                          </div>
                                          <div>
                                            <p class="mb-1" style="color: #6F777B;">Or specify other</p>
                                            <input type="text" class="form-control shadow-none" ng-model="field.input.value.other"
                                              ng-change="field.input.valueChanged('other', field.input.value.other)"
                                              ng-disabled="true" />
                                          </div>
                                          <button ng-if="field.input.is_add_another_btn" class="btn btn-secondary mt-2"
                                            ng-click="field.input.addRecord()" ng-disabled="true">Add Another</button>
                              
                                          <table class="table mt-3" ng-if="field.input.records.length > 0">
                                            <thead>
                                              <tr>
                                                <th>Name</th>
                                                <th>Value</th>
                                                <th>Actions</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr ng-repeat="record in field.input.records track by $index">
                                                <td>Medicine @{{ $index + 1 }}</td>
                                                <td>
                                                  <span ng-if="record.vtm.vtm_string">@{{ record.vtm.vtm_string }}</span>
                                                  <span ng-if="record.vmp.vp_string"> @{{ record.vmp.vp_string }}</span>
                                                  <span ng-if="record.other"> @{{ record.other }}</span>
                                                </td>
                                                <td>
                                                  <button class="btn btn-danger btn-sm" ng-click="field.input.removeRecord($index)">Remove</button>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                              
                              
                              
                              
                              
                              
                              
                              
                                      </div>
                                      <div ng-if="field.input.category == 4">
                                        <div ng-show="field.input.type == 'location_select'">
                              
                                          <div ng-show="!field.input.multi_select" class="select-field-remote">
                                            <!-- Single Select with Remote Options -->
                                            <div class="spinner-border text-secondary" ng-show="field.input.isSearching" role="status">
                                              <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <ui-select ng-model="field.input.value" search-enabled theme="selectize"
                                              ng-if="field.input.remote_options" ng-disabled="true"  ng-change="field.input.valueChanged(field.input.value)">
                                              <ui-select-match placeholder="Select an option">
                                                <span ng-bind="$select.selected.text"></span>
                                              </ui-select-match>
                                              <ui-select-choices repeat="option as option in field.input.options track by $index"
                                                refresh="field.input.refreshOptions($select.search)" refresh-delay="300">
                                                <span ng-bind="option.text"></span>
                                              </ui-select-choices>
                                            </ui-select>
                              
                                            <!-- Single Select without Remote Options -->
                                            <ui-select ng-model="field.input.value" search-enabled theme="selectize"
                                              ng-if="!field.input.remote_options" ng-disabled="true"  ng-change="field.input.valueChanged(field.input.value)">
                                              <ui-select-match placeholder="Select an option">
                                                <span ng-bind="$select.selected.val"></span>
                                              </ui-select-match>
                                              <ui-select-choices repeat="option as option in field.input.options track by $index">
                                                <span ng-bind="option.val"></span>
                                              </ui-select-choices>
                                              <ui-select-no-choice ng-if="!field.input.options.length">
                                                No Information found
                                              </ui-select-no-choice>
                                            </ui-select>
                                          </div>
                              
                                          <div ng-show="field.input.multi_select">
                                            <!-- Multi Select with Remote Options -->
                                            <ui-select multiple ng-model="field.input.value" theme="selectize" search-enabled
                                              ng-if="field.input.remote_options" ng-disabled="true"  ng-change="field.input.valueChanged(field.input.value)">
                                              <ui-select-match placeholder="Select options">
                                                <span ng-bind="$item.text"></span>
                                              </ui-select-match>
                                              <ui-select-choices repeat="option.id as option in field.input.options track by $index"
                                                refresh="field.input.refreshOptions($select.search)" refresh-delay="300">
                                                <span ng-bind="option.text"></span>
                                              </ui-select-choices>
                                              <ui-select-no-choice ng-if="!field.input.options.length">
                                                <span ng-show="field.input.isSearching">Loading...</span>
                                                <span ng-show="!field.input.isSearching">No Information found</span>
                                              </ui-select-no-choice>
                                            </ui-select>
                              
                                            <!-- Multi Select without Remote Options -->
                                            <ui-select multiple ng-model="field.input.value" theme="selectize" search-enabled
                                              ng-if="!field.input.remote_options" ng-disabled="true"  ng-change="field.input.valueChanged(field.input.value)">
                                              <ui-select-match placeholder="Select options">
                                                <span ng-bind="$item.val"></span>
                                              </ui-select-match>
                                              <ui-select-choices repeat="option as option in field.input.options track by $index">
                                                <span ng-bind="option.val"></span>
                                              </ui-select-choices>
                                              <ui-select-no-choice ng-if="!field.input.options.length">
                                                No Information found
                                              </ui-select-no-choice>
                                            </ui-select>
                                          </div>
                                        </div>
                              
                              
                              
                              
                              
                              
                              
                                      </div>
                                      <div class="preview-error">@{{field.input.validation_error}}</div>
                                      <div class="preview-error mt-1" ng-style="{'color': field.input.error ? field.input.error.color : 'inherit'}">@{{field.input.error.msg}}</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                        </div>
                      </div>
                      <div ng-if="stage.is_check_your_answer_type">
                          <h5>The answers you provided are shown below. Please check your answers before submitting.</h5>
                          <div class="answers">
                              <div ng-repeat="preview_stage in stages | filter : {is_nhs_hidden: false, is_check_your_answer_type: false} track by preview_stage.tracker">
                                  <div ng-repeat="preview_item in preview_stage.items track by preview_item.tracker">
                                      <!-- Need to do both for questions + sections -->
                                      <div ng-if="preview_item.type == 'field' && 
                                                  !preview_item.hide_in_summary && 
                                                  (preview_item.always_show || 
                                                  (event_type == 1 && !preview_item.good_care) || 
                                                  (event_type == 2 && preview_item.good_care)) &&
                                                  preview_item.input.value !== undefined && 
                                                  preview_item.input.value !== ''" 
                                          class="answer-row">
                                          <div class="label">@{{preview_item.formatted_summary_label}}</div>
                                          <div ng-if="preview_item.id != '-63'" class="label-answer" ng-bind-html="preview_item.input.formatted_value() | trust"></div>
                                          <div ng-if="preview_item.id == '-63'" 
                                              class="label-answer" 
                                              ng-bind-html="(preview_item.input.formatted_value() + ' - no. of patients ' + form.patients.length) | trust">
                                          </div>
                                          <div class="change-link">
                                              <a href="javascript:void(0)" ng-click="navigate_to_stage(preview_stage.id)">Change</a>
                                          </div>
                                      </div>
  
                                      <div ng-if="(preview_item.id == '-1331' || preview_item.id == '-36') && preview_item.input.value == 'y'">
                                          <div class="label">@{{event_type == 2 ? 'What was the date of the Good Care?' : 'What was the date of Incident?'}}</div>
                                          <div class="label-answer">@{{form.today | date:'dd MMM yyyy'}}</div>
                                          <div class="change-link">
                                              <a href="javascript:void(0)" ng-click="navigate_to_stage(preview_stage.id)">Change</a>
                                          </div>
                                      </div>
  
                                      <div ng-if="(preview_item.id == '-1608' || preview_item.id == '-49') && preview_item.input.value == 'y'">
                                          <div class="label">@{{event_type == 2 ? 'Good care location' : 'Which organisation did the incident happen in?'}}</div>
                                          <div class="label-answer">@{{form.ods}}</div>
                                          <div class="change-link">
                                              <a href="javascript:void(0)" ng-click="navigate_to_stage(preview_stage.id)">Change</a>
                                          </div>
                                      </div>
                                      <div ng-if="(preview_item.id == '-164') && preview_item.input.value == ''">
                                          <div class="label">What was the time of the incident?</div>
                                          <div class="label-answer">I don't know</div>
                                          <div class="change-link">
                                              <a href="javascript:void(0)" ng-click="navigate_to_stage(-162)">Change</a>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <hr />
                              <div ng-if="www_field" ng-repeat="code in www_field.input.value track by $index">
                                  <div ng-if="form.task_list.tasks[code].complete">
                                      <h3>@{{form.task_list.tasks[code].title}}</h3>
                                      <div ng-repeat="preview_stage in form.task_list.tasks[code].pages | filter : {is_nhs_hidden: false} track by preview_stage.tracker">
                                          <div ng-repeat="preview_item in preview_stage.items track by preview_item.tracker">
                                              <!-- Need to do both for questions + sections -->
                                              <div ng-if="preview_item.type == 'field' && !preview_item.hide_in_summary" class="answer-row">
                                              
                                                  <div class="label">@{{preview_item.formatted_summary_label}}</div>
                                                  <div class="label-answer" ng-bind-html="preview_item.input.formatted_value() | trust"></div>
                                                  <!-- <div class="change-link"><a href="javascript:void(0)">Change</a></div> -->
                                              
                                              </div>
                                          </div>
                                      </div>
                                      <hr />
                                  </div>
                              </div>
                          
                          </div>
                             
                      </div>
  
                      <div ng-repeat="p in form.patients track by $index" ng-if="stage.is_check_your_answer_type && form.patients.length != 0 && www_field && event_type == 1">
                          <div class="d-flex align-items-center" style="gap:1rem;">
                              <h2 class="m-0">Patient @{{$index + 1}}</h2>
                              <div >
                                  <a href="javascript:void(0)" ng-click="update_patient($index)">Update</a> | 
                                  <a href="javascript:void(0)" ng-click="form.patients.splice($index, 1)">Remove</a>
                              </div>
                          </div>
                      
                          <!-- Display Patient Details -->
                          <table class="table">
                             
                              <tbody>
                                  <tr ng-repeat="(key, field) in p.data">
                                      <td>@{{field.label}}</td>
                                      <td>
                                          <span ng-if="field.extension === 'PatientAgeCustom'">@{{ field.value }}</span>
                                          <span ng-if="field.extension === 'AgeBracket'">@{{ getDisplayValue(ageRanges,field.value) }}</span>
                                          <span ng-if="field.extension === 'Gender'">@{{ getDisplayValue(genders, field.value) }}</span>
                                          <span ng-if="field.extension === 'PatientEthnicity'">@{{ getDisplayValue(ethnicities, field.value) }}</span>
                                          <span ng-if="field.extension === 'PhysicalHarm'">@{{ getDisplayValue(physicalHarmLevels, field.value) }}</span>
                                          <span ng-if="field.extension === 'PsychologicalHarm'">@{{ getDisplayValue(psychologicalHarmLevels, field.value) }}</span>
                                          <span ng-if="field.extension === 'StrengthOfAssociation'">@{{ getDisplayValue(outcomeRanges,field.value) }}</span>
                                          <span ng-if="field.extension === 'GenderSameAsAtBirth'">@{{ getDisplayValue(genderIdentity,field.value) }}</span>
                                          <span ng-if="!(field.extension === 'PatientAgeCustom' || field.extension === 'AgeBracket' || field.extension === 'Gender' || field.extension === 'PatientEthnicity' || field.extension === 'PhysicalHarm' || field.extension === 'PsychologicalHarm' || field.extension === 'GenderSameAsAtBirth' || field.extension === 'StrengthOfAssociation')">@{{ field.value }}</span>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
  
                      <div ng-if="stage.is_event_summary_type || showTaskSummary " class="mb-4">
                          <div class="row">
                              <div class="task-link col-9 cursor-pointer" ng-click="open_form()">@{{form.main_section == undefined || form.main_section == '' ? "Mandatory Questions" : form.main_section}}</div>
                              <div class="col-3"><div class="completed-status">Completed</div></div>
                          </div>
                          <div ng-if="www_field == false && task.show"  ng-repeat="(taskId, task) in form.task_list.tasks track by taskId">
                              <div class="row task-row" ng-click="open_task(taskId)" >
                                  <div class="task-link col-9 cursor-pointer" >@{{form.task_list.tasks[taskId].title}}</div>
                                  <div class="col-3">
                                      <div class="completed-status" ng-if="form.task_list.tasks[taskId].complete == false">Click here to complete</div>
                                      <div class="completed-status" ng-if="form.task_list.tasks[taskId].complete">Completed</div>
                                  </div>
                              </div>
                          </div>
                          <div ng-if="www_field && event_type == 1" ng-repeat="code in getCombinedValues(www_field.input.value) track by $index">
                              <div class="row task-row" ng-click="open_task(code)">
                                  <div class="task-link col-9 cursor-pointer" >@{{form.task_list.tasks[code].title}}</div>
                                  <div class="col-3">
                                      <div class="completed-status" ng-if="form.task_list.tasks[code].complete == false">Click here to complete</div>
                                      <div class="completed-status" ng-if="form.task_list.tasks[code].complete">Completed</div>
                                  </div>
                              </div>
                          </div>
                          <div ng-if="www_field && event_type == 1">
                              <div class="row task-row" ng-click="open_task(20)">
                                  <div class="task-link col-9 cursor-pointer" >Learning points and further details</div>
                                  <div class="col-3">
                                      <div class="completed-status" ng-if="form.task_list.tasks[20].complete == false">Click here to complete</div>
                                      <div class="completed-status" ng-if="form.task_list.tasks[20].complete">Completed</div>
                                  </div>
                              </div>
                          <hr />
                          </div>
                      </div>
                      <div ng-show="stage.is_patient_summary_type ">
                          <table class="table">
                              <tbody>
                                  <tr ng-repeat="p in form.patients track by $index">
                                      <td>Patient @{{$index + 1}}</td>
                                      <td>@{{p.sex}}</td>
                                      <td>@{{p.age}}</td>
                                      <td><a href="javascript:void(0)" ng-click="update_patient($index)">Update</a></td>
                                      <td><a href="javascript:void(0)" ng-click="form.patients.splice($index,1);">Remove</a></td>
                                  </tr>
                              </tbody>
                          </table>
                          <h3>Was another patient involved in the incident?</h3>
                          <p>If the event you are recording affects 10 or more patients, please record only the single most severe actual or anticipated harm here, and provide fuller details of the event's impact within the free text field labelled <a href="javascript:void(0)">"Describe what happened"</a></p>
                          <input type="radio" name="has_more_patients" id="has_more_patients_yes" class="form-check-input" value="yes" ng-model="add_more_patient"><label for="has_more_patients_yes" class="ms-2"> Yes</label><br />
                          <input type="radio" name="has_more_patients" id="has_more_patients_no" class="form-check-input" value="no" ng-model="add_more_patient"><label for="has_more_patients_no" class="ms-2"> No</label>
                          <div class="preview-error" ng-show="add_more_patient_error">Please select an option before continuing.</div>
                      </div>
                  </div>
                  <p class="preview-error" ng-if="page_required">@{{error_msg}}</p>
                  <div class="text-center d-flex align-items-center justify-content-center gap-2" >
                      <a class="primary-btn" style="text-decoration: none; width: fit-content;display: inline;padding-block: 5px;" ng-if="selected_stage > 0 && selected_stage < stages.length - 1 || form_type =='task'" ng-click="navigate_page(selected_stage - 1); $event.stopPropagation(); $event.preventDefault();" href="javascript:void(0)">
                          <i class="fa-solid fa-arrow-left"></i> Back</a>
                      <button style="background-color: @{{form.next_btn_color}} !important;display: inline-block;"  ng-disabled="doNotClick" class="primary-btn"
                      ng-if="toggle_next_btn"
                          ng-click="navigate_page(selected_stage + 1); $event.stopPropagation(); $event.preventDefault();">@{{form.next_btn_text}} <i class="fa-solid fa-arrow-right"></i></button>
                      <button style="background-color: @{{form.submit_btn_color}} !important;display: inline-block;" ng-disabled="doNotClick" class="primary-btn" ng-if="toggle_submit_btn"  ng-click="submit_form()">@{{form.submit_btn_text}}</button>
                      <button class="primary-btn" style="display: inline-block;" ng-click="goToSummary()" ng-if="summary_active && form.task_list && form.task_list.tasks?.length > 0" >Summary</button>
                  </div>
              </div>
  
          </div>
  
      </div>
  
  
      <!-- Modal -->
      <div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg ">
              <div class="modal-content">
                  <div class="">
                      <!-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> -->
                      <div class="modal-title">
                          <input type="text" class="search-input form-control"
                              placeholder="Find questions, input fields and layout options...">
                      </div>
                      <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-sm-4 input-blocks-pickup-section custom-height">
                              <div><strong>Input blocks</strong></div>
                              <div class="element" data-bs-dismiss="modal"
                                  ng-click="add_field(new_field_space,input_block_type)"
                                  ng-repeat="input_block_type in input_block_types track by $index">
                                  <i class="@{{input_block_type.type_icon}}"></i> @{{input_block_type.type_label}}
                              </div>
                              <div ng-if="new_field_space.parent().type == 'stage'">
                                  <div><strong>Layout blocks</strong></div>
                                  <div class="element" data-bs-dismiss="modal" ng-click="add_group(new_field_space)"
                                      ng-repeat="layout_block_type in layout_block_types track by $index">
                                      <i class="fa fa-plus"></i> @{{layout_block_type}}
                                  </div>
                              </div>
                          </div>
                          <div class="col-sm-8">
                              Examples are coming to this section soon!
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  
      <div id="draft-alert" class="alert alert-danger fade show w-75 mx-auto mt-3  position-fixed" style="max-width: 740px;transform: translateX(-50%);left: 50%;z-index: 2000;top: 10px;" role="alert" ng-show="UI.show_alert">
          <div class="d-flex align-items-center justify-content-between">
              <h4 class="alert-heading">Unsaved Changes!</h4>
              <button type="button" class="btn-close" ng-click="UI.show_alert = false" aria-label="Close"></button>
          </div>
          <p>It appears that you have unsaved changes in the form you are currently working on. If you navigate away from this page, your progress will be lost.</p>
          <hr>
          <p class="mb-0">Please select the appropriate action to proceed. Your data is valuable to us.</p>
          <div class="mt-2">
              <button class="btn btn-danger btn-sm" ng-click="closeTab()">Cancel</button>
              <button class="btn btn-success btn-sm" ng-click="UI.show_alert = false" >Continue</button>
              <button class="btn btn-warning btn-sm" ng-click="submit_draft_form()">Save as Draft</button>
          </div>
      </div>
  
      <div class="backdrop" ng-show="showModal" style="display: grid;place-items: center;background-color: rgba(0, 0, 0, 0.233);">
  
          <div class="cookies-card" >
              <p class="cookie-heading">@{{modalTitle}}</p>
              <p class="cookie-para">
                @{{modalDescription}}
              </p>
              <div class="button-wrapper">
                <button ng-click="cancelTimeout()" class="accept cookie-button">Stay</button>
                <button ng-click="changeWindow()" class="reject cookie-button">Leave</button>
              </div>
              <button class="exit-button" ng-click="hideModal()">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 162 162"
                  class="svgIconCross"
                >
                  <path
                    stroke-linecap="round"
                    stroke-width="17"
                    stroke="black"
                    d="M9.01074 8.98926L153.021 153"
                  ></path>
                  <path
                    stroke-linecap="round"
                    stroke-width="17"
                    stroke="black"
                    d="M9.01074 153L153.021 8.98926"
                  ></path>
                </svg>
              </button>
            </div>
      </div>
  
  
      <div class="backdrop" ng-show="UserInfoShow" style="display: grid;place-items: center;background-color: rgba(0, 0, 0, 0.233);">
  
          <div class="cookies-card" >
              <p class="cookie-heading">@{{UserInfoTitle}}</p>
              <p class="cookie-para new" ng-bind-html="UserInfo">
              </p>
              <div class="button-wrapper">
                <button ng-click="hideUserInfo()" class="reject cookie-button">Okay</button>
              </div>
              <button class="exit-button" ng-click="hideUserInfo()">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 162 162"
                  class="svgIconCross"
                >
                  <path
                    stroke-linecap="round"
                    stroke-width="17"
                    stroke="black"
                    d="M9.01074 8.98926L153.021 153"
                  ></path>
                  <path
                    stroke-linecap="round"
                    stroke-width="17"
                    stroke="black"
                    d="M9.01074 153L153.021 8.98926"
                  ></path>
                </svg>
              </button>
            </div>
      </div>
  
      <div id="logout-warning" ng-if="showWarning">
          <p>You will be logged out in @{{ countdown }} seconds...</p>
      </div>
  </div>

    
    
    <script>
        const form_json = @json($task->form_json);

        var app = angular.module("BespokeFormApp", [
            "ngIntlTelInput",
            'ngSanitize',
            'ui.select',
            'vsGoogleAutocomplete',
            'tooltips'
            ]);

        app.controller('MyController', [
  "$scope",
  "$timeout",
  '$interval',
  function ($scope, $timeout, $interval)  {
            console.log(form_json,'asdf')
            $scope.message = "Hello from AngularJS!";
            $scope.report = true;
            $scope.getAllPages = function() {
    const globalPages = $scope.form.pages || [];

    // Collect all task pages into a single array
    let taskPages = [];
    if ($scope.form.task_list && $scope.form.task_list.tasks) {
        for (const taskId in $scope.form.task_list.tasks) {
            if ($scope.form.task_list.tasks.hasOwnProperty(taskId)) {
                const task = $scope.form.task_list.tasks[taskId];
                taskPages = taskPages.concat(task.pages || []);
            }
        }
    }

    // Merge global pages and task pages
    return globalPages.concat(taskPages);
};
           

            $scope.load_json_file = function (c) {
            let preview_json = c;
            const result = undefined;
            if (preview_json) {
            $scope.form_type_external = result?.data.form_type;
            $scope.location_logo = result?.data.location_logo ?? undefined;
            $scope.stages = []
            const form = JSON.parse(preview_json);
    
            $scope.form = new Form();
            $scope.form.patients = []; // additional data for muliptle set of questions for patients
            $scope.patient_index = 0;
    
            $scope.form.id = form.id;
            $scope.form.title = form.title;
            $scope.form.ods = result?.data.ods ?? 'NHS England Z45';
            $scope.ods_value = result?.data.ods_value ?? null;
            $scope.form.today = form.today && !isNaN(new Date(form.today).getTime()) ? new Date(form.today) : new Date();
            $scope.form.show_progress = form.show_progress ?? false;
            $scope.form.fill_bar_color = form.fill_bar_color ?? '#68bb55';
            $scope.form.next_btn_text = form.next_btn_text ?? "Next";
            $scope.form.next_btn_color = form.next_btn_color ?? "#72C4BA";
            $scope.form.submit_btn_text = form.submit_btn_text ?? "Submit";
            $scope.form.submit_btn_color = form.submit_btn_color ?? "#72C4BA";
            $scope.form.font_size = form.font_size ?? 34;
            $scope.form.text_color = form.text_color ?? '#000000';
            $scope.form.bg_color = form.bg_color ?? '#ffffff';
            $scope.form.priority_value = form.priority_value ?? 0;
            $scope.form.approval_required = form.approval_required ?? false;
            $scope.form.approval_not_required = form.approval_not_required ?? false;
            $scope.form.forms_trigger = form.forms_trigger ?? [];
            if(form.save_location){
              $scope.form.save_location = form.save_location;
            }
            if(form.involvements != undefined && form.involvements.length > 0){
              $scope.Involvements = form.involvements.map(inv => {
                  return new Involvement(inv, $scope); 
              });
              
          }

            if(form.task_list)
            {
    
              $scope.form.task_list = form.task_list;
              let all_tasks = form.task_list.tasks;
              for (code in all_tasks)
              {
                let task = all_tasks[code];
                if(task && task.show == undefined){
                  task.show = false;
                }
                let task_pages = task.pages;
                // Process data
                let formatted_task_pages = [];
                for (let i = 0; i < task_pages.length; i++) {
                  const stage_obj = task_pages[i];
    
                  let stage = new Page(stage_obj,$scope);
                  formatted_task_pages.push(stage);
                }
                task.pages = formatted_task_pages;
              }
              $scope.form.task_list.tasks = all_tasks;
    
            }

            if($scope.form.task_list?.tasks && Object.keys($scope.form.task_list.tasks).length > 0){
              $scope.form.main_section = form.main_section ?? 'Mandatory Questions';
          }

    
            $scope.form.pages = [];
            ////////////////////////
    
            $scope.form_title = form.title;
            const stages_array = form.pages;
            // Process data
            for (let i = 0; i < stages_array.length; i++) {
              const stage_obj = stages_array[i];
    
              let stage = new Page(stage_obj,$scope);
              $scope.stages.push(stage);
            }
            
            
    
            //find Involved Agents Question//
            for(let kk=0;kk<$scope.stages.length;kk++)
            {
              let tstage = $scope.stages[kk];
              if(tstage.is_nhs_page)
              {
                for(let i=0;i<tstage.items.length;i++)
                {
                  let item = tstage.items[i];
                  if(item.nhs_extension_url && item.nhs_extension_url == 'InvolvedAgents')
                  {
                      $scope.www_field = item;
                      break;
                  }
                }
              }
              if($scope.www_field)
                break;
            }

            //sort for paging// you may also need to sort each Section and Field too !
            $scope.stages.sort((a, b) => {
              return a.order - b.order;
            });
            $scope.form.pages = $scope.stages;
            global_pages = $scope.getAllPages();
            
    
            $scope.stage = $scope.stages[$scope.selected_stage];
            
            $timeout(function() {
              console.log('DOM is fully rendered. Now you can interact with the DOM.');
              // UIService.UI.loading = false;
          }, 0);

          }
                };

        $scope.load_json_file(form_json)

        $scope.getRemoteOptions = function (query, remoteUrl) {
            var params = { q: query };
            return [];
        };
        }]);
    
        // Additional capabilities
        function insertElement(arr, index, element) {
  arr.splice(index, 0, element);
}

function case_to_natural(str) {
  let ret = str
    .split("_")
    .filter((x) => x.length > 0)
    .map((x) => x.charAt(0).toUpperCase() + x.slice(1))
    .join(" ");
  return ret;
}

function make_space(order, collection) {
  for (let i = 0; i < collection.length; i++) {
    if (collection[i].order > order) collection[i].order++;
  }
}

function textAreaAdjust(element) {
  element.style.height = "1px";
  element.style.height = element.scrollHeight + "px";
}

var auto_increment = -1;
var tracker = 1;
var global_pages = false;
var www_field = false;
var triggered_forms_to_fillin = {};

function find_by_id(collection, id) {
  for (let i = 0; i < collection.length; i++) {
    let c = collection[i];
    if (c.id == id) return c;
  }
}
function find_section_by_id(id) {
  if (global_pages) {
    for (let i = 0; i < global_pages.length; i++) {
      let page = global_pages[i];
      for (let j = 0; j < page.items.length; j++) {
        let item = page.items[j];
        if (item.type == "section") {
          if (item.id == id) return item;
        }
      }
    }
  }
}

function find_page_by_id(id) {
  if (global_pages) {
    for (let i = 0; i < global_pages.length; i++) {
      let page = global_pages[i];
      if (page.id == id) return page;
    }
  }
}

function find_page_by_question_id(question_id) {
  if (global_pages) {
    for (let i = 0; i < global_pages.length; i++) {
      let page = global_pages[i];
      if (page.items) {
        for (let j = 0; j < page.items.length; j++) {
          let question = page.items[j];
          if (question.id == question_id) {
            return question;
          }
        }
      }
    }
  }
  return null; // Return null if no matching question is found
}

function all_fields (){
  return [].concat(...global_pages.pages.map(page=>page.items));
}

function prevSubmission(email) {
  var $injector = angular.injector(['ng']);
  
  var $http = $injector.get('$http');

  $http({
    method: 'GET',
    url: '/api/check_submission/'+email,
  }).then(function(response) {
    return response.data;
  }).catch(function(error) {
    console.error('Error:', error);
  });
}


function navigate_to_description_page(){
  let scope = angular.element(document.getElementById('preview-controller-div')).scope();
  scope.navigate_to_description_page();
}

function getScope(){
  let all =  angular.element(document.getElementById('preview-controller-div')).scope();
  return all;
}
    </script>

</div>