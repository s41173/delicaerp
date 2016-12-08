<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assembly_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'assembly';
    }

    private $ci,$table;


    //    ======================= relation cek  =====================================

    // backup =======

    function closing()
    {
        $this->ci->db->select('no');
        $this->ci->db->where('approved', 1);
        $query = $this->ci->db->get($this->table)->result();

        foreach ($query as $value)
        { $this->delete($value->no); }
    }
    
    function combo($val)
    {
        $this->ci->db->select('id, no, project, notes');
        $this->ci->db->where('project', $val);
        $val = $this->ci->db->get($this->table)->result();
        if ($val)
        {
          foreach($val as $row){$data['options'][$row->no] = $row->no.' - '.$row->notes;}
          
        }
        else { $data['options'][''] = '--'; }
        return $data;
    }
    
    function get_no($id)
    {
       $this->ci->db->select('id, no, project, notes');
       $this->ci->db->where('id', $id); 
       $val = $this->ci->db->get($this->table)->row();
       return $val->no;
    }


}

/* End of file Property.php */