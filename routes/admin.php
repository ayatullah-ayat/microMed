<?php

use App\Models\ContactInformation;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Admin\CategoryController;



// ------------ Frontend namespace ----------------------


use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SupplierController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WebFooterController;
use App\Http\Controllers\Admin\OtherOrderController;
use App\Http\Controllers\Admin\SaleReturnController;
use App\Http\Controllers\Admin\SocialIconController;
use App\Http\Controllers\Admin\ClientLogosController;
use App\Http\Controllers\Admin\SmsSettignsController;
use App\Http\Controllers\Admin\StockReportController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\ManageCompanyController;
use App\Http\Controllers\Admin\ManageGatewayController;
use App\Http\Controllers\Admin\OfficeAccountController;
use App\Http\Controllers\Admin\PurchaseReturnController;
use App\Http\Controllers\Admin\PartnershipLogoController;
use App\Http\Controllers\Admin\ContactInformationController;
use App\Http\Controllers\Admin\EmailConfigurationController;
use App\Http\Controllers\Admin\Custom\OurCustomServiceController;
use App\Http\Controllers\Admin\Custom\CustomServiceOrderController;
use App\Http\Controllers\Admin\Custom\CustomServiceProductController;
use App\Http\Controllers\Admin\ShopController as AdminShopController;
use App\Http\Controllers\Admin\Custom\CustomServiceCategoryController;
use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\AdminHomeBannerController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ApplyCouponController as AdminApplyCouponController;

// ------------ Frontend namespace ----------------------

// Route::redirect('/admin', '/admin/dashboard', 301);

// --------------------------- Admin Dashboard ---------------------------------

Route::get('/order-export', [OrderController::class, 'orderexport'])->name('order_export');
Route::get('/orderDataCsv', [OrderController::class, 'orderDataCsv'])->name('orderDataCsv');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware'=>['auth:admin', 'PreventBackHistory']], function () {
    
    Route::get('/dashboard',        [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'category', 'as' => 'category.'],function () {
        Route::get('/',             [CategoryController::class, 'index'])->name('index');
        Route::post('/',            [CategoryController::class, 'store'])->name('store');
        Route::put('/{category}',   [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}',[CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'subcategory', 'as' => 'subcategory.'],function () {
        Route::get('/',             [SubcategoryController::class, 'index'])->name('index');
        Route::post('/',            [SubcategoryController::class, 'store'])->name('store');
        Route::put('/{subcategory}',[SubcategoryController::class, 'update'])->name('update');
        Route::delete('/{subcategory}',[SubcategoryController::class, 'destroy'])->name('destroy');
        Route::get('/{id}',         [SubcategoryController::class, 'subcategoriesByCategory'])->name('subcategoriesByCategory');
    });

    Route::group(['prefix' => 'brand', 'as' => 'brand.'], function(){
        Route::get('/',             [BrandController::class, 'index'])->name('index');
        Route::post('/',            [BrandController::class, 'store'])->name('store');
        Route::put('/{brand}',      [BrandController::class, 'update'])->name('update');
        Route::delete('/{brand}',   [BrandController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'variants', 'as' => 'variant.'], function(){
        Route::get('/',             [VariantController::class, 'index'])->name('index');
        Route::post('/',            [VariantController::class, 'store'])->name('store');
        Route::put('/{variant}',    [VariantController::class, 'update'])->name('update');
        Route::delete('/{variant}', [VariantController::class, 'destroy'])->name('destroy');
        Route::get('/{product_id}', [VariantController::class, 'show'])->name('show');
    });

    Route::group(['prefix' => 'units', 'as' => 'unit.'], function(){
        Route::get('/',             [UnitController::class, 'index'])->name('index');
        Route::post('/',            [UnitController::class, 'store'])->name('store');
        Route::put('/{unit}',       [UnitController::class, 'update'])->name('update');
        Route::delete('/{unit}',    [UnitController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'taxes', 'as' => 'tax.'], function(){
        Route::get('/',             [TaxController::class, 'index'])->name('index');
        Route::post('/',            [TaxController::class, 'store'])->name('store');
        Route::put('/{tax}',        [TaxController::class, 'update'])->name('update');
        Route::delete('/{tax}',     [TaxController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'currencies', 'as' => 'currency.'], function(){
        Route::get('/',             [CurrencyController::class, 'index'])->name('index');
        Route::post('/',            [CurrencyController::class, 'store'])->name('store');
        Route::put('/{currency}',   [CurrencyController::class, 'update'])->name('update');
        Route::delete('/{currency}',[CurrencyController::class, 'destroy'])->name('destroy');
    });

    // Route::get('/currency', function () {
    //     return view('backend.pages.currency.currencylist');
    // })->name('currency');

    Route::group(['prefix' => 'profile', 'as' => 'auth_user.'], function () {
        Route::get('/',         [AdminProfileController::class, 'profile'])->name('profile');
        Route::put('/{admin}',  [AdminProfileController::class, 'update'])->name('profile_update');
    });

    Route::group(['prefix' => 'cms-settings', 'as' => 'cms_settings.'], function () {

        Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function () {
            Route::get('/',             [GalleryController::class, 'index'])->name('index');
            Route::post('/',            [GalleryController::class, 'store'])->name('store');
            Route::put('/{gallery}',    [GalleryController::class, 'update'])->name('update');
            Route::delete('/{gallery}', [GalleryController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'clientlogos', 'as' => 'clientlogo.'], function(){
            Route::get('/',                 [ClientLogosController::class, 'index'])->name('index');
            Route::post('/',                [ClientLogosController::class, 'store'])->name('store');
            Route::put('/{clientLogos}',    [ClientLogosController::class, 'update'])->name('update');
            Route::delete('/{clientLogos}', [ClientLogosController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'partnership-logos', 'as' => 'partnership-logo.'], function(){
            Route::get('/',                     [PartnershipLogoController::class, 'index'])->name('index');
            Route::post('/',                    [PartnershipLogoController::class, 'store'])->name('store');
            Route::put('/{partnershipLogo}',    [PartnershipLogoController::class, 'update'])->name('update');
            Route::delete('/{partnershipLogo}', [PartnershipLogoController::class, 'destroy'])->name('destroy');
        });


    });


    Route::group(['prefix' => 'suppliers', 'as' => 'supplier.'], function(){
        Route::get('/',             [SupplierController::class, 'index'])->name('index');
        Route::post('/',            [SupplierController::class, 'store'])->name('store');
        Route::put('/{supplier}',   [SupplierController::class, 'update'])->name('update');
        Route::delete('/{supplier}',[SupplierController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'customers', 'as' => 'customer.'], function(){
        Route::get('/',             [CustomerController::class, 'index'])->name('index');
        Route::post('/',            [CustomerController::class, 'store'])->name('store');
        Route::put('/{customer}',   [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}',[CustomerController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'purchase', 'as' => 'purchase.'], function () {
        Route::get('/',                     [PurchaseController::class, 'index'])->name('index');
        Route::get('/create',               [PurchaseController::class, 'create'])->name('create');
        Route::post('/',                    [PurchaseController::class, 'store'])->name('store');
        Route::get('/{purchase}/edit',      [PurchaseController::class, 'edit'])->name('edit');
        Route::put('/{purchase}',           [PurchaseController::class, 'update'])->name('update');
        Route::delete('/{purchase}',        [PurchaseController::class, 'destroy'])->name('destroy');
        Route::post('/check-invoice',       [PurchaseController::class, 'checkInvoice'])->name('checkInvoice');
        Route::get('/search-product',       [PurchaseController::class, 'searchProduct'])->name('searchProduct');
        Route::get('/search-purchase-product',[PurchaseController::class, 'searchPurchaseProduct'])->name('searchPurchaseProduct');
        Route::get('/get-product',          [PurchaseController::class, 'getProduct'])->name('getProduct');
        Route::get('/invoice/{invoice_no}', [PurchaseController::class, 'showInvoice'])->name('showInvoice');
        Route::post('/payment',             [PurchaseController::class, 'payment'])->name('payment');
        Route::get('/manage-stock',         [PurchaseController::class, 'manage_stock'])->name('manage_stock');
        Route::post('/manage-stock',        [PurchaseController::class, 'store_manage_stock'])->name('store_manage_stock');
    });

    Route::group(['prefix' => 'return-purchase', 'as' => 'return_purchase.'], function () {
        Route::get('/',                     [PurchaseReturnController::class, 'index'])->name('index');
        Route::post('/',                    [PurchaseReturnController::class, 'store'])->name('store');
        Route::get('/{invoice_no}',         [PurchaseReturnController::class, 'showInvoice'])->name('showInvoice');
    });


    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::get('/',                     [ProductController::class, 'index'])->name('index');
        Route::get('/unpublish-products',   [ProductController::class, 'unPublishProducts'])->name('unpublish');
        Route::get('/create',               [ProductController::class, 'create'])->name('create');
        Route::post('/',                    [ProductController::class, 'store'])->name('store');
        Route::get('/{product}',            [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit',       [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}',            [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}',         [ProductController::class, 'destroy'])->name('destroy');
        Route::post('/{product}',           [ProductController::class, 'publish'])->name('publish');
    });


    Route::group(['prefix' => 'orders', 'as' => 'ecom_orders.'], function () {
        Route::get('/',                         [OrderController::class, 'index'])->name('order_manage');
        Route::get('/create',                   [OrderController::class, 'create'])->name('order_add');
        Route::post('/',                        [OrderController::class, 'store'])->name('store');
        Route::post('/{order}',                 [OrderController::class, 'approval'])->name('approval');
        Route::get('/{order}/edit',             [OrderController::class, 'edit'])->name('edit');
        Route::get('/{order}/{notification?}',  [OrderController::class, 'show'])->name('show');
        Route::put('/{order}',                  [OrderController::class, 'update'])->name('update');
        Route::delete('/{order}',               [OrderController::class, 'destroy'])->name('destroy');
    });


    Route::group(['prefix' => 'sales', 'as' => 'ecom_sales.'], function () {
        Route::get('/',                         [SaleController::class, 'index'])->name('manage_sale');
        Route::get('/create',                   [SaleController::class, 'create'])->name('add_sale');
        Route::post('/',                        [SaleController::class, 'store'])->name('store');
        Route::post('/payment',                 [SaleController::class, 'payment'])->name('payment');
        Route::get('/{product}',                [SaleController::class, 'getVariantsByProduct'])->name('getVariantsByProduct');
        Route::get('/{sale}/edit',              [SaleController::class, 'edit'])->name('edit');
        Route::put('/{sale}',                   [SaleController::class, 'update'])->name('update');
        Route::get('/invoice/{invoice_no}',     [SaleController::class, 'showInvoice'])->name('showInvoice');


    });



    Route::group(['prefix' => 'return-sale', 'as' => 'return_sale.'], function () {
        Route::get('/',                     [SaleReturnController::class, 'index'])->name('index');
        Route::post('/',                    [SaleReturnController::class, 'store'])->name('store');
        Route::get('/{invoice_no}',         [SaleReturnController::class, 'showInvoice'])->name('showInvoice');
    });



    // Route::get('/manage-custom-order', [CustomOrderController::class, 'index'])->name('manage_custom_order');
    // Route::get('/add-custom-order', [CustomOrderController::class, 'create'])->name('add_custom_order');

    Route::get('/stock-report',         [StockReportController::class, 'stockreport'])->name('stock_report');
    Route::get('/supplier-stock-report',[StockReportController::class, 'supplierstock'])->name('supplier_stock_report');
    Route::get('/product-stock-report', [StockReportController::class, 'productreport'])->name('product_stock_report');
    Route::get('/products-by-supplier', [StockReportController::class, 'getProductBySupplier'])->name('getProductBySupplier');

    Route::get('/sales-report',         [ReportsController::class, 'salesreport'])->name('sales_report');
    Route::get('/purchase-report',      [ReportsController::class, 'purchasereport'])->name('purchase_report');

    Route::get('/product-tax-report',   [ReportsController::class, 'producttaxreport'])->name('product_tax_report');
    Route::get('/invoice-tax-report',   [ReportsController::class, 'invoicetaxreport'])->name('invoice_tax_report');

    Route::get('/sms-configuration',    [SmsSettignsController::class, 'smsconfiguration'])->name('sms_configuration');
    Route::get('/sms-template',         [SmsSettignsController::class, 'smstemplate'])->name('sms_template');
    Route::get('/email-configuration',  [EmailConfigurationController::class, 'index'])->name('email_configuration');
    
    Route::get('/manage-gateway',       [ManageGatewayController::class, 'index'])->name('manage_gateway');

    Route::get('/contact-us',           [ContactController::class, 'index'])->name('contact_us');

    Route::group(['prefix' => 'customservicecategories', 'as' => 'customservicecategory.'], function(){
        Route::get('/',                         [CustomServiceCategoryController::class, 'index'])->name('index');
        Route::post('/',                        [CustomServiceCategoryController::class, 'store'])->name('store');
        Route::put('/{customServiceCategory}',  [CustomServiceCategoryController::class, 'update'])->name('update');
        Route::delete('/{customServiceCategory}',[CustomServiceCategoryController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'customservices', 'as' => 'customservice.'], function(){
        Route::get('/',                         [OurCustomServiceController::class, 'index'])->name('index');
        Route::post('/',                        [OurCustomServiceController::class, 'store'])->name('store');
        Route::put('/{ourCustomService}',       [OurCustomServiceController::class, 'update'])->name('update');
        Route::delete('/{ourCustomService}',    [OurCustomServiceController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'customserviceproducts', 'as' => 'customserviceproduct.'], function(){
        Route::get('/',                         [CustomServiceProductController::class, 'index'])->name('index');
        Route::post('/',                        [CustomServiceProductController::class, 'store'])->name('store');
        Route::put('/{customServiceProduct}',   [CustomServiceProductController::class, 'update'])->name('update');
        Route::delete('/{customServiceProduct}',[CustomServiceProductController::class, 'destroy'])->name('destroy');
        Route::get('/{service_id}',             [CustomServiceProductController::class, 'getCategory'])->name('getCategory');
    });

    Route::group(['prefix' => 'customserviceorders', 'as' => 'customserviceorder.'], function(){
        Route::get('/',                         [CustomServiceOrderController::class, 'index'])->name('index');
        Route::post('/',                        [CustomServiceOrderController::class, 'store'])->name('store');
        Route::put('/{customServiceOrder}',     [CustomServiceOrderController::class, 'update'])->name('update');
        Route::delete('/{customServiceOrder}',  [CustomServiceOrderController::class, 'destroy'])->name('destroy');
        Route::get('/{category_id}',            [CustomServiceOrderController::class, 'getProduct'])->name('getProduct');
        Route::post('/{customServiceOrder}',    [CustomServiceOrderController::class, 'approval'])->name('approval');
    });

    // Route::get('/custom-product', [CustomProductController::class, 'index'])->name('custom_product');
    // Route::get('/custom-service', [OurCustomServiceController::class, 'index'])->name('admin.custom_service');

    Route::group(['prefix' => 'contacts', 'as' => 'contact.'], function(){
        Route::get('/',             [ContactController::class, 'index'])->name('index');
        Route::post('/',            [ContactController::class, 'store'])->name('store');
        Route::put('/{contact}',    [ContactController::class, 'update'])->name('update');
        Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'contactsinfo', 'as' => 'contactinfo.'], function(){
        Route::get('/',                         [ContactInformationController::class, 'index'])->name('index');
        Route::post('/',                        [ContactInformationController::class, 'store'])->name('store');
        Route::put('/{contactInformation}',     [ContactInformationController::class, 'update'])->name('update');
        Route::delete('/{contactInformation}',  [ContactInformationController::class, 'destroy'])->name('destroy');
    });

    // Route::get('/contact-us', [ContactController::class, 'index'])->name('contact_us');
    
    Route::group(['prefix' => 'shopbanners' , 'as' => 'shopbanner.'], function(){
        Route::get('/',         [AdminShopController::class, 'index'])->name('index');
        Route::post('/',        [AdminShopController::class, 'store'])->name('store');
        Route::put('/{shop}',   [AdminShopController::class, 'update'])->name('update');
        Route::delete('/{shop}',[AdminShopController::class, 'destroy'])->name('destroy');
    });
    
    Route::group(['prefix' => 'homepagebanner' , 'as' => 'homepagebanner.'], function(){
        Route::get('/',         [AdminHomeBannerController::class, 'index'])->name('index');
        Route::post('/',        [AdminHomeBannerController::class, 'store'])->name('store');
        Route::put('/{home}',   [AdminHomeBannerController::class, 'update'])->name('update');
        Route::delete('/{home}',[AdminHomeBannerController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'reviews', 'as' => 'review.'], function(){
        Route::get('/',             [AdminReviewController::class, 'index'])->name('index');
        Route::post('/',            [AdminReviewController::class, 'store'])->name('store');
        Route::put('/{review}',     [AdminReviewController::class, 'update'])->name('update');
        Route::delete('/{review}',  [AdminReviewController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'coupons', 'as' => 'coupon.'], function(){
        Route::get('/',             [CouponController::class, 'index'])->name('index');
        Route::post('/',            [CouponController::class, 'store'])->name('store');
        Route::put('/{coupon}',     [CouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}',  [CouponController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'applycoupons', 'as' => 'applycoupon.'], function(){
        Route::get('/',                     [AdminApplyCouponController::class, 'index'])->name('index');
        Route::post('/',                    [AdminApplyCouponController::class, 'store'])->name('store');
        Route::put('/{coupon}',             [AdminApplyCouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}',          [AdminApplyCouponController::class, 'destroy'])->name('destroy');
        Route::get('/searchProduct',        [AdminApplyCouponController::class, 'searchProduct'])->name('searchProduct');
        Route::get('/searchProductCategory',[AdminApplyCouponController::class, 'searchProductCategory'])->name('searchProductCategory');
        Route::get('/getCouponData',        [AdminApplyCouponController::class, 'getCouponData'])->name('getCouponData');
    });

    Route::group(['prefix' => 'officeacounts', 'as' => 'officeacount.'], function(){
        Route::get('/',                    [OfficeAccountController::class, 'index'])->name('index');
        Route::post('/',                   [OfficeAccountController::class, 'store'])->name('store');
        Route::put('/{officeAccount}',     [OfficeAccountController::class, 'update'])->name('update');
        Route::delete('/{officeAccount}',  [OfficeAccountController::class, 'destroy'])->name('destroy');
        Route::get('/account-report',      [OfficeAccountController::class, 'datewise_account_report'])->name('datewise_account_report');
    });

    Route::group(['prefix' => 'otherOrders', 'as' => 'otherOrder.'], function(){
        Route::get('/',                    [OtherOrderController::class, 'index'])->name('index');
        Route::post('/',                   [OtherOrderController::class, 'store'])->name('store');
        Route::put('/{otherOrder}',        [OtherOrderController::class, 'update'])->name('update');
        Route::delete('/{otherOrder}',     [OtherOrderController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'abouts', 'as' => 'about.'], function(){
        Route::get('/',                    [AdminAboutController::class, 'index'])->name('index');
        Route::post('/',                   [AdminAboutController::class, 'store'])->name('store');
        Route::put('/{about}',             [AdminAboutController::class, 'update'])->name('update');
        Route::delete('/{about}',          [AdminAboutController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'socialicons', 'as' => 'socialicon.'], function(){
        Route::get('/',                     [SocialIconController::class, 'index'])->name('index');
        Route::post('/',                    [SocialIconController::class, 'store'])->name('store');
        Route::put('/{socialicon}',         [SocialIconController::class, 'update'])->name('update');
        Route::delete('/{socialicon}',      [SocialIconController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'footer-abouts', 'as' => 'footer-about.'], function(){
        Route::get('/',                     [WebFooterController::class, 'index'])->name('index');
        Route::post('/',                    [WebFooterController::class, 'store'])->name('store');
        Route::put('/{webfooter}',          [WebFooterController::class, 'update'])->name('update');
        Route::delete('/{webfooter}',       [WebFooterController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'manage-company', 'as' => 'manage_company.'], function(){
        Route::get('/',                     [ManageCompanyController::class, 'index'])->name('index');
        Route::post('/',                    [ManageCompanyController::class, 'store'])->name('store');
        Route::put('/{company}',            [ManageCompanyController::class, 'update'])->name('update');
        Route::delete('/{company}',         [ManageCompanyController::class, 'destroy'])->name('destroy');
    });


});

 
require __DIR__.'/auth.php';
