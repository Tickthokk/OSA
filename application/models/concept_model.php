<?php

class Concept_model extends CI_Model
{

	public
		$concepts = array();

	public function __construct()
	{
		parent::__construct();
		
		# Include the abstract Concept Class
		include_once(APPPATH . 'core/OSA_Concept.php');
	}

	public function load($model = '', $id = null)
	{
		# Manually include necessary files
		include_once(APPPATH . 'models/concepts/' . $model . '_concept.php');

		$uniqid = uniqid();
		$model_name = ucfirst($model) . '_concept';

		$this->concepts[$model][$uniqid] = new $model_name($this->db, get_instance());

		if ( ! is_null($id))
			$this->concepts[$model][$uniqid]->set_id($id);

		return $this->concepts[$model][$uniqid];
	}

}