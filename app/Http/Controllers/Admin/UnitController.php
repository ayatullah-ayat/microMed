<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;
use Exception;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::orderByDesc('id')->get();
        return view('backend.pages.unit.unitlist', compact('units'));
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
    public function store( UnitRequest $request)
    {
        try {
            $data      = $request->all();
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $unit   = Unit::create($data);
            if(!$unit)
                throw new Exception("Unable to create Unit!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Unit Created Successfully!',
                'data'      => $unit
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
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        try {

            $data       = $request->all();
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;
            $unitstatus = $unit->update($data);
            if(!$unitstatus)
                throw new Exception("Unable to Update Unit!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Unit Updated Successfully!',
                'data'      => $unit->first()
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
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        try {

            $isDeleted = $unit->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete unit!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Unit Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }



}
