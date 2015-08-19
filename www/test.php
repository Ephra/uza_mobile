<?php 
 define('HOST','localhost');
 define('USERNAME', 'root');
 define('PASSWORD','');
 define('DB','896128_uza');
 
 $con = mysqli_connect(HOST,USERNAME,PASSWORD,DB);
 
 $product_name = $_GET['product_name'];
 
 $sql = "select description from product where product_name='$product_name'";
 
 $res = mysqli_query($con,$sql);
 
 $result = array();
 
 while($row = mysqli_fetch_array($res)){
 array_push($result, 
 array('description'=>$row[0]));
 }
 
 echo json_encode(array('result'=>$result));
 
 mysqli_close($con);
?>