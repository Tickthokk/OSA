<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 *  Extend routing to allow '-' instead of '_' in URLs
 */

class OSA_Router extends CI_Router {

	/**
	 * Replace '-' in class name with '_'
	 */

	function set_class($class)
    {
       $this->class = str_replace('-', '_', $class);
    }
    
    /**
	 * Replace '-' in method name with '_'
	 */
    function set_method($method)
    {
        $this->method = str_replace('-', '_', $method);
    }

}
// End of OSA_Router.php