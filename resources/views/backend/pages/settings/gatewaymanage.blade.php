@extends('backend.layouts.master')

@section('title', 'Manage Gateway')

@section('content')
<div class="container p-4 shadow">
    <div class="row">
        <div class="col-md-12">
            <div class="container">
                <h4 class="text-dark f-2x">Payment Gateway Option</h4>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                        type="button" role="tab" aria-controls="home" aria-selected="true">Stripe</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                        type="button" role="tab" aria-controls="profile" aria-selected="false">Paypal</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                        type="button" role="tab" aria-controls="contact" aria-selected="false">sslcommerz</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                   <div class="container card p-5">
                       <div class="row">

                           <div class="col-md-12">
                               <div class="form-group">
                                  <label for="">Shop ID <span style="color: red;" class="req">*</span></label>
                                  <input type="text" class="form-control">
                               </div>
                           </div>

                           <div class="col-md-12">
                               <div class="form-group">
                                  <label for="">Secret Key <span style="color: red;" class="req">*</span></label>
                                  <input type="text" class="form-control">
                               </div>
                           </div>

                          <div class="col-md-12" data-col="col">
                                <div class="form-group">
                                    <label for="action">Status</label>
                                    <select name="action" class="action" data-required id="action" data-placeholder="Select Status"></select>
                                </div>
                                <span class="v-msg"></span>
                          </div>

                       
                        <div class="col-md-12">
                            <div class="w-100">
                                <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                                <button id="category_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Submit</span></button>
                                <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>

                       </div>
                   </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="container card p-5">
                        <div class="row">
 
                            <div class="col-md-6">
                                <div class="form-group">
                                   <label for="">Paypal Email<span style="color: red;" class="req">*</span></label>
                                   <input type="email" class="form-control">
                                </div>
                            </div>
 
                            <div class="col-md-6">
                                <div class="form-group">
                                   <label for="">Client ID</label>
                                   <input type="text" class="form-control">
                                </div>
                            </div>
 
                           <div class="col-md-6" data-col="col">
                                 <div class="form-group">
                                     <label for="paypal_currency">Currency <span style="color: red;" class="req">*</span> </label>
                                     <select name="paypal_currency" class="paypal_currency" data-required id="paypal_currency" data-placeholder="Select Currency"></select>
                                 </div>
                                 <span class="v-msg"></span>
                           </div>

                           <div class="col-md-6">
                               <div class="form-group">
                                   <label for="paypal_payment">Payment</label>
                                   <select name="paypal_payment" id="paypal_payment" class="form-control" data-placeholder="Payment"></select>
                               </div>
                           </div>
 
                           <div class="col-md-12" data-col="col">
                                 <div class="form-group">
                                     <label for="paypal_status">Status <span style="color: red;" class="req">*</span> </label>
                                     <select name="paypal_status" class="paypal_status" data-required id="paypal_status" data-placeholder="Select status"></select>
                                 </div>
                                 <span class="v-msg"></span>
                           </div>
 
                        
                         <div class="col-md-12">
                             <div class="w-100">
                                 <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                                 <button id="category_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Submit</span></button>
                                 <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Cancel</button>
                             </div>
                         </div>
 
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="container card p-5">
                        <div class="row">
 
                            <div class="col-md-6">
                                <div class="form-group">
                                   <label for="">sslcommerz Email<span style="color: red;" class="req">*</span></label>
                                   <input type="text" class="form-control">
                                </div>
                            </div>
 
                            <div class="col-md-6">
                                <div class="form-group">
                                   <label for="">Store ID</label>
                                   <input type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                   <label for="">Secret Key</label>
                                   <input type="text" class="form-control">
                                </div>
                            </div>
 
                           <div class="col-md-6" data-col="col">
                                 <div class="form-group">
                                     <label for="currencies">Currency <span style="color: red;" class="req">*</span> </label>
                                     <select name="currencies" class="currencies" data-required id="currencies" data-placeholder="Select Currency"></select>
                                 </div>
                                 <span class="v-msg"></span>
                           </div>

                           <div class="col-md-6" data-col="col">
                                 <div class="form-group">
                                     <label for="payment">Payment <span style="color: red;" class="req">*</span> </label>
                                     <select name="payment" class="payment" data-required id="payment" data-placeholder="Select Payment"></select>
                                 </div>
                                 <span class="v-msg"></span>
                           </div>
 
                           <div class="col-md-6" data-col="col">
                                 <div class="form-group">
                                     <label for="status">Status <span style="color: red;" class="req">*</span> </label>
                                     <select name="status" class="status" data-required id="status" data-placeholder="Select status"></select>
                                 </div>
                                 <span class="v-msg"></span>
                           </div>
 
                        
                         <div class="col-md-12">
                             <div class="w-100">
                                 <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                                 <button id="category_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Submit</span></button>
                                 <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Cancel</button>
                             </div>
                         </div>
 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<link href="{{ asset('assets/backend/css/currency/currency.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="{{ asset('assets/backend/css/settings/managegateway.css')}}" rel="stylesheet">
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
<script>
    $(document).ready(function(){
        init();

        $(document).on('click','#add', createModal)
        $(document).on('click','#category_save_btn', submitToDatabase)
    });


    function init(){

        let arr=[
            {
                selector        : `#action`,
                type            : 'select',
            },
            {
                selector        : '#paypal_payment',
                type            : 'select'
            },
            {
                selector        : '.paypal_currency',
                type            : 'select'
            },
            {
                selector        : '.paypal_status',
                type            : 'select'
            },
            {
                selector        : '#currencies',
                type            : 'select',
            },
            {
                selector        : '.status',
                type            : 'select',
            },
            {
                selector        : '#payment',
                type            : 'select',
            },
            
        ];

        globeInit(arr);

        // $(`.category`).select2({
        //     width           : '100%',
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
    }

    function submitToDatabase(){
        //

        ajaxFormToken();

        let obj = {
            url     : ``, 
            method  : "POST",
            data    : {},
        };

        ajaxRequest(obj);

        hideModal('#categoryModal');
    }

</script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> --}}
@endpush

