<?php
class Question_model extends CI_Model {
    
    
    public function get_options($q_id, $user_id=NULL, $load_results=FALSE)
    {
        $this->db->where('question', $q_id);
        $this->db->order_by('order', 'ASC');
       
        if ($load_results)
        {
            $options = cfr('Option_Results');
        }
        else
        {
            $options = cfr('Option');
        }
       
        if ($options && $user_id !== NULL)
        {
            $this->format_options($q_id, $options, $user_id);
        }
       
        return $options;
       
    }
    
    public function format_options($q_id, &$options, $user_id)
    {
        $this->db->where('q_id', $q_id);
        $this->db->where('user', $user_id);
        $selected_option = cfr('Ballot_Item_Details', 'row');
        
        foreach ($options as &$option)
        {
            $option->has_image = $option->asset == NULL ? FALSE : TRUE;
            $option->is_selected = FALSE;
            if ($selected_option && $option->o_id == $selected_option->option)
            {
                $option->is_selected = TRUE;
            }
        }
    }
    
    public function get_total_votes($q_id)
    {
        $this->db->where('question', $q_id);
        $results = cfr('Question_Results', 'row');
        
        return $results ? $results->ttl_votes : 0;
    }
}