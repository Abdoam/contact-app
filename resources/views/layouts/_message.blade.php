@if ($message = session('message'))
    <div class="alert alert-success my-2">{{ $message }}</div>
@endif
