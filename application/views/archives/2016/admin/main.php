<h1>Administrator Panel</h1>

<a class="back-to-poll" href="/poll/index/">&larr; Back to poll</a>

<a class="reset-ballot" href="/admin/reset_ballot/" title="Parmanently reset your own ballot">Reset ballot</a>
{polls}
<h2>{title}</h2>
<ul class="question-list">
{questions}
	<li class="question">
    	<div class="question-info">
        	<h3>{label}</h3>
            {details}
        </div>
        <div class="question-results">
        	<ul class="option-list">
            	{options}
            	<li class="option">
                    <p class="option-label">{option_label}</p>
                    <div class="votes">
                    	<div class="vote-bar" style="width:{percentage}%;"></div>
                        <p class="vote-info">{num_votes} votes; {percentage}%</p>
                    </div>
                </li>
            	{/options}
            </ul>
        </div>
    </li>
{/questions}
</ul>
{/polls}