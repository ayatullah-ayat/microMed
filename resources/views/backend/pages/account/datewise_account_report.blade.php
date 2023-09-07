@extends('backend.layouts.master')

@section('title','Account Report')

@section('content')
    <div>
        <div>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
    
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><a href="javascript:void(0)" class="text-decoration-none">Date Wise Account Report</a></h6>
                    <a class="btn btn-sm btn-danger downloadPDF"><i class="fa fa-file-pdf"></i> PDF</a>
                </div>
    
                <div class="card-body">
    
                    <div class="row align-items-end">
                        <div class="col-md-2" data-col="col">
                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="text" data-required autocomplete="off" value="{{ date('Y-m-d')}}" class="form-control" id="from_date"
                                    name="from_date">
                            </div>
                            <span class="v-msg"></span>
                        </div>
    
                        <div class="col-md-2" data-col="col">
                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="text" data-required autocomplete="off" class="form-control" id="to_date"
                                    name="to_date">
                            </div>
                            <span class="v-msg"></span>
                        </div>
    
                        <div class="col-md-2 pb-3">
                            <button class="btn btn-sm btn-success" id="search_result"><i
                                    class="fa fa-search"></i>Search</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="mydataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr style="background-color: #eee !important;">
                                    <th>SL</th>
                                    <th>Account Type</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Note</th>
                                    <th>Cash In</th>
                                    <th>Cash out</th>
                                    <th>Current Balance</th>
                                </tr>
                            </thead>
                            <tbody>
    
                                <tr class="odd text-center dataTables_empty_row">
                                    <td colspan="8" class="dataTables_empty" valign="top">No data available in table</td>
                                </tr>
    
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #eee !important;">
                                    <th colspan="5"></th>
                                    <th id="totalCashInt">0</th>
                                    <th id="totalCashOut">0</th>
                                    <th id="totalBalance">0</th>
                                </tr>
                            </tfoot>
    
                        </table>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
    
@endsection


@push('css')
<!-- Custom styles for this page -->
<link href="{{ asset('assets/backend/css/currency/currency.css')}}" rel="stylesheet">
<style>
    .dataTables_empty {
        font-size: 20px;
        color: #f77c7c;
        font-weight: bold;
    }
</style>
@endpush


@push('js')
<script>
    let timeId = null;
    $(document).ready(function(){
        init();
        $(document).on('click','#search_result', getInvoices)
        $(document).on('click','.downloadPDF', openPDF)
    })

    function getInvoices(){
            let 
            from_date   = $('#from_date').val(),
            to_date     = $('#to_date').val();


            clearTimeout(timeId);

            timeId = setTimeout(() => {
                $.ajax({
                    url     : `{{ route('admin.officeacount.datewise_account_report') }}`,
                    method  : 'GET',
                    data    : { from_date, to_date },
                    beforeSend(){
                        $('#mydataTable').find('tbody').html(`
                            <tr class="odd text-center dataTables_empty_row">
                                <td colspan="8" class="h6" valign="top">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i> Loading ...
                                </td>
                            </tr>
                        `);
                    },
                    success(data){
                        loadAjaxData(data);
                    },
                    error(err){
                        console.log(err);
                    },
                })
            }, 500);
    }

    function loadAjaxData(resData){

            let 
            count           = 0,
            totalCashInt    = 0,
            totalCashOut    = 0,
            totalBalance    = 0,
            html            = ``;

            if(resData.length){

                resData.forEach(officeaccount => {
    
                    totalCashInt += officeaccount.cash_in ?? 0;
                    totalCashOut += officeaccount.cash_out ?? 0;
                    totalBalance += officeaccount.current_balance ?? 0;
    
                    html += `<tr>
                        <td>${++count}</td>
                        <td>${officeaccount.account_type ?? 'N/A' }</td>
                        <td>${officeaccount.date }</td>
                        <td>${officeaccount.description ?? 'N/A' }</td>
                        <td>${officeaccount.note ?? 'N/A' }</td>
                        <td>${officeaccount.cash_in ?? '0.0' }</td>
                        <td>${officeaccount.cash_out ?? '0.0' }</td>
                        <td>${officeaccount.current_balance ?? '0.0' }</td>
                    </tr>`;
                })
            }else{
                html += `<tr class="odd text-center dataTables_empty_row">
                    <td colspan="8" class="dataTables_empty" valign="top">No data available in table</td>
                </tr>`;
            }

            setTimeout(function(){
                $('#mydataTable').find('tbody').html(html);
                $('#totalCashInt').text(totalCashInt);
                $('#totalCashOut').text(totalCashOut);
                $('#totalBalance').text(totalBalance);
            },1000)

    }

    function init(){

        $('#from_date').datepicker({
            autoclose       : true,
            clearBtn        : false,
            todayBtn        : true,
            todayHighlight  : true,
            orientation     : 'bottom',
            format          : 'yyyy-mm-dd',
        })

         $('#to_date').datepicker({
            autoclose       : true,
            clearBtn        : false,
            todayBtn        : true,
            todayHighlight  : true,
            orientation     : 'bottom',
            format          : 'yyyy-mm-dd',
        })
    }

    function openPDF(e){
        e.preventDefault();

        let
        from_date = $('#from_date').val(),
        to_date = $('#to_date').val();

        open(`{{ route('admin.officeacount.datewise_account_pdf') }}?from_date=${from_date}&to_date=${to_date}`,'_self')
    }

</script>

@endpush

