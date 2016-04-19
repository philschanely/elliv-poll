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
		
		$this->dso->body_class = 'admin';
		
		if (! $this->dso->is_admin)
		{
			redirect('/poll/');
		}
    }
    
    public function index()
	{
		show_view('admin/main', $this->dso->all);
	}
    
}