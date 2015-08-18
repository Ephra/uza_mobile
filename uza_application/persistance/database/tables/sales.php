<?php    
/**
 * @copyright      (c) 2015, Innovation network and company limited
 * 
 *  sales 
 *  @access        public 
 *  @author        Ephraim Swilla <swillae1@gmail.com>
 *  
 */

class  sales  extends db{



    /**
    *@var id
    */
    public $id;
 


    /**
    *@var product_id
    */
    public $product_id;
 


    /**
    *@var seller_id
    */
    public $seller_id;
 


    /**
    *@var buyer_id
    */
    public $buyer_id;
 


    /**
    *@var time
    */
    public $time;
 


    /**
    *@var quantity
    */
    public $quantity;
 

   /**
     * @access  by database to instatiate fields but you can use it
     * @return array of fields present in database table
     */     
    public function fields(){

      return $data=array('id','product_id','seller_id','buyer_id','time','quantity');
    }


} 
?>