@extends('backend.layouts.master')

@section('title','Category pages')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Categories</a> </h6>
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Category</i></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#SL</th>
                                <th>Category Name</th>
                                <th>Category Description</th>
                                <th>Category Image</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($categories)
                                @foreach ($categories as $category)
                                    <tr data-category="{{json_encode($category)}}">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $category->category_name ?? 'N/A'}}</td>
                                        <td>{{ $category->category_description	 ?? 'N/A'}}</td>
                                        <td>
                                            @if($category->category_image)
                                                <img src="{{ asset($category->category_image) }}" style="width: 80px;" alt="Category Image">
                                            @else 
                                                <img src="" style="width: 80px;" alt="Category Image">
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $category->is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="fa fa-eye text-info text-decoration-none detail"></a>
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.category.destroy',$category->id) }}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="categoryModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"><span class="heading">Create</span> Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Category Information</h5>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Category Name<span style="color: red;" class="req">*</span></label>
                                    <input type="text" class="form-control" id="categoryName" placeholder="Category Name">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                   <label for="">Category Description</label>
                                   {{-- <textarea rows="4" type="text" class="form-control"> --}}
                                    <textarea class="form-control" name="" id="categoryDescription" cols="30" rows="5" placeholder="Category Description"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="">Category Image</label>
                                {!! renderFileInput(['id' => 'categoryImage', 'imageSrc' => '']) !!}
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
                        <button id="category_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="category_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
                        <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
                    </div>
                </div>
    
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoryDetailModal"  tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Category Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body" id="modalDetail"></div>
    
                <div class="modal-footer">
                    <div class="w-100">
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
            $(document).on('click','.detail', showDataToModal)
            $(document).on('click','.update', showUpdateModal)

            $(document).on('click','#reset', resetForm)
            $(document).on('click','#category_save_btn', submitToDatabase)
            $(document).on('click','.delete', deleteToDatabase)
            $(document).on('click' , '#category_update_btn', updateToDatabase)
            $(document).on('change' , '#categoryImage', checkImage)
        });


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

                        // console.log(res?.data);
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

        function resetForm(){
            resetData();
        }

        function showDataToModal(){
            let 
            elem        = $(this),
            tr          = elem.closest('tr'),
            category    = tr?.attr('data-category') ? JSON.parse(tr.attr('data-category')) : null,
            modalDetailElem = $('#modalDetail');

            if(category){
                let html = `
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th>Category Name</th>
                        <td>${category.category_name ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th>Category Description</th>
                        <td>${category.category_description ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th>Category Image</th>
                        <td>
                            ${ category.category_image ? `
                                <img src="{{ asset('') }}${category.category_image}" style="width:80px" alt="Category Image">
                            `: ` <img src="" style="width:80px" alt="Category Image">`}
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>${category.is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>'}</td>
                    </tr>
                </table>
                `;

                modalDetailElem.html(html);
            }

            $('#categoryDetailModal').modal('show')
        }

        function init(){

            let arr=[
                {
                    dropdownParent  : '#categoryModal',
                    selector        : `#stuff`,
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
            showModal('#categoryModal');
            $('#category_save_btn').removeClass('d-none');
            $('#category_update_btn').addClass('d-none');
            $('#categoryModal .heading').text('Create')
            resetData();
        }

        function submitToDatabase(){

            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.category.store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData();
        }

        function showUpdateModal(){

            resetData();

            let category = $(this).closest('tr').attr('data-category');

            if(category){

                $('#category_save_btn').addClass('d-none');
                $('#category_update_btn').removeClass('d-none');

                category = JSON.parse(category);

                $('#categoryModal .heading').text('Edit').attr('data-id', category?.id)

                $('#categoryName').val(category?.category_name)
                $('#categoryDescription').val(category?.category_description)
                
                if(category?.is_active){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                // show previos image on modal
                $(document).find('#img-preview').attr('src', `{{ asset('') }}${category.category_image}`);

                showModal('#categoryModal');
            }


        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#categoryModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.category.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData();
        }

        function formatData(){
            return {
                category_name       : $('#categoryName').val().trim(),
                category_description: $('#categoryDescription').val().trim(),
                category_image      : fileToUpload('#img-preview'),
                is_active           : $('#isActive').is(':checked') ? 1 : 0,
            };
        }

        function resetData(){
            $('#categoryName').val(null)
            $('#categoryDescription').val(null)
            $('#categoryImage').val(null)
            fileToUpload('#img-preview', 'put default src')
            $('#isActive').prop('checked', true)
        }

    </script>
@endpush
