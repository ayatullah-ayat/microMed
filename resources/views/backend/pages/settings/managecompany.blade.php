@extends('backend.layouts.master')

@section('title','Category pages')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="javascript:void(0)" class="text-decoration-none">Manage Company</a> </h6>
                @if(!$companydata)
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Company Info</i></button>
                @endif 
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Address</th>
                                <th>Mobile</th>
                                <th>Website</th>
                                <th>Dark Logo</th>
                                <th>White Logo</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($companydata)
                            <tr company-data="{{ json_encode($companydata) }}">
                                <td>{{ $companydata->company_name ?? 'N/A' }}</td>
                                <td>{{ $companydata->company_address ?? 'N/A' }}</td>
                                <td>{{ $companydata->company_phone ?? 'N/A' }}</td>
                                <td>{{ $companydata->company_website ?? 'N/A' }}</td>
                                <td>
                                    @if( $companydata->dark_logo )
                                        <img src="{{ asset( $companydata->dark_logo ) }}" style="width: 80px;" alt="Category Image">
                                    @else 
                                        <img src="" style="width: 80px;" alt="Category Image">
                                    @endif
                                </td>
                                <td>
                                    @if( $companydata->white_logo )
                                        <img src="{{ asset( $companydata->white_logo ) }}" style="width: 80px;" alt="Category Image">
                                    @else 
                                        <img src="" style="width: 80px;" alt="Category Image">
                                    @endif
                                </td>
                                <td class="text-center">
                                    {!! $companydata->is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                </td>
                                <td class="text-center">
                                    {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                    <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                    <a href="{{ route('admin.manage_company.destroy',$companydata->id ) }}" class="fa fa-trash text-danger text-decoration-none delete"></a>
                                </td>
                            </tr>
                            @endisset

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    
    </div>

    <div class="modal fade" id="manageCompanyModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Create</span> Company Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name">Company Name</label>
                                    <input id="company_name" name="company_name" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_address">Company Address</label>
                                    <input id="company_address" name="company_address" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_phone">Mobile</label>
                                    <input id="company_phone" name="company_phone" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_website">Company Website</label>
                                    <input id="company_website" name="company_website" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="">Dark Logo</label>
                                {!! renderFileInput(['id' => 'dark_logo', 'imageSrc' => '']) !!}
                                <span class="v-msg"></span>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="">White Logo</label>
                                {!! renderFileInput(['id' => 'white_logo', 'previewId' => 'whitelogo']) !!}
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
                        <button id="company_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="company_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
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
            $(document).on('click','#company_save_btn', submitToDatabase)
            $(document).on('click','#company_update_btn', updateToDatabase)

            $(document).on('click','.delete', deleteToDatabase)
            $(document).on('change' , '#dark_logo', checkDarkLogo)
            $(document).on('change' , '#white_logo', checkWhiteLogo)

            $(document).on('click', '.update', showUpdateModal)
        });

        function checkDarkLogo() {
            fileRead(this, '#img-preview');
        }

        function checkWhiteLogo() {
            fileRead(this, '#whitelogo');
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
            showModal('#manageCompanyModal');
            $('#company_save_btn').removeClass('d-none');
            $('#company_update_btn').addClass('d-none');
            $('#manageCompanyModal .heading').text('Create')
            resetData();
        }
        
        function showUpdateModal(){
            resetData();

            let company = $(this).closest('tr').attr('company-data');

            if(company){

                $('#company_save_btn').addClass('d-none');
                $('#company_update_btn').removeClass('d-none');

                company = JSON.parse(company);

                $('#manageCompanyModal .heading').text('Edit').attr('data-id', company?.id)

                $('#company_name').val(company?.company_name)
                $('#company_address').val(company?.company_address)
                $('#company_phone').val(company?.company_phone)
                $('#company_website').val(company?.company_website)
                
                if(company?.is_active){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                // show previos image on modal
                $(document).find('#img-preview').attr('src', `{{ asset('') }}${company.dark_logo}`);
                $(document).find('#whitelogo').attr('src', `{{ asset('') }}${company.white_logo}`);

                showModal('#manageCompanyModal');
            }
        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#manageCompanyModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.manage_company.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function submitToDatabase(){
            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.manage_company.store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function formatData(){
            return {
                company_name        : $('#company_name').val().trim(),
                company_address     : $('#company_address').val().trim(),
                company_phone       : $('#company_phone').val().trim(),
                company_website     : $('#company_website').val().trim(),
                dark_logo           : fileToUpload('#img-preview'),
                white_logo          : fileToUpload('#whitelogo'),
                is_active           : $('#isActive').is(':checked') ? 1 : 0,
            };
        }

        function resetData(){
            $('#company_name').val(null),
            $('#company_address').val(null),
            $('#company_phone').val(null),
            $('#company_website').val(null),
            $('#dark_logo').val(null),
            $('#white_logo').val(null),
            fileToUpload('#img-preview', '')
            $('#isActive').prop('checked', true)
        }

    </script>
@endpush
