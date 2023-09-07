@extends('backend.layouts.master')

@section('title','Website Footer')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Web Footer Settings</a> </h6>
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Add Footer</i></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Footer About</th>
                                <th>Footer Logo</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($footerdatas)
                                @foreach ($footerdatas as $item)
                                    <tr footer-data="{{ json_encode($item) }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->footer_about }}</td>
                                        <td>
                                            @if($item->footer_logo)
                                                <img src="{{ asset($item->footer_logo) }}" style="width: 80px;" alt="Category Image">
                                            @else 
                                                <img src="" style="width: 80px;" alt="Category Image">
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $item->is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.footer-about.destroy', $item->id )}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="WebFooterModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Create</span> Footer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Footer Information</h5>
                                <hr>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="footer_about">Footer About</label>
                                    <textarea name="footer_about" id="footer_about" class="form-control" placeholder="Footer About"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="">Category Image</label>
                                {!! renderFileInput(['id' => 'footer_logo', 'imageSrc' => '']) !!}
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
                        <button id="webfooter_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="webfooter_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
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
            $(document).on('click','#webfooter_save_btn', submitToDatabase)
            $(document).on('click','#reset', resetForm)

            $(document).on('click','.update', showUpdateModal)

            $(document).on('click','.delete', deleteToDatabase)
            $(document).on('click' , '#webfooter_update_btn', updateToDatabase)

            $(document).on('change' , '#footer_logo', checkImage)
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
            showModal('#WebFooterModal');
            $('#webfooter_save_btn').removeClass('d-none');
            $('#webfooter_update_btn').addClass('d-none');
            $('#WebFooterModal .heading').text('Create')
            resetData();
        }

        function submitToDatabase(){

            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.footer-about.store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function showUpdateModal(){

            resetData();

            let webfooter = $(this).closest('tr').attr('footer-data');

            if(webfooter){

                $('#webfooter_save_btn').addClass('d-none');
                $('#webfooter_update_btn').removeClass('d-none');

                webfooter = JSON.parse(webfooter);

                $('#WebFooterModal .heading').text('Edit').attr('data-id', webfooter?.id)

                $('#footer_about').val(webfooter?.footer_about)
                
                if(webfooter?.is_active){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                // show previos image on modal
                $(document).find('#img-preview').attr('src', `{{ asset('') }}${webfooter.footer_logo}`);

                showModal('#WebFooterModal');
            }


        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#WebFooterModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.footer-about.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function formatData(){
            return {
                footer_about    : $('#footer_about').val().trim(),
                footer_logo     : fileToUpload('#img-preview'),
                is_active       : $('#isActive').is(':checked') ? 1 : 0,
            };
        }

        function resetData(){
            $('#footer_about').val(null)
            $('#footer_logo').val(null)
            fileToUpload('#img-preview', '')
            $('#isActive').prop('checked', true)
        }

    </script>
@endpush
