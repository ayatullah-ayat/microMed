@extends('backend.layouts.master')

@section('title','Account Management')

@section('content')
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Daily Account Manament</a> </h6>
                <div class="inner">
                    <button class="btn btn-sm btn-success"><a class="text-white" href="{{ route('office_account_csv')}}"><i class="fa fa-download"> Export excel</i></a></button>
                    <a class="btn btn-sm btn-outline-success" href="{{ route('admin.officeacount.datewise_account_report') }}"><i class="fa fa-landmark"> Account Report</i></a>
                    <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Account</i></button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Account Type</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Cash In</th>
                                <th>Cash out</th>
                                <th>Current Balance</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($officeaccounts)
                                {{-- @dd($officeaccounts) --}}
                                @foreach ($officeaccounts as $officeaccount)
                                    <tr data-officeAccount="{{ json_encode($officeaccount) }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $officeaccount->account_type ?? 'N/A' }}</td>
                                        <td>{{ $officeaccount->date }}</td>
                                        <td>{{ $officeaccount->description ?? 'N/A' }}</td>
                                        <td>{{ $officeaccount->cash_in ?? '0.0' }}</td>
                                        <td>{{ $officeaccount->cash_out ?? '0.0' }}</td>
                                        <td>{{ $officeaccount->current_balance ?? '0.0' }}</td>
                                        <td>{{ $officeaccount->note ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="fa fa-eye text-info text-decoration-none detail"></a>
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.officeacount.destroy', $officeaccount->id )}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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

    <div class="modal fade" id="AccountModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Create</span> Account Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div id="service-container">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_type">Account Type</label>
                                    <input type="text" name="account_type" id="account_type" class="form-control" placeholder="Account Type" />
                                </div>
                            </div>

                            <div class="col-md-6" data-col="col">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="text" data-required="" autocomplete="off" class="form-control" id="date" name="date" placeholder="Date">
                                </div>
                                <span class="v-msg"></span>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea type="text" name="description" id="description" cols="0" rows="3" class="form-control" placeholder="Description"></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cash_in">Cash In</label>
                                    <input type="number" name="cash_in" id="cash_in" class="form-control cash" placeholder="Cash In" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cash_out">Cash Out</label>
                                    <input type="number" name="cash_out" id="cash_out" class="form-control cash" placeholder="Cash Out" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="current_balance">Current Balance</label>
                                    <input type="number" name="current_balance" readonly id="current_balance" class="form-control" placeholder="Current Balance" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea type="text" name="note" id="note" cols="0" rows="3" class="form-control" placeholder="Note"></textarea>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <div class="w-100">
                        <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                        <button id="account_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                        <button id="account_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
                        <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
                    </div>
                </div>
    
            </div>
        </div>
    </div>

    <div class="modal fade" id="accountDetailModal"  tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
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
            $(document).on('click','#account_save_btn', submitToDatabase)
            $(document).on('click', '#reset', resetForm)
            $(document).on('click','.delete', deleteToDatabase)

            $(document).on('click', '.update', showUpdateModal)
            $(document).on('click', '#account_update_btn', updateToDatabase)
            $(document).on('click','.detail', showDataToModal)
            $(document).on('input change','.cash', setTotalValue)
        });

        function setTotalValue(){

            let cashIn  = $('#cash_in').val().trim() ?? 0;
            let cashOut = $('#cash_out').val().trim() ?? 0;

            if(Number(cashIn) < 0){
                $('#cash_in').val(0);
                cashIn = 0;
            } 

            if(Number(cashOut) < 0){
                $('#cash_out').val(0);
                cashOut = 0;
            } 

            let currentBalance = Number(cashIn) - Number(cashOut);

            $('#current_balance').val(currentBalance);
            

        }

        function showDataToModal(){
            let 
            elem        = $(this),
            tr          = elem.closest('tr'),
            accountInfo    = tr?.attr('data-officeAccount') ? JSON.parse(tr.attr('data-officeAccount')) : null,
            modalDetailElem = $('#modalDetail');

            if(accountInfo){
                let html = `
                <table class="table table-sm table-bordered table-striped">
                    
                    <tr>
                        <th> Account Type </th>
                        <td>${accountInfo.account_type ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Date </th>
                        <td>${accountInfo.date ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Description </th>
                        <td>${accountInfo.description ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Cash In </th>
                        <td>${accountInfo.cash_in ?? '0.0'}</td>
                    </tr>
                    <tr>
                        <th> Cash Out </th>
                        <td>${accountInfo.cash_out ?? '0.0'}</td>
                    </tr>
                    <tr>
                        <th> Cash In </th>
                        <td>${accountInfo.current_balance ?? '0.0'}</td>
                    </tr>
                    <tr>
                        <th> Note </th>
                        <td>${accountInfo.note ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th> Created By </th>
                        <td>${accountInfo.created_by_name ?? 'N/A'}</td>
                    </tr>
                    ${
                        accountInfo.updated_by_name ? `<tr>
                        <th> Updated By </th>
                        <td>${accountInfo.updated_by_name ?? 'N/A'}</td>
                    </tr>` : ''
                    }
                    
                </table>
                `;

                modalDetailElem.html(html);
            }

            $('#accountDetailModal').modal('show')
        }

        function init(){

            let arr=[
                {
                    dropdownParent  : '#AccountModal',
                    selector        : `#email_template`,
                    type            : 'select',
                },
                {
                    selector        : `#date`,
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
            showModal('#AccountModal');
            $('#account_save_btn').removeClass('d-none');
            $('#account_update_btn').addClass('d-none');
            $('#AccountModal .heading').text('Create');
            resetData();
        }

        function resetForm(){
            resetData();
        }

        function showUpdateModal(){
            resetData();

            let officeaccount = $(this).closest('tr').attr('data-officeAccount');

            if(officeaccount){

                $('#account_save_btn').addClass('d-none');
                $('#account_update_btn').removeClass('d-none');

                officeaccount = JSON.parse(officeaccount);

                $('#AccountModal .heading').text('Update').attr('data-id', officeaccount?.id)

                $('#date').val(officeaccount?.date)
                $('#account_type').val(officeaccount?.account_type)
                $('#description').val(officeaccount?.description)
                $('#cash_in').val(officeaccount?.cash_in)
                $('#cash_out').val(officeaccount?.cash_out)
                $('#current_balance').val(officeaccount?.current_balance)
                $('#note').val(officeaccount?.note)

                showModal('#AccountModal');
            }
        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#AccountModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.officeacount.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            // resetData();

            // hideModal('#AccountModal');
        }

        function submitToDatabase(){
            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.officeacount.store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
            // resetData();
            // hideModal('#AccountModal');
        }

        function formatData(){

            let cash_in             = $('#cash_in').val(); 
            let cash_out            = $('#cash_out').val(); 
            let current_balance     = $('#current_balance').val(); 
            
            if(!cash_in) cash_in = 0;
            if(!cash_out) cash_out = 0;

            return {
                date            : $('#date').val(),
                account_type    : $('#account_type').val(),
                description     : $('#description').val().trim(),
                cash_in,
                cash_out,
                current_balance,
                note            : $('#note').val().trim(),
            };
        }

        function resetData(){
            $('#date').val(null)
            $('#account_type').val(null)
            $('#description').val(null)
            $('#cash_in').val(null)
            $('#cash_out').val(null)
            $('#current_balance').val(null)
            $('#note').val(null)
        }

    </script>
@endpush
