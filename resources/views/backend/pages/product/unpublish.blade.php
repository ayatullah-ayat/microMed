@extends('backend.layouts.master')

@section('title', 'Manage Product')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary text-dark"><a href="#" class="text-decoration-none">Manage Product</a> </h6>
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
                                <th>Is Publish</th>
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
                                            {{ round(($product->unit_price), 0) ?? 0 }} 
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
                                    <td class="text-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" data-id="{{$product->id}}" {{ $product->is_publish ? 'checked':''}}
                                            class="custom-control-input is-publish" id="{{ $product->id }}">
                                            <label class="custom-control-label" for="{{ $product->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.products.show', $product->id )}}?st=unpublish" class="fa fa-eye text-info text-decoration-none mx-2"></a>
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
            $(document).on('click','.delete-product', deleteToDatabase)
            $(document).on('change','.is-publish', changeStatus)

        })


        function changeStatus(){
           let elem     = $(this),
           is_publish   = elem.prop("checked"),
           id           = elem.attr('data-id');

           ajaxFormToken();

           $.ajax({
               url      :`{{ route('admin.products.publish', '' ) }}/${id}`, 
               method   :'POST',
               data     : {is_publish: Number(is_publish)},
               success(res){
                   if(res?.success){
                       elem.prop("checked", is_publish);
                        _toastMsg(res?.msg ?? 'Success!', 'success', 500);

                        setTimeout(()=> location.reload(), 1000)
                   }
               },
               error(err){
                   console.log(err);
                   elem.prop("checked", !is_publish);
                   _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!');
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