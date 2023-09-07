@extends('backend.layouts.master')

@section('title', 'Manage Product')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary text-dark"><a class="text-decoration-none">Manage Product</a> </h6> 
                <div class="inner">
                    <button class="btn btn-sm btn-danger"><a class="text-white" href="{{ route('admin.products.product_pdf')}}"><i class="fa fa-file-pdf"> Export Pdf</i></a></button>
                    <button class="btn btn-sm btn-success"><a class="text-white" href="{{ route('product_export')}}"><i class="fa fa-download"> Export excel</i></a></button>
                    <button class="btn btn-sm btn-info"><a class="text-white" href="{{ route('admin.products.create') }}"><i class="fa fa-plus"> Product</i></a></button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#SL</th>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Sub-Category</th>
                                <th>Unit</th>
                                <th>Unit Price</th>
                                <th>Sales Price</th>
                                <th>Total Qty</th>
                                <th>Stock Qty</th>
                                <th width="70" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($products as $product)

                                <tr data-productid="{{ $product->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img style="width: 80px;" src="{{ asset($product->product_thumbnail_image) }}" alt="Product Image"> <br/>
                                    </td>
                                    <td>{{ $product->product_name ?? 'N/A' }}</td>
                                    <td>{{ $product->category_name ?? 'N/A' }}</td>
                                    <td>{{ $product->subcategory_name ?? 'N/A' }}</td>
                                    <td>{{ $product->product_unit ? strtoupper($product->product_unit) : 'N/A' }}</td>
                                    <td>
                                        @if($product->is_product_variant)
                                            <span class="view-variant-product badge badge-info" type="button">Variant Product</span>
                                        @else 
                                            {{ round($product->unit_price, 0) ?? 0 }} 
                                        @endif 
                                    </td>
                                    <td>
                                        @if($product->is_product_variant)
                                        <span class="view-variant-product badge badge-info" type="button">Variant Product</span>
                                        @else
                                        {{  salesPrice($product) ?? 0 }}
                                        @endif
                                    </td>
                                    <td>{{ $product->total_product_qty ?? 0 }}</td>
                                    <td>{{ $product->total_stock_qty ?? 0 }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.products.show', $product->id )}}" class="fa fa-eye text-info text-decoration-none"></a>
                                        <a href="{{ route('admin.products.edit',$product->id )}}" class="fa fa-edit mx-2 text-warning text-decoration-none"></a>
                                        <a href="{{ route('admin.products.destroy', $product->id) }}" class="fa fa-trash text-danger text-decoration-none delete-product"></a>
                                    </td>
                                </tr>
                            @endforeach

                            
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    
    </div>
</div>

<div class="modal fade" id="variantDetails" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true"
    role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Product Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="product-info"></div>

            <div class="modal-footer">
                <div class="w-100">
                    <button type="button" class="btn btn-sm btn-danger float-right mx-1"
                        data-dismiss="modal">Close</button>
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
    <script>
        $(document).ready(function(){
            $(document).on('click','.view-variant-product', getVariatProductInfo)
            $(document).on('click','.delete-product', deleteToDatabase)

        })

        function getVariatProductInfo(){

            $('#variantDetails').modal('show')
            let product_id = $(this).closest('tr').attr('data-productid');

            $.ajax({
                url     : `{{ route('admin.variant.show','') }}/${product_id}`,
                method  : 'GET',
                data    : { product_id },
                success(data){
                    // console.log(data);
                    let 
                    totalPrice          = 0,
                    totalSalesPrice     = 0,
                    totalWholesalePrice = 0,
                    productQty          = 0,
                    stockQty            = 0,
                    html                = ``;

                    if(data.length){

                        html += `
                        <table class="table table-sm table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <td>Color</td>
                                    <td>Size</td>
                                    <td>Unit Price</td>
                                    <td>Sales Price</td>
                                    <td>Wholesale Price</td>
                                    <td>Product Qty</td>
                                    <td>Stock Qty</td>
                                </tr>
                            </thead>
                            <tbody>`;

                        data.forEach(d => {

                            totalPrice          += d.unit_price ?? 0;
                            totalSalesPrice     += d.sales_price ?? 0;
                            totalWholesalePrice += d.wholesale_price ?? 0;
                            productQty          += d.product_qty ?? 0;
                            stockQty            += d.stock_qty ?? 0;

                            html += `
                                <tr>
                                    <td>${d.color_name}</td>
                                    <td>${d.size_name}</td>
                                    <td>${d.unit_price}</td>
                                    <td>${d.sales_price}</td>
                                    <td>${d.wholesale_price}</td>
                                    <td>${d.product_qty}</td>
                                    <td>${d.stock_qty}</td>
                                </tr>
                            `;
                        })

                        html += `
                            </tbody>
                            <tfoot>
                                <tr class="bg-dark text-white">
                                    <td colspan="2"></td>
                                    <td>${totalPrice}</td>
                                    <td>${totalSalesPrice}</td>
                                    <td>${totalWholesalePrice}</td>
                                    <td>${productQty}</td>
                                    <td>${stockQty}</td>
                                </tr>
                            </tfoot>
                        </table>
                        `;

                    }else{
                        html += `<div class="w-100 alert alert-danger px-5">
                            <h5>404</h5>
                            <p>No Data Found!</p>
                        </div>`;
                    }


                    $('#product-info').html(html);
                },
                error(err){
                    console.log(err);
                },
            })
        }

        function deleteToDatabase(e){
            e.preventDefault();

            let elem = $(this),
            href = elem.attr('href');
            if(confirm("Are you sure to delete the record?")){
                ajaxFormToken();

                $.ajax({
                    url     : href, 
                    method  : "DELETE",
                    data    : {},
                    success(res){

                        // console.log(res?.data);
                        if(res?.success){
                            _toastMsg(res?.msg ?? 'Success!', 'success');

                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error(err){
                        console.log(err);
                        _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
                    },
                });
            }
        }

    </script>
@endpush