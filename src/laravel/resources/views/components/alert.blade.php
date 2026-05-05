@props(['type' => 'success', 'message' => null])

@if ($message)
    <div
        style="
        border: 1px solid {{ $type === 'success' ? 'green' : 'red' }};
        color: {{ $type === 'success' ? 'green' : 'red' }};
        padding: 10px;
        margin: 10px 0;
    ">
        {{ $message }}
    </div>
@endif
