<?php

class Honor_absences extends DataMapper
{
//   var $has_one = array("country");
   var $table = "honor_absence";
//   var $has_one = array("account");
   var $auto_populate_has_many = TRUE;
   var $auto_populate_has_one = TRUE;

   function __construct($id = NULL)
   {
      parent::__construct($id);
   }

}

/* End of file user.php */
/* Location: ./application/models/user.php */