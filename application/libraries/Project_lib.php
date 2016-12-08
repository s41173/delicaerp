<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_lib {

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    private $ci;
    
    function combo()
    {
        $this->ci->db->select('id, name');
        $this->ci->db->where('status', 0);
        $val = $this->ci->db->get('project')->result();
        $data['options'][''] = '--';
        foreach($val as $row){$data['options'][$row->name] = $row->name;}
        return $data;
    }
    
    function get_details($name=null)
    {
        if ($name)
        {
           $this->ci->db->select('id, no, name, customer, dates, location, desc, status, staff, log');
           $this->ci->db->where('name', $name);
           return $this->ci->db->get('project')->row();
        }
    }


}

/* End of file Property.php */