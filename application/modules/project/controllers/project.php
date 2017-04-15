<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Project extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Project_model', '', TRUE);
        $this->load->model('Assembly_model', 'am', TRUE);
        $this->load->model('Assembly_product_model', 'amp', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->customer = $this->load->library('customer_lib');
        $this->user = $this->load->library('admin_lib');
        $this->assembly = new Assembly_lib();
        $this->product = new Products_lib();
    }

    private $properti, $modul, $title;
    private $customer,$user,$currency,$assembly,$product;
    
    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'sfancy','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {  $this->session->unset_userdata('aid'); $this->get_last_project(); }
    
    function download($asembly_id)
    {
      $this->load->helper('download'); 
      
      $res = $this->am->get_assembly_by_id($asembly_id)->row();
      
      $data = file_get_contents("./images/project/".$res->image); // Read the file's contents
      $name = $res->image;

      force_download($name, $data); 
    }

    function get_last_project()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'project_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $projects = $this->Project_model->get_last_project($this->modul['limit'], $offset)->result();
        $num_rows = $this->Project_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_project');
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
            $this->table->set_heading('No', 'Code', 'Note', 'Customer', 'Date', 'Staff', '#', 'Action');

            $i = 0 + $offset;
            foreach ($projects as $project)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $project->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'PRJ-00'.$project->no, $project->name, $project->prefix.' '.$project->name, tglin($project->dates), $project->staff,
                    anchor($this->title.'/confirmation/'.$project->id,'<span>update</span>',array('class' => $this->post_status($project->status), 'title' => 'edit / update')).' - '.$this->status($project->status),
                    anchor_popup($this->title.'/invoice/'.$project->id,'<span>details</span>',array('class' => 'print', 'title' => '')).' '.
                    anchor($this->title.'/add_trans/'.$project->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/product/'.$project->id,'<span>details</span>',array('class' => 'details', 'title' => '')).'&nbsp; &nbsp;'.
                    anchor($this->title.'/delete/'.$project->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
        $data['main_view'] = 'project_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('project/','<span>back</span>', array('class' => 'back')));

        $projects = $this->Project_model->search($this->input->post('tcust'), $this->input->post('tdate'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Note', 'Customer', 'Date', 'Staff', '#', 'Action');

        $i = 0;
        foreach ($projects as $project)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $project->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'PRJ-00'.$project->no, $project->name, $project->prefix.' '.$project->name, tglin($project->dates), $project->staff,
                anchor($this->title.'/confirmation/'.$project->id,'<span>update</span>',array('class' => $this->post_status($project->status), 'title' => 'edit / update')).' - '.$this->status($project->status),
                anchor_popup($this->title.'/invoice/'.$project->id,'<span>details</span>',array('class' => 'print', 'title' => '')).' '.
                anchor($this->title.'/add_trans/'.$project->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                anchor($this->title.'/product/'.$project->id,'<span>details</span>',array('class' => 'details', 'title' => '')).'&nbsp; &nbsp;'.
                anchor($this->title.'/delete/'.$project->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }
        
        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    function get_list()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['form_action'] = site_url($this->title.'/get_list');
        $data['main_view'] = 'project_list';
        $data['link'] = array('link_back' => anchor($this->title.'/get_list','<span>back</span>', array('class' => 'back')));

        $projects = $this->Project_model->get_project_list()->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Note', 'Customer', 'Date',  'Staff', 'Action');

        $i = 0;
        foreach ($projects as $project)
        {
           $datax = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$project->id.'\',\'titem\')'
                         );

            $this->table->add_row
            (
                ++$i, 'PRJ-00'.$project->id, $project->name, $project->prefix.' '.$project->name, tglin($project->dates), $project->staff,    
                form_button($datax)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('project_list', $data);
    }

//    ===================== approval ===========================================

    private function post_status($val)
    {
       if ($val > 0) {$class = "approve"; }
       else{$class = "notapprove"; }
       return $class;
    }

    private function status($val)
    {
        if ($val == 0){ $val = 'On Progress'; }
        elseif ( $val == 1 ) { $val = 'Finished'; }
        return $val;
    }

    function confirmation($pid)
    {
        $this->acl->otentikasi3($this->title);
        $project = array('status' => 1);
        $this->Project_model->update($pid, $project);
        $this->session->set_flashdata('message', "One $this->title has successfully confirmed..!");
        redirect($this->title);
    }

    function delete($uid)
    {
       $this->acl->otentikasi_admin($this->title);
       $val = $this->Project_model->get_project_by_id($uid)->row();
       if ($val->status == 1){ $this->rollback($uid); } else { $this->remove($uid); }
       redirect($this->title);  
    }
    
    private function rollback($uid)
    {
       $project = array('status' => 0);
       $this->Project_model->update($uid, $project);
       $this->session->set_flashdata('message', "One $this->title has successfully rollback..!"); 
    }
    
    private function remove($uid)
    {
       $project = $this->Project_model->get_project_by_id($uid)->row();
        
        $this->remove_assembly_image($project->no); // remove image assembly
        $this->am->delete_by_project($project->no); // remove assembly
        $this->amp->delete_by_project($project->no); // remove product assembly
        
        $this->Project_model->delete($uid);
        $this->session->set_flashdata('message', "1 $this->title removed, project removed..!");
    }
    
    private function remove_assembly_image($po)
    {
       $result = $this->am->get_last($po)->result(); 
       foreach ($result as $res){ if ($res->image){ $res->image = "./images/project/".$res->image; unlink("$res->image"); } } 
    }


    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Project_model->counter();
        
        $this->load->view('project_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'project_form';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Project_model->counter();

	// Form validation
        $this->form_validation->set_rules('tcust', 'Customer', 'required|callback_valid_customer');
        $this->form_validation->set_rules('tno', 'PRJ - No', 'required|numeric');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstaff', 'Staff', 'required');
        $this->form_validation->set_rules('tlocation', 'Location', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $project = array('no' => $this->input->post('tno'), 'customer' => $this->customer->get_customer_id($this->input->post('tcust')),
                             'dates' => $this->input->post('tdate'), 'name' => $this->input->post('tnote'), 'status' => 0,
                             'location' => $this->input->post('tlocation'), 'desc' => $this->input->post('tdesc'),
                             'staff' => $this->input->post('tstaff'), 'log' => $this->session->userdata('log'));

            $this->Project_model->add($project);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add/');

//            echo 'true';
        }
        else
        {
              $this->load->view('project_form', $data);
//            echo validation_errors();
        }

    }
    
    function invoice($pid=null,$type=null)
    {
        $this->acl->otentikasi2($this->title);
        
        if ($type == 'non'){ $project = $this->Project_model->get_project_by_no($pid)->row(); }
        else { $project = $this->Project_model->get_project_by_id($pid)->row(); }
        
        if ($project->status == 1){ $stts = 'Finished'; }else { $stts = 'On Progress'; }
        // properti
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        $data['company'] = $this->properti['name']; 
        
        $data['pono'] = $project->no;

        $data['customer'] = $this->customer->get_customer_shortname($project->customer);
        $data['date'] = $project->dates;
        $data['note'] = $project->name;
        $data['location'] = $project->location;
        $data['desc'] = $project->desc;
        $data['staff'] = $project->staff;
        $data['status'] = $stts;
        
        $data['items'] = $this->am->get_last($project->no)->result();
        $data['product'] = $this->amp->get_last($project->no,null)->result();

        $this->load->view('project_invoice', $data);
    }

    function add_trans($pid=null)
    {
        $this->acl->otentikasi2($this->title);

        $project = $this->Project_model->get_project_by_id($pid)->row();
        
        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$pid);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$project->no.'/'.$pid);
        $data['code'] = $project->no;

        $data['default']['customer'] = $this->customer->get_customer_shortname($project->customer);
        $data['default']['date'] = $project->dates;
        $data['default']['note'] = $project->name;
        $data['default']['location'] = $project->location;
        $data['default']['desc'] = $project->desc;
        $data['default']['staff'] = $project->staff;
        
        $items = $this->am->get_last($project->no)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Project', 'Notes', 'Desc', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                $item->no, 'PRJ-00'.$item->project, $item->notes, $item->desc,
                anchor_popup($this->title.'/print_item/'.$item->id,'<span>print</span>',$this->atts).' '.
                anchor($this->title.'/delete_item/'.$item->id.'/'.$project->no.'/'.$pid,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('project_transform', $data);
    }
    
    function add_item($no=null,$pid)
    {   
        $config['upload_path'] = './images/project/';
        $config['file_name'] = $no.'_'.$this->input->post('tno');
        $config['allowed_types'] = 'pdf';
        $config['overwrite'] = true;
        $config['max_size']      = '10000';
//        $config['max_width']     = '3000';
//        $config['max_height']    = '3000';
        $config['remove_spaces'] = TRUE;
        
        $this->form_validation->set_rules('tno', 'Assembly No', 'required|callback_valid_assembly_no['.$no.']');
        $this->form_validation->set_rules('tnotes', 'Notes', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', '');

        if ($this->form_validation->run($this) == TRUE && $this->valid_project($pid) == TRUE)
        {
            $this->load->library('upload', $config);
            if ( !$this->upload->do_upload("userfile")) // if upload failure
            {
                $data['error'] = $this->upload->display_errors();
                
                $pitem = array('no' => $this->input->post('tno'), 'project' => $no, 'image' => null,
                           'notes' => $this->input->post('tnotes'), 'desc' => $this->input->post('tdesc'));                
            }
            else
            {
               $info = $this->upload->data();  
               $pitem = array('no' => $this->input->post('tno'), 'project' => $no, 'image' => $info['file_name'],
                              'notes' => $this->input->post('tnotes'), 'desc' => $this->input->post('tdesc'));   
            }
            
            $this->am->add($pitem);
            redirect($this->title.'/add_trans/'.$pid);
        }
        else{ $this->session->set_flashdata('message', "Validation Error..!"); redirect($this->title.'/add_trans/'.$pid); }
    }
    
    function delete_item($id,$po,$pid)
    {
        $this->acl->otentikasi2($this->title);
        
        if ($this->cek_status($pid) == TRUE)
        {
           $img = $this->am->get_assembly_by_id($id)->row();
           $img = $img->image; 
           if ($img){ $img = "./images/project/".$img; unlink("$img"); } 
           
           $no = $this->assembly->get_no($id);
           $this->amp->delete_by_assembly($no,$po);
           $this->am->delete($id);  
           $this->session->set_flashdata('message', "1 item successfully removed..!");
        }
        else{  $this->session->set_flashdata('message', "Project approved, can't remove item...!");  }
        redirect($this->title.'/add_trans/'.$pid);
    }

    // Fungsi update untuk mengupdate db
    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('project/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tcust', 'Customer', 'required|callback_valid_customer');
        $this->form_validation->set_rules('tdate', 'Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tstaff', 'Staff', 'required');
        $this->form_validation->set_rules('tlocation', 'Location', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $project = array('customer' => $this->customer->get_customer_id($this->input->post('tcust')),
                             'dates' => $this->input->post('tdate'), 'name' => $this->input->post('tnote'),
                             'location' => $this->input->post('tlocation'), 'desc' => $this->input->post('tdesc'),
                             'staff' => $this->input->post('tstaff'), 'log' => $this->session->userdata('log'));

            $this->Project_model->update($po, $project);
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/add_trans/'.$po);
//            echo 'true';
        }
        else
        {
            $this->load->view('project_form', $data);
//            echo validation_errors();
        }
    }

    // ========== validation ========================
    
    private function cek_status($id)
    {
        $val = $this->Project_model->get_project_by_id($id)->row();
        $val = $val->status;
        if ($val == 1){ return FALSE; } else { return TRUE; }
    }
    
    public function valid_assembly_no($no,$pid)
    {
        if ($this->am->valid_no($no,$pid) == FALSE)
        {
            $this->form_validation->set_message('valid_assembly_no', "Assembly No Registered...!");
            return FALSE;
        }
        else{ return TRUE; }
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

    public function valid_project($val)
    {
        $val = $this->Project_model->get_project_by_id($val)->row();
        if ($val->status == 1)
        {
            $this->form_validation->set_message('valid_project', "Project Approved.!");
            return FALSE;
        }
        else { return TRUE; }
    }
    
    // ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('purchase/','<span>back</span>', array('class' => 'back')));

        $this->load->view('project_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $customer = $this->input->post('tcust');
        $start  = $this->input->post('tstart');
        $end    = $this->input->post('tend');
        $status = $this->input->post('cstts');
        $type = $this->input->post('ctype');

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        if ($type == 0){ $data['aps'] = $this->Project_model->report($customer,$start,$end,$status)->result(); $page = 'project_report'; }
        elseif ($type == 1){ $data['aps'] = $this->Project_model->report($customer,$start,$end,$status)->result(); $page = 'project_pivot'; }
        elseif ($type == 2) { $data['aps'] = $this->Project_model->report_category($customer,$start,$end,$status)->result(); $page = 'project_category'; }
        elseif ($type == 3) { $data['aps'] = $this->Project_model->report_category($customer,$start,$end,$status)->result(); $page = 'project_category_pivot'; }
        
        if ($this->input->post('cformat') == 0){  $this->load->view($page, $data); }
        elseif ($this->input->post('cformat') == 1)
        {
            $pdf = new Pdf();
            $pdf->create($this->load->view($page, $data, TRUE));
        }
        
    }


    // ======================= ASSEMBLY PRODUCT ==============================
    
    function set_assembly($pid)
    {  
        if ($this->input->post('submit') == 'SET VALUE') { $this->session->set_userdata('aid', $this->input->post('cassembly')); }
        else { $this->session->unset_userdata('aid'); }
        redirect($this->title.'/product/'.$pid);
    }
    
    function product($pid=null)
    {
        $this->acl->otentikasi2($this->title);
        $project = $this->Project_model->get_project_by_id($pid)->row();
        
        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
        $data['form_action'] = site_url($this->title.'/set_assembly/'.$pid);
        $data['form_action_item'] = site_url($this->title.'/add_product/'.$project->no.'/'.$pid);
        $data['code'] = $project->no;

        $data['assembly'] = $this->assembly->combo($project->no);

        $data['default']['customer'] = $this->customer->get_customer_shortname($project->customer);
        $data['default']['date'] = $project->dates;
        $data['default']['note'] = $project->name;
        $data['default']['location'] = $project->location;
        $data['default']['desc'] = $project->desc;
        $data['default']['staff'] = $project->staff;
        
        $items = $this->amp->get_last($project->no,$this->session->userdata('aid'))->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Project', 'Assembly', 'Product', 'Qty', 'Unit', 'Action');

        $i = 1;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                $i, 'PRJ-00'.$item->project, $item->assembly, $item->product, $item->qty, $item->unit,
                anchor($this->title.'/delete_product/'.$item->id.'/'.$pid,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
            $i++;
        }

        $data['table'] = $this->table->generate();
        $this->load->view('project_transform_product', $data);
    }
    
    function add_product($no,$pid)
    {           
        $this->form_validation->set_rules('cassembly', 'Assembly No', 'required');
        $this->form_validation->set_rules('titem', 'Product', 'required');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE && $this->valid_project($pid) == TRUE)
        {
            $product_id = $this->product->get_id($this->input->post('titem'));
            $pitem = array('project' => $no, 'assembly' => $this->input->post('cassembly'), 'product' => $this->input->post('titem'),
                           'qty' => $this->input->post('tqty'), 'unit' => $this->product->get_unit($product_id),
                           'desc' => $this->input->post('tdesc'));  
            
            $this->amp->add($pitem);
            redirect($this->title.'/product/'.$pid);
        }
        else{ $this->session->set_flashdata('message', "Validation Error..!"); redirect($this->title.'/product/'.$pid); }
    }
    
    function delete_product($id,$pid)
    {
        $this->acl->otentikasi2($this->title);
        
        if ($this->cek_status($pid) == TRUE)
        {  
           $this->amp->delete($id);  
           $this->session->set_flashdata('message', "1 product successfully removed..!");
        }
        else{  $this->session->set_flashdata('message', "Project approved, can't remove item...!");  }
        redirect($this->title.'/product/'.$pid);
    }
    
}

?>