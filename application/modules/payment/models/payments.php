<?php

class Payments extends DataMapper
{
//   var $has_one = array("country");
   var $table = "payment_type";
   var $auto_populate_has_many = TRUE;

   function __construct($id = NULL)
   {
      parent::__construct($id);
   }

}

/* End of file user.php */
/* Location: ./application/models/user.php */