<div class="question_left">
    <h1><img class="logo_grey" src="/assets/images/Elliv_logo_grey.png" alt="Elliv 2016"></h1>
    <h2>Are you ready to submit?</h2>
    <div class="awardDescription">
        <p>You can review your answers now, but you will not be able to change your selections after submitting your ballot.</p>
    </div>
    
    {has_ballot_items?}
    <p class="iconDescription">Click on an award to go back and change your answer.</p>
    <p class="scrollDown" id="submit_text">Scroll down to change your selections</p>
    <a class="button" id="button_submit" href="/poll/end/{poll_id}">Submit Ballot</a>
    {/has_ballot_items?}
    
    {has_no_ballot_items?}
    <p class="iconDescription">No nominees are selected. <a href="/poll/question/1">Return to poll questions &raquo;</a></p>
    {/has_no_ballot_items?}
</div>
<div class="question_right">
    {has_ballot_items?}
    <ul>
        {ballot_items}
        <li class="award" id="award{order}">
            <a class="selection" href="/poll/question/{poll_id}/{order}/1"><small>{question_label}</small><br>{option_selected}</a>
        </li>
        {/ballot_items}
    </ul>
    {/has_ballot_items?}
</div>