@props(['type' => 'success', 'message' => null, 'errors' => null])

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

@if ($errors && $errors->any())
    <div style="
        border: 1px solid red;
        color: red;
        padding: 10px;
        margin: 10px 0;
    ">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif