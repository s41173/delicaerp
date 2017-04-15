<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_payment_cash {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'ap_payment_cash';
        $this->table2 = 'payment_trans_cash';
    }

    private $ci,$table,$table2;


    //    ======================= relation cek  =====================================

    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function cek_relation_trans($id,$type,$code='DJ')
    {
       $this->ci->db->where($type, $id);
       $this->ci->db->where('code', $code);
       $query = $this->ci->db->get($this->table2)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function set_post_stts($no, $users)
    {
        $this->ci->db->where('no', $no);
        $this->ci->db->update($this->table, $users);
    }


}

/* End of file Property.php */