<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\Response;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsListRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductDiscount;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductsListRequest $request)
    {
        $productQuery = new Product();
        if ($request->validated('q')) {
            $productQuery = $productQuery->where('id', 'LIKE', '%' . $request->validated('q') . '%');
            $productQuery = $productQuery->orWhere('name', 'LIKE', '%' . $request->validated('q') . '%');
        }

        $products = $productQuery->paginate($request->per_page ?? 5);

        return Response::message('product.messages.rouduct_list_found_successfully')
            ->data(new ProductCollection($products))
            ->send();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreProductRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());

        foreach ($request->validated('product_discounts') as $discount) {
            $discountData = [
                'good_count_from' => $discount['good_count_from'],
                'good_count_to' => $discount['good_count_to'],
                'percent' => $discount['percent'],
                'price' => $discount['price'],
                'product_id' => $product->id
            ];
            ProductDiscount::create($discountData);
        }

        return Response::message('product.messages.product_successfuly_created')
            ->data(new ProductResource($product))
            ->send();
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Response::message('product.messages.product_successfuly_founded')
            ->data(new ProductResource($product))
            ->send();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        ProductDiscount::where('product_id', $product->id)->delete();

        foreach ($request->validated('product_discounts') as $discount) {
            $discountData = [
                'good_count_from' => $discount['good_count_from'],
                'good_count_to' => $discount['good_count_to'],
                'percent' => $discount['percent'],
                'price' => $discount['price'],
                'product_id' => $product->id
            ];
            ProductDiscount::create($discountData);
        }

        return Response::message('product.messages.product_successfuly_updated')
            ->data(new ProductResource($product))
            ->send();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return Response::message('product.messages.product_successfuly_deleted')->send();
    }
}
