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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Customer</h3>
                </div>
                <div class="card-body">
                    <!-- JIKA VALUE DARI message ada, maka alert success akan ditampilkan -->
                    <div v-if="message" class="alert alert-success">
                        Transaksi telah disimpan, Invoice: <strong>#@{{ message }}</strong>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" name="email" v-model="customer.email" class="form-control"
                            @keyup.enter.prevent="searchCustomer" required>
                        <p>Press enter to check email !!</p>
                        <!-- EVENT KETIKA TOMBOL ENTER DITEKAN, MAKA AKAN MEMANGGIL METHOD searchCustomer dari Vuejs -->
                    </div>
                    <!-- JIKA formCustomer BERNILAI TRUE, MAKA FORM AKAN DITAMPILKAN -->
                    <div v-if="formCustomer">
                        <div class="form-group">
                            <label for="">Customer Name</label>
                            <input type="text" name="name" v-model="customer.name" :disabled="resultStatus"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Address</label>
                            <textarea name="address" class="form-control" :disabled="resultStatus"
                                v-model="customer.address" cols="5" rows="5" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Phone</label>
                            <input type="text" name="phone" v-model="customer.phone" :disabled="resultStatus"
                                class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <!-- JIKA VALUE DARI errorMessage ada, maka alert danger akan ditampilkan -->
                    <div v-if="errorMessage" class="alert alert-danger">
                        @{{ errorMessage }}
                    </div>
                    <!-- JIKA TOMBOL DITEKAN MAKA AKAN MEMANGGIL METHOD sendOrder -->
                    <button class="btn btn-primary btn-sm float-right" :disabled="submitForm" @click.prevent="sendOrder">
                        @{{ submitForm ? 'Loading...':'Order Now' }}
                    </button>
                </div>
            </div>
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