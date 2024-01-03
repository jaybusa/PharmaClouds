<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the paginator library to build
    | the simple pagination links. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */
    'common' => [
        'invalid_auth_token' => 'Invalid user authentication',
        'valid_auth_token' => 'Valid user authentication',
        'token_expired' => 'Your session has expired. Please login again.',
        'invalid_token' => 'Invalid user token.',
        'token_blocked' => 'Token Blocked',
        'token_required' => 'Token Required'
    ],
    'datatableUrl'=>'127.0.0.1:8000/js/en.json',
    'login_left_title'=>'Please use your credentials to login.',
    'site_name'=>'Pharmaclouds',
    'admin_text'=>'Admin',
    'pending_orders_text'=>'Pending Orders',
    'completed_order_text'=>'Completed Orders',
    'today_income_text'=>"Today's Income",
    'week_income_text'=>'This Week Income',
    'select_theme_text'=>'Select Theme',
    'wallet_customer_paid_text' => 'Customer Paid',
    'select_language_text'=>'Select Language',
    'light_theme_text'=>'Light',
    'dark_theme_text'=>'Dark',
    'english_text'=>'English',
    'arabic_text'=>'Arabic',
    'email_text'=>'Email',
    'password_text'=>'Password',
    'confirm_password_text'=>'Confirm Password',
    'login_text'=>'Login',
    'dark_mode_text'=>'Dark Mode',
    'sidebar_dashboard_text'=>'Dashboard',
    'maximum_cash_limit' => 'Maximum Cash Limit',
    'sidebar_orders_text'=>'Orders',
    'sidebar_users_text'=>'Users',
    'sidebar_categories_text'=>'Categories',
    'sidebar_settings_text'=>'Settings',
    'home_text'=>'Home',
    'sidebar_users_hairdresser_text'=>'Pharmaclouds',
    'sidebar_users_customer_text'=>'Customer',
    'sidebar_users_management_text'=>'User Management',
    'user_management_create_text1'=>'My information',
    'user_management_create_text2'=>'Login information',

    'sidebar_parent_categories_text'=>'Parent Category',
    'sidebar_category_text'=>'Category',
    'login_otp_enable_disable_text'=>'Login OTP Enable/Disable',
    'enable_text'=>'Enable',
    'disable_text'=>'Disable',
    'tax_text'=>'Tax (%)',
    'commission_text'=>'Commission (%)',
    'support_email_text'=>'Support Email',
    'support_phone_text'=>'Support Phone',
    'copyright_text'=>'Copyright Text',
    'submit_text'=>'Submit',
    'name_required'=>'Please enter name.',
    'email_required'=>'Please enter email.',
    'email_valid'=>'Please enter valid email address.',
    'email_already_register'=>'Email already register. please try diffrent email.',
    'phone_already_register'=>'Phone Number already register. please try diffrent Phone Number.',
    'phone_number_required'=>'Please enter phone number.',
    'password_required'=>'Please enter password.',
    'password_min'=>'Please enter min 6 charater password.',
    'new_password_required'=>'Please enter new password.',
    'new_password_min'=>'Please enter min 6 charater new password.',
    'role_id_required'=>'Please enter role id.',
    'user_register_success'=>'User registered successfully.',
    'user_login_success'=>'User login successfully.',
    'login_invalid_credential'=>'Invalid credentials.',
    'user_inactive_by_admin'=>'Your account inactive by admin.',
    'not_active_account'=>'Your accountis not verified by admin yet.',
    'mobile_already_registered'=>'Mobile number is already registered please try with another mobile number.',
    'otp_required'=>'Please enter otp.',
    'otp_send_success'=>'Otp send successfully.',
    'change_password_success'=>'Change password successfully.',
    'invalid_current_password'=>'Invalid current password.',
    'admin_setting_success'=>'Settings found successfully.',
    'user_email_not_found'=>'User email not found.',
    'otp_subject'=>'Your otp.',
    'logout'=>'Logout successfully.',
    'login_failed'=>'These credentials do not match our records.',
    'tax_required'=>'Please enter tax.',
    'tax_valid'=>'Tax must be greater then zero.',
    'commission_required'=>'Please enter commission.',
    'commission_valid'=>'Commission must be greater then zero.',
    'setting_success'=>'Settings updated successfully.',
    'profile_success'=>'Profile successfully.',
    'profile_update_success'=>'Profile updated successfully.',
    'profile_text'=>'Profile',
    'category_list_success'=>'Category list successfully.',
    'category_id_required'=>'Please select category.',
    'price_required'=>'Please enter price.',
    'save_service_success'=>'Save service successfully.',
    'service_id_required'=>'Please enter service id.',
    'delete_service_success'=>'Delete service successfully.',
    'service_list_success'=>'Service list successfully.',
    'logout_text'=>'Logout',
    'cancel_text'=>'Cancel',
    'logout_body_text'=>'Are you sure you want to logout ?',
    'parent_categories_list_text'=>'Parent Category List',
    'add_parent_categories_text'=>'Add Parent Category',
    'data_table_search_placeholder_text'=>'Search...',
    'data_table_item_per_page_text'=>'Items Per Page',
    'data_table_pagination_showing_text'=>'Showing',
    'data_table_pagination_to_text'=>'to',
    'data_table_pagination_of_text'=>'of',
    'data_table_pagination_entries_text'=>'entries',
    'add_parent_categories_name_text'=>'Name',
    'add_parent_categories_name_text_ar'=>'Arabic Name',
    'save_text'=>'Save',
    'close_text'=>'Close',
    'image_required'=>'Please select image',
    'accept_image_type'=>'Please select only jpg,jpeg,png image only',
    'parent_categories_already_exits'=>'Name already exits. Please enter diffrent name',
    'parent_categories_save_success'=>'Parent category saved successfully',
    'edit_parent_categories_text'=>'Edit Parent Category',
    'delete_parent_categories_text'=>'Delete Parent Category',
    'delete_parent_categories_title_text'=>'Are you sure you want to delete this parent category?',
    'ok_text'=>'Ok',
    'delete_parent_categories_success'=>'Parent category deleted successfully.',
    'categories_list_text'=>'Category List',
    'add_categories_text'=>'Add Category',
    'categories_already_exits'=>'Name already exits. Please enter diffrent name',
    'categories_save_success'=>'Category saved successfully',
    'edit_categories_text'=>'Edit Category',
    'delete_categories_text'=>'Delete Category',
    'delete_categories_title_text'=>'Are you sure you want to delete this category?',
    'delete_categories_success'=>'Category deleted successfully.',
    'parent_category_id_required'=>'Please select parent category',
    'image_text'=>'Image',
    'created_at_text'=>'Created Date',
    'action_text'=>'Action',
    'status_text'=>'Status',
    'phone_number_text'=>'Phone Number arabic',
    'name_text'=>'Name',
    'address_text'=>'Address',
    'parent_category_text'=>'Parent Category',
    'service_ids_required'=>'Please enter service_ids',
    'service_ids_json_required'=>'Service_ids must be json format',
    'latitude_required'=>'Please enter latitude',
    'longitude_required'=>'Please enter longitude',
    'hairdresser_id_required'=>'Please enter hairdresser_id',
    'services_required'=>'Please enter services',
    'services_json_required'=>'Please enter services as json',
    'address_required'=>'Please enter address',
    'order_request_success'=>'Order request successfully',
    'order_id_required'=>'Please enter order id',
    'order_timeout_success'=>'Order timeout successfully',
    'payment_timeout_success'=>'Payment timeout successfully',
    'order_detail_not_found'=>'Order detail not found',
    'order_cancel_success'=>'Order cancel successfully',
    'order_accept_success'=>'Order accept successfully',
    'order_on_the_way_success'=>'Order on the way successfully',
    'order_processing_success'=>'Order processing successfully',
    'order_complete_success'=>'Order complete successfully',
    'order_list_success'=>'Order list successfully',
    'customer_list_text'=>'Customer List',
    'hairdresser_list_text'=>'Pharmaclouds List',
    'customer_not_found'=>'Customer detail not found',
    'hairdresser_not_found'=>'Pharmaclouds detail not found',
    'customer_detail_text'=>'Customer Detail arabic',
    'hairdresser_detail_text'=>'Pharmaclouds Detail arabic',
    'summary_text'=>'Summary',
    'lat_text'=>'Latitude',
    'long_text'=>'Longitude',
    'about_text'=>'About',
    'orders_text'=>'Orders',
    'order_number_text'=>'Order Number',
    'customer_name_text'=>'Customer Name',
    'mechanic_name_text'=>'Dresser Name',
    'total_text'=>'Total',
    'order_detail_text'=>'Order Detail',
    'orders_not_found'=>'Order detail not found',
    'orders_service_text'=>'Order Service Detail',
    'order_service_text'=>'Category Name',
    'order_price_text'=>'Price',
    'order_quntity_text'=>'Quantity',
    'order_total_text'=>'Total',
    'order_final_total_text'=>'Final Total',
    'hairdresser_detail_success'=>'Pharmaclouds detail found successfully',
    'tax_percentage_required'=>'Please enter tax percentage',
    'tax_amount'=>'Please enter tax amount',
    'commision_percentage_required'=>'Please enter commission percentage',
    'commision_amount_required'=>'Please enter commission amount',
    'promo_code_required'=>'Please enter promo code',
    'amount_required'=>'Please enter order amount',
    'invalid_promocode'=>'Invalid promocode',
    'promocode_already_used'=>'Promocode already used',
    'promocode_amount_min_required'=>'Order total must be equal or greater then',
    'promocode_apply_sucess'=>'Promocode apply successfully',
    'payment_method_required'=>'Please enter payment method',
    'due_amount_required'=>'Please enter due amount',
    'wallet_balance_lbl'=>'Wallet Balance',
    'wallet_title'=>'Wallet History',
    'add_wallet_records'=>'Release Amount',
    'wallet_updated_success' => 'Wallet Updated Successfully.',
    'wallet_type_text'=>'Type',
    'wallet_amount_text'=>'Amount',
    'wallet_order_text'=>'Order',
    'wallet_description_text'=>'Description',
    'order_transaction_text'=>'Order Transaction Detail',
    'order_transaction_id_text'=>'Transaction Id',
    'order_due_amount_text'=>'Due Amount',
    'order_final_amount_text'=>'Final Amount',
    'order_payment_method_text'=>'Payment Method',
    'promocode_text'=>'Promocode',
    'promocode_amount_text'=>'Promocode Amount',
    'hairdressor_amount'=>'Pharmaclouds Amount',
    'admin_amount'=>'Admin Amount',
    'wallet_history_success'=>'Wallet History successfully',
    'sender_id_required'=>'Please enter sender id',
    'receiver_id_required'=>'Please enter receiver id',
    'rating_required'=>'Please enter rating',
    'comment_required'=>'Please enter comment',
    'order_review_save'=>'Order review saved successfully',
    'notification_list_success'=>'Notification list successfully',
    'hairdressor_id_required'=>'Please enter hairdresser id',
    'favorite_success'=>'Pharmaclouds favorite successfully',
    'unfavorite_success'=>'Pharmaclouds unfavorite successfully',
    'favorite_list_success'=>'Favorite list successfully',
    'order_reject_success'=>'Order rejected successfully',
    'banner_list_success'=>'Banner list successfully',
    'sidebar_promocodes_text'=>'Promocode',
    'promocode_save_success'=>'Promocode saved successfully',
    'delete_promocode_success'=>'Promocode deleted successfully',
    'promocode_list_text'=>'Promocode list',
    'add_promocode_text'=>'Add Promocode',
    'promode_name_text'=>'Name',
    'promocode_code_text'=>'Code',
    'promocode_min_total_text'=>'Min Total',
    'edit_promcode_text'=>'Edit Promocode',
    'promocde_already_exits'=>'Promocode already exits',
    'promo_min_total_required'=>'Please enter min order total',
    'promocode_min_total_required'=>"Please enter greater zero total",
    'promocode_percentage_text'=>'Percentage',
    'percentage_required'=>'Please enter percentage',
    'promocode_min_percentage_required'=>'Please enter greater zero percentage',
    'delete_promocode_text'=>'Delete Promocode',
    'delete_promocode_title_text'=>'Are you sure you want to delete this promocode?',
    'dispute_reason_required'=>'Please enter reason',
    'order_dispute_success'=>'Your dispute request sent to admin. admin get back to you soon',
    'withdraw_request_success'=>'Withdraw request sent to admin. admin get back to you soon',
    'sidebar_banner_text'=>'Banner',
    'sidebar_withdrawal_req_text'=>'Withdrawal',
    'delete_banner_success'=>'Banner deleted successfully',
    'banner_save_success'=>'Banner saved successfully',
    'banner_list_text'=>'Banner list',
    'add_banner_text'=>'Add Banner',
    'edit_banner_text'=>'Edit Banner',
    'edit_title'=>'Edit',
    'delete_title'=>'Delete',
    'delete_banner_text'=>'Delete Banner',
    'banner_title_text'=>'Title',
    'banner_desc_text'=>'Description',
    'delete_banner_title_text'=>'Are you sure you want to delete this banner?',
    'title_required'=>'Please enter title',
    'desc_required'=>'Please enter description',
    'cash_limit'=>'Cash Limit',
    'cash_limit_required'=>'Please enter cash limit',
    'cash_limit_valid'=>'Cash limit must be greater then zero.',
    'promocode_total_user_limit_min_required'=>'Enter grater zero',
    'promocode_total_user_limit_min_num_required'=>'Enter number only',
    'promocode_total_user_text'=>'Total User Limit',
    'promocode_expired_date_text'=>'Expire Date',
    'promocode_expired'=>'Promocode expired',
    'promocode_use_limit_over'=>'Promocode user access limit over',
    'order_payment_success'=>'Order payment successfully.',
    'user_inactive_by_admin_24_hours'=>'Admin offline your account by 24 hours.',
    'user_deleted'=>'User Deleted.',
    'user_not_deleted'=>'User Not Deleted.',
    'user_not_match'=>'User ID not Matched.',
    'user_role_not_matched'=>'User is not registered with this Role ID..',
    'user_role_text'=>'Role',
    'username_text'=>'Username',

    'minimum_cash_limit'=>'Minimum Cash Limit',
    'app_fee'=>'Application fee',
    'card_pay' => 'Card Payment',
    'cash_pay' => 'Cash Payment',
    'order_request' => "Order Request",
    'order_timeout' => "Order Timeout",
    'order_cancel' => "Order Cancel",
    'order_accept' => "Order Accept",
    'order_on_the_way' => "Order On The Way",
    'order_processing' => "Order Processing",
    'order_complete' => "Order Complete",
    'order_rejected' => "Order Rejected",
    'order_paid' => "Order Paid",
    'payment_timeout' => "Payment Timeout",
    'dresser_register_success'=>"User registered successfully. Admin will approve soon.",
    'dresser_busy' => "Dresser has ongoing Order please choose another dresser or wait for dresser to complete his/her service.",
    'sidebar_report_text' => "Reports",
    'from_date'=>"From Date",
    'selectusersLbl'=>"Select Dresser",
    'to_date'=>"To Date",
    'search'=>"Search",
    'all'=>"All",
    'bank_account_lbl'=> "IBAN Number",
    'dresser_amount_report'=> "Amount Report",
    'wallet_report'=> "Wallet Report",

    'today_online_payment'=> "Today's Online Payment Amount",
    'today_cash_payment'=> "Today's Cash Payment Amount",
    'today_total_dresser_amount'=> "Today's Dresser Payment Amount",
    'today_total_tax'=> "Today's Tax Payment Amount",

    'here_you_can_release_only' => "You can release only ",

    'user_bank_name' => "Full Name In Bank",
    'add_money_wallet_records' => "Add Amount",
    'account_number' => "Account Number",

    'terms_en' => "Terms in English",
    'terms_ar' => "Terms in Arabic",

    'id_proof_text' => "ID Proof",
    'iban_text' => "IBAN Document",

];