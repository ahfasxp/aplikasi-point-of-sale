@extends('layouts.global')
@section('title') POS | Categories @endsection
@section('pageTitle') Management Categories @endsection
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
                    <h3 class="card-title">Add a New Permission</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('users.add_permission') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}"
                                required>
                            <p class="text-danger">{{ $errors->first('name') }}</p>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm">
                                Add New
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-6">
            <!-- Horizontal Form -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Set Permission to Role</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('users.roles_permission') }}" method="GET">
                        @csrf
                        <div class="form-group">
                            <label for="">Roles</label>
                            <div class="input-group">
                                <select name="role" class="form-control">
                                    @foreach ($roles as $value)
                                    <option value="{{ $value }}" {{ request()->get('role') == $value ? 'selected':'' }}>
                                        {{ $value }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-danger">Check!</button>
                                </span>
                            </div>
                        </div>
                    </form>
                    {{-- jika $permission tidak bernilai kosong --}}
                    @if (!empty($permissions))
                    <form action="{{ route('users.setRolePermission', request()->get('role')) }}" method="post">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">Permissions</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        @php $no = 1; @endphp
                                        @foreach ($permissions as $key => $row)
                                        <input type="checkbox" name="permission[]" class="minimal-red" value="{{ $row }}"
                                            {{--  CHECK, JIKA PERMISSION TERSEBUT SUDAH DI SET, MAKA CHECKED --}}
                                            {{ in_array($row, $hasPermission) ? 'checked':'' }}> {{ $row }} <br>
                                        @if ($no++%4 == 0)
                                        <br>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm">
                                <i class="fa fa-send"></i> Set Permission
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
@section('footerScript')
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>
@endsection
