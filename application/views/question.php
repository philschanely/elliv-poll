<header id="vote-header">
    <h1 id="logo">
        <img src="/assets/images/NEWGOLDE.png" alt="Elliv Logo"/>
    </h1>
    <h2>{question.label}</h2>
    <p class="description">{question.details}</p>
    <span id="click-msg">Click the names to read more!</span>
</header>
<section id="vote-main">
    <ul id="nominee-list">
        {options}
        <li id="nominee{order}" class="nominee {is_selected?}select-nominee{/is_selected?}">
            <a href="/poll/save_selection/{o_id}/{return_to_review}"
               class="nominee-vote {is_selected?}select-circle{/is_selected?}" id="nominee1"
               title="Click to vote for this nominee">Vote here</a>
            <div class="nominee-header">
                <a href="#" class="nominee-title">{label}</a>
                <img src="/assets/images/arrow-01.png" class="dropdown-arrow"/>
            </div>
            <div class="nominee-description">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close">&times;</span>
                    </div>
                    {has_image?}
                    <img src="{asset}" />
                    {/has_image?}
                    <h2>{label}</h2>
                    {info}
                </div>
            </div>
        </li>
        {/options}
    </ul>
</section>
<footer id="vote-footer">
    <ul id="vote-nav">
        <li id="back-btn" class="nav-btn">
            <a {is_first_question?}href="/poll/index/{poll_id}"{/is_first_question?}
               {is_not_first_question?}href="/poll/question/{poll_id}/{previous_question}"{/is_not_first_question?}>
                &laquo; Back
            </a>
        </li>
        {poll_questions}
        <li class="nav-btn">
            <a href="/poll/question/{poll_id}/{number}"
               class="nominee-page {is_current?}on-page{/is_current?} {is_completed?}completed{/is_completed?}"
               title="page-{number}">Page {number}</a>
        </li>
        {/poll_questions}
        <li id="next-btn" class="nav-btn">
            <a {is_last_question?}href="/poll/review/{poll_id}"{/is_last_question?}
               {is_not_last_question?}href="/poll/question/{poll_id}/{next_question}"{/is_not_last_question?}>
                Next &raquo;
            </a>
        </li>
    </ul>
</footer>
<div class="shade"></div>