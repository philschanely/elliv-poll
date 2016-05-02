<?php
class Ballot_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->model('log_model');
    }
    
    public function get_option_info($o_id)
    {
        $this->db->where('o_id', $o_id);
        $option = cfr('Option_Details', 'row');
        
        return $option;
    }
    
    public function save_selection($user_id, $o_id)
    {
        // Get provided option from database
        $this->db->where('o_id', $o_id);
        $selected_option = cfr('Option', 'row');
        
        // Store question number
        $q_id = ($selected_option != FALSE) ? $selected_option->question : NULL;
        $new_label = ($selected_option != FALSE) ? $selected_option->label : NULL;
        
        // Search for ballot items for this user that match question number
        $this->db->where('q_id', $q_id);
        $this->db->where('user', $user_id);
        $matching_ballot_details = cfr('Ballot_Item_Details', 'row');
        
        // If they have a match...
        if ($matching_ballot_details != FALSE)
        {
            $old_label = $matching_ballot_details->option_selected;
            // Update the matching ballot item
            $this->db->where('user', $matching_ballot_details->user);
            $this->db->where('option', $matching_ballot_details->option);
            $this->db->update('Ballot_Item', array('option'=>$o_id));
            $this->log_model->add($user_id, LOGTYPE_CHANGEVOTE, "from {$old_label} to {$new_label}");
        }
        // Otherwise... 
        else
        {
            // Insert new ballot item
            $this->db->insert('Ballot_Item', array(
                'user' => $user_id, 
                'option' => $o_id
            ));
            $this->log_model->add($user_id, LOGTYPE_VOTE, "for {$new_label}");
        } 
        
        // Now select the resulting ballot item details to return
        $this->db->where('user', $user_id);
        $this->db->where('option', $o_id);
        $updated_ballot_item = cfr('Ballot_Item_Details', 'row');
        
        return $updated_ballot_item;
    }
    
    public function reset($poll_id, $user_id)
    {
        // Delete the completed poll
        $this->db->where('user', $user_id);
        $this->db->where('poll', $poll_id);
        $this->db->delete('Completed_Poll');
        
        // Remove the ballot items
        $this->db->select('option');
        $this->db->where('user', $user_id);
        $this->db->where('poll_id', $poll_id);
        $ballot_items = cfr('Ballot_Item_Details');
        
        if ($ballot_items)
        {
            $options = array();
            foreach ($ballot_items as $item)
            {
                $options[] = $item->option;
            }
            $this->db->where('user', $user_id);
            $this->db->where_in('option', $options);
            $this->db->delete('Ballot_Item');
        }
        
    }
    
}