<?php

class upload_product {

    public $image_path;
            
    function upload_product() {
        
        if($this->image()){
            
        if (isset($_GET['callback']) == TRUE) {
     
            $object = array(
                "product_name" => $_GET['product_name'],
                "sales_subcat_id" => $_GET['sales_subcat_id'],
                "product_description" => $_GET['product_description'],
                "product_state" => $_GET['product_state'],
                "product_price" => $_GET['product_price'],
                "product_quantity" => $_GET['product_quantity'],
                "image_path"=>  $this->image_path
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
 else {
        return array('success'=>5);
    }
    
    }

    public function image() {

        $uploadImage = new File();
        if (isset($_FILES['image'])) {
            $up= $uploadImage->upload_image($_FILES['name']);
            $this->image_path=$uploadImage->path;
            return $up;
        } else {
             return array('success'=>4);
        }
    }

}

class File {
    public $path;

    public $_supportedFormats = ['image/png', 'image/jpeg', 'image/gif', 'image/jpg'];

    public function upload_image($file) {
        if (is_array($file)) {

            if (in_array($file['type'], $this->_supportedFormars)) {
                $this->path=   'media/uploaded_images/' .time(). $file['image'];
               $x=move_uploaded_file($file['tmp_name'], $this->path);
               return $x==true ? array('success'=>1): array('success'=>0);
               } else {
                return array('success'=>2);
            }
        } else {
             return array('success'=>3);
        }
    }

}
