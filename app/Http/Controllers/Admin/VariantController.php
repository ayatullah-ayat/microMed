<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\VariantRequest;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Exception;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variants = Variant::orderByDesc('id')->get();
        return view('backend.pages.variant.variantlist', compact('variants'));
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
    public function store(VariantRequest $request)
    {
        try {
            $data               = $request->all();
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;

            $variant   = Variant::create($data);
            if(!$variant)
                throw new Exception("Unable to create variant!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Variant Created Successfully!',
                'data'      => $variant
            ]);
                
        } catch (\Exception $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage(),
                'data'      => null
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        $items = ProductVariantPrice::where('product_id', $product_id)->get();
        return response()->json($items);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function edit(Variant $variant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Variant $variant)
    {
        try {

            $data               = $request->all();
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;

            $variantstatus = $variant->update($data);
            if(!$variantstatus)
                throw new Exception("Unable to Update variant!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Variant Updated Successfully!',
                'data'      => $variant->first()
            ]);
                
        } catch (\Exception $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage(),
                'data'      => null
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variant $variant)
    {
        try {

            $isDeleted = $variant->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete variant!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Variant Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }



}
