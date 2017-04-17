<header id="vote-header">
    <h1 id="logo">
        <img src="/assets/images/NEWGOLDE.png" alt="Elliv Logo"/>
    </h1>
    <h2>Thank you for completing the {poll_title}!</h2>
</header>
<section class="poll-end">
    {is_admin?}
    <a class="link-admin" href="/admin/">Admin Panel</a>
    {/is_admin?}
    <div class="selections">
        <p>Your ballot has been submitted. Here are your selections:</p>
        <div class="options">
            <ul>
                {ballot_items}
                <li class="award" id="award-{order}">
                    <small>{question_label}</small><br/>
                    {option_selected}
                </li>
                {/ballot_items}
            </ul>
        </div>
        <a class="btn site-btn" href="http://elliv.com">Elliv.com</a>
    </div>
</section>