@extends('layouts.global')
@section('title') POS | Products @endsection
@section('pageTitle') Management Products @endsection
@section('content')
    @if (session('success'))
        @component("components.alert", ['type' => 'success'])
            @slot('fa')
            check
            @endslot
            {!! session('success') !!}
        @endcomponent
    @endif
    <div class="card">
        <div class="card-header">
            <a href="{{ route('products.create') }}" class="btn btn-sm bg-gradient-primary">Add a New Products <i
                    class="fas fa-plus-circle"></i></a>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
            <table id="example1" class="table text-nowrap table-hover">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div style="width:300px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">
                                @if($product->photo)
                                <img src="{{asset('storage/'.$product->photo)}}" width="50px" height="50px" alt="photo">
                                @else
                                <img src="http://via.placeholder.com/50x50" alt="{{ $product->photo }}">
                                @endif
                                <strong class="ml-2">{{ $product->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $product->stock }}</td>
                        <td>Rp. {{ number_format($product->price) }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->updated_at }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}"
                                class="btn bg-gradient-warning btn-xs">Edit</a>
                            <button type="button" class="btn bg-gradient-danger btn-xs" data-toggle="modal"
                                data-target="#modal-delete-product-{{ $product->id }}">Delete</button>
                        </td>
                        <div class="modal fade" id="modal-delete-product-{{ $product->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Delete Product</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete the product <b>{{ $product->name }}</b> ?</p>
                                        <form class="d-inline" action="{{ route('products.destroy', [$product->id]) }}"
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">There is no data</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>Product Name</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection
@section('footerScript')
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });

</script>
@endsection
