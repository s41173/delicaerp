<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_trans_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'car_payment_trans';
    
    function get_last_item($po)
    {
        $this->db->select('id, ar_payment, code, no, discount, cost, amount');
        $this->db->from($this->table);
        $this->db->where('ar_payment', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function get_po_details($po)
    {
        $this->db->select('car_payment_trans.id, car_payment_trans.ar_payment, car_payment_trans.code, car_payment_trans.no, car_payment_trans.amount,
                          car_payment_trans.discount, car_payment_trans.cost, csales.docno, csales.notes, csales.dates');

        $this->db->from("car_payment_trans, csales");
        $this->db->where('car_payment_trans.no = csales.no');
        $this->db->where('car_payment_trans.ar_payment', $po);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }
    
    function get_last_trans($po,$code)
    {
        $this->db->select('id, ar_payment, code, no, discount, cost, amount');
        $this->db->from($this->table);
        $this->db->where('ar_payment', $po);
        $this->db->where('code', $code);
        $this->db->order_by('id', 'asc');
        return $this->db->get();
    }

    function get_item_based_po($arpayment,$no,$code)
    {
        $this->db->select('id, ar_payment, code, no, discount, cost, amount');
        $this->db->from($this->table);
        $this->db->where('no', $no);
        $this->db->where('code', $code);
        $this->db->where('ar_payment', $arpayment);
        $query = $this->db->get()->num_rows();
        if ($query > 0) { return FALSE; } else { return TRUE; }
    }

    function total_so($po)
    {
        $this->db->select_sum('amount');
        $this->db->select_sum('discount');
        $this->db->select_sum('cost');
        $this->db->where('ar_payment', $po);
        $this->db->where('code', 'CSO');
        return $this->db->get($this->table)->row_array();
    }
    
    function total_sr($po)
    {
        $this->db->select_sum('amount');
        $this->db->select_sum('discount');
        $this->db->select_sum('cost');
        $this->db->where('ar_payment', $po);
        $this->db->where('code', 'SR');
        return $this->db->get($this->table)->row_array();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_payment($uid)
    {
        $this->db->where('ar_payment', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function report($customer,$start,$end,$acc,$cur)
    {
        $this->db->select('car_payment_trans.id, car_payment_trans.ar_payment, car_payment_trans.code, car_payment_trans.no,
                           car_payment_trans.discount, car_payment_trans.cost, car_payment_trans.amount,
                           car_payment.dates, car_payment.no, customer.prefix, customer.name,
                          ');
        
        $this->db->from("car_payment_trans,car_payment, customer");
        $this->db->where('car_payment.customer = customer.id');
        $this->db->where('car_payment_trans.ar_payment = car_payment.no');
        
        $this->cek_null($customer,"customer.name");
        $this->cek_null($acc,"car_payment.acc");
        $this->cek_null($cur,"car_payment.currency");
        $this->between($start,$end);
        $this->db->where('car_payment.approved', 1);
        $this->db->order_by('car_payment.dates', 'asc'); 
        return $this->db->get(); 
    }
    
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    private function between($start=null,$end=null)
    {
        if ($start != null && $end != null)
        {
            return $this->db->where("car_payment.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        }
        else { return null; }
    }
    
}

?>