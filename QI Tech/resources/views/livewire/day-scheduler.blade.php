<div>
    @foreach ($by_days as $day => $value)
        <div x-data="{ isOpen{{ $day }}: {{ $value['active'] ? 'true' : 'false' }} }">
            <div class="card "
                x-bind:style="{ 'border-color': isOpen{{ $day }} && {{ $value['active'] ? 'true' : 'false' }} ? '#2BAFA5' :
                        '' }">
                <div class="py-0 px-2 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="toggle-switch">
                            <input class="toggle-input" id="{{ $day }}-toggle" type="checkbox"
                                wire:click="toggleActive('{{ $day }}')" {{ $value['active'] ? 'checked' : '' }}>
                            <label class="toggle-label" for="{{ $day }}-toggle"></label>
                        </div>
                        <h4 class="h6 m-0">{{ $day }}</h4>
                    </div>

                    @if ($value['active'])
                        @foreach ($value['times'] as $index => $time)
                            <p x-show="!isOpen{{ $day }}" x-transition class="m-0"
                                style="font-size:14px;color:#b2b2b2;"> &#8259; {{ $time }}</p>
                        @endforeach
                    @endif

                    @if ($value['active'])
                    <div class="d-flex gap-2 align-items-center" >
                        <input data-toggle="tooltip" data-placement="top" title="Mandatory" wire:change="toggleMandatory('{{$day}}')" type="checkbox" class="form-check-input" @if($value['mandatory'] == true) checked @endif>
                        <p x-show="isOpen{{ $day }}" x-transition class="m-0 text-black" style="font-weight: 700;font-size:12px;">Mandatory</p>
                    </div>
                    @endif
                    @if ($value['active'])
                        <button type="button" class="btn ico-btn" style="font-size:12px;color:#2BAFA5;"
                            x-on:click="isOpen{{ $day }} = !isOpen{{ $day }}"><i class="fa-solid "
                                x-bind:class="{ 'fa-chevron-up': isOpen{{ $day }}, 'fa-chevron-down': !
                                        isOpen{{ $day }} }"></i></button>
                    @endif
                </div>
                @if ($value['active'])
                    <div class="card-body fade-in" x-bind:class="{ 'expand-height': !isOpen{{ $day }} }"
                        style="height: {{ (count($value['times']) * 43) + 107 }}px;">
                        @foreach ($value['times'] as $index => $time)
                        <div class="d-flex align-items-center justify-content-between day-input-wrapper">
                            <div>
                                @if ($loop->first)
                                <p class="mx-2" style="margin-bottom:5px !important;font-size: 12px;font-weight:bold;">Reminder</p>
                                @endif
                                <input class="form-control" type="time" style="width:9rem;"
                                    value="{{ $time }}"
                                    wire:blur='updateTime("{{ $day }}","{{ $index }}",$event.target.value)'>
                            </div>
                                <div class="d-flex align-items-center day-btn-wrapper">
                                    @if ($index == 0)
                                        <button wire:click="addNewTime('{{ $day }}')" type="button"
                                            class="btn d-flex"
                                            style="border: 2px solid #2BAFA5;border-radius:50%;color: #2BAFA5; padding:0.09rem;font-size:12px;"><i
                                                class="fa-solid fa-plus"></i></button>
                                    @else
                                        <button wire:click="removeTime('{{ $day }}','{{ $index }}')"
                                            type="button" class="btn ico-btn"
                                            style="font-size: 17px;color: #e52525;padding-right:0;"><i
                                                class="fa-regular fa-trash-can"></i></button>
                                    @endif
                                    <button type="button" class="btn ico-btn"
                                        style="font-size: 18px;color: #2BAFA5;"><i
                                            class="fa-regular fa-copy"></i></button>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-between day-input-wrapper align-items-end">
                            <div>
                                <p class="mx-2" style="margin-block:5px !important;font-size: 12px;font-weight:bold;">Cut Off</p>
                                <input class="form-control" type="time" style="width:9rem;"
                                    value="{{ $value['cutoff'] }}"
                                    wire:blur='UpdateCutOfftime("{{ $day }}",$event.target.value)'>
                            </div>
                            <div class="d-flex gap-2 align-items-center" style="margin-bottom: 0.7rem;">
                                <input wire:change="toggleDoNotSubmission('{{$day}}')" type="checkbox" class="form-check-input" @if($value['do_not_allow_submissions'] == true) checked @endif>
                                <p class="m-0 text-black" style="font-weight: 700;font-size:12px;">Do not allow submission after this time</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@push('scripts')
    <script>
        function changeBorderColor(element) {
            if (!$(element).prop('checked')) {
                $(element).closest('.card').css('border-color', '#999 !important');
            }
        }
    </script>
@endpush
