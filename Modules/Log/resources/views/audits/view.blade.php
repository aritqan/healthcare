<div class="card-body p-4">
    <!-- Metadata Section -->
    <div class="d-flex align-items-start mb-4">
        <div class="me-3">
            <i class="fa-solid fa-circle-info"></i>
        </div>
        <div>
            <span class="text-muted d-block mb-1">
                {{ $model->created_at->format('Y-m-d H:i:s') }}
            </span>
            <span class="text-dark fw-bold">
                @lang('log::strings.audit.metadata', array_merge($model->getMetadata(), [
                    'audit_created_at' => $model->created_at->format('Y-m-d H:i:s'),
                    'user_full_name '  => $model->user->full_name ?? 'N/A',
                    'audit_event'      => $model->event_format['label'],
                ]))
            </span>
        </div>
    </div>

    @if($model->event != 'deleted' && $model->event != 'restored')
        <!-- Modified Attributes Section -->
        @foreach ($model->getModified() as $attribute => $modified)
            <div class="alert alert-light border rounded-3 p-3 d-flex align-items-start mb-3">
                <div class="me-3">
                    <i class="fa-solid fa-pen text-primary"></i>
                </div>
                <div class="overflow-scroll">
                    <strong class="text-primary d-block">
                        @lang('log::strings.audit.modified', ['attribute' => $attribute])
                    </strong>
                    <p class="mb-0">
                        <span class="text-danger">
                            @php
                                $oldValue = $modified['old'] ?? null;

                                // Decode if the value is a JSON string
                                if (is_string($oldValue)) {
                                    $decoded = json_decode($oldValue, true);
                                    $oldValue = is_array($decoded) ? $decoded : $oldValue;
                                }
                            @endphp

                            @if (is_array($oldValue))
                                @foreach ($oldValue as $lang => $translations)
                                    @if (is_array($translations))
                                        <strong>{{ strtoupper($lang) }}:</strong>
                                        @foreach ($translations as $key => $value)
                                            <span>{{ $key }}: {{ $value ?? 'N/A' }}</span><br>
                                        @endforeach
                                    @else
                                        <span>{{ $lang . ':' . $translations }}</span><br>
                                    @endif
                                @endforeach
                            @else
                                {{ $oldValue === true ? 'true' : ($oldValue === false ? 'false' : ($oldValue ?? 'N/A')) }}
                            @endif
                        </span>
                        <i class="fa-solid fa-arrow-right mx-2 text-muted"></i>
                        <span class="text-success">
                            @php
                                $newValue = $modified['new'] ?? null;

                                // Decode if the value is a JSON string
                                if (is_string($newValue)) {
                                    $decoded = json_decode($newValue, true);
                                    $newValue = is_array($decoded) ? $decoded : $newValue;
                                }
                            @endphp

                            @if (is_array($newValue))
                                @foreach ($newValue as $lang => $translations)
                                    @if (is_array($translations))
                                        <strong>{{ strtoupper($lang) }}:</strong>
                                        @foreach ($translations as $key => $value)
                                            <span>{{ $key }}: {{ $value ?? 'N/A' }}</span><br>
                                        @endforeach
                                    @else
                                        <span>{{ $lang . ':' . $translations }}</span><br>
                                    @endif
                                @endforeach
                            @else
                                {{ $newValue === true ? 'true' : ($newValue === false ? 'false' : ($newValue ?? 'N/A')) }}
                            @endif
                        </span>
                    </p>
                </div>
            </div>
        @endforeach
    @endif
</div>
