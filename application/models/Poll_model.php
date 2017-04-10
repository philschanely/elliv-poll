<?php
class Poll_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function get($poll_id)
    {
        $this->db->where('poll_id', $poll_id);
        $poll = checkForResults(
            $this->db->get('Poll'), 
            'row'
        );
            
        return $poll;
    }
    
    public function get_first_question($poll_id)
    {
        $this->get_question_by_order($poll_id, 1);
        
        return $question;
    }
    
    public function get_question_by_order($poll_id, $order=1)
    {
        $this->db->where('order', $order);
        $this->db->where('poll', $poll_id);
        
        $question = checkForResults($this->db->get('Question'), 'row');
        
        return $question;
    }
	
    public function get_questions($poll_id, $load_options=FALSE, $load_results=FALSE)
    {
        $this->load->model('question_model');
		
        $this->db->where('poll', $poll_id);   
        $this->db->order_by('order');
        $questions = checkForResults($this->db->get('Question'));
        
        if ($questions && $load_options)
        {
            foreach ($questions as &$question)
            {
                $question->options = $this->question_model->get_options($question->q_id, NULL, $load_results);	
                
                if ($load_results)
                {
                    $total_votes = $this->question_model->get_total_votes($question->q_id);
                }
                
                foreach ($question->options as &$option)
                {
                    $option->option_label = $option->label;
                    if ($load_results)
                    {
                        $option->percentage = $total_votes > 0 
                            ? round($option->num_votes/$total_votes * 100, 2)
                            : '--';
                    }
                }
            }
        }

        return $questions;
    }
    
    public function get_num_questions($poll_id)
    {
        $questions = $this->get_questions($poll_id);
        
        return count($questions);
    }
    
    public function get_review_info($poll_id, $user_id) 
    {
        $this->db->where('poll_id', $poll_id);   
        $this->db->where('user', $user_id);
        $this->db->order_by('order');
        $ballot_items = checkForResults($this->db->get('Ballot_Item_Details'));
        
        return $ballot_items;
    }
    
    
    public function submit_poll($poll_id, $user_id)
    {
        $this->load->model('log_model');
        
        $this->db->insert('Completed_Poll', array(
            'poll'=>$poll_id,
            'user'=>$user_id
        ));
        
        $this->db->where('poll', $poll_id);   
        $this->db->where('user', $user_id);  
        $completed_poll_info = cfr('Completed_Poll_Details', 'row');
        
        $this->log_model->add($user_id, LOGTYPE_SUBMIT);
        
        return $completed_poll_info;
    }
    
    public function is_poll_submitted($poll_id, $user_id)
    {
        $this->db->where('poll', $poll_id);   
        $this->db->where('user', $user_id);  
        $completed_poll_info = cfr('Completed_Poll_Details', 'row');
        
        return (bool) $completed_poll_info;
    }
    
    public function get_answered_questions($poll_id, $user_id) 
    {
        $answered_questions = array();
        
        $this->db->where('poll_id', $poll_id);   
        $this->db->where('user', $user_id);
        $answered_questions_obj = cfr('Ballot_Item_Details');
        
        if ($answered_questions_obj)
        {
            foreach ($answered_questions_obj as $question)
            {
                $answered_questions[] = $question->order;
            }
        }
        
        return $answered_questions;
    }
	
	public function get_polls_for_results($poll_id=CURRPOLL)
	{
            $this->db->order_by('title');
            $this->db->where('poll_id', $poll_id);
            $polls = cfr('Poll');

            if (!empty($polls))
            {
                foreach ($polls as &$poll)
                {
                    $poll->questions = $this->get_questions($poll->poll_id, TRUE, TRUE);
                }
            }

            return $polls;
	}
}