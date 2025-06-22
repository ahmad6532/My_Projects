
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Saved Settings</th>
                        <th>Color</th>
                        <th>Logo</th>
                        <th>Background Logo</th>
                        <th>Font</th>
                        <th>Bespoke Forms</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($head_office_organisation_settings) <= 0) <tr>
                        <td colspan="7" class="font-italic">No locations are assigned to this head office.</td>
                        </tr>
                        @else
                        @foreach($head_office_organisation_settings as $head_office_organisation_setting)
                        <tr>
                            <td>{{$head_office_organisation_setting->name}}</td>
                            <td>
                                <span class="bg-color-tile" style="background-color: {{$head_office_organisation_setting->bg_color_code}}"></span>
                            </td>
                            <td>
                                {!! $head_office_organisation_setting->organisation_setting_logo() !!}
                            </td>
                            <td>
                                {!! $head_office_organisation_setting->organisation_setting_bg_logo() !!}
                            </td>
                            <td>
                                {!! $head_office_organisation_setting->font !!}
                            </td>
                            <td>
                                {{count($head_office_organisation_setting->organisationSettingBespokeForms)}}
                            </td>
                            <td>
                                <div class="dropdown mb-4">
                                    <form method="POST"
                                        action="{!! route('organisation_settings.organisation_setting.delete', $head_office_organisation_setting->id) !!}"
                                        accept-charset="UTF-8">
                                        @csrf
                                        <input name="_method" value="DELETE" type="hidden">
                                        <button class="no-arrow btn btn-outline-cirlce dropdown-toggle" type="button"
                                            id="dropdownMenuButton{{$head_office_organisation_setting->id}}"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu animated--fade-in"
                                            aria-labelledby="dropdownMenuButton{{$head_office_organisation_setting->id}}">
                                            <a href="{{route('organisation_settings.organisation_setting.edit',$head_office_organisation_setting->id)}}"
                                                class="dropdown-item" title="Edit Details">Edit Details</a>
                                            <button onclick="return confirm(&quot;Click Ok to delete $head_office_organisation_setting->name.&quot;)"
                                                class="dropdown-item" title="Delete Organisation Setting">Delete</button>
                                        </div>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                </tbody>
            </table>
            