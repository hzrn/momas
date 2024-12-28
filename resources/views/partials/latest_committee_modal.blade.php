<!-- Latest committee Modal -->
<div class="modal fade" id="latestCommitteeModal" tabindex="-1" aria-labelledby="latestCommitteeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="latestCommitteeModalLabel">{{ __('home.latest_committee_records') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <table id="modalCommitteeTable" class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('home.no') }}</th>
                            <th>{{ __('home.name') }}</th>
                            <th>{{ __('home.phone_num') }}</th>
                            <th>{{ __('home.position') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modalCommittee as $key => $committee)
                            <tr>
                                <td>{{$loop->iteration }}</td>
                                <td>{{ $committee->name }}</td>
                                <td>{{ $committee->phone_num }}</td>
                                <td>{{ $committee->position }}</td>
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
        $('#modalCommitteeTable').DataTable({
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

