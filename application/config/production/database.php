<?php

// Production is largely the same as default
$db['production'] = $db['default'];
// The following are the difference
$db['production']['username'] = 'something_else';
$db['production']['password'] = 'something_else';