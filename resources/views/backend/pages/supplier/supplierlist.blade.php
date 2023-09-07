@extends('backend.layouts.master')

@section('title','Supplier page')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Manage Supplier</a> </h6>
                <div class="inner">
                    <button class="btn btn-sm btn-danger"><a class="text-white" href="{{ route('admin.supplier.supplier_list_pdf')}}"><i class="fa fa-file-pdf"> Export Pdf</i></a></button>
                    <button class="btn btn-sm btn-success"><a class="text-white" href="{{ route('supplier_export')}}"><i class="fa fa-download"> Export excel</i></a></button>
                    <button class="btn btn-sm btn-success mx-2" id="account"><i class="fa fa-user"> Supplier Ledger</i></button>
                    <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Supplier</i></button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#SL</th>
                                <th>Supplier Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($suppliers)
                                @foreach ($suppliers as $supplier)
 
                                    <tr supplier-data="{{json_encode($supplier)}}" data-id="{{ $supplier->id }}" 
                                        data-total-price="{{ $supplier->purchases()->sum('total_price') }}"
                                        data-total-payment="{{ $supplier->purchases()->sum('total_payment') }}"
                                        data-total-qty="{{ $supplier->purchases()->sum('total_qty') }}"
                                        >
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supplier->supplier_name ?? 'N/A' }}</td>
                                        <td>{{ $supplier->supplier_email  ?? 'N/A' }}</td>
                                        <td>{{ $supplier->supplier_phone  ?? 'N/A' }}</td>
                                        <td>{{ $supplier->supplier_address ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            {!! $supplier->is_active ? '<span class="badge badge-success">Active </span>' : '<span class="badge badge-danger">In-Active </span>' !!}
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                            <a href="javasript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{route('admin.supplier.destroy',$supplier->id )}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="supplierModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Create</span> Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold bg-custom-booking">Supplier Information</h5>
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Supplier Name<span style="color: red;" class="req">*</span></label>
                                    <input type="text" class="form-control" id="supplier_name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Supplier Email</label>
                                    <input type="email" class="form-control" id="supplier_email">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Supplier Phone</label>
                                    <input type="number" class="form-control" id="supplier_phone">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Supplier Address</label> 
                                    <input type="text" class="form-control" id="supplier_address"> 
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
                        <button id="supplier_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="supplier_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
                        <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
                    </div>
                </div>
    
            </div>
        </div>
    </div>



    <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true"
        role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Supplier Ledger</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <select name="supplier" id="supplier" data-placeholder="Select Supplier">
                                @foreach ($suppliers as $item)
                                <option value="{{ $item->id }}">{{ $item->supplier_name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4"></div>
    
                    <table class="table table-sm">
                        <tr>
                            <th>Total Product</th>
                            <th>:</th>
                            <td id="totalProduct">0</td>
                        </tr>
                        <tr>
                            <th>Bill Amount</th>
                            <th>:</th>
                            <td id="billAmount">0</td>
                        </tr>
                        <tr>
                            <th>Payment Amount</th>
                            <th>:</th>
                            <th id="total_payment">0</th>
                        </tr>
                        <tr>
                            <th>Payment Due</th>
                            <th>:</th>
                            <th id="total_due">0</th>
                        </tr>
                        <tr class="d-none">
                            <th>Payment Status</th>
                            <th>:</th>
                            <th id="payment_status"></th>
                        </tr>
                    </table>
    
                </div>
    
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
    <link rel="stylesheet" href="{{ asset('assets/backend/css/supplier/supplier.css')}}">
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
            $(document).on('click','#supplier_save_btn', submitToDatabase)

            $(document).on('click', '#reset', resetForm)
            $(document).on('click', '.delete', deleteToDatabase)

            $(document).on('click', '.update', showUpdateModal)
            $(document).on('click','#supplier_update_btn', updateToDatabase)

            $(document).on('click','#account', showAccountModal)
            $(document).on('change','#supplier', showAccountInfo)
        });



        function showAccountInfo(){
            let elem = $(this),
            supplier_id = elem.val(),
            row         = $(document).find(`tr[data-id=${supplier_id}]`),
            total_qty   = 0,
            total_bill  = 0,
            total_payment= 0,
            status      = '',
            statusText  = '';

            if(row?.length){
                total_qty   = Number(row?.attr('data-total-qty') ?? 0);
                total_bill  = row.attr('data-total-price');
                total_payment= row.attr('data-total-payment');

                statusText = (Number(total_bill) - Number(total_payment)) > 0 ? 'Due' : 'Paid';
                status = (Number(total_bill) - Number(total_payment)) > 0 ? 'danger' : 'success';

                if(total_qty > 0){
                    $('#payment_status').closest('tr').removeClass('d-none');
                }

            }

            $('#totalProduct').text(total_qty);
            $('#billAmount').text(total_bill);
            $('#total_payment').text(total_payment);
            $('#total_due').text(Number(total_bill) - Number(total_payment));
                        
            $('#payment_status').html(`
                <span class="badge badge-${status}">${statusText}</span>
            `);
            
        }

        function showAccountModal(){

            $('#accountModal').modal('show');
        }


        function init(){

            let arr=[
                {
                    dropdownParent  : '#supplierModal',
                    selector        : `#stuff`,
                    type            : 'select',
                },
                {
                    dropdownParent  : '#accountModal',
                    selector        : `#supplier`,
                    type            : 'select',
                },
                {
                    selector        : `#booking_date`,
                    type            : 'date',
                    format          : 'yyyy-mm-dd',
                },
            ];

            globeInit(arr);

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
            showModal('#supplierModal');
            $('#supplier_save_btn').removeClass('d-none');
            $('#supplier_update_btn').addClass('d-none');
            $('#supplierModal .heading').text('Create');
            resetData();
        }

        function resetForm(){
            resetData();
        }

        function submitToDatabase(){
            ajaxFormToken();

            let obj = {
                url     : `{{route('admin.supplier.store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
            resetData();
            hideModal('#supplierModal');
        }

        function showUpdateModal(){
            resetData();

            let supplier = $(this).closest('tr').attr('supplier-data');

            if(supplier){

                $('#supplier_save_btn').addClass('d-none');
                $('#supplier_update_btn').removeClass('d-none');

                supplier = JSON.parse(supplier);

                $('#supplierModal .heading').text('Edit').attr('data-id', supplier?.id)

                $('#supplier_name').val(supplier?.supplier_name)
                $('#supplier_email').val(supplier?.supplier_email)
                $('#supplier_phone').val(supplier?.supplier_phone)
                $('#supplier_address').val(supplier?.supplier_address)

                if(supplier?.is_active){
                    $('#isActive').prop('checked',true)
                }else{
                    $('#isInActive').prop('checked',true)
                }

                showModal('#supplierModal');
            }
        }


        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#supplierModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.supplier.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData();

            hideModal('#supplierModal');
        }


        function formatData(){
            return {
                supplier_name    : $('#supplier_name').val().trim(),
                supplier_email   : $('#supplier_email').val(), 
                supplier_phone   : $('#supplier_phone').val(), 
                supplier_address : $('#supplier_address').val(), 
                is_active        : $('#isActive').is(':checked') ? 1 : 0,
            }
        }
 
        function resetData(){
                $('#supplier_name').val(null),
                $('#supplier_email').val(null), 
                $('#supplier_phone').val(null), 
                $('#supplier_address').val(null), 
                $('#isActive').prop('checked', true)
        }

    </script>
@endpush
