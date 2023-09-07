<?php


namespace App\View\Composers;

use App\Models\WebFooter;
use Illuminate\View\View;
use App\Models\SocialIcon;
use Illuminate\Support\Str;
use App\Http\Services\ProductSearch;
use App\Models\Company;
use App\Models\ContactInformation;
use Illuminate\Support\Facades\Cookie;
use App\Models\Custom\CustomServiceCategory;
use App\Models\ManageCompnay;
use App\Models\PartnershipLogo;

class FrontendComposer
{
    use ProductSearch;

    public function compose(View $view)
    {

        $data = $this->productCookies();

        $productIds = $data['productIds'] ?? [];
        $wishLists  = $data['wishLists'] ?? [];
        $cartQtys   = $data['cartQtys'] ?? [];


        $customservicecategoriesFooter = CustomServiceCategory::where('is_active', 1)->latest()
                                ->take(14)
                                ->get();

        $footerabout = WebFooter::where('is_active', 1)->first();
        $sociallink  = SocialIcon::where('is_active', 1)->first();
        $contactInfo = ContactInformation::where('is_active', 1)->first();

        $organizationlogo = PartnershipLogo::latest()->take(2)->get();

        $companylogo = Company::where('is_active', 1)->first();
        // dd($companylogo);
        
        $view->with(compact('productIds', 'cartQtys', 'wishLists', 'customservicecategoriesFooter','footerabout','sociallink','contactInfo','organizationlogo','companylogo'));
    }

}
