@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Service Import</h5>

        <form action="{{ route('service.data.import') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-sm-12">
                    <div class="custom-file">
                        <input type="file" id="service-upload" onchange="checkFileInput('service')" class="custom-file-input" name="file">
                        <label for="file" id="service-label" class="custom-file-label">Excel File</label>
                    </div>
                </div>

                <div class="form-group col-sm-12">
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
        </form>

        <hr>

        <h5 class="card-title">Item Import</h5>

        <form action="{{ route('item.data.import') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-sm-12">
                    <div class="custom-file">
                        <input type="file" id="item-upload" onchange="checkFileInput('item')" class="custom-file-input" name="file">
                        <label for="file" id="item-label" class="custom-file-label">Excel File</label>
                    </div>
                </div>

                <div class="form-group col-sm-12">
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    <script>
        function checkFileInput(inputId) {
            var i = $('#'+inputId+'-label').clone();
            var file = $('#'+inputId+'-upload')[0].files[0].name;
            $('#'+inputId+'-label').text(file);
        }
    </script>
@endpush
