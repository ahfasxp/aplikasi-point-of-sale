@extends('layouts.global')
@section('title') POS | Users @endsection
@section('pageTitle') Management Users @endsection
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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <button type="button" class="btn btn-sm bg-gradient-primary" data-toggle="modal"
                            data-target="#modal-add-user">Add a New User <i class="fas fa-plus-circle"></i></button>
                    </h3>
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
                    <table class="table table-head-fixed text-nowrap projects">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <ul class="list-inline">
                                        <li class="list-inline-item">
                                            @if($user->avatar)
                                            <img alt="Avatar" class="table-avatar"
                                                src="{{asset('storage/'.$user->avatar)}}">
                                            @else
                                            N/A
                                            @endif
                                            <b class="ml-1">{{ $user->name }}</b>
                                        </li>
                                    </ul>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    @foreach ($user->getRoleNames() as $role)
                                    <label for="" class="badge badge-info">{{ $role }}</label>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('users.roles', $user->id) }}" class="btn bg-gradient-info btn-xs"><i
                                            class="fa fa-user-secret"></i>Role</a>
                                    <button type="button" class="btn bg-gradient-warning btn-xs" data-toggle="modal"
                                        data-target="#modal-edit-user-{{$user->id}}">Edit</button>
                                    <button type="button" class="btn bg-gradient-danger btn-xs" data-toggle="modal"
                                        data-target="#modal-delete-user-{{$user->id}}">Delete</button>
                                </td>
                                <div class="modal fade" id="modal-delete-user-{{$user->id}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Delete User</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete the user <b>{{ $user->name }}</b> ?</p>
                                                <form class="d-inline" action="{{ route('users.destroy', [$user->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                                                </form>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                                <div class="modal fade" id="modal-edit-user-{{ $user->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit User</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- form start -->
                                                <form role="form" enctype="multipart/form-data"
                                                    action="{{ route('users.update', [$user->id]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" value="PUT" name="_method">
                                                    <div class="form-group">
                                                        <label for="name">Name</label>
                                                        <input type="text" class="form-control" name="e-name"
                                                            value="{{ $user->name }}" required autocomplete="off"
                                                            placeholder="Enter Name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control" name="e-email"
                                                            value="{{ $user->email }}" required autocomplete="off"
                                                            placeholder="Enter Email">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phone">Phone</label>
                                                        <input type="tel" class="form-control" name="e-phone"
                                                            value="{{ $user->phone }}" required autocomplete="off"
                                                            placeholder="Enter Phone">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Avatar Image (optional)</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" name="e-avatar" class="custom-file-input"
                                                                    id="exampleInputFile">
                                                                <label class="custom-file-label"
                                                                    for="exampleInputFile">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
    <div class="modal fade" id="modal-add-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- form start -->
                    <form role="form" enctype="multipart/form-data" action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="c-name" required autocomplete="off"
                                placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                name="email" required autocomplete="off" placeholder="Enter Email">
                            <div class="invalid-feedback"> {{$errors->first('email')}} </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="c-password" required
                                autocomplete="new-password" minlength="6" maxlength="10" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Avatar Image (optional)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="c-avatar" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
@section('footerScript')
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        bsCustomFileInput.init();
    });

</script>
@endsection
