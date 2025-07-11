<x-main>
    <x-slot:heading>Import
    </x-slot:heading>
{{--@section('content')--}}
    <div class="container">
        <h2>Import entries</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{route('import.process')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="file">Choose Excel File</label>
            <input type="file" name="file" id="file" class="form-control" required>
            <small class="form-text text-muted">Accepted: .xlsx, .xls, .csv | Max: 2MB</small>
    <button type="submit" class="btn btn-primary mt-3">Import</button>
        </form>
    </div>

{{--@endsection--}}
</x-main>