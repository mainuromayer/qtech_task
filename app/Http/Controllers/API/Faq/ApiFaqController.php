<?php

namespace App\Http\Controllers\API\Faq;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ApiFaqController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);

        $paginator = Faq::orderBy('id', 'desc')->paginate($perPage);

        return $this->paginateResponse(
            FaqResource::collection($paginator),
            'FAQs fetched successfully'
        );
    }
}
