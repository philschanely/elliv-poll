<div class="question_left">
	<h1><img src="/assets/images/Elliv_logo_grey.png" alt="Elliv 2016" class="logo_grey"/></h1>
	<h2>{question.label}</h2>
	<div class="awardDescription">
		{question.details}
	</div>
	<p class="iconDescription">To learn more, click on the nominee's name. <br/>To vote, click the circle next to their name.</p>
	<p class="scrollDown">Scroll down to choose your nominee</p>
</div>
<div class="question_right">
	<ul>
		{options}
        <li id="nominee{order}" class="nominee {is_selected?}selected{/is_selected?}"> 
            <a class="nominee_text" href="#nominee{order}_info" title="Click for more information" >{label}</a>
            {has_image?}
            <div class="nominee_photo">
                <img src="{asset}" />
            </div>
            {/has_image?}
            <a id="nominee{order}" href="/poll/save_selection/{o_id}/{return_to_review}" 
            	class="nominee_info" title="Click to vote for this nominee">
                Vote for this nominee
            </a>
            <div id="nominee{order}_info" class="info">
                <div class="modal">
                    <h2>{label}</h2>
                    <a href="#close" title="Close" class="close">&times;</a>
                    <div class="info_details">
                        {info}
                    </div>
                </div>
                <a href="#" title="Close" class="close-full"></a>
            </div>
        </li>
		{/options}
	</ul>
</div>
<div class="nav">
	<p class="nav_arrow">
	    <a id="arrow_left" 
	        {is_first_question?}href="/poll/index/{poll_id}"{/is_first_question?}
	        {is_not_first_question?}href="/poll/question/{poll_id}/{previous_question}"{/is_not_first_question?}>
	        Back Button
        </a>
    </p>
    
	<ul class="nav_icons">
	    {poll_questions}
		<li class="nav_button">
    	    <a {is_current?}id="current"{/is_current?} {is_completed?}class="completed"{/is_completed?} href="/poll/question/{poll_id}/{number}">
    	        Question {number}
            </a>
	    </li>
		{/poll_questions}
		<li class="nav_button nav_review">
		    <a href="/poll/review/{poll_id}" class="submit">
		        Submission page
	        </a>
        </li>
	</ul>
	
    <p class="nav_arrow">
	    <a id="arrow_right"
	        {is_last_question?}href="/poll/review/{poll_id}"{/is_last_question?}
	        {is_not_last_question?}href="/poll/question/{poll_id}/{next_question}"{/is_not_last_question?}>
	        Next Button
        </a>
    </p>
    
</div>