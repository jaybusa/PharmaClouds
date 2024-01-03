<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Category;
// use App\Models\Category;
use App\Models\AdminSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use DB;
use Carbon\Carbon;
use App\Utils;
use Illuminate\Support\Str;



class OrdersController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';

    public function orders(Request $request,$type='demand') {

        // $dataResult['orderreportdata'] = Order::with(['client','coupon'])->where('order_payment_status', '1')->where('createdate', $today)->get();


        $draw = $_POST['draw'];
        $row = $_POST['start'];
        $startDate = isset($_POST['startDate'])?$_POST['startDate']:"";
        $endDate = isset($_POST['endDate'])?$_POST['endDate']:"";
        $rowperpage = $_POST['length']; // Rows display per page
        $columnIndex = $_POST['order'][0]['column']; // Column index
        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $searchValue = $_POST['search']['value']; //mysqli_real_escape_string($con,$_POST['search']['value']); // Search value

        ## Search
        $searchQuery = " ";
        if($searchValue != ''){
           $searchQuery = " and (name like '%".$searchValue."%' or
                user_name like '%".$searchValue."%' or
                user_email like'%".$searchValue."%' ) ";
        }

        if($startDate && $endDate){
            $searchQuery .= " AND createdate <= '".$endDate."' AND createdate >= '".$startDate."' " ;
        }


        $where ='';
        $where_cond = "HAVING orders.is_delete = 0 ";
        if($type == 'demand'){
            $where_cond .= "AND sumtype = 1 AND order_payment_status = 1 AND is_archive = 0";
        } elseif($type == 'archive'){
            $where_cond .= "AND sumtype = 1 AND is_archive = 1";
        } elseif($type == 'shipped'){
            $where_cond .= "AND sumtype = 3 AND is_archive = 0";
        } elseif($type == 'delivered'){
            $where_cond .= "AND sumtype = 6 AND is_archive = 0";
        } elseif($type == 'cancelled'){
            $where_cond .= "AND sumtype = 8 AND is_archive = 0";
        } elseif($type == 'returned'){
            $where_cond .= "AND return_status > 0";
        } elseif($type == 'cod'){
            $where_cond .= "AND sumtype = 1 AND is_online_payment != 1  AND is_archive = 0";
        } elseif($type == 'unpaid'){
            $where_cond .= "AND sumtype = 1 AND order_payment_status != 1  AND is_archive = 0";
        } elseif($type == 'last'){
            $curr_date = date('Y-m-d');
            $sub_5_date = date('Y-m-d', strtotime('-5 days', strtotime(date('Y-m-d'))));
            $where .= "where orders.createdate between '".$sub_5_date."' and '".$curr_date."' AND orders.order_payment_status = 1  AND is_archive = 0";
            $where_cond .= "AND sumtype = 1 AND order_payment_status = 1  AND is_archive = 0";
        }

        ## Total number of records without filtering
        $totalRecords = Order::get('is_delete = 0');
        // echo $totalRecords;
        ## Total number of record with filtering

        $totalRecordwithFilterObject = DB::select('SELECT  orders.* ,sum(type)  as sumtype,clients.name, coupons.name as coupon_name
                                                    FROM  `orders`  join  orderstatus
                                                    on orders.id=orderstatus.order_id
                                                    join clients
                                                    ON orders.client_id=clients.id
                                                    left JOIN coupons
                                                    ON coupons.id = orders.coupon_id where is_delete = 0 '.$searchQuery.'
                                                    GROUP BY orderstatus.order_id '.$where_cond.' ORDER BY `orders`.`id` DESC LIMIT '.$row.' OFFSET '.$rowperpage
                                                    );
        $totalRecordwithFilter = count($totalRecordwithFilterObject);

        // $c_checks = count($checks);
        // $sel = mysqli_query($con,"select count(*) as allcount from employee WHERE 1 ".$searchQuery);
        // $records = mysqli_fetch_assoc($sel);
        // $totalRecordwithFilter = $records['allcount'];
        // $totalRecordwithFilter = R::count('clients', 'is_delete = 0 order by id desc');


        // $clientss = R::findAll('clients', 'is_delete = 0 order by id desc');
        $data = array();
        // $clientss = R::getAll("select * from clients WHERE is_delete = 0 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage);
        // echo "select u.*, count(*) as nb from clients as u left join orders as o on o.client_id = u.id  WHERE is_delete = 0 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." group by u.id limit ".$row.",".$rowperpage;
        // $clientss = R::getAll("select u.*, count(*) as nb from clients as u left join orders as o on o.client_id = u.id  WHERE u.is_delete = 0 ".$searchQuery." group by u.id order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage);
        // $clientss = R::getAll("select * from clients WHERE is_delete = 0 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage);

        foreach($totalRecordwithFilterObject as $order){
            // $client = (object)$client;
            $status = '<a href="'.$this->hosturl.'/clients/status/'.$client->id.'" class="btn green-haze btn-xs">';
            if($client->is_active == 1){
                $status .= '<i class="fa fa-eye"></i> '.buttonActiveButton;
            } else{
                $status .= '<i class="fa fa-eye-slash"></i>'.buttonNotActiveButton;
            }
            $status .= '</a>';
            $button = '<a href="'.$this->hosturl.'/clients/edit/'.$client->id.'" class="btn blue-hoki btn-xs"><i class="fa fa-pencil"></i>'. editButtonTitle .'</a>';
            $dbutton = '<a class="btn grey-cascade btn-xs deletemsg" data-id="'.$client->id.'" data-link="clients"><i class="fa fa-times"></i>'. deleteButtonTitle.'</a>';
            $data[] = array(
                'id'=>$order->id,
                'name'=>$order->payer_name,
                'mobile'=>$order->payer_mobile,
                'date'=>$client->createdate,
                'coupon'=>$client->coupon_name,
                'status'=>$client->p_status,
                'button'=>$client->userordercount,
                'button'=>$button,
                'dbutton'=>$dbutton);
        }
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "data" => $data
        );
        echo json_encode($response);die;


        return view('admin.orders.list',$dataResult);

    }

    public function ordersOld(Request $request,$type='demand') {

        // $data['todayOrder'] = Order::with(['client','coupon'])->where('order_payment_status', '1')->where('createdate', $today)->get();
        $dataResult = [];
        $page = 1;
        $rpp = 10;
        $type = 'demand';
        if(!empty($_GET['page'])) $page = $_GET['page'];
        if(!empty($_GET['type'])) $type = $_GET['type'];

        $offset = $rpp * ($page - 1);
        $limit = $rpp;

        if(isset($_GET['orderid'])){
          $orderid = $_GET['orderid'];
          $orderreportdata = DB::select('SELECT  orders.* ,sum(type)  as sumtype,clients.name, coupons.name as coupon_name
                                  FROM  `orders`  join  orderstatus
                                  on orders.id=orderstatus.order_id
                                  join clients
                                  ON orders.client_id=clients.id
                                  left JOIN coupons
                                  ON coupons.id = orders.coupon_id
                                  GROUP BY orderstatus.order_id HAVING orders.id = '.$orderid.' AND orders.is_delete = 0 LIMIT '.$limit.' OFFSET '.$offset
                                  );
          $dataResult['orderreportdata'] = $orderreportdata;

          $orderdataall = DB::select('SELECT orders.*, sum(type)  as sumtype
                                  FROM  `orders`  join  orderstatus
                                  on orders.id=orderstatus.order_id
                                  join clients
                                  ON orders.client_id=clients.id
                                  left JOIN coupons
                                  ON coupons.id = orders.coupon_id
                                  GROUP BY orderstatus.order_id HAVING orders.id = '.$orderid.'  AND orders.is_delete = 0'
                                  );
          $orderdatacount = count($orderdataall);
          $dataResult['search_order_id'] = $orderid;

        } elseif(isset($_POST['search']) && $_POST['startDate'] && $_POST['endDate']){
          $orderid = $_GET['orderid'];
          $orderreportdata = DB::select('SELECT  orders.* ,sum(type)  as sumtype,clients.name, coupons.name as coupon_name
                                  FROM  `orders`  join  orderstatus
                                  on orders.id=orderstatus.order_id
                                  join clients
                                  ON orders.client_id=clients.id
                                  left JOIN coupons
                                  ON coupons.id = orders.coupon_id
                                  where orders.created_at <= "'.$_POST['endDate'].'" AND orders.created_at >= "'.$_POST['startDate'].'" AND orders.is_delete = 0
                                  GROUP BY orderstatus.order_id  LIMIT '.$limit.' OFFSET '.$offset
                                  );
            $dataResult['orderreportdata'] = $orderreportdata;

          $orderdataall = DB::select('SELECT orders.*, sum(type)  as sumtype
                                  FROM  `orders`  join  orderstatus
                                  on orders.id=orderstatus.order_id
                                  join clients
                                  ON orders.client_id=clients.id
                                  left JOIN coupons
                                  ON coupons.id = orders.coupon_id
                                  where orders.createdate <= "'.$_POST['endDate'].'" AND orders.createdate >= "'.$_POST['startDate'].'" AND orders.is_delete = 0
                                  GROUP BY orderstatus.order_id '
                                  );
          $orderdatacount = count($orderdataall);
          $dataResult['search_order_id'] = $orderid;
        }else {
            $where ='';
          $where_cond = "HAVING orders.is_delete = 0 ";
          if($type == 'demand'){
              $where_cond .= "AND sumtype = 1 AND order_payment_status = 1 AND is_archive = 0";
          } elseif($type == 'archive'){
              $where_cond .= "AND sumtype = 1 AND is_archive = 1";
          } elseif($type == 'shipped'){
              $where_cond .= "AND sumtype = 3 AND is_archive = 0";
          } elseif($type == 'delivered'){
              $where_cond .= "AND sumtype = 6 AND is_archive = 0";
          } elseif($type == 'cancelled'){
              $where_cond .= "AND sumtype = 8 AND is_archive = 0";
          } elseif($type == 'returned'){
              $where_cond .= "AND return_status > 0";
          } elseif($type == 'cod'){
              $where_cond .= "AND sumtype = 1 AND is_online_payment != 1  AND is_archive = 0";
          } elseif($type == 'unpaid'){
              $where_cond .= "AND sumtype = 1 AND order_payment_status != 1  AND is_archive = 0";
          } elseif($type == 'last'){
              $curr_date = date('Y-m-d');
              $sub_5_date = date('Y-m-d', strtotime('-5 days', strtotime(date('Y-m-d'))));
              $where .= "where orders.createdate between '".$sub_5_date."' and '".$curr_date."' AND orders.order_payment_status = 1  AND is_archive = 0";
              $where_cond .= "AND sumtype = 1 AND order_payment_status = 1  AND is_archive = 0";
          }


          if($type == 'vat'){
          $orderreportdata = DB::select('SELECT  orders.* ,sum(type)  as sumtype,clients.name, coupons.name as coupon_name
                                  FROM  `orders`  join  orderstatus
                                  on orders.id=orderstatus.order_id
                                  join clients
                                  ON orders.client_id=clients.id
                                  left JOIN coupons
                                  ON coupons.id = orders.coupon_id
                                  LEFT JOIN `orderproducts` ON `orders`.id = `orderproducts`.order_id LEFT JOIN `product` ON `product`.id = `orderproducts`.`product_id` WHERE `product`.is_vat = 1 AND `orders`.order_payment_status = 1
                                  GROUP BY orderstatus.order_id '.$where_cond.' ORDER BY `orders`.`id` DESC LIMIT '.$limit.' OFFSET '.$offset
                                  );
            $dataResult['orderreportdata'] = $orderreportdata;

            //   SELECT `orders`.* FROM `orders` LEFT JOIN `orderproducts` ON `orders`.id = `orderproducts`.order_id LEFT JOIN `product` ON `product`.id = `orderproducts`.`product_id` WHERE `product`.is_vat = 1 AND `orders`.order_payment_status = 1
          }else if($type=='last'){

          $orderreportdata = DB::select('SELECT  orders.* ,sum(type)  as sumtype,clients.name, coupons.name as coupon_name, orderpayment.create_date as payment_creation_time
                                  FROM  `orders`  join  orderstatus
                                  on orders.id=orderstatus.order_id
                                  join clients
                                  ON orders.client_id=clients.id
                                  left JOIN orderpayment
                                  ON orderpayment.order_id = orders.id
                                  left JOIN coupons
                                  ON coupons.id = orders.coupon_id '.$where.'
                                  GROUP BY orderstatus.order_id '.$where_cond.' ORDER BY orderpayment.create_date DESC LIMIT '.$limit.' OFFSET '.$offset
                                  );
            $dataResult['orderreportdata'] = $orderreportdata;

          }else{

          $orderreportdata = DB::select('SELECT  orders.* ,sum(type)  as sumtype,clients.name, coupons.name as coupon_name
                                  FROM  `orders`  join  orderstatus
                                  on orders.id=orderstatus.order_id
                                  join clients
                                  ON orders.client_id=clients.id
                                  left JOIN coupons
                                  ON coupons.id = orders.coupon_id '.$where.'
                                  GROUP BY orderstatus.order_id '.$where_cond.' ORDER BY `orders`.`id` DESC LIMIT '.$limit.' OFFSET '.$offset
                                  );

          $dataResult['orderreportdata'] = $orderreportdata;
          }

          $orderdataall = DB::select('SELECT orders.*, sum(type)  as sumtype
                                  FROM  `orders`  join  orderstatus
                                  on orders.id=orderstatus.order_id
                                  join clients
                                  ON orders.client_id=clients.id
                                  left JOIN coupons
                                  ON coupons.id = orders.coupon_id '.$where.'
                                  GROUP BY orderstatus.order_id '.$where_cond
                                  );
          $orderdatacount = count($orderdataall);
        }
        $total_pages = ceil($orderdatacount / $rpp);
        $page_data = array(
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_results' => $orderdatacount,
            'results_per_page' => $rpp,
            'type' => $type
        );

        $dataResult['search_order_id'] =  $_GET['orderid'] ?? '';
        $dataResult['startDate'] =  date('Y-m-d');
        $dataResult['startDatestring'] =  date('m-01-Y');
        $dataResult['endDate'] =  date('Y-m-d');
        $dataResult['endDatestring'] =  date('m-d-Y');
        $dataResult['page_data'] =  $page_data;
        $dataResult['type'] =  $type;

        // dd($dataResult);
        return view('admin.orders.list',$dataResult);

    }

    public function order_detail(Request $request) {
    	$id = $request->id;
        $order = \App\Models\Order::detail($id);

        //pre($order->toArray());
        if($order) {
            return view('admin.orders.detail',['order'=>$order]);
        }
        return redirect()->route('admin.orders')->with(['danger'=>__('messages.orders_not_found')]);
    }
}
