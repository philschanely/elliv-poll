<header id="vote-header">
    <h1 id="logo">
        <img src="/assets/images/NEWGOLDE.png" alt="Elliv Logo"/>
    </h1>
    <h2>Administrator Panel</h2>
</header>
<section class="admin-panel">

    <a class="back-to-poll" href="/poll/index/">&larr; Back to poll</a>

    <a class="reset-ballot" href="/admin/reset_ballot/" title="Permanently reset your own ballot">Reset ballot</a>

    {polls}
    <h2>Elliv 2017 Poll Results</h2>
    <ul class="question-list">
        {questions}
        <li class="question">
            <div class="question-info">
                <h3>{label}</h3>
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
</section>