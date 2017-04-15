<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Request_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'request';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last_request($limit, $offset)
    {
        $this->db->select('id, no, type, dates, desc, user, supervisor, approved, realization, realization_date, log');
        $this->db->from($this->table);
        $this->db->order_by('no', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }

    function search($no=null,$date=null)
    {
        $this->db->select('id, no, type, dates, desc, user, supervisor, approved, realization, realization_date, log');
        $this->db->from($this->table);
        $this->cek_null($no,"no");
        $this->cek_null($date,"dates");
        return $this->db->get();
    }

    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

    function counter()
    {
        $this->db->select_max('no');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_request_by_id($uid)
    {
        $this->db->select('id, no, type, dates, desc, user, supervisor, approved, realization, realization_date, log');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get();
    }

    function get_request_by_no($uid)
    {
        $this->db->select('id, no, type, dates, desc, user, supervisor, approved, realization, realization_date, log');
        $this->db->from($this->table);
        $this->db->where('no', $uid);
        return $this->db->get();
    }

    function get_list($date)
    {
        $this->db->select('id, no, type, dates, desc, user, supervisor, approved, realization, realization_date, log');
        $this->db->from($this->table);
        $this->cek_null($date,"dates");
        $this->db->where('approved', 1);
        $this->db->where('realization', 0);
        $this->db->where('type', 'stock');
        return $this->db->get();
    }
    
    function update($uid, $users)
    {
        $this->db->where('no', $uid);
        $this->db->update($this->table, $users);
    }

    function update_id($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }

    function valid_no($no)
    {
        $this->db->where('no', $no);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

    function validating_no($no,$id)
    {
        $this->db->where('no', $no);
        $this->db->where_not_in('id', $id);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) {  return FALSE; } else { return TRUE; }
    }


//    =========================================  REPORT  ===============================

    function report($start,$end)
    {
        $this->db->select("$this->table.id, $this->table.no, $this->table.type, $this->table.dates, $this->table.desc,
                           $this->table.user, $this->table.supervisor, $this->table.approved, $this->table.log, $this->table.realization, $this->table.realization_date,");
        
        $this->db->from("$this->table");
        $this->db->where("$this->table.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where("$this->table.approved", 1);
//        $this->cek_null($type,"$this->table.type");
        $this->db->order_by("$this->table.no", "asc");
        return $this->db->get();
    }

//    =========================================  REPORT  ===============================

}

?>