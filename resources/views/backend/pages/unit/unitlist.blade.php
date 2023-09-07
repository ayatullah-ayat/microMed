@extends('backend.layouts.master')

@section('title', 'Unit page')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Product Unit</a> </h6>
                <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Unit</i></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#SL</th>
                                <th>Unit Name</th>
                                <th>Unit Short Name</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($units)
                                @foreach ($units as $unit)
                                    <tr unit-data="{{json_encode($unit)}}">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$unit->unit_name ?? 'N/A' }}</td>
                                        <td>{{$unit->unit_short_name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            {!! $unit->is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                            <a href="javacript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.unit.destroy', $unit->id) }}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    {{-- Variant modal  --}}

    <div class="modal fade" id="unitModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"><span class="heading">Create</span> Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">

                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Unit Information</h5>
                                <hr>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Unit Name</label>
                                    <input type="text" class="form-control" id="unit_name">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Unit Short Name</label>
                                    <input type="text" class="form-control" id="unit_short_name">
                                </div>
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
                        <button id="unit_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="unit_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
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
            $(document).on('click','#unit_save_btn', submitToDatabase)

            $(document).on('click','#reset', resetForm)
            $(document).on('click' , '.delete', deleteToDatabase)

            $(document).on('click', '.update', showUpdateModal)
            $(document).on('click', '#unit_update_btn', updateToDatabase)
        });


        function init(){

            let arr=[
                {
                    dropdownParent  : '#unitModal',
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

        function createModal(){
            showModal('#unitModal');

            $('#unit_save_btn').removeClass('d-none');
            $('#unit_update_btn').addClass('d-none');
            $('#unitModal .heading').text('Create');
            resetData()
        }

        function resetForm(){
            resetData()
        }

        function submitToDatabase(){
            //

            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.unit.store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData()

            hideModal('#unitModal');
        }

        function showUpdateModal(){
            resetData();

            let unit = $(this).closest('tr').attr('unit-data');

            if(unit){

                $('#unit_save_btn').addClass('d-none');
                $('#unit_update_btn').removeClass('d-none');

                unit = JSON.parse(unit);

                $('#unitModal .heading').text('Edit').attr('data-id', unit?.id)

                $('#unit_name').val(unit?.unit_name)
                $('#unit_short_name').val(unit?.unit_short_name)
                
                if(unit?.is_active){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                showModal('#unitModal');
            }
        }


        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#unitModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.unit.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData();

            hideModal('#unitModal');
        }


        function formatData(){
            return {
                unit_name       : $('#unit_name').val().trim(),
                unit_short_name : $('#unit_short_name').val(),
                is_active       : $('#isActive').is(':checked') ? 1 : 0,
            }
        }

        function resetData(){
            $('#unit_name').val(null),
            $('#unit_short_name').val(null),
            $('#isActive').prop('checked', true)
        }

    </script>
@endpush