<?php

// Make sure they're logged in
if ( ! Auth::check()) exit('No Access');

// And that they're valid
if ( ! Auth::user()->acl) exit('No Permission');

// And that their ACL is valid
if (Auth::user()->acl->level < 1) exit('Seriously, No Permission');