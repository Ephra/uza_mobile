<?php    
/**
 * @copyright      (c) 2015, Innovation network and company limited
 * 
 *  product 
 *  @access        public 
 *  @author        Ephraim Swilla <swillae1@gmail.com>
 *  
 */

class  product  extends db{



    /**
    *@var id
    */
    public $id;
 


    /**
    *@var name
    */
    public $name;
 


    /**
    *@var description
    */
    public $description;
 


    /**
    *@var state
    */
    public $state;
 


    /**
    *@var price
    */
    public $price;
 


    /**
    *@var category_type_id
    */
    public $category_type_id;
 


    /**
    *@var quantity
    */
    public $quantity;
 


    /**
    *@var seller_id
    */
    public $seller_id;
 


    /**
    *@var buyer_id
    */
    public $buyer_id;
 


    /**
    *@var image_path
    */
    public $image_path;
 


    /**
    *@var upload_time
    */
    public $upload_time;
 

   /**
     * @access  by database to instatiate fields but you can use it
     * @return array of fields present in database table
     */     
    public function fields(){

      return $data=array('id','name','description','state','price','category_type_id','quantity','seller_id','buyer_id','image_path','upload_time');
    }


} 
?>