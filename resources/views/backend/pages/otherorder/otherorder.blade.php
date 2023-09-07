    @extends('backend.layouts.master')

    @section('title','Manage Other Order')

    @section('content')
        <div>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">

                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><a href="javascript:void(0)" class="text-decoration-none">Other Orders</a> </h6>
                    <div class="inner">
                        <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.otherOrder.datewise_report') }}"><i class="fa fa-chart-line"> Other Orders Report</i></a>
                        <button class="btn btn-sm btn-success"><a class="text-white" href="{{ route('other_order_data_export') }}"><i class="fa fa-download"> Export excel</i></a></button>
                        <button class="btn btn-sm btn-info" id="add"><i class="fa fa-plus"> Order</i></button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Order Date</th>
                                    <th>Order NO</th>
                                    <th>Category</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total Price</th>
                                    <th>Discount</th>
                                    <th>Service Charge</th>
                                    <th>Advance Amount</th>
                                    <th>Due Amount</th>
                                    <th>Mobile</th>
                                    <th>Company</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Note</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($otherOrders)
                                    @foreach ($otherOrders as $otherOrder)
                                    
                                    @php
                                        $total_order_price = 0;
                                        $qty = 0;
                                        $total_prices = 0;
                                        $prices = null;
                                        $category_names = null;

                                        $itemLength = count($otherOrder->categories);
                                        $index = 0;

                                        foreach ($otherOrder->categories as $item) {
                                            $total_order_price += $item->total_order_price;
                                            $qty += $item->order_qty;
                                            $total_prices += $item->price;
                                            if (++$index === $itemLength) {
                                                $category_names .= "$item->category_name";
                                                $prices .= "$item->price";
                                            }else{
                                                $category_names .= "$item->category_name, ";
                                                $prices .= "$item->price, ";
                                            }
                                        }  
                                    @endphp
                                        <tr otherorder-data="{{ json_encode($otherOrder) }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $otherOrder->order_date ?? 'N/A' }}</td>
                                            <td><a href="{{ route('admin.otherOrder.showInvoice', $otherOrder->id) }}">{{ $otherOrder->order_no ?? 'N/A' }}</a></td>
                                            <td>{{ $category_names  ?? 'N/A' }}</td>
                                            <td>{{ $qty  ?? '0.0' }}</td>
                                            <td>{{ $prices ?? '0.0' }}</td>
                                            <td>{{ $total_order_price  ?? '0.0' }}</td>
                                            <td>{{ $otherOrder->order_discount_price  ?? '0.0' }}</td>
                                            <td>{{ $otherOrder->service_charge  ?? '0.0' }}</td>
                                            <td>{{ $otherOrder->advance_balance  ?? '0.0' }}</td>
                                            <td>{{ $otherOrder->due_price  ?? '0.0' }}</td>
                                            <td>{{ $otherOrder->moible_no  ?? 'N/A' }}</td>
                                            <td>{{ $otherOrder->institute_description  ?? 'N/A' }}</td>
                                            <td>{{ $otherOrder->address  ?? 'N/A' }}</td>
                                            <td>{{ $otherOrder->status }}</td>
                                            <td>{{ $otherOrder->note  ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                {{-- <a href="javascript:void(0)" class="fa fa-eye text-info text-decoration-none"></a> --}}
                                                <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                                <a href="{{ route('admin.otherOrder.destroy',$otherOrder->id )}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
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
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
        
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"><span class="heading">Create</span> Other Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
        
                    <div class="modal-body">
                        {{-- old modal --}}
                        <div id="service-container">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order_date">Order Date</label>
                                        <input type="text" name="order_date" id="order_date" class="form-control" placeholder="Order Date">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order_no">Order NO</label>
                                        <input type="text" name="order_no" id="order_no" class="form-control" placeholder="Order NO" />
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="customer_name">Customer Name</label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Customer" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="institute_description">Institute/Company Name</label>
                                        <textarea name="institute_description" id="institute_description" cols="" rows="1" class="form-control" placeholder="Institute Description"></textarea>
                                    </div>
                                </div>     
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="institute_description">Institute/Company Address</label>
                                        <textarea name="address" id="address" cols="" rows="1" class="form-control" placeholder="Address"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="note">Note</label>
                                        <textarea name="note" id="note" cols="" rows="1" class="form-control" placeholder="Note"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="note">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="pending">Pending</option>
                                            <option value="confirm">Confirm</option>
                                            <option value="processing">Processing</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="returned">Returned</option>
                                        </select>
                                    </div>
                                </div>

                            
                                {{-- new added code start --}}


                                <div class="col-md-12">
                                    <div class="table-responsive w-100">
                                        <table class="table table-bordered table-sm">
                                            <thead class="bg-danger text-white">
                                                <tr>
                                                    <th width="650">Category</th>
                                                    <th width="550" class="text-center">Qty</th>
                                                    <th width="550" class="text-center">Price</th>
                                                    <th width="50" class="text-center">
                                                        <button class="btn btn-sm btn-info add_row"><i class="fa fa-plus"></i> Add</button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="sale_tbody">
                                                <tr>
                                                    <td>
                                                        <div class="form-group" id="cate-name">
                                                            <input type="text" name="category_name" id="category_name" class="form-control" placeholder="Category Name" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="number" name="qty" id="qty" class="form-control calprice" placeholder="Quantity" />
                                                        </div>
                                                        <span class="v-msg"></span>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="number" name="price" id="price" class="form-control calprice" placeholder="Price" />
                                                        </div>
                                                        <span class="v-msg"></span>
                                                    </td>
                                                    <td>
                                                        <input type="number" id="total_price" class="form-control qty calculatePrice" disabled>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot id="sale_tfoot">
                                                <tr>
                                                    <th colspan="2"></th>
                                                    <th colspan="1">Total Price</th>
                                                    <th>
                                                        <input type="number" readonly id="grand_total_price" name="grand_total_price" value="0" class="text-right">
                                                    </th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2"></th>
                                                    <th colspan="1">Discount</th>
                                                    <th>
                                                        <input type="number" name="order_discount_price" id="order_discount_price" value="0" class="text-right">
                                                    </th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2"></th>
                                                    <th colspan="1">Service Charge</th>
                                                    <th>
                                                        <input type="number" name="service_charge" id="service_charge" value="0" class="text-right">
                                                    </th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2"></th>
                                                    <th colspan="1">Advance Price</th>
                                                    <th>
                                                        <input type="number" name="advance_price" id="advance_price" id="total_payment" value="0" class="text-right">
                                                    </th>
                                                    <th></th>
                                                </tr>
        
                                                <tr>
                                                    <th colspan="2"></th>
                                                    <th colspan="1">Due</th>
                                                    <th>
                                                        <input type="number" readonly name="due_price" id="due_price" value="0" class="text-right">
                                                    </th>
                                                    <th></th>
                                                </tr>
                
                                            </tfoot>
                                        </table>
                                    </div>
        
                                </div>


                                {{-- new added code end --}}
        
                            </div>
                        </div>



                        {{-- new added modal start --}}

                        
        
                    <div class="modal-footer">
                        <div class="w-100">
                            <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                            <button id="otherOrder_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                            <button id="otherOrder_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
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
                $(document).on('click','#otherOrder_save_btn', submitToDatabase)
                $(document).on('input keyup change', ".calprice", priceCalculation)
                $(document).on('input keyup change', ".totalCalprice", totalPriceCalculation)
                $(document).on('input keyup change', "#order_discount_price", discountHandler)
                $(document).on('input keyup change', "#service_charge", serviceChargeHandler)
                $(document).on('input keyup change', "#advance_price", advanceHandler)

                

                $(document).on('click', '.delete', deleteToDatabase)
                $(document).on('click','#reset', resetForm)


                $(document).on('click','.add_row', createRow)
                $(document).on('click','.deleteRow', deleteRow)


                $(document).on('click', '.update', showUpdateModal)
                $(document).on('click', '#otherOrder_update_btn', updateToDatabase)
            });

            async function advanceHandler(e) {
                    try{
                        updateTotalDuePrice(e);
                    }catch(e){
                        console.log(e);
                    }
            }

            

            async function serviceChargeHandler(e){
                try{
                    await updateTotalDuePrice(e);
                }catch(e){
                    console.log(e);
                }
            }

            async function discountHandler(e) {
                try{
                    await updateTotalDuePrice(e);
                }catch(e) {
                    console.log(e);
                }
            }

            function updateTotalDuePrice(e){
                let advance_bal = Number($('#advance_price').val()),
                    value = Number(e.target.value),
                    total_price = totalPriceUpdate(),
                    discount_price = Number($('#order_discount_price').val()),
                    service_charge = Number($('#service_charge').val());

                    if(advance_bal > 0 && discount_price > 0 && service_charge > 0) {
                        $('#due_price').val(((total_price + service_charge) - discount_price) - advance_bal);
                        $('#grand_total_price').val((total_price + service_charge) - discount_price);
                    }else if(advance_bal > 0 && service_charge == 0 && discount_price > 0) {
                        $('#due_price').val((total_price - discount_price) - advance_bal);
                        $('#grand_total_price').val((total_price) - discount_price);
                    }else if(advance_bal > 0 && discount_price == 0 && service_charge > 0) {
                        $('#due_price').val((total_price + service_charge) - advance_bal);
                        $('#grand_total_price').val(total_price + service_charge);
                    }else if(discount_price > 0 && service_charge > 0 && advance_bal == 0){
                        $('#due_price').val((total_price + service_charge) - discount_price);
                        $('#grand_total_price').val((total_price + service_charge) - discount_price);
                    }
                    else if(advance_bal > 0){
                        $('#due_price').val(total_price - advance_bal);
                    }else if(discount_price > 0) {
                        $('#grand_total_price').val(total_price - discount_price);
                        $('#due_price').val(total_price - discount_price);
                    }else if(service_charge > 0) {
                        $('#due_price').val(total_price + service_charge);
                        $('#grand_total_price').val(total_price + service_charge);
                    }
            }

            function createModal(){
                showModal('#categoryModal');
                $('#otherOrder_save_btn').removeClass('d-none');
                $('#otherOrder_update_btn').addClass('d-none');
                $('#categoryModal .heading').text('Create');
                resetData();
            }

            function showUpdateModal (){
                resetData();

                let orderData = $(this).closest('tr').attr('otherorder-data');

                const order_no = JSON.parse(orderData).order_no;
                let url = `{{ route('admin.otherOrder.get_sporders', '') }}/${order_no}`;
                $.ajax({
                        url     : url, 
                        method  : "GET",
                        success(res){
                            console.log(res);

                            displayAllOrders(res?.data);
                        },
                        error(err){
                            console.log(err);
                            // _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
                        },
                });
                console.log(JSON.parse(orderData).order_no);


                    // $('#otherOrder_save_btn').addClass('d-none');
                    // $('#otherOrder_update_btn').removeClass('d-none');

                    // orderData = JSON.parse(orderData);

                    // $('#categoryModal .heading').text('Edit').attr('data-id', orderData?.id)

                    // $('#order_date').val(orderData?.order_date)
                    // $('#order_no').val(orderData?.order_no)
                    // $('#category_name').val(orderData?.category_name)
                    // $('#qty').val(orderData?.order_qty)
                    // $('#price').val(orderData?.price)
                    // $('#total_price').val(orderData?.total_order_price)
                    // $('#advance_price').val(orderData?.advance_balance)
                    // $('#due_price').val(orderData?.due_price)
                    // $('#mobile').val(orderData?.moible_no)
                    // $('#institute_description').val(orderData?.institute_description)
                    // $('#address').val(orderData?.address)
                    // $('#note').val(orderData?.note)
                    // $('#service_charge').val(orderData?.service_charge)
                    // $('#order_discount_price').val(orderData?.order_discount_price)

            }

            function displayAllOrders(ordersData) {
                console.log('ord==',ordersData);

                    $('#otherOrder_save_btn').addClass('d-none');
                    $('#otherOrder_update_btn').removeClass('d-none');
                    $('#categoryModal .heading').text('Edit').attr('data-id', ordersData[0]?.id)

                    $('#order_date').val(ordersData[0]?.order_date)
                    $('#order_no').val(ordersData[0]?.order_no)
                    $('#advance_price').val(ordersData[0]?.advance_balance)
                    $('#due_price').val(ordersData[0]?.due_price)
                    $('#mobile').val(ordersData[0]?.moible_no)
                    $('#institute_description').val(ordersData[0]?.institute_description)
                    $('#address').val(ordersData[0]?.address)
                    $('#note').val(ordersData[0]?.note)
                    $('#service_charge').val(ordersData[0]?.service_charge)
                    $('#order_discount_price').val(ordersData[0]?.order_discount_price)
                    
                    $('#category_name').val(ordersData[0]?.category_name)
                    $('#qty').val(ordersData[0]?.order_qty)
                    $('#price').val(ordersData[0]?.price)
                    $('#total_price').val(ordersData[0]?.total_order_price)
                    showModal('#categoryModal');

                    const el = `<input type="hidden" name="order_no" value="${ordersData[0]?.id}" id="order_id" class="form-control" />`;
                    $( el ).insertAfter( $("#cate-name") );
                    
                    ordersData.map((orderData, index) => {
                        index !== 0 &&  createEditRow(orderData)
                    })
            }

            function createEditRow(orderData) {
                    let row = `
                    <tr>
                        <td>
                            <div class="form-group">
                                <input type="text" name="category_name" value="${orderData.category_name}" id="category_name" class="form-control" placeholder="Category Name" />
                                <input type="hidden" name="order_no" value="${orderData.id}" id="order_id" class="form-control" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" name="qty" value="${orderData?.order_qty}" id="qty" class="form-control calprice" placeholder="Quantity" />
                            </div>
                            <span class="v-msg"></span>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" name="price" value="${orderData?.price}" id="price" class="form-control calprice" placeholder="Price" />
                            </div>
                            <span class="v-msg"></span>
                        </td>
                        <td>
                            <input type="number" id="total_price" value="${orderData?.total_order_price}" class="form-control qty calculatePrice" disabled>
                        </td>
                        <td class="text-center">
                            <i class="fa fa-times text-danger fa-lg deleteRow" type="button"></i>
                        </td>
                    </tr>
                    `;

                    $('#sale_tbody').append(row);
            }

            function updateToDatabase(){
                ajaxFormToken();
                let fdata = formatData();
                



                let id  = $('#categoryModal .heading').attr('data-id');
                let obj = {
                    url     : `{{ route('admin.otherOrder.update', '' ) }}/${id}`, 
                    method  : "PUT",
                    data    : formatData(),
                };

                ajaxRequest(obj, { reload: true, timer: 2000 })

                // resetData();
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

            function priceCalculation(){
                let price   = Number($(this).closest('tr').find('#price').val().trim() ?? 0);
                let qty     = Number($(this).closest('tr').find('#qty').val().trim() ?? 0);

                if( price < 0 ){
                    price  =0;
                    $(this).closest('tr').find('#price').val(price)
                }
                if( qty < 0 ){
                    qty  =0;
                    $(this).closest('tr').find('#qty').val(qty)
                }

                let total   = price * qty;
                $(this).closest('tr').find('#total_price').val(total);
                
                // update total price and due
                totalPriceUpdate();
                
            }

            function totalPriceUpdate(){

                let rows = $('#sale_tbody').find('tr');
                console.log('rows length',rows.length);
                let grand_total = 0;
                [...rows].forEach(row => {
                    grand_total += Number($(row).find('#total_price').val());
                })

                if($('#order_discount_price').val() > 0){
                    $('#grand_total_price').val(grand_total - $('#order_discount_price').val());
                    $('#due_price').val(grand_total - $('#order_discount_price').val());
                }else {
                    $('#grand_total_price').val(grand_total);
                    $('#due_price').val(grand_total);
                }
                return grand_total;
            }

            function totalPriceCalculation(){
                let total_price     = Number($('#total_price').val().trim() ?? 0);
                let advance_price   = Number($('#advance_price').val().trim() ?? 0);
                let service_charge  = Number($('#service_charge').val().trim() ?? 0);
                let discount        = Number($('#order_discount_price').val().trim() ?? 0);

                if( total_price < 0 ){
                    total_price  =0;
                    $('#total_price').val(total_price)
                }

                if( advance_price < 0 ){
                    advance_price  =0;
                    $('#advance_price').val(advance_price)
                }

                let total   = ((total_price + service_charge) - discount) - advance_price;
                $('#due_price').val(total).prop('readonly', true);
            }

            function init(){

                let arr=[
                    {
                        dropdownParent  : '#categoryModal',
                        selector        : `#email_template`,
                        type            : 'select',
                    },
                    {
                        selector        : `#order_date`,
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

            function resetForm(){
                resetData()
            }

            function submitToDatabase(){
                //
                ajaxFormToken();

                let obj = {
                    url     : `{{ route('admin.otherOrder.store')}}`, 
                    method  : "POST",
                    data    : formatData(),
                };

                ajaxRequest(obj, { reload: true, timer: 2000 })
            }

            function formatData(){
                return {
                    order_date              : $('#order_date').val().trim(),
                    order_no                : $('#order_no').val().trim(),
                    customer_name           : $('#customer_name').val().trim(),
                    advance_balance         : $('#advance_price').val().trim(),
                    due_price               : $('#due_price').val().trim(),
                    service_charge          : $('#service_charge').val().trim(),
                    order_discount_price    : $('#order_discount_price').val().trim(),
                    moible_no               : $('#mobile').val().trim(),
                    institute_description   : $('#institute_description').val().trim(),
                    address                 : $('#address').val().trim(),
                    note                    : $('#note').val().trim(),
                    status                  : $('#status').val(),
                    products                : productsInfo(),
                }
            }

            function productsInfo(){

                    let rows        = $('#sale_tbody').find('tr');
                    let total_qty   = 0;
                    let grandtotal  = 0;
                    let productsArr =[];

                    [...rows].forEach( row => {
                        console.log('item===', row);
                        let category_name      = $(row).find('#category_name').val();
                        let order_qty    = Number($(row).find('#qty').val());
                        let price   = Number($(row).find('#price').val());
                        let total_order_price = Number($(row).find('#total_price').val());
                        let order_id = Number($(row).find('#order_id').val());

                        if(category_name){
                            productsArr.push({
                                category_name,
                                order_qty,
                                price,
                                total_order_price,
                                order_id : order_id || ''
                            });
                        }

                    });

                return productsArr;
            }

            function resetData(){
                $('#order_date').val(null)
                $('#order_no').val(null)
                $('#category_name').val(null)
                $('#qty').val(null)
                $('#price').val(null)
                $('#total_price').val(null)
                $('#advance_price').val(null)
                $('#due_price').val(null)
                $('#order_discount_price').val(null)
                $('#mobile').val(null)
                $('#institute_description').val(null)
                $('#address').val(null)
                $('#note').val(null)
                $('#service_charge').val(null)
            }

            function createRow(){
                    let id = new Date().getTime();
                    let row = `
                    <tr>
                        <td>
                            <div class="form-group">
                                <input type="text" name="category_name" id="category_name" class="form-control" placeholder="Category Name" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" name="qty" id="qty" class="form-control calprice" placeholder="Quantity" />
                            </div>
                            <span class="v-msg"></span>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" name="price" id="price" class="form-control calprice" placeholder="Price" />
                            </div>
                            <span class="v-msg"></span>
                        </td>
                        <td>
                            <input type="number" id="total_price" class="form-control qty calculatePrice" disabled>
                        </td>
                        <td class="text-center">
                            <i class="fa fa-times text-danger fa-lg deleteRow" type="button"></i>
                        </td>
                    </tr>
                    `;

                    $('#sale_tbody').append(row);
                }


                async function deleteRow(){
                    let 
                    currentRow  = $(this).closest('tr'),
                    rows        = $('#sale_tbody').find('tr');

                    if(rows.length <= 1){
                        alert("You can't delete Last Item?")
                        return false;
                    }

                    
                    currentRow.remove();
                    await totalPriceUpdate();
                }


        </script>


{{-- <script>

    let timeId = null;
    $(document).ready(function(){
        init();

        
        $(document).on('change','.product', getColorSizes)
        $(document).on('input keyup change', ".calculatePrice", priceCalculation)
        $(document).on('input keyup change', "#discount", priceCalculation)

        $(document).on('click','#submitToSale', submitToDatabase)
    });



    function priceCalculation(){

        let rows        = $('#sale_tbody').find('tr');
        let total_qty   = 0;
        let grandtotal  = 0;
        let discount    = Number($('#discount').val() ?? 0);

        [...rows].forEach( row => {
            let qty     = Number($(row).find('.qty').val() ?? 0);
            let price   = Number($(row).find('.sales_price').val() ?? 0);

            let total   = price * qty;
            $(row).find('.subtotal').val(total);

            total_qty  += qty;
            grandtotal += total;

        });

        // $('#grand_qty').text(total_qty);
        $('#grand_sub_total').val(grandtotal);
        $('#grand_total').val(grandtotal - discount);
    }

    


    


    function init(){

        let arr=[
            {
                selector        : `#customer`,
                type            : 'select',
            },
            {
                selector        : `#color`,
                type            : 'select'
            },
            {
                selector        : `#size`,
                type            : 'select'
            },
            {
                selector        : `#sale_date`,
                type            : 'date',
                format          : 'yyyy-mm-dd',
            },
        ];

        globeInit(arr);

        InitProduct();
    }


    function submitToDatabase(){
        //

        ajaxFormToken();

        clearTimeout(timeId)

        timeId = setTimeout(() => {
            let obj = {
                url     : `{{ route('admin.ecom_sales.store') }}`, 
                method  : "POST",
                data    : formatData(),
            };

            $.ajax({
                ...obj,
                success(res){
                    console.log(res);
                     if(res?.success){
                        _toastMsg(res?.msg ?? 'Success!', 'success');
                        resetData()
                    }else{
                        _toastMsg(res?.msg ?? 'Something wents wrong!');
                    }
                },
                error(err){
                    console.log(err);
                    _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
                },
            })
        }, 500);

    }


    function InitProduct(selector='#product_name'){
        $(selector).select2({
            width               : '100%',
            theme               : 'bootstrap4',
            minimumInputLength  : 2,
            ajax: {
                url         : `{{ route('admin.purchase.searchProduct')}}`,
                dataType    : 'json',
                type        : "GET",
                quietMillis : 50,
                data        : function (term) {
                    return {
                        term: term
                    };
                },
                processResults : function (data) {
                    console.log(data);
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.product_name ?? 'N/A',
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
    }


    function formatData(){
        return {
            sale_date           : $('#sale_date').val(),
            customer_id         : $('#customer').val(),
            customer_name       : $('#customer').find('option:selected').text(),
            order_subtotal      : $('#grand_sub_total').val().trim() ?? 0,
            discount_price      : $('#discount').val().trim() ?? 0,
            order_grand_total   : $('#grand_total').val().trim() ?? 0,
            total_payment       : $('#total_payment').val().trim() ?? 0,
            products            : productsInfo()
        }
    }

    function productsInfo(){

        let rows        = $('#sale_tbody').find('tr');
        let total_qty   = 0;
        let grandtotal  = 0;
        let productsArr =[];

        [...rows].forEach( row => {
            let product_id      = $(row).find('.product').val();
            let product_name    = $(row).find('.product').find('option:selected').text();
            let product_color   = $(row).find('.color').val();
            let product_size    = $(row).find('.size').val();
            let product_qty     = Number($(row).find('.qty').val() ?? 0);
            let sales_price     = Number($(row).find('.sales_price').val() ?? 0);
            let purchase_price  = Number($(row).find('.purchase_price').val() ?? 0);
            let subtotal        = Number($(row).find('.subtotal').val() ?? 0);

            if(product_id){
                productsArr.push({
                    product_id,
                    product_name,
                    product_color,
                    product_size,
                    product_qty,
                    sales_price,
                    purchase_price,
                    subtotal
                });
            }

        });

        return productsArr;
    }



    function resetData(){
        $('#sale_date').val('')
        $('#customer').val(null).trigger('change')
        $('#grand_sub_total').val(0)
        $('#discount').val(0)
        $('#grand_total').val(0)

        let rowDefault = `<tr>
            <td>
                <div class="form-group">
                    <select name="product" class="product" data-required id="product_name"
                        data-placeholder="Select Product"></select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select name="color" class="color" data-required id="color" data-placeholder="Select Color"></select>
                </div>
                <span class="v-msg"></span>
            </td>
            <td>
                <div class="form-group">
                    <select name="size" class="size" data-required id="size" data-placeholder="Select Size"></select>
                </div>
                <span class="v-msg"></span>
            </td>
            <td>
                <input type="number" class="form-control qty calculatePrice">
            </td>
            <td>
                <input type="number" class="form-control sales_price calculatePrice">
                <input type="hidden" class="form-control purchase_price">
            </td>
            <td>
                <input type="number" readonly class="form-control subtotal text-right px-2">
            </td>
            <td class="text-center">
                <i class="fa fa-times text-danger fa-lg deleteRow" type="button"></i>
            </td>
        </tr>`;

        $('#sale_tbody').html(rowDefault);

        init();
    }

</script> --}}
    @endpush
