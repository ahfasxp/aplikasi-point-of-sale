<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Order;
use App\Order_detail;
use App\Customer;
use App\User;
use Carbon\Carbon;
use Cookie;
use DB;
use PDF;
use App\Exports\OrderInvoice;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        //MENGAMBIL DATA CUSTOMER
        $customers = Customer::orderBy('name', 'ASC')->get();
        //MENGAMBIL DATA USER YANG MEMILIKI ROLE KASIR
        $users = User::role(['kasir', 'admin'])->orderBy('name', 'ASC')->get();
        //MENGAMBIL DATA TRANSAKSI
        $orders = Order::orderBy('created_at', 'DESC')->with('order_detail', 'customer');

        //JIKA PELANGGAN DIPILIH PADA COMBOBOX
        if (!empty($request->customer_id)) {
            //MAKA DITAMBAHKAN WHERE CONDITION
            $orders = $orders->where('customer_id', $request->customer_id);
        }
    
        //JIKA USER / KASIR DIPILIH PADA COMBOBOX
        if (!empty($request->user_id)) {
            //MAKA DITAMBAHKAN WHERE CONDITION
            $orders = $orders->where('user_id', $request->user_id);
        }
    
        //JIKA START DATE & END DATE TERISI
        if (!empty($request->start_date) && !empty($request->end_date)) {
            //MAKA DI VALIDASI DIMANA FORMATNYA HARUS DATE
            $this->validate($request, [
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date'
            ]);
            
            //START & END DATE DI RE-FORMAT MENJADI Y-m-d H:i:s
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d') . ' 00:00:01';
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d') . ' 23:59:59';
    
            //DITAMBAHKAN WHEREBETWEEN CONDITION UNTUK MENGAMBIL DATA DENGAN RANGE
            $orders = $orders->whereBetween('created_at', [$start_date, $end_date])->get();
        } else {
            //JIKA START DATE & END DATE KOSONG, MAKA DI-LOAD 10 DATA TERBARU
            $orders = $orders->take(10)->skip(0)->get();
        }
    
        //MENAMPILKAN KE VIEW
        return view('orders.index', [
            'orders' => $orders,
            'sold' => $this->countItem($orders),
            'total' => $this->countTotal($orders),
            'total_customer' => $this->countCustomer($orders),
            'customers' => $customers,
            'users' => $users
        ]);
    }

    private function countCustomer($orders)
    {
        $customer = [];

        if($orders->count() > 0){
            foreach($orders as $row){
                $customer[] = $row->customer->email;
            }
        }

        return count(array_unique($customer));
    }

    private function countTotal($orders)
    {
        //DEFAULT TOTAL BERNILAI 0
        $total = 0;
        //JIKA DATA ADA
        if ($orders->count() > 0) {
            //MENGAMBIL VALUE DARI TOTAL -> PLUCK() AKAN MENGUBAHNYA MENJADI ARRAY
            $sub_total = $orders->pluck('total')->all();
            //KEMUDIAN DATA YANG ADA DIDALAM ARRAY DIJUMLAHKAN
            $total = array_sum($sub_total);
        }
        return $total;
    }

    private function countItem($order)
    {
        //DEFAULT DATA 0
        $data = 0;
        //JIKA DATA TERSEDIA
        if ($order->count() > 0) {
            //DI-LOOPING
            foreach ($order as $row) {
                //UNTUK MENGAMBIL QTY 
                $qty = $row->order_detail->pluck('qty')->all();
                //KEMUDIAN QTY DIJUMLAHKAN
                $val = array_sum($qty);
                $data += $val;
            }
        } 
        return $data;
    }

    public function invoicePdf($invoice)
    {
        //MENGAMBIL DATA TRANSAKSI BERDASARKAN INVOICE
        $order = Order::where('invoice', $invoice)
                ->with('customer', 'order_detail', 'order_detail.product')->first();
        //SET CONFIG PDF MENGGUNAKAN FONT SANS-SERIF
        //DENGAN ME-LOAD VIEW INVOICE.BLADE.PHP
        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif'])
            ->loadView('orders.report.invoice', compact('order'));
        return $pdf->stream();
    }

    public function invoiceExcel($invoice)
    {
        return (new OrderInvoice($invoice))->download('invoice-' . $invoice . '.xlsx');
    }
    
    public function addOrder()
    {
        $products = Product::orderBy('created_at', 'DESC')->get();
        return view('orders.add', compact('products'));
    }

    public function getProduct($id)
    {
        $products = Product::findOrFail($id);
        return response()->json($products, 200);
    }

    public function addToCart(Request $request)
    {
        //validasi data yang diterima
        //dari ajax request addToCart mengirimkan product_id dan qty
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer'
        ]);

    
        //mengambil data product berdasarkan id
        $product = Product::findOrFail($request->product_id);
        //mengambil cookie cart dengan $request->cookie('cart')
        $getCart = json_decode($request->cookie('cart'), true);

        //jika datanya ada
        if ($getCart) {
            //jika key nya exists berdasarkan product_id
            if (array_key_exists($request->product_id, $getCart)) {
                //jumlahkan qty barangnya
                $getCart[$request->product_id]['qty'] += $request->qty;
                //dikirim kembali untuk disimpan ke cookie
                return response()->json($getCart, 200)
                    ->cookie('cart', json_encode($getCart), 120);
            } 
        }

    
        //jika cart kosong, maka tambahkan cart baru
        $getCart[$request->product_id] = [
            'code' => $product->code,
            'name' => $product->name,
            'price' => $product->price,
            'qty' => $request->qty
        ];
        //kirim responsenya kemudian simpan ke cookie
        return response()->json($getCart, 200)
            ->cookie('cart', json_encode($getCart), 120);
    }

    public function getCart()
    {
        //mengambil cart dari cookie
        $cart = json_decode(request()->cookie('cart'), true);
        //mengirimkan kembali dalam bentuk json untuk ditampilkan dengan vuejs
        return response()->json($cart, 200);
    }
    
    public function removeCart($id)
    {
        $cart = json_decode(request()->cookie('cart'), true);
        //menghapus cart berdasarkan product_id
        unset($cart[$id]);
        //cart diperbaharui
        return response()->json($cart, 200)->cookie('cart', json_encode($cart), 120);
    }

    public function checkout()
    {
        return view('orders.checkout');
    }

    public function storeOrder(Request $request)
    {
        //validasi 
        $this->validate($request, [
            'email' => 'required|email',
            'name' => 'required|string|max:100',
            'address' => 'required',
            'phone' => 'required|numeric'
        ]);

    
        //mengambil list cart dari cookie
        $cart = json_decode($request->cookie('cart'), true);
        //memanipulasi array untuk menciptakan key baru yakni result dari hasil perkalian price * qty
        $result = collect($cart)->map(function($value) {
            return [
                'code' => $value['code'],
                'name' => $value['name'],
                'qty' => $value['qty'],
                'price' => $value['price'],
                'result' => $value['price'] * $value['qty']
            ];
        })->all();

    
        //database transaction
        DB::beginTransaction();
        try {
            //menyimpan data ke table customers
            $customer = Customer::firstOrCreate([
                'email' => $request->email
            ], [
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone
            ]);

    
            //menyimpan data ke table orders
            $order = Order::create([
                'invoice' => $this->generateInvoice(),
                'customer_id' => $customer->id,
                'user_id' => auth()->user()->id,
                'total' => array_sum(array_column($result, 'result'))
                //array_sum untuk menjumlahkan value dari result
            ]);

    
            //looping cart untuk disimpan ke table order_details
            foreach ($result as $key => $row) {
                $order->order_detail()->create([
                    'product_id' => $key,
                    'qty' => $row['qty'],
                    'price' => $row['price']
                ]);
            }
            //apabila tidak terjadi error, penyimpanan diverifikasi
            DB::commit();

    
            //me-return status dan message berupa code invoice, dan menghapus cookie
            return response()->json([
                'status' => 'success',
                'message' => $order->invoice,
            ], 200)->cookie(Cookie::forget('cart'));
        } catch (\Exception $e) {
            //jika ada error, maka akan dirollback sehingga tidak ada data yang tersimpan 
            DB::rollback();
            //pesan gagal akan di-return
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function generateInvoice()
    {
        //mengambil data dari table orders
        $order = Order::orderBy('created_at', 'DESC');
        //jika sudah terdapat records
        if ($order->count() > 0) {
            //mengambil data pertama yang sdh dishort DESC
            $order = $order->first();
            //explode invoice untuk mendapatkan angkanya
            $explode = explode('-', $order->invoice);
            //angka dari hasil explode di +1
            return "INV-" . ($explode[1] + 1);
        }
        //jika belum terdapat records maka akan me-return INV-1
        return 'INV-1';
    }
}
