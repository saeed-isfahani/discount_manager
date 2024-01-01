<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Shop\ShopStatusEnum;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ShopCountRequest;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Requests\UploadShopLogoRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ShopCollection;
use App\Http\Resources\ShopResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(PaginateRequest $request)
    {
        $shops = new Shop;
        if ($request->validated('q')) {
            $shops = $shops->where('title', 'LIKE', '%' . $request->validated('q') . '%');
        }
        if ($request->validated('status')) {
            $shops = $shops->where('status', $request->validated('status'));
        }
        if ($request->validated('start_date') and $request->validated('end_date')) {
            $shops = $shops->whereBetween('created_at', $request->validated('start_date'), $request->validated('end_date'));
        }

        $shops = $shops->paginate($request->validated('per_page') ?? 5);

        return Response::message('shop.messages.shop_list_found_successfully')
            ->data(new ShopCollection($shops))
            ->send();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {
        if ($request->validated('owner_id')) {
            $shopData = $request->validated();
        } else {
            $shopData = $request->merge(['owner_id' => auth()->user()->id])->validated();
        }
        $shop = auth()->user()->shops()->create($shopData);

        return Response::message('shop.messages.shop_successfuly_created')
            ->data(new ShopResource($shop))
            ->send();
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        return Response::message('general.messages.successfull')
            ->data(new ShopResource($shop))
            ->send();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request, Shop $shop)
    {
        if (auth()->id() != $shop->owner_id) {
            throw new UnauthorizedException();
        }

        if ($request->validated('owner_id')) {
            $shopData = $request->validated();
        } else {
            $shopData = $request->merge(['owner_id' => auth()->user()->id])->validated();
        }

        $result = $shop->update($shopData);
        if ($result) {
            return Response::message('shop.messages.shop_successfuly_updated')
                ->data(new ShopResource($shop))
                ->send();
        }

        return new BadRequestHttpException();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        $shop->delete();
        return Response::message('shop deleted successfully')->send();
    }

    public function logo(UploadShopLogoRequest $request, Shop $shop)
    {
        if (auth()->id() != $shop->owner_id) {
            throw new UnauthorizedException();
        }

        $logo = $request->file('logo');

        $basePath = 'public';
        $uri = 'shop/logo';
        $fileName = $logo->hashName();

        if (Storage::putFileAs($basePath . '/' . $uri, $logo, $fileName) and $shop->update(['logo' => $uri . '/' . $fileName])) {
            return Response::message('shop.messages.shop_logo_unsuccessfuly_uploaded')
                ->data(new ShopResource($shop))
                ->send();
        } else {
            return Response::message('shop.messages.shop_logo_successfuly_uploaded')
                ->status(422)
                ->data(new ShopResource($shop))
                ->send();
        }
    }

    public function activate(Shop $shop)
    {
        $shop->update([
            'status' => ShopStatusEnum::ACTIVE->value,
        ]);
        return Response::message('general.messages.successfull')
            ->data(new ShopResource($shop))
            ->send();
    }

    public function deactivate(Shop $shop)
    {
        $shop->update([
            'status' => ShopStatusEnum::DEACTIVE->value,
        ]);
        return Response::message('general.messages.successfull')
            ->data(new ShopResource($shop))
            ->send();
    }

    public function shopCount()
    {
        $shopModel = new Shop;

        $allShopsCount = $shopModel->count();
        $shopCountResult = $shopModel->select(DB::raw('status, COUNT(id) as cnt'))
            ->groupBy('status')
            ->get();

        $shopCountResult = $shopCountResult->push(['status' => 'all', 'cnt' => $allShopsCount]);

        return Response::message('general.messages.successfull')
            ->data($shopCountResult)
            ->send();
    }

    public function shopCountByCategory()
    {
        $shopCount = Category::leftJoin('shops', 'categories.id', '=', 'shops.category_id')
            ->select('categories.name as category_name', DB::raw('COUNT(shops.id) as shop_count'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        return Response::message('general.messages.successfull')
            ->data($shopCount)
            ->send();
    }

    public function shopCountByMonth()
    {
        $shopModel = new Shop;

        $shopCount = $shopModel->select(DB::raw('MONTHNAME(created_at) month, COUNT(id) as cnt'))
            ->groupBy('month')->get();

        return Response::message('general.messages.successfull')
            ->data($shopCount)
            ->send();
    }

    public function products(PaginateRequest $request, Shop $shop)
    {
        $products = Product::where('shop_id', $shop->id);

        if ($request->validated('q')) {
            $products = $products->where('name', 'LIKE', '%' . $request->validated('q') . '%');
        }

        return Response::message('general.messages.successfull')
            ->data(new ProductCollection($products->get()))
            ->send();
    }
}
