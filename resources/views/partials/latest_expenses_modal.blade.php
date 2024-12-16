<!-- Latest expenses Modal -->
<div class="modal fade" id="latestExpensesModal" tabindex="-1" aria-labelledby="latestExpensesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="latestExpensesModalLabel">{{ __('home.latest_expenses_records') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <table id="modalExpensesTable" class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('home.no') }}</th>
                            <th>{{ __('home.date') }}</th>
                            <th>{{ __('home.description') }}</th>
                            <th>{{ __('home.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modalExpenses as $key => $expenses)
                            <tr>
                                <td>{{$loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($expenses->date)->format('Y/m/d') }}</td>
                                <td>{{ $expenses->description }}</td>
                                <td>{{ formatRM($expenses->amount) }}</td>
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
        $('#modalExpensesTable').DataTable({
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
