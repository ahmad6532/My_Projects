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
    
    <div ng-controller="MyController" id="preview-controller-div">
        
        <div ng-repeat="stage in form.pages | orderBy : 'order' track by stage.tracker" class="elements-area container mt-4 custom-scroll pb-4 pt-2">
          <h5 class="text-center">@{{stage.name}}</h5>
            <div ng-repeat="section in stage.items | orderBy : 'order' track by section.tracker">
                <div  class="group-item row">
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
        </div>
    </div>
    
    <script>
        const form_json = @json($case->link_case_with_form->raw_form);

        var app = angular.module("BespokeFormApp", [
            "ngIntlTelInput",
            'ngSanitize',
            'ui.select',
            'vsGoogleAutocomplete',
            'tooltips'
            ]);

        app.controller('MyController', function($scope) {
            console.log(form_json,'asdf')
            $scope.message = "Hello from AngularJS!";
            $scope.report = true;

           

            $scope.load_json_file = function (c) {
            let preview_json = c;
            if (preview_json) {
                let form = JSON.parse(preview_json);

                const stages_array = form.pages;
                console.log(form,'asdf');

                $scope.form = new Form();
                $scope.form.id = form.id;
                $scope.form.title = form.title;
                if (form.task_list) $scope.form.task_list = form.task_list;

                $scope.form.pages = [];

                // Process data
                for (let i = 0; i < stages_array.length; i++) {
                    let stage_obj = stages_array[i];

                    let stage = new Page(stage_obj);
                    stage.setForm($scope.form); // for now its manual !
                    $scope.form.pages.push(stage);
                }

                // UIService.showAlert("Form Imported Successfully!", "success");
            }
                };

        $scope.load_json_file(form_json)

        $scope.getRemoteOptions = function (query, remoteUrl) {
            var params = { q: query };
            return [];
        };
        });
    
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