<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Car_payment extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Carpayment_model', '', TRUE);
        $this->load->model('Payment_trans_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->load->library('bank');
        $this->customer = $this->load->library('customer_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journal = $this->load->library('journal_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->cek = $this->load->library('checkout');
        $this->sales = new Csales_lib();
        $this->tax = $this->load->library('tax_lib');
        $this->sales_return = new Sales_return_lib();
        $this->account = new Account_lib();

    }

    private $properti, $modul, $title,$journalgl,$account;
    private $customer,$user,$journal,$cek,$sales,$tax,$currency,$sales_return;

    public $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {
        $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'arpayment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $appayments = $this->Carpayment_model->get_last($this->modul['limit'], $offset)->result();
        $num_rows = $this->Carpayment_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = $this->modul['limit'];
            $config['uri_segment'] = $uri_segment;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links(); //array menampilkan link untuk pagination.
            // akhir dari config untuk pagination
            

            // library HTML table untuk membuat template table class zebra
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Date', 'Cust', 'ACC', 'Check No', 'Total', 'Action');

            $i = 0 + $offset;
            foreach ($appayments as $appayment)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'CCR-0'.$appayment->no, tglin($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($appayment->amount),
                    anchor($this->title.'/confirmation/'.$appayment->id,'<span>update</span>',array('class' => $this->post_status($appayment->approved), 'title' => 'edit / update')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$appayment->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$appayment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$appayment->id.'/'.$appayment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'arpayment_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $appayments = $this->Carpayment_model->search($this->input->post('tno'), $this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Cust', 'ACC', 'Check No', 'Total', 'Action');

        $i = 0;
        foreach ($appayments as $appayment)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $appayment->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'CCR-0'.$appayment->no, tglin($appayment->dates), $appayment->prefix.' '.$appayment->name, $this->acc($appayment->acc).' - '.$appayment->currency, $appayment->check_no, number_format($appayment->amount),
                anchor($this->title.'/confirmation/'.$appayment->id,'<span>update</span>',array('class' => $this->post_status($appayment->approved), 'title' => 'edit / update')).' '.
                anchor_popup($this->title.'/print_invoice/'.$appayment->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$appayment->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$appayment->id.'/'.$appayment->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    private function acc($val=null)
    { switch ($val) { case 'bank': $val = 'Bank'; break; case 'cash': $val = 'Cash'; break; case 'pettycash': $val = 'Petty Cash'; break; } return $val; }
//    ===================== approval ===========================================

    private function post_status($val)
    { if ($val == 0) {$class = "notapprove"; } elseif ($val == 1){$class = "approve"; } return $class; }

    function confirmation($pid)
    {
        $appayment = $this->Carpayment_model->get_ar_payment_by_id($pid)->row();

        if ($appayment->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
            $this->cek_journal($appayment->dates,$appayment->currency); // cek apakah journal sudah approved atau belum
            $total = $appayment->amount;
//
            if ($total == 0)
            {
              $this->session->set_flashdata('message', "CCR-00$appayment->no has no value..!"); // cek payment punya 0 value
              redirect($this->title);
            }
            elseif ($this->cek_so_settled($appayment->no) == FALSE || $this->cek_sr_settled($appayment->no) == FALSE )
            {
                $this->session->set_flashdata('message', "CCR-00$appayment->no has been settled..!"); // cek po sudah settled atau belum
                redirect($this->title);
            }
            elseif ($this->valid_check_no($appayment->no,$pid) == FALSE )
            {
                $this->session->set_flashdata('message', "CCR-00$appayment->no check no registered..!"); 
                redirect($this->title);
            }
            else
            {
               $this->settled_so($appayment->no,$appayment->dates); // fungsi untuk mensettled kan semua po
               $this->settled_sr($appayment->no); // fungsi untuk mensettled kan semua sr

               $data = array('approved' => 1);
               $this->Carpayment_model->update_id($pid, $data);

               //  create journal
               $this->journal->create_journal($appayment->dates, $appayment->currency,
                                              'Payment for : '.$this->get_trans_code($appayment->no).' - '.$appayment->prefix.' '.$appayment->name,
                                              'CCR', $appayment->no, 'AR', $appayment->amount);
                
               $cm = new Control_model();
               
               if ($appayment->post_dated == 1){ $account = $cm->get_id(51); }else { $account = $appayment->account; } 
               
               $ar = $cm->get_id(17);
               $late = $cm->get_id(6); // pendapatan denda keterlambatan
               $discount = $cm->get_id(4); // discount
                
               $this->journalgl->new_journal('00000'.$appayment->no,$appayment->dates,'CR',$appayment->currency, 'Customer payment for : '.$this->get_trans_code($appayment->no).' - '.$appayment->prefix.' '.$appayment->name, $appayment->amount, $this->session->userdata('log'));
               $dpid = $this->journalgl->get_journal_id('CR','00000'.$appayment->no);
                
               $this->journalgl->add_trans($dpid,$ar,0,$appayment->amount-$appayment->late); // piutang usaha
               $this->journalgl->add_trans($dpid,$account,$appayment->amount,0); // kas / piutang giro
               if ($appayment->late > 0){ $this->journalgl->add_trans($dpid,$late,0,$appayment->late); }
               
               if ($appayment->discount > 0)
               {
                 $this->journalgl->new_journal('0'.$appayment->no,$appayment->dates,'SD',$appayment->currency, 'Customer down payment for : '.$this->get_trans_code($appayment->no).' - '.$appayment->prefix.' '.$appayment->name, $appayment->amount, $this->session->userdata('log'));
                 $jid = $this->journalgl->get_journal_id('SD','0'.$appayment->no); 
                 
                 $this->journalgl->add_trans($jid,$discount,$appayment->discount,0);
                 $this->journalgl->add_trans($jid,$ar,0,$appayment->discount);
               }
                
               $this->session->set_flashdata('message', "$this->title CCR-0$appayment->no confirmed..!"); // set flash data message dengan session
               redirect($this->title);
            }
        }

    }
    
    private function settled_sr($no)
    {
        $vals = $this->Payment_trans_model->get_last_trans($no,'SR')->result();
        $data = array('status' => 1);

        foreach ($vals as $val)
        {  $this->sales_return->settled_sr($val->no,$data); }
    }

    private function get_trans_code($po)
    {
        $val = $this->Payment_trans_model->get_last_item($po)->result();
        $ress=null;

        foreach ($val as $res)
        { $ress = $ress.$res->code.'-00'.$res->no.','; }

        return $ress;
    }

    private function valid_check_no($no=null,$pid=null)
    {
        $val = $this->Carpayment_model->get_ar_payment_by_no($no)->row();
        if ($val->check_no != null)
        {
            if ($this->Carpayment_model->cek_no($val->check_no,$pid) == FALSE)
            { return FALSE; } else { return TRUE; }
        }
        else { return TRUE; }
    }

    private function settled_so($no,$dates=null)
    {
        $vals = $this->Payment_trans_model->get_last_item($no)->result();
        $data = array('status' => 1);

        foreach ($vals as $val)
        {
           $this->sales->settled_so($val->no,$data); 
        }
    }

    private function unsettled_so($no,$dates=null)
    {
        $vals = $this->Payment_trans_model->get_last_item($no)->result();
        $data = array('status' => 0);

        foreach ($vals as $val)
        { 
            $this->sales->settled_so($val->no,$data); 
        }
    }
    
    private function unsettled_sr($no)
    {
        $vals = $this->Payment_trans_model->get_last_trans($no,'SR')->result();
        $data = array('status' => 0);

        foreach ($vals as $val)
        {  $this->sales_return->settled_sr($val->no,$data); }
    }

    private function cek_so_settled($no)
    {
        $vals = $this->Payment_trans_model->get_last_item($no)->result();
        $res = FALSE;

        foreach ($vals as $val)
        {
            if ($this->sales->cek_settled($val->no) == FALSE)
            {
                $res = FALSE;
                break;
            }
            else { $res = TRUE; }
        }

        return $res;
    }
    
    private function cek_sr_settled($no)
    {
        $vals = $this->Payment_trans_model->get_last_trans($no,'SR')->result();
        $num  = $this->Payment_trans_model->get_last_trans($no,'SR')->num_rows();
        $res = TRUE;

        if ($num > 0)
        {
           foreach ($vals as $val)
           {
              if ($this->sales_return->cek_settled($val->no) == FALSE)
              {
                  $res = FALSE;
                  break;
              }
              else { $res = TRUE; }
           }
        }

        return $res;
    }

    private function cek_journal($date,$currency)
    {
        if ($this->journal->valid_journal($date,$currency) == FALSE)
        {
           $this->session->set_flashdata('message', "Journal for [".tgleng($date)."] - ".$currency." approved..!");
           redirect($this->title);
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $appayment = $this->Carpayment_model->get_ar_payment_by_no($po)->row();

        if ( $appayment->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - CCR-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }


//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $appayment = $this->Carpayment_model->get_ar_payment_by_id($uid)->row();

        if ($appayment->approved != 1)
        {
            $this->Payment_trans_model->delete_payment($po); // model to delete appayment item
            $this->Carpayment_model->delete($uid); // memanggil model untuk mendelete data
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else
        {
            if ( $this->valid_period($appayment->dates) == TRUE ) 
            {
                if ($appayment->approved == 1)
                {
                    if ($this->valid_sales_return($po) == TRUE )
                    {
                      $data = array('approved' => 0);
                      $this->Carpayment_model->update_id($uid, $data);  
                  
                      $this->unsettled_so($po,$appayment->dates);
                      $this->journalgl->remove_journal('CR', '00000'.$po); 
                      $this->journalgl->remove_journal('SD', '0'.$po);
                      $this->session->set_flashdata('message', "1 $this->title successfully rollback..!"); 
                    }
                    else { $this->session->set_flashdata('message', "1 $this->title has related to sales return component..!");  }
                }
                else
                {
                  $this->Payment_trans_model->delete_payment($po); // model to delete appayment item
                  $this->Carpayment_model->delete($uid); // memanggil model untuk mendelete data
                  $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
                }
            }
            else
            { $this->session->set_flashdata('message', "1 $this->title can't removed, journal approved..!"); }
        }

        redirect($this->title);
    }
    
    function valid_sales_return($po)
    {
       $value = $this->Payment_trans_model->get_last_item($po)->result();
       $result = FALSE;
       foreach ($value as $res)
       {
         if ($this->sales_return->cek_relation($res->no, 'sales') == FALSE) { break; $result = FALSE; }
         else { $result = TRUE; }
       }
       return $result;
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['currency'] = $this->currency->combo();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('arpayment_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'appayment_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['currency'] = $this->currency->combo();
        $data['code'] = $this->Carpayment_model->counter_no();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tcustomer', 'Customer', 'required|callback_valid_customer');
//        $this->form_validation->set_rules('tcheck', 'Check No', 'callback_valid_check|callback_valid_check_no');
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_date|callback_valid_period');
        $this->form_validation->set_rules('ccurrency', 'Currency', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $appayment = array('customer' => $this->customer->get_customer_id($this->input->post('tcustomer')),
                               'no' => $this->Carpayment_model->counter_no(), 'docno' => $this->input->post('tdocno'),
                               'check_no' => null, 'dates' => $this->input->post('tdate'),
                               'currency' => $this->input->post('ccurrency'), 'acc' => $this->input->post('cacc'),
                               'amount' => 0, 'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
//
            $this->Carpayment_model->add($appayment);

            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$data['code'].'/');
//            echo 'true';
        }
        else
        {
              $this->load->view('arpayment_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po);
        $data['form_action_return'] = site_url($this->title.'/add_return/'.$po);
        
        $data['currency'] = $this->currency->combo();
        $data['tax'] = $this->tax->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");
        
        $appayment = $this->Carpayment_model->get_ar_payment_by_no($po)->row();
        
        // account list
        if ($appayment->acc == 'bank'){  $acc = $this->account->combo_based_classi(8); }
        else{  $acc = $this->account->combo_based_classi(7); }
        
        $data['bank'] = $acc;
        $data['venid'] = $appayment->customer;

        $data['default']['customer'] = $appayment->name;
        $data['default']['date'] = $appayment->dates;
        $data['default']['currency'] = $appayment->currency;
        $data['default']['check'] = $appayment->check_no;
        $data['default']['late'] = $appayment->late;
        $data['default']['cost'] = $appayment->cost;
        $data['default']['discount'] = $appayment->discount;
        $data['default']['balance'] = $appayment->amount;
        $data['default']['acc'] = $appayment->acc;
        $data['default']['docno'] = $appayment->docno;
        $data['default']['status'] = $appayment->post_dated;

        $data['default']['user'] = $this->user->get_username($appayment->user);

//        ============================ Check  =========================================

        $data['default']['bank']  = $appayment->account;
        $data['default']['due']  = $appayment->due;
        $data['default']['balancecek']  = $appayment->amount;

//        ============================ Check  =========================================

//        ============================ Item  =========================================
        $items = $this->Payment_trans_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Amount', 'Discount', 'Cost', 'Balance', 'Action');

//        $this->db->select('id, ap_payment, code, no, notes, amount');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $item->code.'-0'.$item->no, number_format($item->amount+$item->discount), number_format($item->discount), number_format($item->cost), number_format($item->amount),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('arpayment_transform', $data);
    }

    private function get_cash_status($val)
    { if ($val == 1){ return 'Cash';}elseif ($val == 0){ return 'Credit'; } elseif ($val == 2){ return 'Excess'; }  }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('titem', 'Transaction', 'required|callback_valid_po['.$po.']');
        $this->form_validation->set_rules('tamount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('tdiscount', 'Discount', 'required|numeric');
        $this->form_validation->set_rules('tcost', 'Cost', 'required|numeric');
        $this->form_validation->set_rules('tbalance', 'Balance', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $sales = $this->sales->get_so($this->input->post('titem'));

            $pitem = array('ar_payment' => $po, 'code' => 'CSO', 'no' => $this->input->post('titem'), 'cost' => $this->input->post('tcost'),
                           'discount' => $this->input->post('tdiscount'), 'amount' => intval($sales->p2-$this->input->post('tdiscount')-$this->input->post('tcost')));

            $this->Payment_trans_model->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }
    
   
    private function update_trans($po)
    {
        $totals = $this->Payment_trans_model->total_so($po);
        $res = $totals['amount'];
        $res1 = $totals['discount'];
        $res2 = $totals['cost'];
        
        $appayment = array('amount' => $res, 'discount' => $res1, 'cost' => $res2);
	$this->Carpayment_model->update($po, $appayment);
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Payment_trans_model->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($po);
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$po);
    }
//    ==========================================================================================

    // Fungsi update untuk mengupdate db
    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);
        $this->cek_confirmation($po,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tcheck', 'Check No', '');
        $this->form_validation->set_rules('tdate', 'Date', 'required|callback_valid_date|callback_valid_period');
        $this->form_validation->set_rules('cbank', 'Bank', 'callback_valid_check');
        $this->form_validation->set_rules('tdue', 'Due Date', 'callback_valid_check_due');


        if ($this->form_validation->run($this) == TRUE)
        {
            $val = $this->Payment_trans_model->total_so($po);
            
            $appayment = array('log' => $this->session->userdata('log'), 'post_dated' => $this->input->post('cpost'),
                               'acc' => $this->input->post('cacc'), 'dates' => $this->input->post('tdate'), 
                               'late' => $this->input->post('tlate'), 'amount' => intval($val['amount']+$this->input->post('tlate')),
                               'account' => $this->input->post('cbank'), 'due' => setnull($this->input->post('tdue')),
                               'check_no' => $this->cek_null($this->input->post('tcheck')));

            $this->Carpayment_model->update($po, $appayment);
            echo 'true';
        }
        else { echo validation_errors(); }
    }
    
    public function valid_sr($no,$po)
    {
        if ($this->Payment_trans_model->get_item_based_po($po,$no,'SR') == FALSE)
        {
            $this->form_validation->set_message('valid_sr', "SR already registered to journal.!");
            return FALSE;
        }
        else { return TRUE; }
    }


    public function valid_po($no,$arpayment)
    {
        if ($this->Payment_trans_model->get_item_based_po($arpayment,$no,'SO') == FALSE)
        {
            $this->form_validation->set_message('valid_po', "SO already registered to journal.!");
            return FALSE;
        }
        else { return TRUE; }
    }

    public function valid_date($date)
    {
        $cur = $this->input->post('ccurrency');
        if ($this->journal->valid_journal($date,$cur) == FALSE)
        {
            $this->form_validation->set_message('valid_date', "Journal [ ".tgleng($date)." ] - ".$cur." already approved.!");
            return FALSE;
        }
        else {  return TRUE; }
    }


    public function valid_customer($name)
    {
        if ($this->customer->valid_customer($name) == FALSE)
        {
            $this->form_validation->set_message('valid_customer', "Invalid Customer.!");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    public function valid_period($date=null)
    {
        $p = new Period();
        $p->get();

        $month = date('n', strtotime($date));
        $year  = date('Y', strtotime($date));

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
            $this->form_validation->set_message('valid_period', "Invalid Period.!");
            return FALSE;
        }
        else {  return TRUE; }
    }


    function valid_check($val)
    {
        $acc = $this->input->post('tacc');

        if ($acc == 'Bank')
        {
            if ($val == null) { $this->form_validation->set_message('valid_check', "Check No / Field Required..!"); return FALSE; }
            else { return TRUE; }
        }
        else { return TRUE; }
    }

    function valid_check_due($due)
    {
        if ($this->input->post('tcheck') != "")
        {
            if ($due == ""){  $this->form_validation->set_message('valid_check_due', "Due Date Required..!"); return FALSE; } else { return TRUE; }
        }
        else { return TRUE; }
    }

// ===================================== PRINT ===========================================
    
   function invoice($po=null)
   {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Invoice '.ucwords($this->modul['title']);
        $data['h2title'] = 'Print Invoice'.$this->modul['title'];

        $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'tombolprint','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $data['pono'] = $po;
        $this->load->view('appayment_invoice_form', $data);
   }

   function print_invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $appayment = $this->Carpayment_model->get_ar_payment_by_no($po)->row();
//
       $data['pono'] = $po;
       $data['acc'] = strtoupper($this->acc($appayment->acc));
       $data['podate'] = tglin($appayment->dates);
       $data['account'] = $this->account->get_code($appayment->account).' - '.$this->account->get_name($appayment->account);
       $data['docno'] = $appayment->docno;
       $data['customer'] = $appayment->prefix.' '.$appayment->name;
       $data['ven_bank'] = $this->customer->get_customer_bank($appayment->customer);
       $data['amount'] = number_format($appayment->amount);
//
       $data['items'] = $this->Payment_trans_model->get_po_details($po)->result();

       $terbilang = $this->load->library('terbilang');
       if ($appayment->currency == 'IDR')
       { $data['terbilang'] = ucwords($terbilang->baca($appayment->amount)).' Rupiah'; }
       else { $data['terbilang'] = ucwords($terbilang->baca($appayment->amount)); }

       
//
//       // property display
//       $data['paddress'] = $this->properti['address'];
//       $data['p_phone1'] = $this->properti['phone1'];
//       $data['p_phone2'] = $this->properti['phone2'];
//       $data['p_city'] = ucfirst($this->properti['city']);
//       $data['p_zip'] = $this->properti['zip'];
//       $data['p_npwp'] = $this->properti['npwp'];

       $this->load->view('arpayment_invoice', $data);
   }

// ===================================== PRINT ===========================================

    private function cek_null($val=null)
    { if ($val) { return $val; } else { return NULL; } }


    //    ================================ REPORT =====================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();

        $this->load->view('arpayment_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $customer = $this->input->post('tcustomer');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $acc = $this->input->post('cacc');
        $cur = $this->input->post('ccurrency');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['acc'] = $acc;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Carpayment_model->report($customer,$start,$end,$acc,$cur)->result();
        $data['reports1'] = $this->Payment_trans_model->report($customer,$start,$end,$acc,$cur)->result();

        $total = $this->Carpayment_model->total($customer,$start,$end,$acc,$cur);
        $data['total'] = $total['amount'];
        
        if ($this->input->post('ctype') == 0){ $this->load->view('arpayment_report', $data);}
        elseif ($this->input->post('ctype') == 1){ $this->load->view('arpayment_report_trans', $data);}
        elseif ($this->input->post('ctype') == 2){ $this->load->view('arpayment_report_pivottable', $data);}

    }

//    ================================ REPORT =====================================

//    ================================ AJAX =====================================
    
    function get_so()
    {
       $no = $this->input->post('po');
       $val = $this->sales->get_so($no);
       echo $val->p2;
    }

}

?>