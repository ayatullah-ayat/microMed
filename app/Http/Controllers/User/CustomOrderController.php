<?php

namespace App\Http\Controllers\User;

use DB;
use Exception;
use App\Models\Product;
use App\Models\SocialIcon;
use Illuminate\Http\Request;
use App\Models\ContactInformation;
use App\Events\CustomizeOrderEvent;
use App\Http\Services\ImageChecker;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;
use App\Http\Services\CustomerChecker;
use App\Http\Requests\CustomOrderRequest;
use App\Models\Custom\CustomServiceOrder;
use App\Models\Custom\CustomServiceProduct;

class CustomOrderController extends Controller
{

    use CustomerChecker, ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
    public function store(CustomOrderRequest $request)
    {
        try {
            // dd($request->all());

            $order_attachment   = $request->order_attachment;
            $data               = $request->all();
            $fileLocation       = null;
            $order_attachment =  $order_attachment && count(json_decode($order_attachment)) > 0 ? json_decode($order_attachment)[0] : null;
    
            if($order_attachment){
                //file, dir
                $fileResponse = $this->uploadFile($order_attachment, 'CustomServiceOrder/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);
    
                $fileLocation = $fileResponse['fileLocation'];
            }
            
            $data['order_attachment']       = $fileLocation;
            $data['order_no']               =  uniqid();
            $data['order_qty']              =  0;
            $data['order_discount_price']   =  0;
            $data['order_total_price']      =  0;
            $data['advance_balance']        =  0;
            $data['status']                 =  'pending';

            DB::beginTransaction();
            $oldcustomer = $this->isCustomerExists($data['customer_name'], $data['customer_phone']);
            if($oldcustomer['success']){
                $data['customer_id'] = $oldcustomer['data']->id;

            }else{

                $customer = $this->createCustomer([
                    'customer_name'     => $data['customer_name'],
                    'customer_phone'    => $data['customer_phone'],
                    'customer_address'  => $data['customer_address'],
                    'current_balance'   => 0,
                    'is_active'         => 1,
                ]);

                if(!$customer['success']) 
                    throw new Exception($customer['msg'], 403);

                $data['customer_id'] = $customer['data']->id;

                $customerType = $this->createCustomerType([
                    'customer_id' => $data['customer_id'],
                    'customer_type' => 'customize',
                ]);
                
                if(!$customerType['success']) 
                    throw new Exception($customerType['msg'], 403);

            }

            $customserviceorder = CustomServiceOrder::create($data);

            if(!$customserviceorder)
                throw new Exception('Unable to create Order', 403);



            Event::dispatch(new CustomizeOrderEvent($customserviceorder));

            DB::commit();

            return response()->json([
                'success'   => true,
                'msg'       => 'Order Created Successfully!',
                'data'      => $customserviceorder
            ]);

        } catch (\Exception $th) {
            DB::rollback();

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
    public function show(CustomServiceProduct $customServiceProduct)
    {
        $socialicon = SocialIcon::where('is_active', 1)->first();

        $customProuductInfo = CustomServiceProduct::where('is_active', 1)->get();

      

        $otherProducts = Product::select('*')
            ->latest()
            ->take(20)
            ->where('is_active', 1)
            ->where('is_publish', 1)
            ->get();
            
        return view('frontend.pages.customorder', compact('customServiceProduct', 'otherProducts','socialicon'));
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
}
