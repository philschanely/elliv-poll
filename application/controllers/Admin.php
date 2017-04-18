<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Administrator controller
 *
 * @package    CI_Starter
 * @subpackage Controllers
 * @author     Phil Schanely <philschanely@cedarville.edu>
 */
class Admin extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
		
        $this->authenticate->check_for_auth();
        $this->user_id = $this->dso->user->user_id;
		
        $this->dso->body_class = 'white-page';

        if (! $this->dso->is_admin)
        {
            redirect('/poll/');
        }
    }
    
    public function index($poll_id=CURRPOLL)
    {
        $this->load->model('poll_model');
        $polls = $this->poll_model->get_polls_for_results($poll_id);

        $this->dso->polls = $polls;

        show_view('admin/main', $this->dso->all);
    }
    
    public function reset_ballot($poll_id=CURRPOLL)
    {
        $this->load->model('ballot_model');
        $this->ballot_model->reset($poll_id, $this->user_id);
        
        redirect('/admin/');
    }
    
}