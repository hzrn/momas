<!-- Latest Income Modal -->
<div class="modal fade" id="latestIncomeModal" tabindex="-1" aria-labelledby="latestIncomeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="latestIncomeModalLabel">{{ __('home.latest_income_records') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <table id="modalIncomeTable" class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('home.no') }}</th>
                            <th>{{ __('home.date') }}</th>
                            <th>{{ __('home.description') }}</th>
                            <th>{{ __('home.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modalIncome as $key => $income)
                            <tr>
                                <td>{{$loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($income->date)->format('Y/m/d') }}</td>
                                <td>{{ $income->description }}</td>
                                <td>{{ formatRM($income->amount) }}</td>
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
        $('#modalIncomeTable').DataTable({
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
