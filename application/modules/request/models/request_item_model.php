<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Request_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'request_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, request, product, qty, desc, request_date, user, status');
        $this->db->from($this->table);
        $this->db->where('request', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }
    
    function get_by_id($uid)
    {
        $this->db->select('id, request, product, qty, desc, request_date, user, status');
        $this->db->from($this->table);
        $this->db->where('id', $uid);
        return $this->db->get(); 
    }
    
    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }

    function total($po)
    {
        $this->db->select_sum('tax');
        $this->db->select_sum('amount');
        $this->db->where('request', $po);
        return $this->db->get($this->table)->row_array();
    }

    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('request', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function report($po)
    {
        $this->db->select("$this->table.id, $this->table.request, product.name as product, product.unit, $this->table.qty, 
                           $this->table.desc, $this->table.request_date, $this->table.user, $this->table.status");
        $this->db->from("$this->table,product");
        $this->db->where("$this->table.product = product.id");
        $this->db->where("$this->table.request", $po);
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }
    
    function report_item($po)
    {
        $this->db->select("$this->table.id, $this->table.request, $this->table.qty, $this->table.product, $this->table.product as unit,
                           $this->table.desc, $this->table.request_date, $this->table.user, $this->table.status");
        
        $this->db->from("$this->table");
        $this->db->where("$this->table.request", $po);
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }
    
//    function report_category($start,$end)
//    {
//        $this->db->select("$this->table.id, $this->table.request, request.type, product.name as product, product.id as pid, product.unit, $this->table.qty, 
//                           $this->table.desc, $this->table.request_date, $this->table.user, $this->table.status,
//                           request.no, request.dates, realization, realization_date
//                          ");
//        
//        $this->db->from("$this->table,request,product");
//        $this->db->where("$this->table.product = product.id");
//        $this->db->where("$this->table.request = request.no");
//        $this->db->where("request.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
//        $this->db->where("request.approved", 1);
//        $this->db->order_by("$this->table.id", 'asc');
//        return $this->db->get();
//    }
    
    function report_category($start,$end)
    {
        $this->db->select("$this->table.id, $this->table.request, request.type, $this->table.product, $this->table.qty, 
                           $this->table.desc, $this->table.request_date, $this->table.user, $this->table.status,
                           request.no, request.dates, realization, realization_date
                          ");
        
        $this->db->from("$this->table,request");
        $this->db->where("$this->table.request = request.no");
        $this->db->where("request.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where("request.approved", 1);
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }
    

}

?>