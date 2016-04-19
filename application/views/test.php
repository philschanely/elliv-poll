<!-- See main/test for examples of data being sent to this view. -->

<h2>Test page!</h2>
<p>Features coming soon</p>

<!-- Enter single values like this: -->
<p>My name is {user.first_name} {user.last_name}.</p>
<!-- Since you've seen normal variable values already I'm showing here 
     the fact that we can also call properties of an object using dot notation. -->

<!-- Protect a set of code with a boolean protection like this: -->
{has_children?}
<p>I have {num_children} kids.</p>
{/has_children?}
<!-- in this case, the paragraph will only appear if has_children is set to TRUE. -->


<!-- Show values of an array like this: -->
<ul>
{children}
  <li>{name}, age {age}</li>
{/children}
</ul>
<!-- In this case there would be an array call children that has objects stored 
     in it for each child. Each of these objects has a name and age property. -->