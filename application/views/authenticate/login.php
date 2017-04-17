<header id="vote-header">
    <h1 id="logo">
        <img src="/assets/images/NEWGOLDE.png" alt="Elliv Logo"/>
    </h1>
    <h2>Elliv Poll Sign In</h2>
</header>
<section class="login-page">
    <form action="{base_url}authenticate/login" method="post" class="login-form">

        {login_error?}
        <p class="alert alert-warning">{message}</p>
        {/login_error?}

        <p>Please sign in with your Cedarville University username and password.</p>
        <ul>
            <li class="control-group">
                <label class="control-label">Username</label>
                <div class="controls">
                    <input type="text" name="username" placeholder="username"/>
                </div>
            </li>
            <li class="control-group">
                <label class="control-label">Password</label>
                <div class="controls">
                    <input type="password" name="password" placeholder="password"/>
                </div>
            </li>
        </ul>
        <p><input class="button" type="submit" value="Sign in"/></p>
    </form>
</section>