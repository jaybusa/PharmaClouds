<div class="menu">
    <div class="main-menu">
        <div class="scroll">
            <ul class="list-unstyled">
                <li class="@if($currentRoute=='admin.dashboard') active @endif">
                    <a href="{{route('admin.dashboard')}}">
                        <i class="iconsminds-shop-4"></i>
                        <span>{{trans('messages.sidebar_dashboard_text')}}</span>
                    </a>
                </li>
                {{-- <li class="@if($currentRoute=='admin.orders' || $currentRoute=='admin.order_detail') active @endif">
                    <a href="{{route('admin.orders')}}">
                        <i class="iconsminds-shopping-cart"></i> {{trans('messages.sidebar_orders_text')}}
                    </a>
                </li> --}}
                <li class="@if($currentRoute=='admin.customer' || $currentRoute=='admin.hairdresser' || $currentRoute=='admin.customer_detail' || $currentRoute=='admin.hairdresser_detail') active @endif">
                    <a href="#users">
                        <i class="iconsminds-conference"></i> {{trans('messages.sidebar_users_text')}}
                    </a>
                </li>

                <li class="@if($currentRoute=='admin.parent_category' || $currentRoute=='admin.category') active @endif">
                    <a href="#category">
                        <i class="simple-icon-grid"></i>  {{trans('messages.sidebar_categories_text')}}
                    </a>
                </li>
                {{-- <li class="@if($currentRoute=='admin.settings') active @endif">
                    <a href="{{route('admin.settings')}}">
                        <i class="iconsminds-gear"></i> {{trans('messages.sidebar_settings_text')}}
                    </a>
                </li>

                <li class="@if($currentRoute=='admin.promocode') active @endif">
                    <a href="{{route('admin.promocode')}}">
                        <i class="iconsminds-clock"></i> {{trans('messages.sidebar_promocodes_text')}}
                    </a>
                </li>

                <li class="@if($currentRoute=='admin.banner') active @endif">
                    <a href="{{route('admin.banner')}}">
                        <i class="iconsminds-basket-coins"></i> {{trans('messages.sidebar_banner_text')}}
                    </a>
                </li>
                <li class="@if($currentRoute=='admin.withdrawal_requests') active @endif">
                    <a href="{{route('admin.withdrawal_requests')}}">
                        <i class="glyph-icon iconsminds-dollar"></i> {{trans('messages.sidebar_withdrawal_req_text')}}
                    </a>
                </li>
                <li class="@if($currentRoute=='admin.report'|| $currentRoute=='admin.amount_report') active @endif">
                    <a href="#report">
                        <i class="glyph-icon iconsminds-file-clipboard-file---text"></i> {{trans('messages.sidebar_report_text')}}
                    </a>
                </li> --}}



            </ul>
        </div>
    </div>

    <div class="sub-menu">
        <div class="scroll">
            <!-- <ul class="list-unstyled" data-link="dashboard">
                <li class="active">
                    <a href="Dashboard.Default.html">
                        <i class="simple-icon-rocket"></i> <span class="d-inline-block">Default</span>
                    </a>
                </li>

            </ul> -->

            <ul class="list-unstyled" data-link="users">
                {{-- <li class="@if($currentRoute=='admin.hairdresser' || $currentRoute=='admin.hairdresser_detail') active @endif">
                    <a href="{{route('admin.hairdresser')}}">
                        <i class="simple-icon-user"></i> <span class="d-inline-block">{{trans('messages.sidebar_users_hairdresser_text')}}</span>
                    </a>
                </li> --}}
                <li class="@if($currentRoute=='admin.customer' || $currentRoute=='admin.customer_detail') active @endif">
                    <a href="{{route('admin.customer')}}">
                        <i class="simple-icon-user"></i> <span class="d-inline-block">{{trans('messages.sidebar_users_management_text')}}</span>
                    </a>
                </li>
            </ul>

            <ul class="list-unstyled" data-link="category">
                <li class="@if($currentRoute=='admin.parent_category') active @endif">
                    <a href="{{route('admin.parent_category')}}">
                        <i class="iconsminds-align-justify-all"></i> <span class="d-inline-block">{{trans('messages.sidebar_parent_categories_text')}}</span>
                    </a>
                </li>
                <li class="@if($currentRoute=='admin.category') active @endif">
                    <a href="{{route('admin.category')}}">
                        <i class="iconsminds-align-justify-all"></i> <span class="d-inline-block">{{trans('messages.sidebar_category_text')}}</span>
                    </a>
                </li>
            </ul>


            <ul class="list-unstyled" data-link="report">
                <li class="@if($currentRoute=='admin.report') active @endif">
                    <a href="{{route('admin.report')}}">
                        <i class="glyph-icon iconsminds-file-clipboard-file---text"></i> <span class="d-inline-block">{{trans('messages.wallet_report')}}</span>
                    </a>
                </li>
                <li class="@if($currentRoute=='admin.amount_report') active @endif">
                    <a href="{{route('admin.amount_report')}}">
                        <i class="glyph-icon iconsminds-file-clipboard-file---text"></i> <span class="d-inline-block">{{trans('messages.dresser_amount_report')}}</span>
                    </a>
                </li>
            </ul>


        </div>
    </div>
</div>
