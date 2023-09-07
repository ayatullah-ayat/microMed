<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15x">
            @php
            $company = getCompanyProfile();
            @endphp
            @if($company && $company->white_logo)
            <img src="{{ asset($company->white_logo) }}" alt="" class="topbar_logo" >
            @else 
            <i class="fas fa-laugh-wink"></i>
            @endif 
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            {{-- <i class="fa fa-home" aria-hidden="true"></i> --}}
            <span>Admin Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Ecommerce</div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#sales" aria-expanded="true"
            aria-controls="sales">
            {{-- <i class="fas fa-fw fa-cog"></i> --}}
            <i class="fas fa-chart-line"></i>
            <span>Sales</span>
        </a>
        <div id="sales" class="collapse" aria-labelledby="sales" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="{{ route('admin.ecom_sales.add_sale') }}">New Sale</a>
                <a class="collapse-item" href="{{ route('admin.ecom_sales.manage_sale') }}">Manage Sale</a>
                <a class="collapse-item" href="{{ route('admin.return_sale.index') }}">Return Sales</a>
                {{-- <a class="collapse-item" href="#">Pos Sale</a> --}}
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#orders" aria-expanded="true"
            aria-controls="orders">
            <i class="fa fa-truck"></i>
            <span>Orders</span>
        </a>
        <div id="orders" class="collapse" aria-labelledby="orders" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="{{ route('admin.ecom_orders.order_add') }}">New Order</a>
                <a class="collapse-item" href="{{ route('admin.ecom_orders.order_manage') }}">Manage Order</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#products" aria-expanded="true"
            aria-controls="products">
            <i class="fas fa-shopping-bag"></i>
            <span>Products</span>
        </a>
        <div id="products" class="collapse" aria-labelledby="products" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="{{ route('admin.products.create') }}">New Product</a>
                <a class="collapse-item" href="{{ route('admin.products.index') }}">Manage Products</a>
                <a class="collapse-item" href="{{ route('admin.products.unpublish') }}">Unpublish Products</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.customer.index') }}">
            <i class="fas fa-users"></i>
            <span>Customers</span>
        </a>
    </li>


    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.supplier.index')}}">
            <i class="fas fa-users"></i>
            <span>Suppliers</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#purchase" aria-expanded="true"
            aria-controls="purchase">
            <i class="fas fa-cart-arrow-down"></i>
            <span>Purchase</span>
        </a>
        <div id="purchase" class="collapse" aria-labelledby="purchase" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="{{ route('admin.purchase.create') }}">New Purchase</a>
                <a class="collapse-item" href="{{ route('admin.purchase.index') }}">Manage Purchase</a>
                <a class="collapse-item" href="{{ route('admin.purchase.manage_stock') }}">Manage Stock</a>
                <a class="collapse-item" href="{{ route('admin.return_purchase.index') }}">Return Purchases</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#category" aria-expanded="true"
            aria-controls="category">
            <i class="fas fa-list"></i>
            <span>Category</span>
        </a>
        <div id="category" class="collapse" aria-labelledby="category" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="{{ route('admin.category.index')}}">Category</a>
                <a class="collapse-item" href="{{ route('admin.subcategory.index')}}">Sub Category</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.brand.index')}}" >
            <i class="fab fa-apple"></i>
            <span>Brands</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.variant.index')}}">
            <i class="fa fa-palette"></i>
            <span>Variants</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.unit.index')}}" >
            <i class="fa fa-weight"></i>
            <span>Units</span>
        </a>
    </li>

    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#tax" aria-expanded="true"
            aria-controls="tax">
            <i class="fa fa-bullseye"></i>
            <span>Tax</span>
        </a>
        <div id="tax" class="collapse" aria-labelledby="tax" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="{{route('admin.tax.index')}}">Manage Tax</a>
            </div>
        </div>
    </li> --}}


    <li class="nav-item">
        <a class="nav-link" href="{{route('admin.currency.index')}}">
            <i class="fa fa-coins"></i>
            <span>Currency</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#coupon" aria-expanded="true"
            aria-controls="products">
            <i class="fas fa-gift"></i>
            <span>Coupon</span>
        </a>
        <div id="coupon" class="collapse" aria-labelledby="products" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="{{ route('admin.coupon.index') }}">Manage Coupon</a>
                <a class="collapse-item" href="{{ route('admin.applycoupon.index') }}">Apply Coupon</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.review.index') }}">
            <i class="fas fa-comments"></i>
            <span>Reviews</span>
        </a>
    </li>

{{-- =========================================================================== --}}
    <div class="sidebar-heading">Customize Service & Account</div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#custom_service" aria-expanded="true"
            aria-controls="custom_service">
            <i class="fab fa-servicestack"></i>
            <span>Customize Services</span>
        </a>
        <div id="custom_service" class="collapse" aria-labelledby="custom_orders" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="{{ route('admin.customservice.index') }}">Service</a>
                <a class="collapse-item" href="{{ route('admin.customservicecategory.index') }}">Service Category</a>
                <a class="collapse-item" href="{{ route('admin.customserviceproduct.index') }}">Customize Product</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.customserviceorder.index') }}">
            <i class="fas fa-truck"></i>
            <span>Customize Orders</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.otherOrder.index') }}">
            <i class="fas fa-truck"></i>
            <span>Other Orders</span>
        </a>
    </li>
    
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#account" aria-expanded="true"
            aria-controls="account">
            <i class="fa fa-landmark"></i>
            <span>Account</span>
        </a>
        <div id="account" class="collapse" aria-labelledby="account" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="#">New Entry</a>
                <a class="collapse-item" href="#">Report</a>
            </div>
        </div>
    </li> --}}

    
    <li class="nav-item">
        <a class="nav-link" href="{{route('admin.officeacount.index')}}">
            <i class="fa fa-landmark"></i>
            <span>Account Management</span>
        </a>
    </li>

    {{-- =========================================================================== --}}


    <div class="sidebar-heading">Report</div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Stock" aria-expanded="true"
            aria-controls="Stock">
            <i class="fa fa-poll-h"></i>
            <span>Report</span>
        </a>
        <div id="Stock" class="collapse" aria-labelledby="Stock" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="{{ route('admin.stock_report') }}">Stock Report</a>
                <a class="collapse-item" href="{{ route('admin.supplier_stock_report') }}">Stock Report (Supplier)</a>
                <a class="collapse-item" href="{{ route('admin.product_stock_report') }}">Stock Report (Product)</a>
                <a class="collapse-item" href="{{route('admin.purchase_report')}}">Purchase Report (Date Wise)</a>
                <a class="collapse-item" href="{{route('admin.sales_report')}}">Sales Report (Date Wise)</a>

            </div>
        </div>
    </li>

    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Report" aria-expanded="true"
            aria-controls="Report">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Report</span>
        </a>
        <div id="Report" class="collapse" aria-labelledby="Report" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="{{route('admin.sales_report')}}">Sales Report</a>
                <a class="collapse-item" href="{{ route('admin.purchase_report')}}">Purchase Report</a>
                <a class="collapse-item" href="{{ route('admin.product_tax_report')}}">Tax Report (Product Wise)</a>
                <a class="collapse-item" href="{{ route('admin.invoice_tax_report')}}">Tax Report (Invoice Wise)</a>
            </div>
        </div>
    </li> --}}

    <div class="sidebar-heading">Settings</div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#sms" aria-expanded="true"
            aria-controls="sms">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
        </a>
        <div id="sms" class="collapse" aria-labelledby="sms" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                {{-- <a class="collapse-item" href="{{ route('admin.sms_configuration') }}">SMS Configuration</a> --}}
                {{-- <a class="collapse-item" href="{{ route('admin.sms_template') }}">SMS Template</a> --}}
                <a class="collapse-item" href="{{ route('admin.manage_company.index') }}">Manage Company</a>
                {{-- <a class="collapse-item" href="{{ route('admin.manage_gateway')}}">Gateway</a> --}}
                {{-- <a class="collapse-item" href="{{ route('admin.email_configuration')}}">Email Configuration</a> --}}
            </div>
        </div>
    </li>

    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#web" aria-expanded="true"
            aria-controls="web">
            <i class="fas fa-fw fa-cog"></i>
            <span>Web Settings</span>
        </a>
        <div id="web" class="collapse" aria-labelledby="web" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                <a class="collapse-item" href="#">Sales Report</a>
                <a class="collapse-item" href="#">Purchase Report</a>
                <a class="collapse-item" href="#">Tax Report (Product Wise)</a>
                <a class="collapse-item" href="#">Tax Report (Invoice Wise)</a>
            </div>
        </div>
    </li> --}}

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Software" aria-expanded="true"
            aria-controls="Software">
            <i class="fas fa-fw fa-cog"></i>
            <span>CMS Settings</span>
        </a>
        <div id="Software" class="collapse" aria-labelledby="Software" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner  ">
                {{-- <a class="collapse-item" href="#">Web Logo</a> --}}
                {{-- <a class="collapse-item" href="#">Web Header</a> --}}
                <a class="collapse-item" href="{{ route('admin.homepagebanner.index') }}">Home Banner</a>
                <a class="collapse-item" href="{{ route('admin.footer-about.index') }}">Footer About</a>
                <a class="collapse-item" href="{{ route('admin.socialicon.index') }}">Manage Social Links</a>
                <a class="collapse-item" href="{{ route('admin.contact.index') }}">Contact Form List</a>
                <a class="collapse-item" href="{{ route('admin.contactinfo.index') }}">Contact Information</a>
                <a class="collapse-item" href="{{ route('admin.about.index') }}">About Us</a>
                <a class="collapse-item" href="{{ route('admin.shopbanner.index') }}">Shop Banner</a>
                 <a class="collapse-item" href="{{ route('admin.cms_settings.ads_manager.index') }}">Manage Ads</a>
                <a class="collapse-item" href="{{ route('admin.cms_settings.clientlogo.index') }}">Client Logo</a>
                <a class="collapse-item" href="{{ route('admin.cms_settings.partnership-logo.index') }}">Organization's Logo</a>
                <a class="collapse-item" href="{{ route('admin.cms_settings.gallery.index')}}">Our Gallery</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class=" -circle border-0" id="sidebarToggle"></button>
    </div>

</ul>