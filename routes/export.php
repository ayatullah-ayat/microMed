<?php

use App\Http\Controllers\Admin\Custom\CustomServiceOrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OfficeAccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OtherOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\PurchaseReturnController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SaleReturnController;
use App\Http\Controllers\Admin\StockReportController;
use App\Http\Controllers\Admin\SupplierController;

Route::get('order-export', [OrderController::class, 'orderexport'])->name('order_export');
Route::get('orderDataCsv', [OrderController::class, 'orderDataCsv'])->name('orderDataCsv');
Route::get('product-export',[ProductController::class, 'productexport'])->name('product_export');

Route::get('/office-account-csv',   [OfficeAccountController::class, 'officeDataCsv'])->name('office_account_csv');
Route::get('/stock-report-export',  [StockReportController::class, 'exportStockReport'])->name('stock_report_export');
Route::get('/other-order-export',   [OtherOrderController::class, 'OtherOrderExport'])->name('other_order_data_export');
Route::get('/custom-service-order-export', [CustomServiceOrderController::class, 'exportCustomServiceOrder'])->name('custom_service_order_export');
Route::get('supplier-export',       [SupplierController::class, 'ExportSuppliers'])->name('supplier_export');
Route::get('/customer-export',      [CustomerController::class, 'customerExportData'])->name('customer_export');
Route::get('/sale-data-export',     [SaleController::class, 'saledataexport'])->name('sale_data_export');
Route::get('/sale-return-export', [SaleReturnController::class, 'salereturnexport'])->name('sale_return_export');
Route::get('/purchase-data-export', [PurchaseController::class, 'purchasedataexport'])->name('purchase_data_export');
Route::get('/purchase-return-export', [PurchaseReturnController::class, 'purchasereturnexport'])->name('purchase_return_export');


Route::get('/supplier-stock-report-export',         [StockReportController::class, 'supplierstockreportExport'])->name('supplier_stock_report_export');
Route::get('/purchase-product-stock-report-export', [StockReportController::class, 'purchaseProductStockReportExport'])->name('purchase_product_stock_report_export');

Route::get('/date-purchase-report-export', [ReportsController::class, 'purchasereportexport'])->name('date_purchase_report_export');
Route::get('/date-sales-report-export', [ReportsController::class, 'datesalesreportexport'])->name('date_sales_report_export');