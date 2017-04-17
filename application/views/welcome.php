<header id="vote-header">
    <h1 id="logo">
        <img src="/assets/images/NEWGOLDE.png" alt="Elliv Logo"/>
    </h1>
    <h2>Welcome to the {poll_title}!</h2>
</header>
<section class="poll-welcome" id="vote-main">
    {is_admin?}
    <a class="link-admin" href="/admin/">Admin Panel</a>
    {/is_admin?}

    <div class="intro">
        {poll_intro}
    </div>
    <a class="btn vote-btn" href="/poll/question/{poll_id}/1">Vote Now</a>
</section>