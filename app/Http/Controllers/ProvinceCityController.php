<?php

namespace App\Http\Controllers;

use App\Enums\ProvincesCities\ProvincesCitiesTypeEnum;
use App\Facades\Response;
use App\Http\Resources\ProvinceCityCollection;
use App\Models\ProvinceCity;

class ProvinceCityController extends Controller
{
    public function provincesList()
    {
        $provincesList = ProvinceCity::where('type', ProvincesCitiesTypeEnum::PROVINCE)->get();
        return Response::message('general.messages.successfull')->data(new ProvinceCityCollection($provincesList))->send();
    }

    public function citiesList(ProvinceCity $province)
    {
        $citiesList = ProvinceCity::where('type', ProvincesCitiesTypeEnum::CITY)
            ->where('parent_id', $province->id)
            ->get();
        return Response::message('general.messages.successfull')->data(new ProvinceCityCollection($citiesList))->send();
    }
}
