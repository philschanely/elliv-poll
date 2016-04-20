<?php

class Log_model extends CI_Model {   

    public function add($user, $log_type, $notes=NULL)
    {
        $data = array(
            'user'=>$user,
            'date'=>date('Y-m-d H:i:s'),
            'type'=>$log_type,
            'notes'=>$notes
        );

        $this->db->insert('Log', $data);
    }
    
}