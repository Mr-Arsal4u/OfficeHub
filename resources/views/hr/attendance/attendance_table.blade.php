@forelse ($employees as $employee)
    <tr>
        @php
            if (isset($date)) {
                $attendance = $employee->attendances->where('date', $date)->first();
            } else {
                $attendance = $employee->attendances->where('date', now()->toDateString())->first();
            }
        @endphp
        <td>{{ $employee->first_name ?? '' }} {{ $employee->last_name ?? '' }}
        </td>
        <td>
            @if (isset($date) && $date != null)
                {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}
            @else
                {{ \Carbon\Carbon::today()->format('Y-m-d') }}
            @endif
        </td>
        <td>
            @if ($employee->hasRole('admin'))
                <span class="badge bg-secondary">Admin - Read Only</span>
            @else
                <select class="form-control status-dropdown" data-employee-id="{{ $employee->id }}">
                    <option value="">-- Select --</option>
                    <option value="present" {{ isset($attendance) && $attendance->status == 'present' ? 'selected' : '' }}>
                        Present</option>
                    <option value="absent" {{ isset($attendance) && $attendance->status == 'absent' ? 'selected' : '' }}>
                        Absent</option>
                    <option value="leave" {{ isset($attendance) && $attendance->status == 'leave' ? 'selected' : '' }}>Leave
                    </option>
                </select>
            @endif
        </td>
        <td>
            @if ($employee->hasRole('admin'))
                @if ($attendance && $attendance->check_in_time)
                    <span>{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            @else
                @if ($attendance && $attendance->check_in_time)
                    <span>{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}</span>
                @else
                    <input type="time" class="form-control check-in-input" data-employee-id="{{ $employee->id }}">
                @endif
            @endif
        </td>
        <td>
            @if ($employee->hasRole('admin'))
                @if ($attendance && $attendance->check_out_time)
                    <span>{{ \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            @else
                @if ($attendance && $attendance->check_out_time)
                    <span>{{ \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') }}</span>
                @else
                    <input type="time" class="form-control check-out-input" data-employee-id="{{ $employee->id }}">
                @endif
            @endif
        </td>

    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center">No attendance records found.</td>
    </tr>
@endforelse
