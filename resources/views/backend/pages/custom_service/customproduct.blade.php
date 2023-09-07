@extends('backend.layouts.master')

@section('title','Custom Product')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Custom Service Product</a> </h6>
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Product</i></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#SL</th>
                                <th>Service Name</th>
                                <th>Category</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Product Thumbnail</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($customproducts)
                                @foreach ($customproducts as $customproduct)
                                    <tr customproduct-data="{{ json_encode($customproduct) }}">
                                        <td>{{ $loop->iteration}}</td>
                                        <td>{{ $customproduct->service->service_name ?? 'N/A' }}</td>
                                        <td>{{ $customproduct->category->category_name ?? 'N/A' }}</td>
                                        <td>{{ $customproduct->product_name  ?? 'N/A'}}</td>
                                        <td>{{ $customproduct->product_description ?? 'N/A' }}</td>
                                        <td>
                                            @if($customproduct->product_thumbnail)
                                                <img src="{{ asset($customproduct->product_thumbnail) }}" style="width: 80px;" alt="service Image">
                                            @else 
                                                <img src="" style="width: 80px;" alt="service Image">
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $customproduct->is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.customserviceproduct.destroy', $customproduct->id )}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customProductModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Create</span> Custom Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Product Information</h5>
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_id">Service</label>
                                    <select name="service_id" id="service_id" class="form-control" data-placeholder="Select a Service">
                                        @isset($customservices)
                                            @foreach ($customservices as $customservice)
                                                <option value="{{ $customservice->id }}">{{ $customservice->service_name }}</option>
                                            @endforeach        
                                        @endisset
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control" data-placeholder="Select a Category"></select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Product Name <span style="color: red;" class="req">*</span></label>
                                    <input type="text" class="form-control" id="product_name">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="product_description">Product Description</label>
                                    <textarea name="product_description" id="product_description" cols="0" rows="5" class="form-control"></textarea>
                                </div>
                            </div>

                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label for="product_thumbnail">Product Thumbnail</label> <br>
                                     <input type="file" name="" id="product_thumbnail" name="product_thumbnail">
                                </div>
                            </div> --}}

                            <div class="col-md-12 mb-2">
                                <label for="">Product Thumbnail</label>
                                {!! renderFileInput(['id' => 'product_thumbnail', 'imageSrc' => '']) !!}
                                <span class="v-msg"></span>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Is Active</label><br>
                                    <input type="radio" name="is_active" id="isActive" checked>
                                    <label for="isActive">Active</label>
                                    <input type="radio" name="is_active" id="isInActive">
                                    <label for="isInActive">Inactive</label>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                        <button id="customproduct_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="customproduct_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
                        <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
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
@endpush

@push('js')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/backend/libs/demo/datatables-demo.js') }}"></script>
    <script>
        
        $(document).ready(function(){
            init();

            $(document).on('click','#add', createModal)
            $(document).on('change','#service_id', loadCategoryByService)
            $(document).on('click','#customproduct_save_btn', submitToDatabase)

            $(document).on('click', '.delete', deleteToDatabase)
            $(document).on('click' , '#reset', resetForm)
            $(document).on('change' ,'#product_thumbnail', checkImage)

            $(document).on('click', '.update', showUpdateModal)
            $(document).on('click','#customproduct_update_btn', updateToDatabase)
        });


        function loadCategoryByService(){
            let service_id = $(this).val();

            $.ajax({
                url     : `{{ route('admin.customserviceproduct.getCategory','')}}/${service_id}`,
                method  : 'GET',
                beforeSend(){
                    console.log('sending ...');
                },
                success(data){

                    let options = ``;
                    if(data.length){
                        data.forEach(item => {
                            options += `<option value="${item.id}">${item.text}</option>`;
                        });
                    }

                    $(document).find('#category_id').html(options);
                },
                error(err){
                    console.log(err);
                },
            })
        }

        // call the func on change file input 
        function checkImage() {
            fileRead(this, '#img-preview');
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

                        console.log(res);
                        if(res?.success){
                            _toastMsg(res?.msg ?? 'Success!', 'success');
                            resetData();

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

        function init(){

            let arr=[
                {
                    dropdownParent  : '#customProductModal',
                    selector        : `#service_id`,
                    type            : 'select',
                },
                {
                    dropdownParent  : '#customProductModal',
                    selector        : `#category_id`,
                    type            : 'select',
                },
                {
                    selector        : `#booking_date`,
                    type            : 'date',
                    format          : 'yyyy-mm-dd',
                },
            ];

            globeInit(arr);

            // $(`#stuff`).select2({
            //     width           : '100%',
            //     dropdownParent  : $('#categoryModal'),
            //     theme           : 'bootstrap4',
            // }).val(null).trigger('change')


            // $('#booking_date').datepicker({
            //     autoclose : true,
            //     clearBtn : false,
            //     todayBtn : true,
            //     todayHighlight : true,
            //     orientation : 'bottom',
            //     format : 'yyyy-mm-dd',
            // })
        }

        function createModal(){
            showModal('#customProductModal');
            $('#customproduct_save_btn').removeClass('d-none');
            $('#customproduct_update_btn').addClass('d-none');
            $('#customProductModal .heading').text('Create')
            resetData();
        }

        function resetForm(){
            resetData()
        }
       
        function submitToDatabase(){
            //
            
            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.customserviceproduct.store') }}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData()
        }

        function showUpdateModal(){
            resetData();
            let customproduct = $(this).closest('tr').attr('customproduct-data');
            if(customproduct){

                $('#customproduct_save_btn').addClass('d-none');
                $('#customproduct_update_btn').removeClass('d-none');

                customproduct = JSON.parse(customproduct);

                $('#customProductModal .heading').text('Edit').attr('data-id', customproduct?.id)

                $('#product_name').val(customproduct?.product_name)
                $('#product_description').val(customproduct?.product_description)
                $('#service_id').val(customproduct?.service_id).trigger('change')

                setTimeout(() => {
                 $('#category_id').val(customproduct?.category_id).trigger('change')
                }, 1000);

                if(customproduct?.is_active){
                    $('#isActive').prop('checked',true)
                    }else{
                    $('#isInActive').prop('checked',true)
                }
                // show previos image on modal
                $(document).find('#img-preview').attr('src', `{{ asset('') }}${customproduct.product_thumbnail}`);

                showModal('#customProductModal');
            }
        }

        
        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#customProductModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.customserviceproduct.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function formatData(){
            return {
                service_id          : $('#service_id').val(),
                category_id         : $('#category_id').val(),
                product_name        : $('#product_name').val().trim(),
                product_description : $('#product_description').val().trim(),
                product_thumbnail   : fileToUpload('#img-preview'),
                is_active           : $('#isActive').is(':checked') ? 1 : 0,
            };
        }

        function resetData(){
            $('#service_id').val(null),
            $('#category_id').val(null),
            $('#product_name').val(null),
            $('#product_description').val(null),
            $('#product_thumbnail').val(null),
            fileToUpload('#img-preview', 'put default src'),
            $('#isActive').prop('checked', true)
        }

    </script>
@endpush
