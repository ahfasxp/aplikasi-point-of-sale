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
                <form action="{{ route('users.set_role', $user->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <td>:</td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>:</td>
                                    <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>:</td>
                                    <td>
                                        @foreach ($roles as $row)
                                        <input type="radio" name="role" {{ $user->hasRole($row) ? 'checked':'' }}
                                            value="{{ $row }}"> {{  $row }} <br>
                                        @endforeach
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm float-right">
                            Set Role
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
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
