<?php
class User_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function sync_user($user)
    {
        $user_id = $user->ID;
        
        $data = array(
            'first_name'=>$user->firstName,
            'last_name'=>$user->lastName,
            'email'=>$user->username . '@cedarville.edu',
        );
        
        if ($this->user_exists($user_id))
        {
            $this->db->where('user_id', $user_id);
            $this->db->update('User', $data);
        }
        else
        {
            $data['user_id'] = $user_id;
            $this->db->insert('User', $data);
        }
        
        $user = $this->get($user_id);
        
        return $user;
    }
    
    public function get($user_id)
    {
        $this->db->where('user_id', $user_id);
        $user = cfr('User', 'row');
        
        return $user;
    }
    
    public function user_exists($user_id)
    {
        $user = $this->get($user_id);
        
        return $user == FALSE ? FALSE : TRUE;
    }
    
    public function validate($user_id)
    {
        if ($this->user_exists($user_id))
        {
            return $this->get($user_id);
        }
        else
        {
            return FALSE;
        }
    }
}