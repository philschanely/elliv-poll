<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poll extends CI_Controller {
    
    var $user_id = NULL;
   
    public function __construct()
    {
        parent::__construct();
        
        $this->authenticate->check_for_auth();
        $this->user_id = $this->dso->user->user_id;
    }
    
    /**
     * Index
     * 
     * Landing page for the indicated poll that shows an introduction and the
     * option to begin the poll. 
     * 
     * @poll_id int The id for the desired poll. 
     */
    public function index($poll_id=1)
    {
        $this->load->model(array(
            'poll_model'
        ));
        
        if ($this->poll_model->is_poll_submitted($poll_id, $this->user_id))
        {
            redirect('/poll/end/' . $poll_id);
        }
        
        // Load poll information
        $poll = $this->poll_model->get($poll_id);
        
        // Save poll info into view data array
        $this->dso->body_class = 'background_blue';
        $this->dso->poll_title = $poll->title;
        $this->dso->poll_intro = $poll->intro;
        $this->dso->poll_id = $poll->poll_id;
        
        show_view('welcome', $this->dso->all);
        
    }
    
    /**
     * 
     */
    public function end($poll_id)
    {
        $this->load->model(array(
            'poll_model'
        ));
        
        if (! $this->poll_model->is_poll_submitted($poll_id, $this->user_id)) 
        {
            $this->poll_model->submit_poll($poll_id, $this->user_id);
        }
        
        $ballot_items = 
            $this->poll_model->get_review_info($poll_id, $this->user_id);
        $poll = $this->poll_model->get($poll_id);
        
        $this->dso->body_class = 'background_blue';
        $this->dso->poll_title = $poll->title;
        $this->dso->ballot_items = $ballot_items;
        $this->dso->poll_id = $poll_id;
        
        show_view('end', $this->dso->all);
    }
    
    public function question($poll_id, $question_order=1, $return_to_review=0)
    {
        $this->load->model(array(
            'poll_model',
            'question_model'
        ));
        
        $user_id = 1; #temp user
        
        $question = NULL;
        $questions = array();
        $options = NULL;
        $is_first_question = FALSE;
        $is_last_question = FALSE;
    
        $num_questions = $this->poll_model->get_num_questions($poll_id);
        $answered_questions = 
            $this->poll_model->get_answered_questions($poll_id, $this->user_id);
        
        for ($i=0; $i<$num_questions; $i++)
        {
            $questions[] = (object) array(
                'number' => $i+1,
                'is_current' => ($question_order == $i+1) ? TRUE : FALSE,
                'is_completed' => in_array($i+1, $answered_questions) ? TRUE : FALSE
            );
        }
        
        $question = 
            $this->poll_model->get_question_by_order($user_id, $question_order);
        $options = 
            $this->question_model->get_options($question->q_id, $this->user_id);
        
        $next_question = $question->order + 1;
        $previous_question = $question->order - 1;
        
        if ($question->order == 1) 
        {
            $is_first_question = TRUE;
        }
        
        if ($question->order == $num_questions) 
        {
            $is_last_question = TRUE;
        }
        
        $this->dso->body_class = '';
        $this->dso->poll_id = $poll_id;
        $this->dso->question = $question;
        $this->dso->options = $options;
        $this->dso->next_question = $next_question;
        $this->dso->previous_question = $previous_question;
        $this->dso->is_first_question = $is_first_question;
        $this->dso->is_last_question = $is_last_question;
        $this->dso->is_not_first_question = ! $is_first_question;
        $this->dso->is_not_last_question = ! $is_last_question;
        $this->dso->poll_questions = $questions;
        $this->dso->return_to_review = $return_to_review;
        
        show_view('question', $this->dso->all);
    }
    
    public function review($poll_id)
    {
        $this->load->model(array(
            'poll_model'
        ));
        
        $ballot_items = $this->poll_model->get_review_info($poll_id, $this->user_id);
        
        $this->dso->ballot_items = $ballot_items;
        $this->dso->has_ballot_items = empty($ballot_items) ? FALSE : TRUE;
        $this->dso->has_no_ballot_items = empty($ballot_items) ? TRUE : FALSE;
        $this->dso->poll_id = $poll_id;
        
        show_view('review', $this->dso->all);
    }
    
    public function save_selection($o_id, $return_to_review=0)
    {
        $this->load->model(array(
            'ballot_model',
            'poll_model'
        ));
        
        // Save the user's selected option
        $ballot_item = $this->ballot_model->save_selection($this->user_id, $o_id);
        
        // Redirect to appropriate page...
        $num_questions = $this->poll_model->get_num_questions($ballot_item->poll_id);
        $next_question = $ballot_item->order + 1;
        
        $next_page_url = "/poll/question/{$ballot_item->poll_id}/{$next_question}";
        $review_page_url = "/poll/review/{$ballot_item->poll_id}";
        
        if ($next_question > $num_questions || $return_to_review == 1) 
        {
            redirect("/poll/review/{$ballot_item->poll_id}", 'location');
        }
        else 
        {
            redirect("/poll/question/{$ballot_item->poll_id}/{$next_question}", 'location');
        }
    }
}