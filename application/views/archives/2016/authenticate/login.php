<h1><small>Elliv Poll</small><br/> Sign in</h1>
<form action="{base_url}authenticate/login" method="post">
    {login_error?}
    <p class="alert alert-warning">{message}</p>
    {/login_error?}

    <p>Please sign in with your Cedarville University username and password.</p>
    <ul>
        <li class="control-group">
            <label class="control-label">Username</label>
            <div class="controls">
                <input type="text" name="username" />
            </div>
        </li>
        <li class="control-group">
            <label class="control-label">Password</label>
            <div class="controls">
                <input type="password" name="password" />
            </div>
        </li>
    </ul>
    <p><input class="button" type="submit" value="Sign in" /></p>
</form>