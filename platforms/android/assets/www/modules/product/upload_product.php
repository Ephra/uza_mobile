<?php

class upload_product {

    function upload_product() {
        global $db;

        if (isset($_GET['callback']) == TRUE) {

            $object = array(
                "product_name" => $_GET['product_name'],
                "sales_subcat_id" => $_GET['sales_subcat_id'],
                "product_description" => $_GET['product_description'],
                "product_state" => $_GET['product_state'],
                "product_price" => $_GET['product_price'],
                "product_quantity" => $_GET['product_quantity'],
                "product_image" => $_FILES['image']['name'],
                "product_image_tmp" => $_FILES['image']['name_tmp'],
                ""
            );




            foreach ($object as $key => $value) {
                # code...
                if (empty($value)) {
                    # code...
                    $result = array(
                        'status' => 0,
                        'message' => 'Please, fill all fields'
                    );
                }
                echo $_GET['callback'] . '(' . json_encode($result) . ')';
                exit();
            }

            $product_register = $db->insert('product', $object);
            if (empty($register)) {
                $result = array(
                    'status' => 0,
                    'message' => 'Product registration Failed'
                );
            } else {
                $result = array(
                    'status' => 1,
                    'message' => 'Product registration Success'
                );
            }

            echo $_GET['callback'] . '(' . json_encode($result) . ')';
        }
    }

}
