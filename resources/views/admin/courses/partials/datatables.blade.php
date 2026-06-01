@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.bootstrap5.css">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.6/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function () {
            $('.data-table').each(function () {
                if ($.fn.DataTable.isDataTable(this)) {
                    return;
                }

                $(this).DataTable({
                    paging: true,
                    searching: true,
                    info: true,
                    order: [],
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        emptyTable: 'No records found.'
                    }
                });
            });
        });
    </script>
@endpush
