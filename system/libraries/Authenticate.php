<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 * 
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Authenticate Class
 *
 * This class enables the creation of calendars
 *
 * @package	CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author	Phil Schanely and Thomas Neal
 * @link	http://cedarville.edu/ctl
 */
class CI_Authenticate {

    var $CI;
    var $prefix;
    var $login_path;
    var $logout_path;
    var $redirect_path;
    var $synch_with_warehouse;
    var $force_login;
    var $auth_on_cedarnet;
    var $auth_in_app;
    var $auth_groups;
    var $default_user;
    var $manual_authentication;

    private $_username;
    private $_firstName;
    private $_lastName;
    private $_email;
    private $_ID;

    /**
     * Constructor
     */
    public function __construct($config = array())
    {
        $this->CI =& get_instance();
        $this->default_user = (object) array(
            'ID'=>0,
            'firstName'=>'',
            'lastName'=>'',
            'groups'=>array()
        );
        $this->manual_authentication = FALSE;
        $this->prefix = 'ctl';
        $this->login_path = 'authenticate/login';
        $this->logout_path = 'authenticate/logout';
        $this->redirect_path = '';
        $this->synch_with_warehouse = TRUE;
        $this->force_login = TRUE;
        $this->auth_on_cedarnet = FALSE;
        $this->auth_in_app = FALSE;
        $this->auth_groups = '';
    }

    // --------------------------------------------------------------------

    /**
     * Login a user
     * 
     * Governs logging in a user given view-relevant data, a login URL, a redirect 
     * address, session prefix and optional warehouse synchronization setting.
     * 
     * @param type $data
     * @param type $view
     * @param type $redirect_to
     * @param type $app_session_prefix
     * @param type $synch_with_warehouse 
     */
    public function login(
        $data=array(), 
        $view=NULL, 
        $redirect_to=NULL, 
        $app_session_prefix=NULL, 
        $synch_with_warehouse=NULL
    )
    {
        $this->CI->load->library(array('parser','form_validation'));
        $this->CI->load->helper(array('form','template','url'));
		
        // Get default values for overrides if none are provided
        $view = $view === NULL ? $this->login_path : $view;
        $redirect_to = $redirect_to === NULL ? $this->redirect_path : $redirect_to;
        $app_session_prefix = $app_session_prefix === NULL ? $this->prefix : $app_session_prefix;
        $synch_with_warehouse = $synch_with_warehouse === NULL ? $this->synch_with_warehouse : $view;
        
        // Ensure local prefix is set
        $this->prefix = $app_session_prefix;
        
        // Validate form fields
        $this->CI->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->CI->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->CI->form_validation->run() == FALSE)
        {
            // Show the login form 
            show_view($view,$data);
        }
        else
        {
            // Try to authenticate the user
            $user = $this->_ctl_login(
                $this->CI->input->post('username'),
                $this->CI->input->post('password'),
                $this->prefix,
                $this->auth_groups,
                $synch_with_warehouse
            );
            if ($user)
            {
                // Successful login ... run additional authentication checks
                $custom_login_processor = $app_session_prefix . '_login';
                if (method_exists($this, $custom_login_processor))
                {
                    $this->$custom_login_processor($data, $view, $redirect_to, $user);
                }
                else
                {
                    #ep("Successful login. Redirect to {$redirect_to}");
                    redirect(base_url($redirect_to), 'refresh');
                }
            }
            else
            {
                // Either the user is not valid or not allowed in this application
                $data->login_error = TRUE;
                if ($this->auth_on_cedarnet)
                {
                    $data->message = 'You do not have permission to use this application. Please contact the CT&amp;L for assistance. ';
                }
                else
                {
                    $data->message = 'Invalid username or password; please try again. ';
                }
                show_view($view,$data);
            }
        }
    }
    
    public function logout($data=array(), $view=NULL, $redirect_to=NULL, $force=NULL, $app_session_prefix=NULL)
    {
        $this->CI->load->helper(array('url'));

        // Get default values for overrides if none are provided
        $view = $view === NULL ? $this->logout_path : $view;
        $redirect_to = $redirect_to === NULL ? $this->login_path : $redirect_to;
        $force = $force === NULL ? $this->force_login : $force;
        $app_session_prefix = $app_session_prefix === NULL ? $this->prefix : $app_session_prefix;
        $this->_ctl_logout($app_session_prefix);
        if ($force) 
        {
            redirect($redirect_to);
        }
        else
        {
            $data['redirect_url'] = $redirect_to;
            show_view($view, $data);
        }
    }

    /**
     * Check for applicaiton authentication
     * 
     * Looks for authentication and user values in session using the provided prefix. 
     * Optionally redirects to a login for if $login_url and $force are set accordingly.
     * 
     * @param type $app_prefix
     * @param type $login_url
     * @param type $force
     * @return type 
     */
    public function check_for_auth($app_prefix=NULL, $login_url=NULL, $force=NULL)
    {
        $this->CI->load->library(array('parser'));
        $this->CI->load->helper(array('template', 'url'));
        
        // Set local values in case none are povided
        $app_prefix = $app_prefix === NULL ? $this->prefix : $app_prefix;
        $login_url = $login_url === NULL ? $this->login_path : $login_url;
        $force = $force === NULL ? $this->force_login : $force;
        
        // Ensure local prefix is updated
        $this->prefix = $app_prefix;
        
        if ($this->CI->session->userdata($app_prefix . '_auth'))
        {
            #ep('Authenticated');
            $this->_setup_user();
            return $this->ID;
        }
        else
        {
            #ep('Not authenticated');
            if ($force)
            {
                $referral = uri_string();
                redirect($login_url . '?ref=' . $referral);
            }
        }
    }
    
    /**
     * Cedarville University Authenticator
     *
     * Official Cedarnet authentication service automator.
     * @param string $sUsername the username provided.
     * @param string $sPassword the password provided.
     * @return array
     */
    private function _cu_authenticate($username='', $password='') 
    {
        $url = 'https://ctl2.cedarville.edu/library/services/cu_login.php';
        $body = 'username=' . $username . '&password=' . $password;
        $options = array('method' => 'POST', 'content' => $body, 'header'  => "Content-Type: application/x-www-form-urlencoded");

        // Create the stream context
        $context = stream_context_create(array('http' => $options));

        // Pass the context to file_get_contents()
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);
        
        if ($result->ErrorCode) 
        {
            return FALSE;
        }
        
        $user = array();
        $user['ID'] = (int) $result->RedwoodId;
        $user['username'] = (string) $result->UserName;
        $user['firstName'] = (string) $result->FirstName;
        $user['lastName'] = (string) $result->LastName;
        $user['groups'] = $result->GroupList;
        
        return (object) $user;
    }
    
    /**
     * CTL Login
     *
     * Handles Cedarville authentication for a CTL application user; ctl users 
     * will have one of several values in their LDAP group list; 
     * centerforteachingandlearning, centerforteachingandlearning_student, or ctl_intern.
     * Specific applications can pass in their groups if relevant.
     *
     * @param type $_username
     * @param type $_password
     * @param type $_sessionRoot
     * @param type $_grouplist
     * @param type $_synchronizeWithWarehouse
     * @param type $_showFeedback
     * @return type 
     */
    private function _ctl_login(
        $_username, 
        $_password,
        $_sessionRoot='ctl', 
        $_grouplist='',
        $_synchronizeWithWarehouse=TRUE,
        $_showFeedback=FALSE
    ) {
        // Ensure the provided session prefix is synchronized
        if ($this->prefix != $_sessionRoot) $this->prefix = $_sessionRoot;
        
        // Prepare group list
        $default_group_list = array(
            'infotech-nis-group',
            'CTL_Group',
            'CenterForTeachingAndLearning',
            'ctl_intern',
            'CTL_Intern',
            'ctl_admin',
            'ctl_client',
            'centerforteachingandlearning',
            'ctl_group',
            'centerforteachingandlearning_student',
            'Accreditation_Shared_Jdrive_Group'
        );
        $_grouplist = $_grouplist === '' ? $default_group_list : $_grouplist;
        #ep('Trying to log in through CU service ...', $_showFeedback);
		
        // Prepare authentication variables
        $bAuthOnCU = FALSE;
        $bGroupAuthSuccessful = FALSE;
		
        // Get user details from provided credentials
        $user = $this->manual_authentication 
            ? $this->default_user 
            : $this->_cu_authenticate($_username, $_password);
		
        // Check for authentication
        if ($user !== FALSE) 
        {	
            $this->auth_on_cedarnet = $bAuthOnCU = TRUE;
            $this->CI->session->set_userdata('auth_cedarnet',$bAuthOnCU);
			
            // Check for group membership
            if (!empty($user->groups)) 
            {
                if ($_grouplist === FALSE) 
                {
                    #ep('No groups required',$_showFeedback);
                    $bGroupAuthSuccessful = TRUE;
                } 
                else 
                {
                    // Parse groups list for matches in key groups
                    foreach ($user->groups as $sGroup) 
                    {
                        // If any key group matches user's group, they are allowed
                        // into the application. 
                        if (in_array($sGroup,$_grouplist)) 
                        {
                            $bGroupAuthSuccessful = TRUE;
                            #ep('Found as an allowed member',$_showFeedback);
                            break;
                        }
                    }
                }
                #ep('Finished parsing groups', $_showFeedback);
            } 
            else 
            {
                #ep('User is not in any LDAP groups', $_showFeedback);
            }
        } 
        else 
        {
            #ep('Authentication service not available.', $_showFeedback);
        }
		
        // Set session variables as appropriate
        if ($bAuthOnCU && $bGroupAuthSuccessful) 
        {
            #ep('Authenticated: Setting session variables',$_showFeedback);
			
            // Set up session keynames
            $keys = $this->_setup_user_keys($_sessionRoot);
            
            $this->CI->session->{$keys['auth']} = TRUE;
            $this->CI->session->{$keys['username']} = $user->username;
            $this->CI->session->{$keys['ID']} = $user->ID;
            $this->CI->session->{$keys['firstName']} = $user->firstName;
            $this->CI->session->{$keys['lastName']} = $user->lastName;
            $this->CI->session->{$keys['email']} = $user->username . '@cedarville.edu';
            $this->CI->session->{$keys['groupList']} = $user->groups;
        } 
        else 
        {
            #ep('Not Authenticated: Unsetting session variables',$_showFeedback);
            $this->_ctl_logout($_sessionRoot, $_showFeedback);
        }
		
        // Syncrhonize with warehouse if necessary
        if ($bAuthOnCU && $bGroupAuthSuccessful && $_synchronizeWithWarehouse) 
        {
            $this->_synchronize_person_with_warehouse(
                $user->ID,
                $user->username,
                $user->firstName,
                $user->lastName,
                $user->username . '@cedarville.edu'
            );
        }
        
        // Set local versions
        $this->_setup_user();
		
        return $bAuthOnCU && $bGroupAuthSuccessful ? $user : FALSE;
    }
    
    /**
     * CTL Logout
     * 
     * Logs a user out of the current application context by setting all relevant
     * values to be empty. DOES NOT destroy the whole session.
     * 
     * @param type $_sessionRoot
     * @param type $_showFeedback
     * @return boolean 
     */
    private function _ctl_logout(
        $_sessionRoot='ctl', 
        $_showFeedback=FALSE
    )
    {
        $keys = $this->_setup_user_keys($_sessionRoot);
        $this->CI->session->{$keys['auth']} = FALSE;
        $this->CI->session->{$keys['username']} = NULL;
        $this->CI->session->{$keys['ID']} = NULL;
        $this->CI->session->{$keys['firstName']} = NULL;
        $this->CI->session->{$keys['lastName']} = NULL;
        $this->CI->session->{$keys['email']} = NULL;
        $this->CI->session->{$keys['groupList']} = NULL;
        return TRUE;
    }
    
    private function _setup_user_keys($_sessionRoot)
    {
        $user_keys = array();
        $user_keys['auth']=     $_sessionRoot . '_auth';
        $user_keys['username']= $_sessionRoot . '_username';
        $user_keys['ID']=       $_sessionRoot . '_ID';
        $user_keys['firstName']=$_sessionRoot . '_firstName';
        $user_keys['lastName']= $_sessionRoot . '_lastName';
        $user_keys['email']=    $_sessionRoot . '_email';
        $user_keys['groupList']='cedarnet_groups';
        return $user_keys;
    }
    
    /* Syncrhonize person with warehouse
     * 
     * Synchronize the provided person's data with the warehouse Person
     * @param int redwoodid - the user's redwood id or other primary key
     * @param string username - the user's username or NULL if no value
     * @param string firstname - the user's firstname or NULL if no value
     * @param string lastname - the user's lastname or NULL if no value
     * @param string email - the user's email or NULL if no value
     * @return bool - based on success of syncrhonization
     */
    private function _synchronize_person_with_warehouse(
        $_redwoodid=0,
        $_username=NULL,
        $_firstname=NULL,
        $_lastname=NULL,
        $_email=NULL,
        $_showMsgs=TRUE
    ) {
        // Establish connection and db variables
        $this->CI->load->database();
        #echoPretty('Synching with warehouse');
		
        // Make sure db is present and redwoodID is provided
        if ($_redwoodid > 0) {
            // Build values that are present
            $values = array();
            if ($_username !== NULL) $values['username'] = $_username;
            if ($_firstname !== NULL) $values['firstName'] = $_firstname;
            if ($_lastname !== NULL) $values['lastName'] = $_lastname;
            if ($_email !== NULL) $values['email'] = $_email;

            // Try to retrieve matching person
            $this->CI->db->where('redwoodID', $_redwoodid);
            $query = $this->CI->db->get('`warehouse`.`Person`');
            $result = checkForResults($query,'row');

            // If no match, add the person
            if (!$result) {
                $values['redwoodID'] = $_redwoodid;
                $oInsert = $this->CI->db->insert('`warehouse`.`Person`', $values);
                #echoPretty($this->CI->db->last_query());
            }
            // Otherwise, update the matching person
            else {
                $this->CI->db->where('redwoodID', $result->redwoodID);
                $oUpdate = $this->CI->db->update('`warehouse`.`Person`', $values);
                #echoPretty($this->CI->db->last_query());
            }
            return TRUE;
        } elseif ($_redwoodid == '1') {
            return TRUE;
        } else {
            #echoPretty('No database or redwoodID');
            return FALSE;
        }
    }
    
    /** 
     * Set up session user
     * Retrieves user data from session and stores locally for faster processing
     */
    private function _setup_user()
    {
        $this->_ID = $this->CI->session->{$this->prefix . '_ID'};
        $this->_username = $this->CI->session->{$this->prefix . '_username'};
        $this->_firstName = $this->CI->session->{$this->prefix . '_firstName'};
        $this->_lastName = $this->CI->session->{$this->prefix . '_lastName'};
        $this->_email = $this->CI->session->{$this->prefix . '_email'};
    }
    
    /**
     * Magic getter function
     * 
     * Called if app tries to retrieve a non-property. Property is prefixed with the
     * appropriate application prefix and that value is search for on the session 
     * object. If found, it is returned. If the property is also a preset private
     * value, the session equivalent is set locally and then returned for faster
     * local processing. 
     * 
     * @param type $variable_name
     * @return type 
     */
    public function __get($variable_name)
    {
        $return_value = NULL;
        $private_name = '_' . $variable_name;
        if (property_exists($this, $private_name)) 
        {
            $this->$private_name = 
                $this->CI->session->{$this->prefix . $private_name};
            $return_value = $this->$private_name;
        }
        elseif ($this->CI->session->{$this->prefix . $private_name})
        {
            $return_value = $this->CI->session->{$this->prefix . $private_name};
        }
        return $return_value;
    }
    
    /**
     * Magic setter function
     * 
     * Called if a app tries to set a non-property. Property is prefixed with the 
     * appropriate application prefix and then saved in the session object.
     * 
     * @param type $variable_name
     * @param type $desired_value
     * @return boolean 
     */
    public function __set($variable_name, $desired_value)
    {
        $private_name = '_' . $variable_name;
        $this->CI->session->{$this->prefix . $private_name} = $desired_value;
        return TRUE;
    }
    
}

// END CI_Authenticate class

/* End of file Authenticate.php */
/* Location: ./system/libraries/Authenticate.php */