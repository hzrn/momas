<!-- Latest item Modal -->
<div class="modal fade" id="latestItemModal" tabindex="-1" aria-labelledby="latestItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="latestItemModalLabel">{{ __('home.latest_item_records') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <table id="modalItemTable" class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('home.no') }}</th>
                            <th>{{ __('home.name') }}</th>
                            <th>{{ __('home.description') }}</th>
                            <th>{{ __('home.quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modalItem as $key => $item)
                            <tr>
                                <td>{{$loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('home.no_records_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#modalItemTable').DataTable({
            responsive: true,
            autoWidth: false,
            paging: false,
            searching: false,
            ordering: true,
            language: {
                emptyTable: "{{ __('home.no_records_found') }}"
            }
        });
    });
</script>
