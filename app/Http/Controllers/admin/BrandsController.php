<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{

    public function index(Request $request)
    {
        $brands = Brand::latest('id');

        if ($request->get('keyword')) {
            $brands = $brands->where('name', 'like', '%' . $request->keyword . '%');
        }

        $brands = $brands->paginate(10);

        return view('admin.brands.list', compact('brands'));
    }

    public function create()
    {
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['brands'] = $brands;

        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required'
        ]);

        if ($validator->passes()) {

            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            session()->flash('success', 'Marca criada com sucesso!');

            return response()->json([
                'status' => true,
                'message' => 'Marca criada com sucesso!'
            ]);

        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $brand = Brand::find($id);

        if (empty($brand)) {
            session()->flash('error', 'Dados não encontrados.');
            return redirect()->route('brands.index');
        }

        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['brands'] = $brands;

        return view('admin.brands.edit', compact('brand', 'brands'));
    }


    public function update($id, Request $request)
    {
        $brand = Brand::find($id);

        if (empty($brand)) {

            session()->flash('error', 'Dados não encontrados.');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Marca não encontrada'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
            'status' => 'required'
        ]);

        if ($validator->passes()) {

            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            session()->flash('success', 'Marca atualizada com sucesso!');

            return response()->json([
                'status' => true,
                'message' => 'Marca salva com sucesso!'
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);

        if (empty($brand)) {

            session()->flash('error', 'Brand not found');

            return response()->json([
                'status' => true,
                'message' => 'Brand not found'
            ]);
        }

        $brand->delete();

        session()->flash('success', 'Marca deletada com sucesso!');

        return response()->json([
            'status' => true,
            'message' => 'Marca deletada com sucesso!'
        ]);
    }
}
