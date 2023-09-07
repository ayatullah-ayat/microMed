<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Exports\OtherOrdertExport;
use App\Http\Controllers\Controller;
use App\Models\OtherOrder;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OtherOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $otherOrders = OtherOrder::orderByDesc('id')->get();
        
         $otherOrders = OtherOrder::orderByDesc('order_date')
                        ->groupBy('order_no')
                        ->orderByDesc('id')
                        ->get();
        return view('backend.pages.otherorder.otherorder', compact('otherOrders'));
    }


    public function OtherOrderExport(){
        return Excel::download(new OtherOrdertExport, 'otherdata.xlsx');
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
        try {
            
            $data = $request->all();

            if(!$request->order_no){
                $data['order_no'] =  uniqid();
            }
            
            


            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;

            foreach ($data['products'] as $product) {
                $orderorder = OtherOrder::create([
                    'order_date' => $data['order_date'],
                    'order_no' => $data['order_no'],
                    'customer_name' => $data['customer_name'],
                    'advance_balance' => $data['advance_balance'],
                    'due_price' => $data['due_price'],
                    'service_charge' => $data['service_charge'],
                    'order_discount_price' => $data['order_discount_price'],
                    'moible_no' => $data['moible_no'],
                    'institute_description' => $data['institute_description'],
                    'address' => $data['address'],
                    'note' => $data['note'],
                    'status' => $data['status'],
                    'category_name' => $product['category_name'],
                    'order_qty' => $product['order_qty'],
                    'price' => $product['price'],
                    'total_order_price' => $product['total_order_price'],
                    'created_by' => $data['created_by']
                ]);
                if(!$orderorder)
                    throw new Exception("Unable to create Other Order!", 403);
            }
            // $orderorder   = OtherOrder::create($data);

            return response()->json([
                'success'   => true,
                'msg'       => 'Other Order Created Successfully!',
                'data'      => $orderorder
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(OtherOrder $otherOrder)
    {
        try {

            // dd($otherOrder);

            // $pdf = PDF::loadView('backend.pages.otherorder.show_order', compact('otherOrder'), [], [
            //     'margin_left'   => 20,
            //     'margin_right'  => 15,
            //     'margin_top'    => 48,
            //     'margin_bottom' => 25,
            //     'margin_header' => 10,
            //     'margin_footer' => 10,
            //     'watermark'     => $this->setWaterMark($otherOrder),
            // ]);


            $otherOrders = [];
            if($otherOrder && $otherOrder->order_no){
                $otherOrders = $otherOrder->where('order_no', $otherOrder->order_no)->get();
            }

            $pdf = PDF::loadView('backend.pages.otherorder.show_order', compact('otherOrder', 'otherOrders'), [], [
                'margin_left'   => 20,
                'margin_right'  => 20,
                'margin_top'    => 45,
                'margin_bottom' => 15,
                'margin_header' => 10,
                'margin_footer' => 10,
                // 'watermark'     => $this->setWaterMark($otherOrder, $otherOrders),
            ]);

            $pagecount = $pdf->getMpdf()->setSourceFile(public_path('/assets/backend/pdf/final_pad.pdf'));
            $tplIdx = $pdf->getMpdf()->importPage($pagecount);
            $pdf->getMpdf()->useTemplate($tplIdx);
            // dd($pdf);

            return $pdf->stream('other_order_invoice_' . preg_replace("/\s/", '_', ($otherOrder->order_no ?? '')) . '_' . ($otherOrder->order_date ?? '') . '_.pdf');
        } catch (Exception $e) {
            dd($e->getMessage());
        }


        // return view('backend.pages.order.show_order', compact('order'));

    }
    
    
    private function setWaterMark($otherOrder, $otherOrders)
    {
        if(isset($otherOrders) && count($otherOrders) > 1){

            $subtotal               = 0;
            $serviceCharge          = 0;
            $discountTotal          = 0;
            $advanceBalanceTotal    = 0;

            foreach($otherOrders as $other):
                $subtotal           += $other->total_order_price;
                $discountTotal      += $other->order_discount_price;
                $serviceCharge      += $other->service_charge;
                $advanceBalanceTotal+= $other->advance_balance;
            endforeach;

            $gTotal = ($subtotal + $serviceCharge) - $discountTotal;

            return $otherOrder && $gTotal - $advanceBalanceTotal <= 0 ? 'Paid' : 'Due';
        }

        return $otherOrder && ($otherOrder->total_order_price + $otherOrder->service_charge) - $otherOrder->advance_balance  <= 0 ? 'Paid': 'Due';
    }


    // private function setWaterMark($otherOrder)
    // {
    //     return $otherOrder && ($otherOrder->total_order_price + $otherOrder->service_charge) - $otherOrder->advance_balance  <= 0 ? 'Paid': 'Due';
    // }



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
     * 
     * $data['order_no']               =  uniqid();
     */
    public function update(Request $request, OtherOrder $otherOrder)
    {
        try {

            $data = $request->all();

            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;

            foreach ($data['products'] as $product) {
                $otherorder = [
                    'order_date'            => $data['order_date'],
                    'order_no'              => $data['order_no'],
                    'customer_name'         => $data['customer_name'],
                    'advance_balance'       => $data['advance_balance'],
                    'due_price'             => $data['due_price'],
                    'service_charge'        => $data['service_charge'],
                    'order_discount_price'  => $data['order_discount_price'],
                    'moible_no'             => $data['moible_no'],
                    'institute_description' => $data['institute_description'],
                    'address'               => $data['address'],
                    'note'                  => $data['note'],
                    'status'                => $data['status'],
                    'category_name'         => $product['category_name'],
                    'order_qty'             => $product['order_qty'],
                    'price'                 => $product['price'],
                    'total_order_price'     => $product['total_order_price'],
                ];
                
                if($product['order_id'] && !empty($product['order_id'])){
                    $orderstatus        = OtherOrder::find($product['order_id'])->update($otherorder);
                }else {
                    OtherOrder::create($otherorder);
                }
            }

            if(!$orderstatus)
                throw new Exception("Unable to Update Order!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Order Updated Successfully!'
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( OtherOrder $otherOrder)
    {
        try {

            $isDeleted = $otherOrder->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Order!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Order Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


    public function getAllOrdersByOrderNo($order_no) {
        $orders = OtherOrder::where('order_no', $order_no)->get();

        return response()->json(['data' => $orders], 200);
    }

    public function datewise_report(Request $request)
    {

        $from   = $request->from_date;
        $to     = $request->to_date;

        if ($request->ajax()) {

            $q = OtherOrder::where('order_date', '>=', $from);

            if ($to) {
                $q->where('order_date', '<=', $to);
            }

            $orders = $q->groupBy('order_no')->orderBy('order_date')->with(['categories'])->get();

            return response()->json($orders);
        }


        return view('backend.pages.otherorder.datewise_other_report');
    }

    public function datewise_pdf(Request $request)
    {

        $from   = $request->from_date;
        $to     = $request->to_date;

        if(!$from){
            return back();
        }
        $q = OtherOrder::where('order_date', '>=', $from);

        if ($to) {
            $q->where('order_date', '<=', $to);
        }

        $orders = $q->groupBy('order_no')->orderBy('order_date')->with(['categories'])->get();

        $pdf = PDF::loadView('backend.pages.otherorder.pdf_view', compact('orders'), [], [
            'margin_left'   => 20,
            'margin_right'  => 15,
            'margin_top'    => 45,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark'     => env('APP_NAME', 'Micro Media')
        ]);

        return $pdf->stream('getorderdata.pdf');
    }


}
