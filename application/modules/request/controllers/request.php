<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Request extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Request_model', '', TRUE);
        $this->load->model('Request_item_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->unit = $this->load->library('unit_lib');
        $this->product      = $this->load->library('products_lib');
        $this->user         = $this->load->library('admin_lib');
        $this->return_stock = $this->load->library('return_stock');
        $this->wt           = $this->load->library('warehouse_transaction');
        $this->vendor       = $this->load->library('vendor_lib');
        $this->purchase     = new Purchase_lib();

    }

    private $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    private $properti, $modul, $title, $currency, $unit;
    private $user,$product,$return_stock,$wt,$vendor,$purchase;

    function index()
    {
        $this->get_last_request();
    }

    function get_last_request()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'request_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $requests = $this->Request_model->get_last_request($this->modul['limit'], $offset)->result();
        $num_rows = $this->Request_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_request');
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
            $this->table->set_heading('No', 'Code', 'Type', 'Date', 'Notes', 'Log', 'Action');

            $i = 0 + $offset;
            foreach ($requests as $request)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $request->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'PR-00'.$request->no, strtoupper($request->type), tglin($request->dates), $request->desc, $request->log,
                    anchor($this->title.'/realize/'.$request->id.'/'.$request->no,'<span>update</span>',array('class' => $this->realize_status($request->realization), 'title' => 'realize')).' &nbsp; | &nbsp; '.
                    anchor($this->title.'/confirmation/'.$request->id,'<span>update</span>',array('class' => $this->post_status($request->approved), 'title' => 'approval')).' '.
                    anchor_popup($this->title.'/print_invoice/'.$request->no,'<span>print</span>',$this->atts).' '.
                    anchor($this->title.'/add_trans/'.$request->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$request->id.'/'.$request->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'request_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

        $requests = $this->Request_model->search($this->input->post('tno'), $this->input->post('tdate'), $this->input->post('ctype'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Type', 'Date', 'Notes', 'Log', 'Action');

        $i = 0;
        foreach ($requests as $request)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $request->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'PR-00'.$request->no, strtoupper($request->type), tglin($request->dates), $request->desc, $request->log,
                anchor($this->title.'/realize/'.$request->id.'/'.$request->no,'<span>update</span>',array('class' => $this->realize_status($request->realization), 'title' => 'realize')).' &nbsp; | &nbsp; '.
                anchor($this->title.'/confirmation/'.$request->id,'<span>update</span>',array('class' => $this->post_status($request->approved), 'title' => 'approval')).' '.
                anchor_popup($this->title.'/print_invoice/'.$request->no,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/add_trans/'.$request->no,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/delete/'.$request->id.'/'.$request->no,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    function realize($id,$po)
    {
        $this->cek_confirmation($po,null,'value');
        $this->acl->otentikasi2($this->title);
        
        $val = $this->Request_model->get_request_by_id($id)->row();
        if ($val->realization == 0){ $res = 1; $reldate = date('Y-m-d'); }else { $res = 0; $reldate = null; }
        
        $request = array('realization' => $res, 'realization_date' => $reldate);        
        $this->Request_model->update_id($id,$request); // memanggil model untuk mengubah status
        $this->session->set_flashdata('message', "1 item successfully realize..!"); // set flash data message dengan session
        redirect($this->title);
    }
    
    function get_list()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'product_list';
        $data['form_action'] = site_url($this->title.'/get_list');

        $stocks = $this->Request_model->get_list($this->input->post('tdate'))->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Action');

        $i = 0;
        foreach ($stocks as $stock)
        {
          $datax = array('name' => 'button', 'type' => 'button', 'content' => 'Select', 'onclick' => 'setvalue(\''.$stock->no.'\',\'tpr\')');
          $this->table->add_row( ++$i, 'PR-00'.$stock->no, tglin($stock->dates), $stock->desc, form_button($datax) );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('request_list', $data);
        
    }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val == 0) {$class = "notapprove"; }
       elseif ($val == 1){$class = "approve"; }
       return $class;
    }

    function confirmation($pid)
    {
        $request = $this->Request_model->get_request_by_id($pid)->row();

        if ($request->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!"); // set flash data message dengan session
           redirect($this->title);
        }
        else
        {
           $data = array('approved' => 1);
           $this->Request_model->update_id($pid, $data);

           $this->session->set_flashdata('message', "$this->title FPB-00$request->no confirmed..!");
           redirect($this->title);
        }

    }


    private function cek_confirmation($po=null,$page=null,$type=null)
    {
        $request = $this->Request_model->get_request_by_no($po)->row();

        if (!$type)
        {
            if ( $request->approved == 1 )
            {
               $this->session->set_flashdata('message', "Can't change value - PR-00$po approved..!"); // set flash data message dengan session
               if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
            }
        }
        else
        {
           if ( $request->approved == 0 )
           {
              $this->session->set_flashdata('message', "Can't change value - PR-00$po not approved..!"); // set flash data message dengan session
              if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
           } 
        }
        
    }
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);
        $val = $this->Request_model->get_request_by_id($uid)->row();
        
        if ($this->purchase->cek_relation($po, 'request') == 'TRUE')
        {
           $this->Request_item_model->delete_po($po);
           $this->Request_model->delete($uid);
           $this->session->set_flashdata('message', "1 $this->title successfully removed..!"); 
        }
        else{ $this->session->set_flashdata('message', "1 $this->title related to another component...!"); }
        redirect($this->title);
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Request_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('request_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'request_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Request_model->counter();
        $data['user'] = $this->session->userdata("username");

	// Form validation
        $this->form_validation->set_rules('tno', 'PR - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('tsupervisor', 'Supervisor', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $request = array('no' => $this->input->post('tno'), 'type' => $this->input->post('ctype'), 'approved' => 0, 'supervisor' => $this->input->post('tsupervisor'),
                            'dates' => $this->input->post('tdate'), 'desc' => $this->input->post('tnote'),
                            'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Request_model->add($request);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('request_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $request = $this->Request_model->get_request_by_no($po)->row();
        
        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po.'/'.$request->type);
        $data['code'] = $po;

        $data['default']['type'] = $request->type;
        $data['default']['date'] = $request->dates;
        $data['default']['note'] = $request->desc;
        $data['default']['supervisor'] = $request->supervisor;
        $data['user'] = $this->user->get_username($request->user);

//        ============================ Purchase Item  =========================================
        $items = $this->Request_item_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Product', 'Qty', 'Desc', 'Date', 'User', 'Status', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            if ($request->type == 'stock'){ $product = $this->product->get_name($item->product); }else { $product = $item->product; }
            if ($request->type == 'stock'){ $unit = $this->product->get_unit($item->product); }else { $unit = 'pcs'; }
            
            $this->table->add_row
            (
                ++$i, $product, $item->qty.' '.$unit, $item->desc, tglin($item->request_date), $item->user, strtoupper($item->status),
                anchor($this->title.'/status/'.$item->id.'/'.$po,'<span>update</span>',array('class' => $this->alert_order($item->status), 'title' => 'edit / update')).' &nbsp; | &nbsp; '.
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('request_transform', $data);
    }
    
    private function alert_order($val)
    {
       if ($val == "reject") {$class = "credit"; }
       elseif ($val == "accept"){$class = "settled"; }
       return $class;
    }
    
    private function realize_status($val)
    {
       if ($val == 0) {$class = "credit"; }
       elseif ($val == 1){$class = "settled"; }
       return $class;
    }

//    ======================  Item Transaction   ===============================================================

    function add_item($po=null,$type=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('tproduct', 'Item Name', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('tdesc', 'Desc', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('trequestdate', 'Demand Date', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            if ($type == 'stock'){ $product = $this->product->get_id($this->input->post('tproduct')); } else { $product = $this->input->post('tproduct'); }
            
            $pitem = array('product' => $product, 
                           'request' => $po,
                           'qty' => $this->input->post('tqty'), 'desc' => $this->input->post('tdesc'), 'user' => $this->input->post('tuser'), 
                           'request_date' => $this->input->post('trequestdate'), 'status' => 'accept');

            $this->Request_item_model->add($pitem);
            echo 'true';
        }
        else{   echo validation_errors(); }
    }
    
    function status($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $val = $this->Request_item_model->get_by_id($id)->row();
        if ($val->status == 'accept'){ $res = 'reject'; }else { $res = 'accept'; }
        
        $request = array('status' => $res);        
        $this->Request_item_model->update($id,$request); // memanggil model untuk mengubah status
        $this->session->set_flashdata('message', "1 item successfully changed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$po);
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Request_item_model->delete($id); // memanggil model untuk mendelete data
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$po);
    }
//    ==========================================================================================

    // Fungsi update untuk mengupdate db
    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);
//        $this->cek_confirmation($po,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tno', 'FPB - No', 'required|numeric|callback_valid_confirmation');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'Warehouse Dept', 'required');
        $this->form_validation->set_rules('tsupervisor', 'Supervisor', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {

            $request = array('no' => $this->input->post('tno'), 'type' => $this->input->post('ctype'), 'supervisor' => $this->input->post('tsupervisor'),
                            'dates' => $this->input->post('tdate'), 'desc' => $this->input->post('tnote'),
                            'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));

            $this->Request_model->update($po, $request);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('request_transform', $data);
            echo validation_errors();
        }
    }

    public function valid_no($no)
    {
        if ($this->Request_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }


    public function valid_confirmation($po)
    {
        $stockin = $this->Request_model->get_request_by_no($po)->row();

        if ( $stockin->approved == 1 )
        {
           $this->form_validation->set_message('valid_confirmation', "Can't change value - FPB-00$po approved..!");
           return FALSE;
        }
        else { return TRUE; }
    }

// ===================================== PRINT ===========================================
  
   function print_invoice($po=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $request = $this->Request_model->get_request_by_no($po)->row();

       $data['no'] = $po;
       $data['podate'] = tgleng($request->dates);
       $data['user'] = $this->user->get_username($request->user);
       $data['supervisor'] = $request->supervisor;
       $data['type'] = $request->type;
       
       if ($request->type == 'stock'){ $data['items'] = $this->Request_item_model->report($po)->result(); }
       else { $data['items'] = $this->Request_item_model->report_item($po)->result(); }
       
       $this->load->view('request_invoice', $data);
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $this->load->view('request_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);
        
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $type = $this->input->post('ctype');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        
        if ($type == 0){ $data['reports'] = $this->Request_model->report($start,$end)->result(); $this->load->view('request_report', $data);  } 
        elseif ($type == 1){ $data['reports'] = $this->Request_model->report($start,$end)->result(); $this->load->view('request_report_details', $data);  } 
        elseif ($type == 2) { $data['reports'] = $this->Request_item_model->report_category($start,$end)->result(); $this->load->view('request_report_category', $data); }
        elseif ($type == 3) { $data['reports'] = $this->Request_item_model->report_category($start,$end)->result(); $this->load->view('request_pivotable', $data); }
        
    }


// ====================================== REPORT =========================================

}

?>