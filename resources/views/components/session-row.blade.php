@props(['session', 'deviceInfo', 'isCurrentSession' => false])

<tr @class(['table-success bg-opacity-10' => $isCurrentSession])>
    <td>
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                <i data-lucide="{{ $deviceInfo['device_icon'] }}" class="text-primary"
                    style="width: 18px; height: 18px;"></i>
            </div>
            <div>
                <h6 class="mb-0">{{ $deviceInfo['device'] }}</h6>
                @if ($isCurrentSession)
                    <small class="text-success fw-semibold">Current Session</small>
                @endif
            </div>
        </div>
    </td>
    <td>
        <div class="d-flex align-items-center">
            <i data-lucide="{{ $deviceInfo['browser_icon'] }}" class="text-info me-2"
                style="width: 16px; height: 16px;"></i>
            {{ $deviceInfo['browser'] }}
        </div>
    </td>
    <td>
        <code class="bg-light px-2 py-1 rounded">{{ $session->ip_address }}</code>
    </td>
    <td>
        <div>
            <small class="fw-semibold">
                {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
            </small>
            <br>
            <small class="text-muted">
                {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->format('M d, Y H:i') }}
            </small>
        </div>
    </td>
    <td>
        @if (!$isCurrentSession)
            <button type="button" class="btn btn-outline-danger btn-sm logout_btn"
                data-url="{{ route('logout.session', $session->id) }}" data-bs-toggle="tooltip"
                title="Logout this session">
                <i data-lucide="log-out" style="width: 14px; height: 14px;"></i>
                Logout
            </button>
        @else
            <span class="badge bg-success">Active</span>
        @endif
    </td>
</tr>
