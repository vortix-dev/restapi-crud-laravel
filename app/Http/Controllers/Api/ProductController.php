<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductRessource;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        $products = Product::get();
        if($products->count()>0)
        {
            return ProductRessource::collection($products);
        }
        else
        {
            return response()->json(['message' => 'No record Available '], 200);
        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
                            'name' => 'required|string|max:255',
                            'description' => 'required',
                            'price' => 'required|integer',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'Please Fill All Fields',
                'error' => $validator->errors(),    
            ], 422);
        }
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        return response()->json([
            'message' => 'Product created Successfully',
            'data' => new ProductRessource($product)
        ]);
    }
    public function show(Product $product){
        return new ProductRessource($product);
    }
    public function update(Request $request, Product $product){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|integer',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'Please Fill All Fields',
                'error' => $validator->errors(),    
            ], 422);
        }
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        return response()->json([
            'message' => 'Product updated Successfully',
            'data' => new ProductRessource($product)
        ],200);
    }
    public function destroy(Product $product){
        $product->delete();
        return response()->json([
            'message' => 'Product deleted Successfully',
        ],200);
    }

    
    
}
