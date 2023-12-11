<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Shop\ShopStatusEnum;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Requests\UploadShopLogoRequest;
use App\Http\Resources\ShopCollection;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
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
        if ($request->q) {
            $shops = $shops->where('title', 'LIKE', '%' . $request->q . '%');
        }
        if ($request->status) {
            $shops = $shops->where('status', $request->status);
        }
        if ($request->date) {
            $shops = $shops->whereDate('created_at', $request->date);
        }

        $shops = $shops->paginate($request->per_page ?? 5);

        return Response::message('shop.messages.shop_list_found_successfully')
            ->data(new ShopCollection($shops))
            ->send();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {
        $shop = auth()->user()->shops()->create($request->merge(['owner_id' => auth()->user()->id])->all());

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

        $result = $shop->update($request->merge(['owner_id' => auth()->user()->id])->all());
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

        $path = 'shop/logo/';
        $fileName = $logo->hashName() . '.' . $logo->extension();

        if (Storage::putFileAs($path, $logo, $fileName) and $shop->update(['logo' => $path . '/' . $fileName])) {
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

    public function active(Shop $shop)
    {
        $shop->update([
            'status' => ShopStatusEnum::ACTIVE->value,
        ]);
        return Response::message('general.messages.successfull')
            ->data(new ShopResource($shop))
            ->send();
    }

    public function deactive(Shop $shop)
    {
        $shop->update([
            'status' => ShopStatusEnum::DEACTIVE->value,
        ]);
        return Response::message('general.messages.successfull')
            ->data(new ShopResource($shop))
            ->send();
    }
}
