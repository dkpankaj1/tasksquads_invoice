{{-- confirm delete model --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>


@push('pageScript')
    <script>
        $(document).ready(function () {
            let deleteUrl = '';
            // Open delete confirmation modal
            $(document).on('click', '.delete-btn', function () {
                deleteUrl = $(this).data('url');
                $('#deleteModal').modal('show');
            });
            
            // Confirm delete
            $('#confirmDelete').on('click', function () {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#deleteModal').modal('hide');
                        if (response.status === "success") {
                            $('#datatable').DataTable().ajax.reload(null, false);
                            toastr.success(response.message);
                        }
                        if (response.status === "error") {
                            toastr.error(response.message);
                        }
                    },
                    error: function () {
                        alert(
                            'An error occurred while trying to delete the classroom.'
                        );
                    }
                });
            });
        });
    </script>
@endpush