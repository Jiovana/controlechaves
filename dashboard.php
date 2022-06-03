<?php
    //include_once 'dbconnect.php';
    session_start();

    //if($_SESSION['email']=="" || $_SESSION['role']=="user"){
       // header('location:index.php');           
   // }

/*

    $select = $pdo->prepare("select sum(total) as t, count(invoice_id) as inv from tbl_invoice");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_OBJ);

    $total_order = $row->inv;
    $net_total = $row->t;


    $select=$pdo->prepare("select order_date, total from tbl_invoice group by order_date LIMIT 30");
                    
    $select->execute();
    $ttl = [];
    $date = [];
                                
    while($row=$select->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $ttl[] = $total;
        $date[] = $order_date;
   }                
*/

    include_once 'header.php';
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Admin Dashboard</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->

        <div class="box-body">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php echo $total_order;?></h3>

                            <p>Total Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3><?php echo '$'.number_format($net_total,2);?></h3>

                            <p>Total Revenue</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->


                <?php 
            $select = $pdo->prepare("select count(pname) as p from tbl_product");
            $select->execute();
            $row = $select->fetch(PDO::FETCH_OBJ);
            $total_product = $row->p;     
          ?>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?php echo $total_product;?></h3>

                            <p>Total Products</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->


                <?php 
            $select = $pdo->prepare("select count(category) as c from tbl_category");
            $select->execute();
            $row = $select->fetch(PDO::FETCH_OBJ);
            $total_cat = $row->c;     
          ?>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3><?php echo $total_cat;?></h3>

                            <p>Total Categories</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
        </div>

        <div class="box box-warning">

            <div class="box-header with-border">
                <h3 class="box-title">Earning by Date</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <div class="box-body">
                <div class="chart">
                    <canvas id="earningChart" style="height:250px;"></canvas>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Best 15 Selling Product</h3>
                    </div>
                    <div class="box-body">
                        <table id="bestproducttable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                    $select=$pdo->prepare("select product_id, product_name, price, sum(qty) as q, sum(qty*price) as total from tbl_invoice_details group by product_id order by sum(qty) DESC LIMIT 15");
                    $select->execute();
                                
                    while($row=$select->fetch(PDO::FETCH_OBJ)){
                        echo '
                            <tr>
                            <td>'.$row->product_id.'</td>
                            <td>'.$row->product_name.'</td>
                            <td><span class="label label-info">'.$row->q.'</span></td>
                            <td><span class="label label-success">$'.$row->price.'</span></td>   
                            <td><span class="label label-danger">$'.$row->total.'</span></td>  
                           
                            </tr> ';
                        }
                        ?>
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Recent 15 Orders</h3>
                    </div>
                    <div class="box-body">
                        <table id="tableorders" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Customer Name</th>
                                    <th>Order Date</th>
                                    <th>Total</th>
                                    <th>Payment Type</th>
                                </tr>
                            </thead>
                            <tbody>
                <?php 
                    $select=$pdo->prepare("select * from tbl_invoice order by invoice_id desc limit 15");
                    $select->execute();
                                
                    while($row=$select->fetch(PDO::FETCH_OBJ)){
                        echo '
                            <tr>
                            <td><a href="editorder.php?id='.$row->invoice_id.'">'.$row->invoice_id.'</td>
                            <td>'.$row->customer_name.'</td>
                            <td>'.$row->order_date.'</td>
                            <td><span class="label label-danger">$'.$row->total.'</span></td>';
                            
                            if($row->payment_type=="cash"){
                                echo '<td><span class="label label-primary">'.$row->payment_type.'</span> </td>';
                            } else if($row->payment_type=="card"){
                                echo '<td><span class="label label-warning">'.$row->payment_type.'</span> </td>';
                            }else if ($row->payment_type=="check"){
                                echo '<td><span class="label label-info">'.$row->payment_type.'</span> </td>';
                            }
                            echo '</tr>';
                    }
                ?>
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>

        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    var ctx = document.getElementById('earningChart').getContext('2d');
    var chart = new Chart(ctx, {
        //type of chart
        type: 'bar',
        //data of dataset
        data: {
            labels: <?php echo json_encode($date);?>,
            datasets: [{
                label: 'Total Earning',
                backgroundColor: 'rgb(255,99,132)',
                borderColor: 'rgb(255, 99, 132)',
                data: <?php echo json_encode($ttl);?>
            }]
        },
        //configuratio of options
        options: {}
    });

</script>

<!--<script>
    $(document).ready(function() {
        $('#bestproducttable').DataTable({});
    });

</script>-->

<script>
    $(document).ready(function() {
        $('#tableorders').DataTable({
            "order": [
                [0, "desc"]
            ]
        });
    });

</script>


<?php
    include_once 'footer.php';
?>
