@extends('backend.layouts.master')

@section('content')

    <div> 
    
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class=" w-100 "><h5>Ecommerce</h5></div>
            </div>
        </div>
        <div class="row summary-container">
    
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Earnings
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalprofit ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4" id="stockQty" data-bs-toggle="tooltip" data-bs-placement="top-right" title="Total Sales Qty: {{ $totalsalesqty ?? 0 }}">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Sale
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalsalesprice ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4" id="stockQty" data-bs-toggle="tooltip" data-bs-placement="top-right" title="Total Return Qty: {{ $totalreturnqty ?? 0 }}">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                   Return Sale
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalreturnprice ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-xl-3 col-md-6 mb-4" id="stockQty" data-bs-toggle="tooltip" data-bs-placement="top-right" title="Total Purchase Qty: {{ $totalpurchaseqty ?? 0 }}">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Purchase
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalpurchaseprice ?? 0}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4" id="stockQty" data-bs-toggle="tooltip" data-bs-placement="top-right" title="Total Return Qty: {{ $purchasereturnqty ?? 0 }}">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Return Purchase
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $purchasereturnprice ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Customer
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customers ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Supplier
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $suppliers ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Review
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalreview ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Product
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $products ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Active Product
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeproduct ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Unpublish Product
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unpublisproduct ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalorder ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingorder ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Confirm Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $confirmorder ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Processing Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $processingorder ?? 0}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Delivered Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedorder ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Cancelled Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelorder ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4" id="stockQty" data-bs-toggle="tooltip" data-bs-placement="top-right" title="Total Stock Price: {{ $totalstockprices ?? 0 }}">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                   Total stock Qty
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalstockqty ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4" id="stockQty" data-bs-toggle="tooltip" data-bs-placement="top-right" title="Total out Stock Price: {{ $totaloutstockprices ?? 0 }}">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Stock out Qty
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totaloutstockqty ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Sales Report
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">40</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Purchase Report
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">40</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Stock Report
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">40</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Account Summary
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">40</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
    
            <!-- Earnings (Monthly) Card Example -->
            {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <!-- Content Row -->
    
        <div class="row">
    
            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header bg-danger py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-white">Earnings Overview</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart" data-currency="৳" data-earnings="{{ json_encode($monthWiseYearlyEarnings) }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-5 pb-5">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header bg-danger py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-white">Revenue Sources</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-0">
                            <canvas id="myPieChart" data-revenue="{{ json_encode($revenueSources) }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Content Row -->
        <div class="row">
    
            <!-- Content Column -->
            <div class="col-lg-6 mb-4">
    
                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Order</h6>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                            <thead>
                                <tr class="bg-danger text-white">
                                    <th>Customer</th>
                                    <th>Order NO</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($orderdata)
                                    @foreach ($orderdata as $orderItem)
                                        <tr>
                                            <td>{{ $orderItem->customer_name ?? 'N/A' }}</td>
                                            <td>{{ $orderItem->order_no ?? 'N/A' }}</td>
                                            <td>
                                                @if($orderItem->status == "pending")
                                                <span class="badge badge-warning status_modal" type="button">
                                                    {{ ucfirst($orderItem->status) }}
                                                </span>
                                                @elseif($orderItem->status == "confirm")
                                                <span class="badge badge-info status_modal" type="button">
                                                    {{ ucfirst($orderItem->status) }}
                                                </span>
                                                @elseif($orderItem->status == "processing")
                                                <span class="badge badge-dark status_modal" type="button">
                                                    {{ ucfirst($orderItem->status) }}
                                                </span>
                                                @elseif($orderItem->status == "rejected" || $orderItem->status == "cancelled" || $orderItem->status == "returned")
                                                <span class="badge badge-danger">
                                                    {{ ucfirst($orderItem->status) }}
                                                </span>
                                                @elseif($orderItem->status == "completed")
                                                <span class="badge badge-success">
                                                    Delivered
                                                </span>
                                                @endif
                                            </td>
                                            <td>{{ $orderItem->order_total_price ?? '0.0' }}</td>
                                        </tr> 
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
    
            </div>
    
            <div class="col-lg-6 mb-4">
    
                <!-- Illustrations -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Popular Product</h6>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                            <thead>
                                <tr class="bg-danger text-white">
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Max Order</th>
                                    <th>Max Order Qty</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($popularProducts as $popularProduct)
                                    <tr>
                                        <td> <a href="{{ route('admin.products.show', $popularProduct->product_id )}}">{{ $popularProduct->product_name ?? 'N/A' }}</a></td>
                                        <td>{{ $popularProduct->category_name ?? 'N/A' }}</td>
                                        <td>{{ $popularProduct->max_order_product ?? 'N/A' }}</td>
                                        <td>{{ $popularProduct->max_order_product_qty ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- Customization --}}
        <div class="row">
            <h5 class="col-md-12">Customized Product</h5>
        </div>

        <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Service
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customservices ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Product
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customproducts ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Active Product
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customactiveproducts ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customorders ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Confirm Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customorderconfirm ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customorderpending ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Processing Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customorderprocessing  ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Delivered Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customordercomplete ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Cancelled Order
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customordercancel ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
    
    </div>
@endsection


@push('css')
    <style>
        .summary-container .card{
            position: relative;
            transform-style: preserve-3d;
        }

        .summary-container .card::before{
            position: absolute;
            content: '';
            width: 0;
            height: 0;
            bottom: -13px;
            left: -4px;
            border-left: 15px solid transparent;
            border-bottom: 0px solid transparent;
            border-top:15px solid #c0bbbb;
            transform: translateZ(-1px);
        }
    </style>
@endpush
