<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Local Authentication extension
 *
 * Local wrapper for core authenticate class. Customize values set in the 
 * constructor function for this application.
 *
 * @package    App_name
 * @subpackage Library
 * @author     Phil Schanely <philschanely@cedarville.edu>
 */
class MY_Authenticate extends CI_Authenticate {
    
    var $is_ajax;
    
    public function __construct($config = array()) 
    {
        // Initialize the core class
        parent::__construct($config);
        
        $this->is_ajax = $this->CI->input->is_ajax_request();
        
        // Local settings --------
        $this->login_path = 'authenticate/login';
        $this->logout_path = 'authenticate/logout';
        $this->redirect_path = '';
        $this->synch_with_warehouse = FALSE;
        $this->auth_groups = FALSE;
        
        // Session prefix for all authenctication values
        $this->prefix = 'ellivpoll';        
    }
    
    public function check_for_auth($app_prefix=NULL, $login_url=NULL, $force=NULL)
    {
        $this->CI->load->model('user_model');
        
        $userID = parent::check_for_auth($app_prefix, $login_url, $force);
        $user = $this->CI->user_model->validate($userID);
        if ($user == FALSE)
        {
            $this->CI->dso->auth_in_app = FALSE;
            if ($force) redirect($login_url);
        }
        else
        {
            $this->CI->dso->user = $user;
            $this->CI->dso->auth_in_app = TRUE;
			
			// Local additions for login rights
			$this->CI->dso->is_admin = $user->access_level <= ACCESS_LEVEL_ADMIN ? TRUE : FALSE;
			$this->CI->dso->is_superadmin = $user->access_level <= ACCESS_LEVEL_SUPERADMIN ? TRUE : FALSE;
        	
        }
		
		
        return $user;
    }
    
    public function ellivpoll_login($data, $view, $redirect_to, $user)
    {
        $this->CI->load->model(array('user_model', 'log_model'));
        
        $this->CI->user_model->sync_user($user);
        
        $new_user = $this->check_for_auth();
        
        $this->CI->log_model->add($new_user->user_id, LOGTYPE_SIGNIN);
        
        redirect(base_url($redirect_to), 'refresh');
    }
}