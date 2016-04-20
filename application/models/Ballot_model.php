<?php
class Ballot_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
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
        
        // Search for ballot items for this user that match question number
        $this->db->where('q_id', $q_id);
        $this->db->where('user', $user_id);
        $matching_ballot_details = cfr('Ballot_Item_Details', 'row');
        
        // If they have a match...
        if ($matching_ballot_details != FALSE)
        {
            // Update the matching ballot item
            $this->db->where('user', $matching_ballot_details->user);
            $this->db->where('option', $matching_ballot_details->option);
            $this->db->update('Ballot_Item', array('option'=>$o_id));
        }
        // Otherwise... 
        else
        {
            // Insert new ballot item
            $this->db->insert('Ballot_Item', array(
                'user' => $user_id, 
                'option' => $o_id
            ));
        } 
        
        // Now select the resulting ballot item details to return
        $this->db->where('user', $user_id);
        $this->db->where('option', $o_id);
        $updated_ballot_item = cfr('Ballot_Item_Details', 'row');
        
        return $updated_ballot_item;
    }
    
}