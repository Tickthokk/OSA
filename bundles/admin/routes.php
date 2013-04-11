<?php

Route::get('(:bundle)', 'admin::dashboard@index');

Route::controller(Controller::detect('admin'));