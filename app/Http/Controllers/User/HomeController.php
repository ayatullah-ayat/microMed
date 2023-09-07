<?php

namespace App\Http\Controllers\User;

use App\Models\Shop;
use App\Models\WebFooter;
use App\Models\SocialIcon;
use App\Models\ClientLogos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Custom\OurCustomService;
use App\Models\Custom\CustomServiceProduct;
use App\Models\Custom\CustomServiceCategory;
use Illuminate\Support\Facades\Artisan;
use App\Models\AdsManager;
use App\Models\HomeBanner;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $maxId = 0;
    protected $limit = 9;
    protected $clientLogosLimit = 12;

    public function index()
    {
            //   Artisan::call('storage:link');
        // Artisan::call('route:clear');
        // Artisan::call('view:clear');
        // Artisan::call('config:cache');
        // Artisan::call('config:clear');
        // dd(Artisan::call('key:generate'));
        // dd(env('MAIL_PASSWORD'));
        // Artisan::call('migrate --force');
        
        // dd('done');
        
        $maxId    = request()->max_id ?? $this->maxId;
        $limit    = request()->limit ?? $this->limit;
        $operator = request()->operator ?? '>';


        $customservices = OurCustomService::where('is_active',1)
                        ->orderByDesc('id')
                        ->get();

        $countCustomservicecategories= CustomServiceCategory::where('is_active', 1)->count();


        $sql = CustomServiceCategory::where('is_active', 1);
        if ($maxId) {
            $sql->where('id', $operator, $maxId);
        }

        $customservicecategories = $sql
                                // ->latest()
                                ->take($limit)
                                ->get();
                                

        if (request()->ajax()) {

            $response = $this->renderCustomServiceCategory($customservicecategories);

            return response()->json([
                'html'      => $response['html'],
                'max_id'    => $response['max_id'],
                'isLast'    => $response['isLast']
            ]);
        }


        $serviceproducts        = CustomServiceProduct::where('is_active', 1)->get();

        $shopbanner = Shop::where('is_active', 1)->first();

        $clientLogosLimit = $this->clientLogosLimit;

        $clientlogos = ClientLogos::orderByDesc('id')
            ->take($clientLogosLimit)
            ->get();

        $countClientLogos = ClientLogos::count();
        // $sociallink = SocialIcon::where('is_active', 1)->first();
        // $footerabout = WebFooter::where('is_active', 1)->first();
        
        $ad = AdsManager::orderByDesc('id')->first();

        $homeBanner = HomeBanner::first();
        // dd($customservicecategories);
        return view('frontend.pages.home', compact('customservices' , 'homeBanner', 'customservicecategories' , 'serviceproducts', 'shopbanner', 'clientlogos', 'countCustomservicecategories', 'limit', 'countClientLogos', 'clientLogosLimit','ad'));
    }


    public function home_client_loadmore(){
        $maxId    = request()->max_id ?? $this->maxId;
        $limit    = request()->limit ?? $this->clientLogosLimit;
        $operator = request()->operator ?? '<';

        $sql = ClientLogos::orderByDesc('id');
        if ($maxId) {
            $sql->where('id', $operator, $maxId);
        }

        $clientLogos = $sql->take($limit)->get();

        if (request()->ajax()) {

            $response = $this->renderClientLogos($clientLogos);

            return response()->json([
                'html'      => $response['html'],
                'max_id'    => $response['max_id'],
                'isLast'    => $response['isLast']
            ]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getProduct($category_id)
    {
        try {

            $products = CustomServiceProduct::where([
                ['category_id', $category_id],
                ['is_active', 1]
            ])
            ->get();
            return response()->json($products);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }


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


    private function renderCustomServiceCategory($customservicecategories){
    
       try{


            $lastId         = 0;
            $isLastRecord   = false;
            $lastData       = CustomServiceCategory::first();
            if ($lastData) {
                $lastId = $lastData->id;
            }

            $maxCatId       = 0;
            $html           = "";

            
            if ($customservicecategories) :
                foreach ($customservicecategories as $customservicecategory) :
                    $maxCatId = $customservicecategory->id;
                    $cat_name = \Illuminate\Support\Str::limit($customservicecategory->category_name, 8);
                    $cat_description = \Illuminate\Support\Str::limit($customservicecategory->category_description, 30);
                    if ($lastId == $maxCatId) $isLastRecord = true;

                    $imageSRC = $customservicecategory->category_thumbnail ? asset($customservicecategory->category_thumbnail) : asset('assets/frontend/img/product/1234.png');
                    $html .= "<div class=\"col-md-4 col-sm-6 col-6 mb-1 product-area-column\">
                            <div class=\"product-content d-flex\">

                                <div class=\"animating reveal\">
                                    <div class=\"animating image-wrap\">
                                        <div class=\"animating product-img\">
                                            <img src=\"{$imageSRC}\" alt=\"Product img\">
                                        </div>
                                    </div>
                                </div>

                                <div class=\"product-details text-center\">
                                    <h3 class=\"product-title\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$customservicecategory->category_name}\"> {$cat_name} </h3>
                                    <p class=\"product-text\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{ $customservicecategory->category_description }\">  {$cat_description} </p>
                                    <a href=\"javascript:void(0)\" id=\"category_id\" data-categoryid=\"{$customservicecategory->id}\" data-categoryName=\"{$customservicecategory->category_name}\" type=\"button\" class=\"product-button customize-btn\"> Customize </a>
                                </div>
            
                            </div>
                        </div>";
                endforeach;
            endif;

         return [
                'html'   => $html,
                'max_id' => $maxCatId,
                'isLast' => $isLastRecord
            ];

        } catch (\Throwable $th) {

            return [
                'html'   => null,
                'max_id' => 0,
                'isLast' => false
            ];
        }
    }


    private function renderClientLogos($logos)
    {

        try {


            $lastId         = 0;
            $isLastRecord   = false;
            $lastData       = ClientLogos::first();
            if ($lastData) {
                $lastId = $lastData->id;
            }

            $maxLogoId       = 0;
            $html           = "";

            if ($logos) :
                foreach ($logos as $clientlogo) :
                    $maxLogoId = $clientlogo->id;
                    if ($lastId == $maxLogoId) $isLastRecord = true;

                    $imageSRC = $clientlogo->logo ? asset($clientlogo->logo) : null;
                    $html .= "<div class=\"col-md-2\">
                                <div class=\"single-client text-center m-1\">
                                    <img src=\"{$imageSRC}\" alt=\"Logo\">
                                </div>
                            </div>";
                endforeach;
            endif;

            return [
                'html'   => $html,
                'max_id' => $maxLogoId,
                'isLast' => $isLastRecord
            ];
        } catch (\Throwable $th) {

            return [
                'html'   => null,
                'max_id' => 0,
                'isLast' => false
            ];
        }
    }
}
