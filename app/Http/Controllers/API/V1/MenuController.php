<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Category;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    use ApiResponder;

    /**
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function menu(Request $request): JsonResponse
    {
        $menu = Category::orderBy("order")
            ->with("products")
            ->get();

        return $this->successResponse($menu);
    }
}
