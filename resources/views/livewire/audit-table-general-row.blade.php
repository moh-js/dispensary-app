<tr>
    <td scope="row">{{ $sr }}</td>
    <td>
        <span class=" badge badge-{{ ${$audit->event} }}">
            {{ class_basename($audit->auditable_type) }} {{ $audit->event }}
        </span>
    </td>
    <td>{{ $audit->user->name??'' }}</td>
    <td>
        @empty(!$audit->new_values)
            @empty(!$audit->old_values)
                <strong>New Values:</strong>
                {{ implode(', ', $audit->new_values) }}
            @else
                <strong>Values:</strong>

                {{ implode(', ', $audit->new_values) }}
            @endempty

        @endempty

        @empty(!$audit->old_values)
            <strong>Old:</strong>
            {{ implode(', ', $audit->old_values) }}
        @endempty
    </td>
    <td>{{ $audit->created_at->format('d-m-Y H:i') }}</td>
</tr>
