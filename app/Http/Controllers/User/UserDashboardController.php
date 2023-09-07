<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Cookie;

class UserDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wishListProducts = null;
        $wishLists = Cookie::get('wishLists'. auth()->user()->id ?? null);
        if (!is_null($wishLists)) {
            $wishLists      = unserialize($wishLists);
            $uniqueProducts = array_unique($wishLists);
            $wishListProducts= Product::whereIn('id', $uniqueProducts)->get();

        }


        $orders = Order::where('user_id', auth()->guard('web')->user()->id ?? null )
                ->with('orderDetails')
                ->get();


        $pendingOrders = Order::where('user_id', auth()->guard('web')->user()->id ?? null)
                ->where('status', 'pending')
                ->count();

        $completedOrders = Order::where('user_id', auth()->guard('web')->user()->id ?? null)
                ->where('status', 'completed')
                ->count();


        $districts = $this->allDistricts();

        return view('frontend.dashboard', compact('wishListProducts', 'orders', 'pendingOrders', 'completedOrders', 'districts'));
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


    private function allDistricts(){
        return [
            'ঢাকা' => 'ঢাকা',
            'চট্টগ্রাম' => 'চট্টগ্রাম',
            'রাঙ্গামাটি' => 'রাঙ্গামাটি',
            'ফেনী' => 'ফেনী',
            'নোয়াখালী' => 'নোয়াখালী',
            'কুমিল্লা' => 'কুমিল্লা',
            'বরিশাল' => 'বরিশাল',
            'সিলেট' => 'সিলেট',
            'যশোর' => 'যশোর',
            'রাজশাহী' => 'রাজশাহী',
            'দিনাজপুর' => 'দিনাজপুর',
            'বগুড়া' => 'বগুড়া',
            'পাবনা' => 'পাবনা',
            'ময়মনসিংহ' => 'ময়মনসিংহ',
            'ফরিদপুর' => 'ফরিদপুর',
            'রংপুর' => 'রংপুর',
            'খুলনা' => 'খুলনা',
            'টাঙ্গাইল' => 'টাঙ্গাইল',
            'পঞ্চগড়' => 'পঞ্চগড়',
            'ভোলা' => 'ভোলা',
            'বান্দরবান' => 'বান্দরবান',
            'চাঁদপুর' => 'চাঁদপুর',
            'হবিগঞ্জ' => 'হবিগঞ্জ',
            'লক্ষীপুর' => 'লক্ষীপুর',
            'বরগুনা' => 'বরগুনা',
            'ঝালকাঠি' => 'ঝালকাঠি',
            'পিরোজপুর' => 'পিরোজপুর',
            'পটুয়াখালী' => 'পটুয়াখালী',
            'ঝিনাইদহ' => 'ঝিনাইদহ',
            'নড়াইল' => 'নড়াইল',
            'মাগুরা' => 'মাগুরা',
            'লালমনিরহাট' => 'লালমনিরহাট',
            'কুড়িগ্রাম' => 'কুড়িগ্রাম',
            'নীলফামারী' => 'নীলফামারী',
            'গাইবান্ধা' => 'গাইবান্ধা',
            'ঠাকুরগাঁ' => 'ঠাকুরগাঁ',
            'সাতক্ষিরা' => 'সাতক্ষিরা',
            'বাগেরহাট' => 'বাগেরহাট',
            'চুয়াডাঙ্গা' => 'চুয়াডাঙ্গা',
            'মেহেরপুর' => 'মেহেরপুর',
            'সিরাজগঞ্জ' => 'সিরাজগঞ্জ',
            'জয়পুরহাট' => 'জয়পুরহাট',
            'নাটোর' => 'নাটোর',
            'নওগাঁ' => 'নওগাঁ',
            'নওয়াবগঞ্জ' => 'নওয়াবগঞ্জ',
            'খাগড়াছড়ি' => 'খাগড়াছড়ি',
            'ব্রাহ্মণবাড়ীয়া' => 'ব্রাহ্মণবাড়ীয়া',
            'সুনামগঞ্জ' => 'সুনামগঞ্জ',
            'কক্সবাজার' => 'কক্সবাজার',
            'মৌলভীবাজার' => 'মৌলভীবাজার',
            'গোপালগঞ্জ' => 'গোপালগঞ্জ',
            'শরীয়তপুর' => 'শরীয়তপুর',
            'মাদারীপুর' => 'মাদারীপুর',
            'রাজবাড়ী' => 'রাজবাড়ী',
            'গাজীপুর' => 'গাজীপুর',
            'কিশোরগঞ্জ' => 'কিশোরগঞ্জ',
            'জামালপুর' => 'জামালপুর',
            'শেরপুর' => 'শেরপুর',
            'নেত্রকোনা' => 'নেত্রকোনা',
            'মুন্সীগঞ্জ' => 'মুন্সীগঞ্জ',
            'নরসিংদী' => 'নরসিংদী',
            'নারায়ণগঞ্জ' => 'নারায়ণগঞ্জ',
            'মানিকগঞ্জ' => 'মানিকগঞ্জ',
        ];
    }
}
