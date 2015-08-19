<?php

class upload_product {
    
    

    function upload_product() {
        

        if (isset($_GET['callback']) == TRUE) {

            $object = array(
                "product_name" => $_GET['product_name'],
                "sales_subcat_id" => $_GET['sales_subcat_id'],
                "product_description" => $_GET['product_description'],
                "product_state" => $_GET['product_state'],
                "product_price" => $_GET['product_price'],
                "product_quantity" => $_GET['product_quantity'],
                       
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

    public function image() {

    $uploadImage=new File();
    if(isset($_FILES['image'])){
        $uploadImage->upload_image($_FILES['name']);
    }
    
 else {
        echo 'Image not uploaded';
    }
       
    }

}

class File{
    
   public $_supportedFormats=['image/png','image/jpeg','image/gif','image/jpg'];
   
   public function upload_image($file){
       if(is_array($file)){
           
           if(in_array($file['type'], $this->_supportedFormars)){
               move_uploaded_file($file['tmp_name'],'media/uploaded_images/'.$file['name']);
              
                 }
                    else {
                     echo 'the file format is not supported';
              }
                   }
        else {
           echo 'Image was not chosen';   
       }
              
   }
        
}
    
    

