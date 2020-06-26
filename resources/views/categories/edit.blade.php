@extends('layouts.global')
@section('title') POS | Edit Category @endsection
@section('pageTitle') Edit Category @endsection
@section('content')
    @if (session('success'))
        @component("components.alert", ['type' => 'success'])
            @slot('fa')
            check
            @endslot
            {!! session('success') !!}
        @endcomponent
    @endif
    @if (session('error'))
        @component("components.alert", ['type' => 'danger'])
            @slot('fa')
            ban
            @endslot
            {!! session('error') !!}
        @endcomponent
    @endif
    <!-- row -->
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse ($categories as $row)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->description }}</td>
                                <td>
                                    <a href="{{ route('categories.edit', $row->id) }}"
                                        class="btn bg-gradient-warning btn-xs">Edit</a>
                                    <button type="button" class="btn bg-gradient-danger btn-xs" data-toggle="modal"
                                        data-target="#modal-delete-category-{{ $row->id }}">Delete</button>
                                </td>
                            </tr>
                            <div class="modal fade" id="modal-delete-category-{{ $row->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Delete Category</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete the category <b>{{ $row->name }}</b> ?</p>
                                            <form class="d-inline" action="{{ route('categories.destroy', [$row->id]) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="submit" value="Delete" class="btn btn-danger btn-sm"></form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">There is no data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-6">
            <!-- Horizontal Form -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Category</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('categories.update', $category->id) }}">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="category" class="col-sm-3 col-form-label">Category</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $category->name }}"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name"
                                    name="name" placeholder="Category Name" required autocomplete="off">
                                <div class="invalid-feedback"> {{$errors->first('name')}} </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="desc" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <textarea value="{{ $category->description }}"
                                    class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" id="desc"
                                    name="desc" placeholder="Description" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn bg-gradient-primary float-right">Update</button>
                        <a href="{{ route('categories.index') }}" class="btn bg-gradient-secondary">Cancel</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

