<?php    
/**
 * @copyright      (c) 2015, Innovation network and company limited
 * 
 *  category_type 
 *  @access        public 
 *  @author        Ephraim Swilla <swillae1@gmail.com>
 *  
 */

class  category_type  extends db{



    /**
    *@var category_id
    */
    public $category_id;
 


    /**
    *@var category_name
    */
    public $category_name;
 


    /**
    *@var subcategory_id
    */
    public $subcategory_id;
 

   /**
     * @access  by database to instatiate fields but you can use it
     * @return array of fields present in database table
     */     
    public function fields(){

      return $data=array('category_id','category_name','subcategory_id');
    }


} 
?>