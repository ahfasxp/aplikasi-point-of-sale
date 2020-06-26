@extends('layouts.global')
@section('title') POS | Edit Product @endsection
@section('pageTitle') Edit Product @endsection
@section('content')
    @if (session('error'))
        @component("components.alert", ['type' => 'danger'])
            @slot('fa') 
            ban
            @endslot
            {!! session('error') !!}
        @endcomponent
    @endif
    <!-- general form elements -->
    <div class="card card-primary">
        <!-- form start -->
        <form role="form" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="card-body">
                <div class="form-group">
                    <label for="code">Product Code</label>
                    <input readonly type="text" class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                        value="{{ $product->code }}" id="code" name="code" placeholder="Enter code" required
                        autocomplete="off">
                    <div class="invalid-feedback">{{ $errors->first('code') }}</div>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ $product->name }}" id="name" name="name" placeholder="Enter name" required
                        autocomplete="off" maxlength="60">
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                </div>
                <div class="form-group">
                    <label for="desc">Description</label>
                    <textarea class="form-control {{ $errors->has('desc') ? 'is-invalid' : '' }}" rows="4" id="desc"
                        name="desc" placeholder="Enter description" required
                        autocomplete="off">{{ $product->description }}</textarea>
                    <div class="invalid-feedback">{{ $errors->first('desc') }}</div>
                </div>
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}"
                        value="{{ $product->stock }}" id="stock" name="stock" placeholder="Enter stock" required
                        autocomplete="off">
                    <div class="invalid-feedback">{{ $errors->first('stock') }}</div>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                        value="{{ $product->price }}" id="price" name="price" placeholder="Enter price" required
                        autocomplete="off">
                    <div class="invalid-feedback">{{ $errors->first('price') }}</div>
                </div>
                <!-- select -->
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category"
                        required>
                        <option value="" disable>Select</option>
                        @foreach($categories as $categories)
                        <option value="{{ $categories->id }}"
                            {{ $categories->id == $product->category_id ? 'selected' : '' }}>
                            {{ ucfirst($categories->name) }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">{{ $errors->first('category') }}</div>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile">Photo</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="photo" name="photo">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                        <div class="invalid-feedback">{{ $errors->first('photo') }}</div>
                    </div>
                    <br>
                    @if ($product->photo)
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" width="150px"
                        height="150px">
                    @endif
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <!-- /.card -->
@endsection
@section('footerScript')
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        bsCustomFileInput.init();
    });

</script>
@endsection
