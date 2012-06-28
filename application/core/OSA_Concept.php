<?php
/**
 * Concept - An abstract class referencing a common theme among database elements
 * 	This class allows you to dynamically get and set variables.
 * 	Uses magic method's __get and __set.
 * 
 * @uses in a controller
 * 	For example, use a "users" table.  Users have an ID, Username, Email and Password.
 * 	$user = new user(37); # (and `class user extends concept`)
 * 	echo $user->email;  # would dynamically select the value from the table
 *  $user->email = 'exam@ple.com'; # would dynamically save the value into the table
 *  $values = $user->get_more('username', 'email'); # $values would be an assoc array of the wanted fields
 *  $user->set_more(array('username' => 'eXaMpLe', 'email' => 'exam@ple.com')); # table values would be saved in tandem with the assoc array
 *  
 * @author Nick Wright
 */
abstract class OSA_Concept
{
	
	protected
		$_data = array(),
		$_fields = array(),
		$_table = array();

	public
		$db = null,
		$id = null,
		$CI = null; // CodeIgniter Reference
	
	public function __construct($db) {
		$this->db =& $db;
		$this->_find_fields();
	}
	
	/**
	 * Find Fields
	 * 	Get the fields for the referenced table
	 *  We remove the 'id' from the fields, because it's:
	 *  	A) Already set, and
	 *  	B) We never want to change it
	 */
	private function _find_fields() {
		$result = $this->db->list_fields($this->_table);
		$this->_fields = array_diff($result, array('id'));
	}

	/**
	 * __get - Magic Method
	 * @param string $var
	 * @return wanted value from database
	 */
	public function __get($var) {
		if ( ! isset($this->_data[$var]))
			if (method_exists($this, '_get_' . $var)) 
				$this->{'_get_' . $var}();
			else
				$this->_autoload($var);
		
		return $this->_data[$var];
	}
	
	/**
	 * Autoload
	 * 	An extension of __get.  
	 * 	Load's the requested variable into the field register
	 * @param string $var
	 * @see __get
	 * @return nothing
	 */
	private function _autoload($var) {
		if (isset($this->_data[$var])) 
			return;
		
		if (empty($this->id)) 
			return false;
		
		if ( ! in_array($var, $this->_fields)) {
			$this->_data[$var] = null;
			return false;
		}
		
		$result = $this->db
			->select($var)
			->from($this->_table)
			->where('id', (int) $this->id)
			->get()->first_row('array');
		
		$this->_data[$var] = $result[$var];
	}
	
	/**
	 * __set - Magic Method
	 * @param string $var
	 * @param mixed $value
	 */
	public function __set($var, $value) {
		if (empty($this->id))
			return false;
		
		# Save Locally
		$this->_data[$var] = $value;
		
		# Check for a specific save function
		# Otherwise, write straight to the database
		if (method_exists($this, '_set_' . $var)) 
			$this->{'_set_' . $var}($value);
		else
			$this->_autosave($var, $value);
	}
	
	/**
	 * Autosave
	 * 	An extension of __set.  
	 * 	Save's the requested variable into the field register and table
	 * @param string $var
	 * @see __set
	 * @return nothing
	 */
	private function _autosave($var, $value) {
		# Check to make sure it's a valid field
		if ( ! in_array($var, $this->_fields))
			return false;
		
		# Write to the database
		$this->db
			->where('id', (int) $this->id)
			->update($this->_table, array($var => $value));
	}
	
	/**
	 * get_more
	 * 	Get's a lot of fields with one call.
	 * @param array $fields
	 * 	We test if $fields is an array, if it is, then use that
	 * 	Otherwise, we'll be using the func_get_args()
	 * @uses 
	 * 	$class->get_more('a', 'b', 'c'); # Uses func_get_args();
	 * 	$class->get_more(array('a', 'b', 'c')); # Uses $fields
	 * @return the entirety of the registered field/values
	 */
	public function get_more($fields) {
		if (empty($this->id))
			return false;
		
		if (!is_array($fields)) 
			$fields = func_get_args();
		
		$vars = array();
		foreach($fields as $var) 
			if (in_array($var, $this->_fields)) 
				$vars[] = $var;
		
		if (empty($vars))
			return false;
		
		$result = $this->db
			->select(implode(', ', $vars))
			->from($this->_table)
			->where('id', (int) $this->id)
			->get()->first_row('array');
		
		foreach ((array) $result as $var => $value);
			$this->_data[$var] = $value;
		
		return $this->_data;
	}
	
	/**
	 * get_all
	 * 	Gets all fields in the table
	 * @return the entirety of the registered field/values
	 */
	public function get_all() {
		return call_user_func_array(array($this, 'get_more'), $this->_fields);
	}
	
	/**
	 * set_more
	 * @param array $values
	 */
	public function set_more($values) {
		if (empty($this->id))
			return false;
		
		foreach (array_keys((array) $values) as $var) 
			if (!in_array($var, $this->_fields)) 
				unset($values[$var]);
		
		if (empty($values))
			return false;
		
		# Database Set
		$this->db
			->where('id', (int) $this->id)
			->update($this->_table, $values);
		
		# Local Set
		foreach ($values as $field => $value)
			$this->_data[$field] = $value;
	}
	
}