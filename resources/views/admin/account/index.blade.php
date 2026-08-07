<x-app-layout>
    <x-breadcrumbs :render="Breadcrumbs::render('account.index')" />

    <div class="card">
        <div class="card-body">

            <div class="align-items-center">
                <div class="d-flex align-items-center">
                    <div class="overflow-hidden ms-4">
                        <h4 class="m-0 text-dark fs-20">{{ $user->name }}</h4>
                        <p class="my-1 text-muted fs-16">{{ $user->email }}</p>
                    </div>
                </div>
                <hr>
                <a class="btn btn-primary" href="{{ route('account.update') }}">Edit Profile</a>
            </div>


        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <x-card title="Active Browser Sessions" class="sessions-card">
                <p class="text-muted mb-4">
                    Manage and monitor your active sessions across different devices and browsers.
                </p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Device</th>
                                <th>Browser</th>
                                <th>IP Address</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activeSession as $session)
                                @php
                                    $device = getDeviceInfo($session->user_agent);
                                    $isCurrentSession = $session->id === session()->getId();
                                @endphp
                                <x-session-row :session="$session" :device-info="$device" :is-current-session="$isCurrentSession" />
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i data-lucide="monitor" class="mb-2"
                                                style="width: 48px; height: 48px;"></i>
                                            <p>No active sessions found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($activeSession->count() > 1)
                    <div class="alert alert-info mt-3">
                        <div class="d-flex align-items-center">
                            <i data-lucide="info" class="me-2" style="width: 16px; height: 16px;"></i>
                            <div>
                                <strong>Security Notice:</strong>
                                You can logout other sessions if you suspect unauthorized access.
                            </div>
                        </div>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    <!-- Session Logout Confirmation Modal -->
    <div class="modal fade" id="session-logout-modal" tabindex="-1" aria-labelledby="sessionLogoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="sessionLogoutModalLabel">
                        <i data-lucide="log-out" class="me-2 text-danger" style="width: 20px; height: 20px;"></i>
                        Logout Browser Session
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="alert alert-warning d-flex align-items-center">
                        <i data-lucide="alert-triangle" class="me-2" style="width: 20px; height: 20px;"></i>
                        <div>
                            <strong>Confirm Action</strong><br>
                            <small>This will immediately logout the selected browser session. The user will need to
                                login again on that device.</small>
                        </div>
                    </div>

                    <p class="mb-4">Are you sure you want to logout this browser session?</p>

                    <form action="" method="POST" id="delete_session_form">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i data-lucide="x" class="me-1" style="width: 16px; height: 16px;"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i data-lucide="log-out" class="me-1" style="width: 16px; height: 16px;"></i>
                                Logout Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @push('pageScript')
        <script>
            $(document).ready(function() {
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Handle session logout
                $('.logout_btn').on('click', function() {
                    const sessionUrl = $(this).data('url');
                    if (sessionUrl) {
                        $('#delete_session_form').attr('action', sessionUrl);
                        $('#session-logout-modal').modal('show');
                    } else {
                        console.error('Session URL is undefined');
                    }
                });

                // Handle form submission with loading state
                $('#delete_session_form').on('submit', function() {
                    const submitBtn = $(this).find('button[type="submit"]');
                    submitBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-1"></span>Logging out...');
                });
            });
        </script>
    @endpush

</x-app-layout>
