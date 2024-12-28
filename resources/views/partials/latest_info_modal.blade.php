<!-- Latest info Modal -->
<div class="modal fade" id="latestInfoModal" tabindex="-1" aria-labelledby="latestInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="latestInfoModalLabel">{{ __('home.latest_info_records') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <table id="modalInfoTable" class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('home.no') }}</th>
                            <th>{{ __('home.title') }}</th>
                            <th>{{ __('home.date') }}</th>
                            <th>{{ __('home.description') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modalInfo as $key => $info)
                            <tr>
                                <td>{{$loop->iteration }}</td>
                                <td>{{ $info->title }}</td>
                                <td>{{ $info->date }}</td>
                                <td>{{ $info->description ?? '-' }}</td>
                            </tr>
                        @empty

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#modalInfoTable').DataTable({
            responsive: true,
            autoWidth: false,
            paging: false,
            searching: false,
            ordering: true,
            language: {
                emptyTable: "{{ __('home.no_records_found') }}",
                url: "{{ app()->getLocale() === 'ms' ? 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/ms.json' : 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/en-GB.json' }}"
            }
        });
    });
</script>

