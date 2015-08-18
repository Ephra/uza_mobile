<?php    
/**
 * @copyright      (c) 2015, Innovation network and company limited
 * 
 *  user 
 *  @access        public 
 *  @author        Ephraim Swilla <swillae1@gmail.com>
 *  
 */

class  user  extends db{



    /**
    *@var id
    */
    public $id;
 


    /**
    *@var fname
    */
    public $fname;
 


    /**
    *@var lname
    */
    public $lname;
 


    /**
    *@var email
    */
    public $email;
 


    /**
    *@var password
    */
    public $password;
 


    /**
    *@var phone
    */
    public $phone;
 


    /**
    *@var location
    */
    public $location;
 


    /**
    *@var type
    */
    public $type;
 

   /**
     * @access  by database to instatiate fields but you can use it
     * @return array of fields present in database table
     */     
    public function fields(){

      return $data=array('id','fname','lname','email','password','phone','location','type');
    }


 public function user_pic($width = 40, $height = 40, $option = "") {
        if (empty($this->profile_pic)) {
            $this->profile_pic = "profile.jpg";
            $id = 0;
            $link = $id . "/" . $this->profile_pic;
        } else {
            $link = $this->id . "/" . $this->profile_pic;
        }
        $title = $this->firstname;
        if (preg_match("/http/", $this->profile_pic)) {
            $src = $this->profile_pic;
        } else {
            $img_src = "media/members/" . $link;
            if (is_file($img_src)) {
                $src = HOME . $img_src;
            } else {
                $src = HOME . "media/members/0/profile.jpg";
            }
        }
        return "<img src=\"" . $src . "\" width=\"$width\" height=\"$height\" title=\"$title\" $option>";
    }
    }
?>