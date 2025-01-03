@extends('layouts.app_adminkit')

@section('content')
<!-- Modal for Calendar -->
<div id="calendar-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Event Calendar') }}</h5>
            </div>
            <div class="modal-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<h1 class="h3 mb-3">{{ $title }}</h1>

<!-- Action Buttons -->
<a href="{{ route('info.create') }}" class="btn btn-primary mb-3">{{ __('info.add') }}</a>
<a href="{{ route('info.exportPDF', request()->all()) }}" class="btn btn-secondary mb-3">{{ __('info.export_pdf') }}</a>
<a href="#" id="show-calendar-btn" class="btn btn-info mb-3">{{ __('Show Calendar') }}</a>

<!-- Information Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive p-2">
                    <table id="info-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('info.no') }}</th>
                                <th>{{ __('info.title') }}</th>
                                <th>{{ __('info.category') }}</th>
                                <th>{{ __('info.date') }}</th>
                                <th>{{ __('info.description') }}</th>
                                <th>{{ __('info.photo') }}</th>
                                <th>{{ __('info.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($info as $item)
                            @php
                                // Format the date
                                $formattedDate = \Carbon\Carbon::parse($item->date)->format('d/m/Y h:i A');

                                // Generate the WhatsApp message
                                $message = __('info.title') . ": {$item->title}\n" .
                                           __('info.date') . ": $formattedDate\n" .
                                           __('info.description') . ": " . strip_tags($item->description ?? __('info.no_description'));

                                // Create the WhatsApp link
                                $whatsappLink = "https://wa.me/send?text=" . urlencode($message);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->category->name ?? 'N/A' }}</td>
                                <td>{!! $formattedDate !!}</td>
                                <td>{!! nl2br(e($item->description ?? '-')) !!}</td>
                                <td>
                                    @if($item->photo)
                                        <img src="{{ $item->photo }}" alt="{{ __('info.photo') }}" width="50" height="50">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('info.show', $item->id) }}" class="btn btn-secondary mb-1">{{ __('info.details') }}</a>
                                    <a href="{{ route('info.edit', $item->id) }}" class="btn btn-warning mb-1">{{ __('info.edit') }}</a>
                                    <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-success mb-1">
                                        <i class="bi bi-whatsapp pe-1"></i>{{ __('info.share_whatsapp') }}
                                    </a>
                                    <form action="{{ route('info.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger mb-1">{{ __('info.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    #calendar-modal .modal-dialog {
        max-width: 90%;
        margin: 1.75rem auto;
    }

    #calendar {
        height: 80%;
        min-height: 350px;
        max-width: 100%;
        margin: 0 auto;
    }

    .dataTables_length,
    .dataTables_filter {
        margin-top: 3px;
        margin-bottom: 3px;
    }

</style>

<!-- FullCalendar Styles -->
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.8/index.global.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.8/index.global.min.css" rel="stylesheet" />

<!-- FullCalendar Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.8/index.global.min.js"></script>


<!-- FullCalendar Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Default view
        headerToolbar: {
            start: 'today prev,next', // Left-aligned buttons
            center: 'title', // Centered title
            end: 'dayGridMonth,timeGridWeek,timeGridDay', // Right-aligned buttons
        },
        height: 'auto', // Adjust calendar height automatically
        events: '{{ route('info.calendar') }}', // Fetch events dynamically

        // Add popover to show additional event details
        eventDidMount: function (info) {
            // Attach Bootstrap popover
            const popover = new bootstrap.Popover(info.el, {
                title: info.event.title, // Event title
                placement: 'auto', // Auto placement of the popover
                trigger: 'hover', // Trigger on hover
                content: `
                    <p><strong>Description:</strong> ${info.event.extendedProps.description || 'No description available'}</p>
                    <p><strong>Date:</strong> ${info.event.start.toLocaleDateString()}</p>
                    <p><strong>Time:</strong> ${info.event.extendedProps.time || ''}</p>
                `,
                html: true, // Allow HTML content
            });

            // Show popover on hover
            info.el.addEventListener('mouseenter', function () {
                popover.show();
            });

            // Hide popover when mouse leaves
            info.el.addEventListener('mouseleave', function () {
                popover.hide();
            });
        },
    });

    calendar.render();

    // Show Modal and Render Calendar
    document.getElementById('show-calendar-btn').addEventListener('click', function (e) {
        e.preventDefault();
        const calendarModal = document.getElementById('calendar-modal');
        $(calendarModal).modal('show');

        $(calendarModal).on('shown.bs.modal', function () {
            calendar.render();
        });
    });
});


</script>

<!-- DataTable Script -->
<script>
$(document).ready(function () {
    $('#info-table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "responsive": true,
        columnDefs: [
            { orderable: false, targets: [5, 6] }
        ],
        "language": {
            "url": "{{ app()->getLocale() === 'ms' ? 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/ms.json' : 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/en-GB.json' }}"
        }
    });
});
</script>
@endsection
