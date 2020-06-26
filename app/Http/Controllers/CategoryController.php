<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at', 'DESC')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        //Validasi
        $this->validate($request, [
            'name' => 'required|string|max:20|unique:categories',
            'desc' => 'nullable|string'
        ]);

        try {
            $categories = Category::firstOrCreate([
                'name' => $request->name,
                'description' => $request->desc
            ]);
            return redirect()->back()->with(['success' => 'Data successfully added']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::orderBy('created_at', 'DESC')->get();
        return view('categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        //Validasi
        $this->validate($request, [
            'name' => 'required|string|max:20|unique:categories',
            'desc' => 'nullable|string'
        ]);

        try {
            //select data berdasarkan id
            $categories = Category::findOrFail($id);
            //update data
            $categories->update([
                'name' => $request->name,
                'description' => $request->description
            ]);
            
            //redirect ke route kategori.index
            return redirect(route('categories.index'))->with(['success' => 'Data edited successfully']);
        } catch (\Exception $e) {
            //jika gagal, redirect ke form yang sama lalu membuat flash message error
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            
            return redirect()->back()->with(['success' => 'Category ' .$category->name. ' successfully delete']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}