<div style="display: grid; place-items: center;">

    <button id="toggleButton" type="button" class="btn ico-btn calender-btn">
        <i class="fa-solid fa-calendar-days px-2"></i> Open Calendar
    </button>

    <div id='calendar-container' style="display: none;" wire:ignore>
        <div class="calender-wrapper"id="calendar-wrapper">
            <div id='calendar'></div>
            <button id="close-calendar" style="margin-top: 10px;z-index:200;position: absolute;bottom:-60px;">Save Event</button>
        </div>  
    </div>
    
    <div class="calender-card-backdrop" style="display: {{ isset($event) ? 'grid' : 'none' }};">
        <div class="calender-card-wrapper" x-data="{ isOpen: {{ isset($event) && $event['active'] ? 'true' : 'false' }} }">
            <div class="card "
                x-bind:style="{
                    'border-color': isOpen && {{ isset($event) && $event['active'] ? 'true' : 'false' }} ?
                        '#2BAFA5' : ''
                }">
                @if (isset($event))
                    <div class="py-0 px-2 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="toggle-switch">
                                <input class="toggle-input" id="{{ $event->id }}-toggle" type="checkbox"
                                    wire:click="toggleActive('{{ $event->id }}')"
                                    {{ $event['active'] ? 'checked' : '' }}>
                                <label class="toggle-label" for="{{ $event->id }}-toggle"></label>
                            </div>
                            <h4 class="h6 m-0">{{ $event_title }}</h4>
                        </div>

                        @if ($event['active'])
                            @foreach (json_decode($event['times']) as $index => $time)
                                <p x-show="!isOpen" x-transition class="m-0" style="font-size:14px;color:#b2b2b2;">
                                    &#8259; {{ $time }}</p>
                            @endforeach
                        @endif

                        @if ($event['active'])
                            <button type="button" class="btn ico-btn" style="font-size:12px;color:#2BAFA5;"
                                x-on:click="isOpen = !isOpen"><i class="fa-solid "
                                    x-bind:class="{
                                        'fa-chevron-up': isOpen,
                                        'fa-chevron-down': !
                                            isOpen
                                    }"></i></button>
                        @endif
                    </div>
                    @if ($event['active'])
                        <div class="card-body fade-in" x-bind:class="{ 'expand-height': !isOpen }"
                            style="height: {{ count(json_decode($event['times'], true)) * 152 }}px;">
                            @foreach (json_decode($event['times']) as $index => $time)
                                <div class="d-flex align-items-center justify-content-between day-input-wrapper">
                                    <input class="form-control" type="time" style="width:9rem;"
                                        value="{{ $time }}"
                                        wire:blur='updateTime("{{ $event->id }}","{{ $index }}",$event.target.value)'>
                                    <div class="d-flex align-items-center day-btn-wrapper">
                                        @if ($index == 0)
                                            <button wire:click="addNewTime('{{ $event->id }}')" type="button"
                                                class="btn d-flex"
                                                style="border: 2px solid #2BAFA5;border-radius:50%;color: #2BAFA5; padding:0.09rem;font-size:12px;"><i
                                                    class="fa-solid fa-plus"></i></button>
                                        @else
                                            <button
                                                wire:click="removeTime('{{ $event->id }}','{{ $index }}')"
                                                type="button" class="btn ico-btn"
                                                style="font-size: 17px;color: #e52525;padding-right:0;border:0;"><i
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
                                        value="{{ $event['cutoff'] }}"
                                        wire:blur='UpdateCutOfftime("{{ $event->id }}",$event.target.value)'>
                                </div>
                                <div class="d-flex gap-2 align-items-center" style="margin-bottom: 0.7rem;">
                                    <input wire:change="toggleDoNotSubmission('{{$event->id}}')" type="checkbox" class="form-check-input" @if($event['do_not_allow_submissions'] == true) checked @endif>
                                    <p class="m-0 text-black" style="font-weight: 700;font-size:12px;">Do not allow submission after this time</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-footer text-muted d-flex gap-2 justify-content-around align-items-center"
                        style="padding-top: 0;">

                        <div>
                            <label for="" style="font-size: 12px;">Name</label>
                            <input class="form-control" type="text" style="width:9rem;"
                                wire:model.debounce="event_title" wire:blur='updateTitle({{ $event->id }})'>
                        </div>
                        <div class="d-flex flex-column align-items-start form-flex gap-1">
                            <div class="d-flex align-items-start gap-2">
                                <label for="" style="font-size: 12px;">Repeat</label>
                                <input class="form-check-input" id="repeat-toggle" type="checkbox"
                                    wire:click="repeatToggle('{{ $event->id }}', $event.target.checked)"
                                    {{ $event->repeat_state != 'off' ? 'checked' : '' }}>
                            </div>
                            <div class="select-wrapper gap-2 " style="width: 8rem;">
                                <select {{ $event->repeat_state == 'off' ? 'disabled' : '' }} id="repeat-select"
                                    class="form-control w-100 rounded-1" style="font-size:14px;"
                                    wire:change="repeatToggle('{{ $event->id }}', $event.target.value)">
                                    <option value="1" {{ $event->repeat_state == 'month' ? 'selected' : '' }}>
                                        Every Month</option>
                                    <option value="2" {{ $event->repeat_state == 'year' ? 'selected' : '' }}>Every
                                        Year</option>
                                </select>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <label for="" style="font-size: 12px;">Delete Event</label>
                            <button id="removeEvent" wire:click="removeEvent('{{ $event->id }}')" type="button"
                                class="btn ico-btn" style="color: #e52525;padding-right:0;border:0;"><i
                                    class="fa-regular fa-trash-can"></i></button>
                        </div>
                        <div class="d-flex flex-column">
                            <label for="" style="font-size: 12px;">Create Event</label>
                            <button id="createEvent" type="button"
                                class="btn ico-btn" style="color: #28a745; padding-right: 0; border: 0;" 
                                onclick="location.href = location.href;">
                                <i class="fa-solid fa-calendar"></i>
                            </button>
                        </div>
                        
                        
                        <script>
                            function closePopup() {
                                // Assuming you're using Bootstrap or any modal for the pop-up
                                var modal = document.querySelector('.modal'); // Replace '.modal' with the actual class or ID of the popup
                                if (modal) {
                                    modal.style.display = 'none'; // Close the modal
                                }
                            }
                        </script>
                        
                        
                    </div>
                @else
                    <div class="mx-auto mb-2">
                        <button id="addBtn" onclick="addNewEvent()" type="button" class="btn ico-btn calender-btn">
                            <i class="fa-solid fa-clock px-2"></i> Add Schedule
                        </button>
                    </div>

                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
    {{-- <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script> --}}
    {{-- <script src='https://code.jquery.com/jquery-3.6.4.min.js'></script> --}}

    <script>
        var newDate = '';
        var eventsArray = @json($events);
        var calendar;
        $(document).ready(function() {
            var calendarEl = document.getElementById('calendar');

            function initializeCalendar() {
                calendar = new Calendar(calendarEl, {
                    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek'
                    },
                    businessHours: true,
                    events: eventsArray,
                    eventOverlap: false,
                    eventDidMount: function(info) {
                        $(info.el).tooltip({
                            title: info.event.extendedProps.times.join(' - '),
                            placement: "top",
                            trigger: "hover",
                            container: "body"
                        });
                    },
                    eventClick: function(info) {
                        @this.call('editEvent', info.event.extendedProps.event_id);
                        $('.calender-card-backdrop').fadeIn();
                    },
                    dateClick: function(info) {
                        newDate = info.dateStr;
                        var selectedDate = newDate;
                        var eventsForDate = calendar.getEvents().filter(function(event) {
                            return moment(event.start).format('YYYY-MM-DD') === selectedDate;
                        });

                        if (eventsForDate.length > 0) {
                            @this.call('editEvent', eventsForDate[0].extendedProps.event_id);
                        }
                        $('.calender-card-backdrop').fadeIn();
                    }
                });
                calendar.render();
            }

            $('#toggleButton').on('click', function() {
                $('#calendar-container').toggle();
                if ($('#calendar-container').is(':visible')) {
                    initializeCalendar();
                }
            });

            Livewire.on('eventToggled', function(events) {
                eventsArray = events;
                initializeCalendar();
            })

            $('#calendar-container').on('click', function(event) {
                if (!$(event.target).closest('#calendar').length) {
                    $(this).hide();
                }
            });

            $('.calender-card-backdrop').on('click', function(event) {
                if (!$(event.target).closest('.card').length) {
                    $(this).fadeOut();
                    @this.call('resetEvent');
                }
            })



            $('#repeat-toggle').on('click', function() {
                console.log('out')
                if (!$(this).prop('checked')) {
                    console.log('out')
                    $('#repeat-select').attr('disabled', true);
                    console.log($('#repeat-select'))
                } else {
                    $('#repeat-select').removeAttr('disabled', false);
                }
            });


        });

        function addNewEvent() {
            @this.call('addEvent', newDate);
        }


        function CustomInfoElement(info) {
            var timesHtml = info.event.extendedProps.times.map(function(time) {
                return '<li>' + time + '</li>';
            }).join('');

            return {
                html: '<div class="fc-content">' +
                    '<h6 class="fc-title">' + info.event.title + '</h6>' +
                    '<ul class="fc-times">Times: ' + timesHtml + '</ul>' +
                    '</div>',
            };
        }    
    </script>
    {{-- script for CLOSE BUTTON AND RELOAD --}}
    <script>
    document.getElementById("close-calendar").addEventListener("click", function() {
    document.getElementById("calendar-wrapper").style.display = "none";
    location.reload();
    });
    </script>

{{-- script for close butonnnn --}}

<script>
    document.getElementById('close-calendar').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent any default action
        document.getElementById('calendar-container').style.display = 'none'; // Hide the calendar modal
    });
</script>
@endpush
