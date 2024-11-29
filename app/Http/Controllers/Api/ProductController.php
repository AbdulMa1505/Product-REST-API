<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductResource;
use PhpParser\Node\Expr\BinaryOp\Pow;

class ProductController extends Controller
{
    public function index(){
        $products =Product::get();
        if($products->count()>0){
            return ProductResource::collection($products);

        }
        else{
            return response()->json(['message'=>'No Record found',],200);
        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|string'
        ]);
            
        if ($validator->fails()) {
            return response()->json([
            'message' => 'All fields are required',
            'errors' => $validator->errors()->all(), 
        ], 422);
        }
        $product=Product::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price

        ]);
        return response()->json([
            'message'=>'created successfully',
            'data'=> new ProductResource ($product)

            
        ],200);
    }
   
   
    public function update(Request $request, Product $product)
    {
        // Validating input fields
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are required',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        // Updating the product
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product)
        ], 200);
    }
    public function destroy(Product $product ){
        $product->delete();
        return  response()->json([
            'message' => 'Product deleted successfully'
        ],200);
        
    }
}
