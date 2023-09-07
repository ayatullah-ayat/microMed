<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\ProductSearch;
use App\Models\Custom\CustomServiceProduct;

class SearchController extends Controller
{
    use ProductSearch;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query      = request('key');
        $orderBy    = request('order_by') ?? 'created_at';
        $totalItems = 0;
        $products   = $this->ecommerceProduct($query, $orderBy);

        if(count($products)){
            $totalItems += count($products);
        }

        $customProducts   = $this->customizeProduct($query);

        if (count($customProducts)) {
            $totalItems += count($customProducts);
        }

        if (request()->ajax()) {
            $products       = $this->renderProduct($products);
            $customProducts = $this->renderCustomizeProduct($customProducts);

            return response()->json([
                'productHTML'   => isset($products['html']) ? $products['html'] : $products,
                'customizeHTML' => $customProducts,
            ]);
        }

        // dd($totalItems);

        return view('frontend.pages.search_result', compact('products', 'customProducts', 'totalItems', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    public function searchProduct(Request $request)
    {
        if ($request->ajax()) {
            $query          = $request->get('query');
            $products       = $this->ecommerceProduct($query);
            $customProducts = $this->customizeProduct($query);

            $data = view('frontend.layouts.partials.search_list', compact('products', 'customProducts', 'query'))->render();
            return response()->json($data);
        }
    }


}
