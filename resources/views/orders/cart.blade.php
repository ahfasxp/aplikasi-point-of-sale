<div class="col-6">
    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Cart List</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- MENGGUNAKAN LOOPING VUEJS -->
                    <tr v-for="(row, index) in shoppingCart">
                        <td>@{{ row.name }} (@{{ row.code }})</td>
                        <td>@{{ row.price | currency }}</td>
                        <td>@{{ row.qty }}</td>
                        <td>
                            <!-- EVENT ONCLICK UNTUK MENGHAPUS CART -->
                            <button @click.prevent="removeCart(index)" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer text-muted">
            @if(Request::path() == 'transaction')
            <a href="{{ route('orders.checkout') }}" class="btn btn-info float-right">
                Checkout
            </a>
            @else
            <a href="{{ route('orders.transaction') }}" class="btn btn-secondary btn-sm float-right">
                Back
            </a>
            @endif
        </div>
        <!-- /.card-footer -->
    </div>
    <!-- /.card -->
</div>
