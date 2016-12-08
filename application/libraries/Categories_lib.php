<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'categories';
    }

    private $ci,$table;
    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table1)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function combo()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get($this->table)->result();
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }

    function combo_all()
    {
        $this->ci->db->select('id, name');
        $val = $this->ci->db->get($this->table)->result();
        $data['options'][''] = '-- All --';
        foreach($val as $row){$data['options'][$row->id] = $row->name;}
        return $data;
    }
    
    function get_code($name=null)
    {
        $this->ci->db->select('code');
        $this->ci->db->from('units');
        $this->ci->db->where('name', $name);
        $res = $this->ci->db->get()->row();
        return $res->code;
    }


}

/* End of file Property.php */