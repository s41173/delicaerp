<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_lib
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->table = 'employee';
        $this->dept = new Dept_lib();
    }

    private $ci,$table,$dept;
    
    function cek_relation($id,$type)
    {
       $this->ci->db->where($type, $id);
       $query = $this->ci->db->get($this->table)->num_rows();
       if ($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function get_name($id)
    {
        if ($id)
        {
//            $this->ci->db->select('name');
            $this->ci->db->from($this->table);
            $this->ci->db->where('id', $id);
            $res = $this->ci->db->get()->row();
            if ($res){ return $res->first_title.' '.$res->name.' '.$res->end_title; }
        }
    }
    
    function get_nip($id)
    {
        if ($id)
        {
            $this->ci->db->select('nip');
            $this->ci->db->from($this->table);
            $this->ci->db->where('id', $id);
            $res = $this->ci->db->get()->row();
            if ($res){ return $res->nip; }
        }
    }
    
    function get_acc_no($id)
    {
        if ($id)
        {
            $this->ci->db->select('acc_no');
            $this->ci->db->from($this->table);
            $this->ci->db->where('id', $id);
            $res = $this->ci->db->get()->row();
            if ($res){ return $res->acc_no; }
        }
    }
    
    function get_id_by_att($code)
    {
        if ($code)
        {
            $this->ci->db->select('id');
            $this->ci->db->from($this->table);
            $this->ci->db->where('attcode', $code);
            $res = $this->ci->db->get()->row();
            if ($res){ return $res->id; }
        }
    }

    function get_id_by_nip($nip)
    {
        $this->ci->db->select('id');
        $this->ci->db->from($this->table);
        $this->ci->db->where('nip', $nip);
        $res = $this->ci->db->get()->row();
        if ($res){ return $res->id; }
    }
    
    function get_division_by_nip($nip)
    {
        $this->ci->db->select('division_id');
        $this->ci->db->from($this->table);
        $this->ci->db->where('nip', $nip);
        $res = $this->ci->db->get()->row();
        if ($res){ return $res->division_id; }
    }
    
    function get_division_by_id($id)
    {
        $this->ci->db->select('division_id');
        $this->ci->db->from($this->table);
        $this->ci->db->where('id', $id);
        $res = $this->ci->db->get()->row();
        if ($res){ return $res->division_id; }
    }
    
    function save($division,$dept,$attcode,$nip, $name, $type,$active)
    {
//        return $division.' - '.$dept.' - '.$attcode.' - '.$nip.' - '.$type.' - '.$active;
        if (!$dept){ $dept = 0; }
        $trans = array('division_id' => $division, 'dept_id' => $dept, 'attcode' => $attcode, 'nip' => $nip, 
                       'name' => $name, 'type' => $type, 'active' => $active);
        $this->ci->db->insert($this->table, $trans);
    }
    
    
    
}


/* End of file Property.php */