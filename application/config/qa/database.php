<?php

// QA is largely the same as default
$db['qa'] = $db['default'];
// The following are the difference
$db['qa']['hostname'] = DB_HOST;
$db['qa']['username'] = DB_USER;
$db['qa']['password'] = DB_PASSWORD;