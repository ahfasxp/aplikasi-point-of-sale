@extends('layouts.global')
@section('title') POS | Transaction @endsection
@section('pageTitle') Transaction @endsection
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
    <div class="row" id="dw">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="addToCart" method="post">
                        <!-- select -->
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Product</label>
                            <div class="col-sm-10">
                                <select name="product_id" v-model="cart.product_id" id="product_id" class="form-control"
                                    required width="100%">
                                    <option value="">Pilih</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Qty</label>
                            <div class="col-sm-6">
                                <input type="number" v-model="cart.qty" class="form-control" value="1" min="1" id="qty"
                                    name="qty" placeholder="Enter qty" required autocomplete="off">
                            </div>
                            <div class="col-sm-4">
                                <button class="btn btn-primary btn-flat" :disabled="submitCart">
                                    <i class="fas fa-cart-plus"></i>
                                    @{{ submitCart ? 'Loading...' : 'Add to Cart' }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <div v-if="product.name">
                        <h4>Detail Product</h4>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="col-12">
                                    <img :src="'storage/' + product.photo" class="product-image" alt="Product Image">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <h4 class="my-3">@{{ product.name }}</h4>
                                <p>Code : [@{{ product.code }}]</p>
                                <hr>
                                <div class="bg-gray py-2 px-3 mt-4">
                                    <h3 class="mb-0">
                                        @{{ product.price | currency }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
        @include('orders.cart')
    </div>
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
<!-- Select2 and Accounting -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>
<script src="{{ asset('js/transaksi.js') }}"></script>
@endsection
