<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'DESC')->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string|max:10|unique:products',
            'name' => 'required|string|max:60',
            'desc' => 'required|string|max:100',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'category' => 'required|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg' 
        ]);

        try {
            $photo = NULL;

            if($request->file('photo')){
                $photo = $request->file('photo')->store('products', 'public');
            }

            $product = Product::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->desc,
                'stock' => $request->stock,
                'price' => $request->price,
                'category_id' => $request->category,
                'photo' => $photo
            ]);

            return redirect()->route('products.index')->with('success', '<strong>' . $product->name . '</strong> successfully added');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'required|string|max:10|exists:products,code',
            'name' => 'required|string|max:60',
            'desc' => 'required|string|max:100',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'category' => 'required|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg' 
        ]);

        try {
            $product = Product::findOrFail($id);
            $photo = $product->photo;
            if($request->file('photo')){                
                if($product->photo && file_exists(storage_path('app/public/' . $product->photo))){         
                    \Storage::delete('public/'.$product->photo);         
                }
                $photo = $request->file('photo')->store('products', 'public');
            }
            $product->update([
                'name' => $request->name,
                'description' => $request->desc,
                'stock' => $request->stock,
                'price' => $request->price,
                'category_id' => $request->category,
                'photo' => $photo
            ]);    
            
            return redirect()->route('products.index')->with('success', 'Product edited successfully');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if($product->photo && file_exists(storage_path('app/public/' . $product->photo))){
            \Storage::delete('public/' . $product->photo);
        }
        $product->delete();
        return redirect()->back()->with('success', $product->name . ' successfully delete');
    }
}
