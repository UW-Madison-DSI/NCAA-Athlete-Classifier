<?php
/******************************************************************************\
|                                                                              |
|                                  model.php                                   |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This defines an abstract base class for models.                       |
|                                                                              |
|        Author(s): Abe Megahed                                                |
|                                                                              |
|        This file is subject to the terms and conditions defined in           |
|        'LICENSE.txt', which is part of this source code distribution.        |
|                                                                              |
|******************************************************************************|
|  Copyright (C) 2024 Data Science Institute, Univeristy of Wisconsin-Madison  |
\******************************************************************************/

abstract class Model {

	//
	// attributes
	//

	public $attributes = [];

	//
	// construcxtor
	//

	/**
	 * Create a new model.
	 *
	 * @param $attributes[]
	 * @return Team
	 */
	function __construct(array $attributes) {

		// set attributes
		//
		$this->attributes = $attributes;
	}

	//
	// querying methods
	//

	/**
	 * Test if this model has this attribute.
	 *
	 * @return boolean
	 */
	public function has(string $attribute) {	
		return property_exists($this->attributes, $attribute);
		// return array_key_exists($attribute, $this->attributes);	
	}

	/**
	 * Get this model's attribute.
	 *
	 * @return object
	 */
	public function get(string $attribute) {	
		if (!$this->has($attribute)) {
			return;
		}
		return get_object_vars($this->attributes)[$attribute];
		// return $this->attributes[$attribute];
	}

	//
	// ajax methods
	//

	/**
	 * Fetch this team from the server.
	 *
	 * @return Athlete
	 */
	public function fetch() {
		$this->attributes = json_decode(file_get_contents($this->url()));
		return $this;
	}

	//
	// database saving methods
	//

	/**
	 * Store this model in the database.
	 *
	 * @return bool - success
	 */
	public function store($db) {

		// check for data
		//
		if (!$this->attributes) {
			return;
		}

		// delete previous row if already exists
		//
		if (exists($db, $this->table(), 'id', $this->attributes->id)) {
			delete($db, $this->table(), $this->attributes->id);
		}

		// add new row to database
		//
		return insert($db, $this->table(), $this->toArray());
	}

	//
	// output methods
	//

	/**
	 * Save this model to a file.
	 *
	 * @return bool - success
	 */
	public function saveAs($filename) {

		// check if we can save
		//
		if (!$this->attributes) {
			return;
		}

		// write model data
		//
		file_put_contents($filename, json_encode($this->attributes, JSON_PRETTY_PRINT));
	}

	/**
	 * Convert this model to JSON.
	 *
	 * @return bool - success
	 */
	public function toJson() {
		return json_encode($this->attributes);
	}
}