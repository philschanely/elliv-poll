<div class="question">
	<h1><img src="#" alt="Elliv" /></h1>
	<h2>{question.label}</h2>
	<div class="award-description">
		{question.details}
	</div>
</div>
<div class="options">
	<ul>
		{options}
        <li id="nominee-{order}" class="nominee {is_selected?}selected{/is_selected?}">
            <a class="nominee-text" href="#nominee-{order}-info" title="Click for more information" >{label}</a>
            {has_image?}
            <div class="nominee-photo">
                <img src="{asset}" />
            </div>
            {/has_image?}
            <a id="nominee-{order}" href="/poll/save_selection/{o_id}/{return_to_review}"
            	class="nominee-info" title="Click to vote for this nominee">
                Vote for this nominee
            </a>
            <div id="nominee-{order}-info" class="info">
                <div class="modal">
                    <h2>{label}</h2>
                    <a href="#nominee-{order}" title="Close" class="close">&times;</a>
                    <div class="info-details">
                        {info}
                    </div>
                </div>
                <a href="#nominee-{order}" title="Close" class="close-full"></a>
            </div>
        </li>
		{/options}
	</ul>
</div>
<div class="nav">
	<p class="nav-arrow">
	    <a class="arrow-left"
	        {is_first_question?}href="/poll/index/{poll_id}"{/is_first_question?}
	        {is_not_first_question?}href="/poll/question/{poll_id}/{previous_question}"{/is_not_first_question?}>
	        Back Button
        </a>
    </p>
    
	<ul class="nav-icons">
	    {poll_questions}
		<li class="nav-button">
    	    <a class="{is_current?}current{/is_current?} {is_completed?}completed{/is_completed?}"
               href="/poll/question/{poll_id}/{number}">
    	        Question {number}
            </a>
	    </li>
		{/poll_questions}
		<li class="nav-button nav-review">
		    <a href="/poll/review/{poll_id}" class="submit">
		        Submission page
	        </a>
        </li>
	</ul>
	
    <p class="nav-arrow">
	    <a id="arrow-right"
	        {is_last_question?}href="/poll/review/{poll_id}"{/is_last_question?}
	        {is_not_last_question?}href="/poll/question/{poll_id}/{next_question}"{/is_not_last_question?}>
	        Next Button
        </a>
    </p>
    
</div>