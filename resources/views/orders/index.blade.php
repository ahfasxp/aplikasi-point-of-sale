@extends('layouts.global')
@section('title')
    POS | Management Orders
@endsection
@section('pageTitle')
    Management Orders
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <!-- form start -->
                        <form role="form" action="{{ route('orders.index') }}" method="get">
                        @csrf
                            <div class="form-group">
                                <label for="">Start Date</label>
                                <input type="text" id="start_date"
                                    class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                                    name="start_date" placeholder="Enter date"
                                    value="{{ request()->get('start_date') }}">
                            </div>
                            <div class="form-group">
                                <label for="">End Date</label>
                                <input type="text" id="end_date"
                                    class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
                                    name="end_date" placeholder="End date" value="{{ request()->get('end_date') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Pelanggan</label>
                            <select name="customer_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($customers as $cust)
                                <option value="{{ $cust->id }}"
                                    {{ request()->get('customer_id') == $cust->id ? 'selected':'' }}>
                                    {{ $cust->name }} - {{ $cust->email }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Kasir</label>
                            <select name="user_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ request()->get('user_id') == $user->id ? 'selected':'' }}>
                                    {{ $user->name }} - {{ $user->email }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-4 col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Item Sold</span>
                <span class="info-box-number">{{ $sold }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-4 col-md-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-friends"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Omset</span>
                <span class="info-box-number">Rp {{ number_format($total) }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-4 col-md-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tags"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Customers</span>
                <span class="info-box-number">{{ $total_customer }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.card-header -->
<div class="card">
    <div class="card-body">
        <table id="example1" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Customers</th>
                    <th>Phone</th>
                    <th>Total Shopping</th>
                    <th>Cashier</th>
                    <th>Date Transaction</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $row)
                <tr>
                    <td><strong>#{{ $row->invoice }}</strong></td>
                    <td>{{ $row->customer->name }}</td>
                    <td>{{ $row->customer->phone }}</td>
                    <td>Rp {{ number_format($row->total) }}</td>
                    <td>{{ $row->user->name }}</td>
                    <td>{{ $row->created_at->format('d-m-Y H:i:s') }}</td>
                    <td>
                        <a href="{{ route('order.pdf', $row->invoice) }}" target="_blank"
                            class="btn btn-primary btn-sm">
                            <i class="fa fa-print"></i>
                        </a>
                        <a href="{{ route('order.excel', $row->invoice) }}" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-file-excel"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="7">Tidak ada data transaksi</td>
                </tr>
                @endforelse
        </table>
    </div>
</div>
<!-- /.card-body -->
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

    $('#start_date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
​
    $('#end_date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
</script>  
@endsection
