<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Requests\UploadShopLogoRequest;
use App\Http\Resources\ShopCollection;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $shops = auth()->user()->shops()->paginate(50);

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        //
    }

    public function logo(UploadShopLogoRequest $request, Shop $shop)
    {
        if (auth()->id() != $shop->owner_id) {
            throw new UnauthorizedException();
        }

        $logo = $request->file('logo');

        $uri = 'shop\\logo\\' . $logo->hashName();

        if (Storage::put($uri, $logo) and $shop->update(['logo' => $uri])) {
            return Response::message('shop.messages.shop_logo_successfuly_uploaded')
                ->data(new ShopResource($shop))
                ->send();
        } else {
            return Response::message('shop.messages.shop_logo_successfuly_uploaded')
                ->status(422)
                ->data(new ShopResource($shop))
                ->send();
        }
    }
}
