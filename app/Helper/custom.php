<?php

use App\Models\Admin;
use App\Models\Company;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;

if (!function_exists('check_internet')) {

    if (!function_exists('numToWords')){
        function numToWords($number){
            $numberToWords = new NumberToWords();
            $numberToWords = $numberToWords->getNumberTransformer('en');
            $inWords = $numberToWords->toWords($number);
            // dd($inWords);
            return $inWords;
        }
    }
    function check_internet()
    {
        try {
            $host_name  = 'www.google.com';
            $port_no    = '80';

            $st = (bool)@fsockopen($host_name, $port_no, $err_no, $err_str, 10);
            if (!$st)
                throw new Exception("Please check Your Internet!", 403);

            return [
                'success'    => true,
                'msg'         => 'OK',
                'code'        => 200
            ];
        } catch (Exception $e) {
            return [
                'success'    => false,
                'msg'         => $e->getMessage(),
                'code'        => $e->getCode()
            ];
        }
    }
}



if (!function_exists('renderFileInput')) {

    function renderFileInput(array $array=[])
    {
        $id                 = $array['id'] ?? 'image'; // categoryImage
        $previewId          = $array['previewId'] ?? 'img-preview';
        $previewImageStyle  = $array['previewImageStyle'] ?? 'cursor:pointer;width:300px; height: 300px !important;';
        $defaultImageSrc    = $array['imageSrc'] ?? 'assets/img/blank-img.png';

        return "<div class=\"input-group\">
                <div class=\"input-group-append\">
                    <button title=\"Image Preview\" class=\"btn btn-primary collapsed\" type=\"button\" data-toggle=\"collapse\"
                        data-target=\"#collapseExample_{$id}\" aria-expanded=\"false\" aria-controls=\"collapseExample\"><i class=\"fa fa-image fa-lg\"></i></button>
                </div>
                <input type=\"file\" name=\"photo\" id=\"{$id}\" class=\"form-control\" accept=\"image/*\">
            </div>
        
            <div class=\"collapse pt-4\" id=\"collapseExample_{$id}\">
                <div class=\"d-flex justify-content-center\"
                    onclick=\" javascript: document.getElementById('$id').click() \">
                    <img title=\"Click to upload image\" src=\"$defaultImageSrc\" alt=\"Default Image\"
                        id=\"$previewId\" class=\"img-fluid img-responsive img-thumbnail\"
                        ondragstart=\"javascript: return false;\" style=\"$previewImageStyle\">
                </div>
            </div>";
    }
}


if (!function_exists('profilePhoto')) {
    function profilePhoto($path=null,array $attributes=[]){

        $avatar = Avatar::create(auth()->user()->name)->toBase64();
        

        $profilePath = $path ? asset($path) : "$avatar";

        return "<img src=\"$profilePath\"/>";
        $result = "<img src=\"$profilePath\" " . join(' ', array_map(function ($key) use ($attributes) {
            if (is_bool($attributes[$key])) {
                return $attributes[$key] ? $key : '';
            }

            return $key . '="' . $attributes[$key] . '"';

        }, array_keys($attributes))) . ' alt="Profile Image" >';

        return $result;

    }
}



if (!function_exists('hasProfile')){
    function hasProfile($guard="web"){
        return auth()->guard($guard)->user()->profile;
    }
}

if (!function_exists('salesPrice')){
    function salesPrice($product){
        return round($product->sales_price, 0);
        // return number_format(($product->total_product_unit_price - ($product->total_product_unit_price *  ($product->product_discount / 100))) / $product->total_product_qty ?? 0.0 , 2);
    }

}

// (100 * 251) / 284;

if (!function_exists('wholesalesPrice')) {
    function wholesalesPrice($product)
    {
        if(!$product->total_product_qty) return 0;
        
        return round(($product->total_product_wholesale_price / $product->total_product_qty) ?? 0.0, 0);
    }
}


if (!function_exists('matchColor')) {
    function matchColor($color_name=null)
    {
        return preg_match('/white|#f{1,5}|সাদা/im', $color_name);
    }
}


if (!function_exists('loadMoreButton')) {
    function loadMoreButton($dataURI=null, $maxId="1", $limit="10", $btnClass="btn btn-dark btn-sm mx-5")
    {
        return "
            <button data-uri=\"{$dataURI}\" class=\"{$btnClass} loadMoreBtn\" data-filter-maxid=\"\" data-maxid=\"{$maxId}\" data-limit=\"{$limit}\">Load More</button>
        ";
    }
}


if (!function_exists('getUnreadNotification')) {
    function getUnreadNotification()
    {
        return Notification::whereNull('read_at')
        ->where('notifiable_id', auth()->guard('admin')->user()->id ?? null)
        ->get();
    }
}



if (!function_exists('totalEarningsMonthwise')) {
    function totalEarningsMonthwise($isRevenue = false)
    {
        $revenues=[];
        $earnings= [
            'Jan' => 0,
            'Feb' => 0,
            'Mar' => 0,
            'Apr' => 0,
            'May' => 0,
            'Jun' => 0,
            'Jul' => 0,
            'Aug' => 0,
            'Sep' => 0,
            'Oct' => 0,
            'Nov' => 0,
            'Dec' => 0,
        ];

        $salesRevenue   = totalSalesRevenue();
        $orderRevenue   = totalOrderRevenue();

        // dd($salesRevenue, $orderRevenue);

        if(!$isRevenue){

            $salesPurchase          = totalSalesPurchase();
            $orderPurchase          = totalOrderPurchase();
    
            if($salesRevenue && count($salesRevenue)){
                foreach ($salesRevenue as $k => $value) {
                    $earnings['Jan']= ($value->Jan + $orderRevenue[$k]->Jan) - ($salesPurchase[$k]->Jan + $orderPurchase[$k]->Jan);
                    $earnings['Feb']= ($value->Feb + $orderRevenue[$k]->Feb) - ($salesPurchase[$k]->Feb + $orderPurchase[$k]->Feb);
                    $earnings['Mar']= ($value->Mar + $orderRevenue[$k]->Mar) - ($salesPurchase[$k]->Mar + $orderPurchase[$k]->Mar);
                    $earnings['Apr']= ($value->Apr + $orderRevenue[$k]->Apr) - ($salesPurchase[$k]->Apr + $orderPurchase[$k]->Apr);
                    $earnings['May']= ($value->May + $orderRevenue[$k]->May) - ($salesPurchase[$k]->May + $orderPurchase[$k]->May);
                    $earnings['Jun']= ($value->Jun + $orderRevenue[$k]->Jun) - ($salesPurchase[$k]->Jun + $orderPurchase[$k]->Jun);
                    $earnings['Jul']= ($value->Jul + $orderRevenue[$k]->Jul) - ($salesPurchase[$k]->Jul + $orderPurchase[$k]->Jul);
                    $earnings['Aug']= ($value->Aug + $orderRevenue[$k]->Aug) - ($salesPurchase[$k]->Aug + $orderPurchase[$k]->Aug);
                    $earnings['Sep']= ($value->Sep + $orderRevenue[$k]->Sep) - ($salesPurchase[$k]->Sep + $orderPurchase[$k]->Sep);
                    $earnings['Oct']= ($value->Oct + $orderRevenue[$k]->Oct) - ($salesPurchase[$k]->Oct + $orderPurchase[$k]->Oct);
                    $earnings['Nov']= ($value->Nov + $orderRevenue[$k]->Nov) - ($salesPurchase[$k]->Nov + $orderPurchase[$k]->Nov);
                    $earnings['Dec']= ($value->Dec + $orderRevenue[$k]->Dec) - ($salesPurchase[$k]->Dec + $orderPurchase[$k]->Dec);
                }
            }
    
            return $earnings;
        }

        $revenues = [
            'online'    => $orderRevenue && count($orderRevenue) ? $orderRevenue[0] : null,
            'offline'   => $salesRevenue && count($salesRevenue) ? $salesRevenue[0] : null,
        ];

        return $revenues;
    }
} 


function totalSalesPurchase(){
    return DB::select("SELECT 
        SUM(IF(month = 'Jan', sold_purchase_price, 0)) AS 'Jan',
        SUM(IF(month = 'Feb', sold_purchase_price, 0)) AS 'Feb',
        SUM(IF(month = 'Mar', sold_purchase_price, 0)) AS 'Mar',
        SUM(IF(month = 'Apr', sold_purchase_price, 0)) AS 'Apr',
        SUM(IF(month = 'May', sold_purchase_price, 0)) AS 'May',
        SUM(IF(month = 'Jun', sold_purchase_price, 0)) AS 'Jun',
        SUM(IF(month = 'Jul', sold_purchase_price, 0)) AS 'Jul',
        SUM(IF(month = 'Aug', sold_purchase_price, 0)) AS 'Aug',
        SUM(IF(month = 'Sep', sold_purchase_price, 0)) AS 'Sep',
        SUM(IF(month = 'Oct', sold_purchase_price, 0)) AS 'Oct',
        SUM(IF(month = 'Nov', sold_purchase_price, 0)) AS 'Nov',
        SUM(IF(month = 'Dec', sold_purchase_price, 0)) AS 'Dec',
        SUM(sold_purchase_price) AS total_yearly_sold_purchase_price,
        SUM(total) AS total_yearly_sold_purchase_qty
        FROM (
        SELECT DATE_FORMAT(sales.sales_date, '%b') AS month, 
        SUM(sale_products.product_qty) as total,
        round(sum(sale_products.purchase_price * sale_products.product_qty),2) AS sold_purchase_price
        FROM sale_products
        JOIN sales ON sale_products.sale_id=sales.id
        WHERE sales.sales_date <= NOW() and sales.sales_date >= Date_add(Now(),interval - 12 month) 
        GROUP BY DATE_FORMAT(sales.sales_date, '%m-%Y')) as sub");
}

function totalOrderPurchase(){
    return DB::select("SELECT 
        -- SUM(IF(month = 'Jan', total, 0)) AS 'Jan',
        SUM(IF(month = 'Jan', completed_order_purchase_price, 0)) AS 'Jan',
        SUM(IF(month = 'Feb', completed_order_purchase_price, 0)) AS 'Feb',
        SUM(IF(month = 'Mar', completed_order_purchase_price, 0)) AS 'Mar',
        SUM(IF(month = 'Apr', completed_order_purchase_price, 0)) AS 'Apr',
        SUM(IF(month = 'May', completed_order_purchase_price, 0)) AS 'May',
        SUM(IF(month = 'Jun', completed_order_purchase_price, 0)) AS 'Jun',
        SUM(IF(month = 'Jul', completed_order_purchase_price, 0)) AS 'Jul',
        SUM(IF(month = 'Aug', completed_order_purchase_price, 0)) AS 'Aug',
        SUM(IF(month = 'Sep', completed_order_purchase_price, 0)) AS 'Sep',
        SUM(IF(month = 'Oct', completed_order_purchase_price, 0)) AS 'Oct',
        SUM(IF(month = 'Nov', completed_order_purchase_price, 0)) AS 'Nov',
        SUM(IF(month = 'Dec', completed_order_purchase_price, 0)) AS 'Dec',
        SUM(completed_order_purchase_price) AS total_yearly_purchase_price,
        SUM(total) AS total_yearly_purchase_qty
        FROM (
        SELECT DATE_FORMAT(order_date, '%b') AS month, 
        SUM(order_details.product_qty) as total,
        round(sum(order_details.purchase_price * order_details.product_qty),2) AS completed_order_purchase_price
        FROM orders
        JOIN order_details ON order_details.order_id=orders.id
        WHERE orders.order_date <= NOW() and orders.order_date >= Date_add(Now(),interval - 12 month) 
        and orders.status = 'completed'
        GROUP BY DATE_FORMAT(orders.order_date, '%m-%Y')) as sub");
}



function totalSalesRevenue(){
    return DB::select("SELECT 
        SUM(IF(month = 'Jan', total_sold_total_price, 0)) AS 'Jan',
        SUM(IF(month = 'Feb', total_sold_total_price, 0)) AS 'Feb',
        SUM(IF(month = 'Mar', total_sold_total_price, 0)) AS 'Mar',
        SUM(IF(month = 'Apr', total_sold_total_price, 0)) AS 'Apr',
        SUM(IF(month = 'May', total_sold_total_price, 0)) AS 'May',
        SUM(IF(month = 'Jun', total_sold_total_price, 0)) AS 'Jun',
        SUM(IF(month = 'Jul', total_sold_total_price, 0)) AS 'Jul',
        SUM(IF(month = 'Aug', total_sold_total_price, 0)) AS 'Aug',
        SUM(IF(month = 'Sep', total_sold_total_price, 0)) AS 'Sep',
        SUM(IF(month = 'Oct', total_sold_total_price, 0)) AS 'Oct',
        SUM(IF(month = 'Nov', total_sold_total_price, 0)) AS 'Nov',
        SUM(IF(month = 'Dec', total_sold_total_price, 0)) AS 'Dec',
        SUM(total_sold_total_price) AS total_yearly_sold_price,
        SUM(total) AS total_yearly_sold_qty
        FROM (
        SELECT DATE_FORMAT(sales_date, '%b') AS month, 
        SUM(sold_total_qty) as total,
        SUM(order_grand_total) AS total_sold_total_price
        FROM sales
        WHERE sales_date <= NOW() and sales_date >= Date_add(Now(),interval - 12 month) 
        GROUP BY DATE_FORMAT(sales_date, '%m-%Y')) as sub");
}

function totalOrderRevenue(){
    return DB::select("SELECT 
        -- SUM(IF(month = 'Jan', total, 0)) AS 'Jan',
        SUM(IF(month = 'Jan', completed_order_total_price, 0)) AS 'Jan',
        SUM(IF(month = 'Feb', completed_order_total_price, 0)) AS 'Feb',
        SUM(IF(month = 'Mar', completed_order_total_price, 0)) AS 'Mar',
        SUM(IF(month = 'Apr', completed_order_total_price, 0)) AS 'Apr',
        SUM(IF(month = 'May', completed_order_total_price, 0)) AS 'May',
        SUM(IF(month = 'Jun', completed_order_total_price, 0)) AS 'Jun',
        SUM(IF(month = 'Jul', completed_order_total_price, 0)) AS 'Jul',
        SUM(IF(month = 'Aug', completed_order_total_price, 0)) AS 'Aug',
        SUM(IF(month = 'Sep', completed_order_total_price, 0)) AS 'Sep',
        SUM(IF(month = 'Oct', completed_order_total_price, 0)) AS 'Oct',
        SUM(IF(month = 'Nov', completed_order_total_price, 0)) AS 'Nov',
        SUM(IF(month = 'Dec', completed_order_total_price, 0)) AS 'Dec',
        SUM(completed_order_total_price) AS total_yearly_order_price,
        SUM(total) AS total_yearly_order_qty
        FROM (
        SELECT DATE_FORMAT(order_date, '%b') AS month, 
        SUM(order_total_qty) as total,
        SUM(order_total_price) AS completed_order_total_price
        FROM orders
        WHERE order_date <= NOW() and order_date >= Date_add(Now(),interval - 12 month) and status = 'completed'
        GROUP BY DATE_FORMAT(order_date, '%m-%Y')) as sub");
}


function getCompanyProfile(){
    return Company::first();
}


function pdfFooter($organizationlogo=null){

    dd('pdfFooter');
    $url = url('/');
    $html = "";

    if(isset($organizationlogo) && count($organizationlogo)):
        // foreach ($organizationlogo as $organizationItem):
        //     $logoPath = asset($organizationItem->logo) ?? '';
        //     $html .= "<span><img class=\"img-fluid\" width=\"60px\" src=\"{$logoPath}\" alt=\"\"></span>";
        // endforeach;
        $logoPath = asset('assets/backend/img/key-ring1.png');
        $logoPath2 = asset('assets/backend/img/Merchandise2.png');
        $html .= "<span><img class=\"img-fluid mx-1\" width=\"60px\" src=\"{$logoPath}\" alt=\"\"></span>";
        $html .= "<span><img class=\"img-fluid\" width=\"60px\" src=\"{$logoPath2}\" alt=\"\"></span>";
    endif;

    return "<table width=\"100%\">
        <tr>
            <td align=\"left\" width=\"50%\">
                <p><b>ওয়েবসাইট</b>: {$url}</p>
                <p><b>ফেইসবুক পেইজ</b>: https://www.facebook.com/micromediabd</p>
            </td>
            <th align=\"right\" width=\"50%\">
                <p><b>আমাদের অংগ প্রতিষ্ঠান সমূহ:</b></p>
                <p>{$html}</p>
            </th>
        </tr>
    </table>";
}
