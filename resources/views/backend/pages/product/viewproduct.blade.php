@extends('backend.layouts.master')

@section('title', 'Manage Product')

@section('content')

<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            @php
                $back = request('st') ? request('st') : 'index';
            @endphp

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary text-dark"><a href="javascript:void(0)" class="text-decoration-none">View Product</a> </h6>
                <a class="text-white btn btn-sm btn-info" href="{{ route("admin.products.{$back}") }}"><i class="fa fa-arrow-left"> Back</i></a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered"  width="100%" cellspacing="0">
                        <tbody>
                            <tr class="borderd bg-danger text-white">
                                <th colspan="2">
                                    <h4>
                                        Product Master Information
                                    </h4>
                                </th>
                            </tr>
                            <tr>
                                <th>Product Thumbnail</th>
                                <td>
                                    <img src="{{ asset( $product->product_thumbnail_image ?? 'N/A' ) }}" style="width: 180px;" alt="Category Image">
                                </td>
                            </tr>
                            <tr>
                                <th>Product Name</th>
                                <td>{{ $product->product_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>SKU</th>
                                <td>{{ $product->product_sku ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Sub-Category</th>
                                <td>{{ $product->subCategory->subcategory_name ?? 'N/A' }}</td>
                            </tr>
                            @if(count($product->brands))
                                @php
                                    $brands = [];
                                    foreach ($product->brands as $brand) {
                                        $brands[]= $brand->brand_name;
                                    }
                                @endphp
                            <tr>
                                <th>Brand</th>
                                <td>{{ implode(',', $brands) ?? 'N/A' }}</td>
                            </tr>
                            @endif 
                            @if(isset($product->short_description))
                            <tr>
                                <th>Short Description</th>
                                <td>{!! $product->short_description ?? 'N/A' !!}</td>
                            </tr>
                            @endif 
                            <tr>
                                <th>Description</th>
                                <td>{!! $product->product_description ?? 'N/A' !!}</td>
                            </tr>
                            <tr>
                                <th>Specification</th>
                                <td>{!! $product->product_specification ?? 'N/A' !!}</td>
                            </tr>
                            <tr>
                                <th>Unit</th>
                                <td>{{ $product->product_unit ?? 'N/A' }}</td>
                            </tr>
                           
                            <tr>
                                <th>Currency</th>
                                <td>{{ $product->currency ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Discount (%)</th>
                                <td>{{ $product->product_discount ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- @dd($product, $product->is_product_variant) --}}

                    @if($product->is_product_variant)
                    <div class="table-responsive">
                        <table class="table table-bordered"  width="100%" cellspacing="0">
                            <thead>
                                <tr class="borderd bg-danger text-white">
                                    <th colspan="7" class="text-white">
                                        <h4>
                                            Product Variation Information
                                        </h4>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Unit Price</th></th>
                                    <th>Sales Price</th>
                                    <th>WholeSale Price</th>
                                    <th>Product Qty</th>
                                    <th>Stock Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($product->sizes)
                                    {{-- @dd($product->sizes) --}}
                                        @foreach ($product->sizes as $variant)
                                            <tr>
                                                <td>{{ $variant->color_name ?? 'N/A' }}</td>
                                                <td>{{ $variant->size_name ?? 'N/A' }}</td>
                                                <td>{{ $variant->unit_price ?? 'N/A' }}</td>
                                                <td>{{ $variant->sales_price ?? 'N/A' }}</td>
                                                <td>{{ $variant->wholesale_price ?? 'N/A' }}</td>
                                                @if($product->is_product_variant)
                                                <td>{{ $variant->product_qty ?? 'N/A' }}</td>
                                                <td>{{ $variant->stock_qty ?? 'N/A' }}</td>
                                                @endif 
                                            </tr>
                                        @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if(!$product->is_product_variant)
                    <div class="table-responsive">
                        <table class="table table-bordered"  width="100%" cellspacing="0">
                            <thead>
                                <tr class="borderd bg-danger text-white">
                                    <th colspan="7" class="text-white">
                                        <h4>
                                            Product Pricing
                                        </h4>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Purchase Price</th>
                                    <th>Unit Price</th>
                                    <th>Sales Price</th>
                                    <th>Wholesale Price</th>
                                    <th>Total Qty</th>
                                    <th>Stock Qty</th>
                                    <th>Stock Out Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ round($product->purchase_price, 0) ?? 0 }}</td>
                                    <td>{{ round($product->unit_price, 0) ?? 0 }}</td>
                                    <td>{{ salesPrice($product) ?? 0 }}</td>
                                    <td>{{ round(wholesalesPrice($product), 0) ?? 0 }}</td>
                                    <td>{{ $product->total_product_qty ?? '0' }}</td>
                                    <td>{{ $product->total_stock_qty ?? '0' }}</td>
                                    <td>{{ $product->total_stock_out_qty ?? '0' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <div class="row bg-danger text-white pl-3">
                            <h4 style=" font-size: 1.5rem; font-weight:500; padding: 10px 0px; margin-left: 10px;">
                                Product Image Gallery
                            </h4>
                        </div>

                        <div class="row w-100">
                            @isset($product->productImages)
                                @foreach($product->productImages as $productGallery)
                                    <div class="col-md-2" data-productid="{{ $productGallery->product_id }}">
                                        <img src="{{ asset( $productGallery->product_image ?? 'N/A' ) }}" style="width: 100%; margin:10px;" alt="Category Image">
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                    </div>

                </div>
            </div>
        </div>
    
    </div>
</div>

@endsection

@push('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/css/currency/currency.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/product/product.css') }}">
@endpush

@push('js')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/backend/libs/demo/datatables-demo.js') }}"></script>
@endpush