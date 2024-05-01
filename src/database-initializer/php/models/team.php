<?php
/******************************************************************************\
|                                                                              |
|                                   team.php                                   |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This defines a class for fetching, storing, and saving teams.         |
|                                                                              |
|        Author(s): Abe Megahed                                                |
|                                                                              |
|        This file is subject to the terms and conditions defined in           |
|        'LICENSE.txt', which is part of this source code distribution.        |
|                                                                              |
|******************************************************************************|
|  Copyright (C) 2024 Data Science Institute, Univeristy of Wisconsin-Madison  |
\******************************************************************************/

include_once "config.php";
include_once "models/model.php";
include_once "models/league.php";

class Team extends Model {

	//
	// attributes
	//

	public $league = NULL;
	public $id = NULL;
	public $year = NULL;

	/**
	 * Create a new model.
	 *
	 * @param $attributes[]
	 * @return Team
	 */
	function __construct(array $attributes) {

		// set attributes
		//
		$this->league = $attributes['league'];
		$this->id = $attributes['id'];
		$this->year = $attributes['year'];
	}

	//
	// querying methods
	//

	/**
	 * Get this team's url.
	 *
	 * @return string
	 */
	public static function baseUrl($league, $year) {
		return League::baseUrl($league) . '/seasons/' . $year . '/teams';
	}

	/**
	 * Get this team's url.
	 *
	 * @return string
	 */
	public function url() {
		return self::baseUrl($this->league, $this->year) . '/' . $this->id;
	}

	/**
	 * Get this team's database table.
	 *
	 * @return string
	 */
	public function table() {
		return strtolower($this->league) . '_teams';
	}

	//
	// saving methods
	//

	/**
	 * Save this athlete to a directory.
	 *
	 * @param string $dirname - the name of the directory to save to.
	 */
	public function save(string $dirname = '') {

		// find names
		//
		$name = $this->get('displayName');
		$filename = ($dirname? $dirname . '/' : '') . $name . '.json';

		// make new team directory
		//
		if ($dirname && !file_exists($dirname)) {
			mkdir($dirname);
		}

		// write team data
		//
		$this->saveAs($filename);

		// write team logo
		//
		if ($this->has('logos')) {
			$logos = $this->get('logos');
			$href = $logos[0]->href;
			$extension = strtolower(pathinfo($href, PATHINFO_EXTENSION));
			$imagename = ($dirname? $dirname . '/' : '') . $name . '.' . $extension;
			$image = file_get_contents($href);
			file_put_contents($imagename, $image);
		}
	}

	//
	// conversion methods
	//

	/**
	 * Convert this team to an array.
	 *
	 * @return array
	 */
	public function toArray() {
		$venue = $this->has('venue')? $this->get('venue') : NULL;
		$address = $venue? ($venue->address ?? NULL) : NULL;

		return [
			'id' => $this->get('id'),
			'location' => $this->get('location'),

			// name attributes
			//
			'name' => $this->get('displayName'),
			'short_name' => $this->get('shortDisplayName'),

			// team colors
			//
			'color' => $this->get('color'),
			'alternate_color' => $this->get('alternateColor'),

			// team attributes
			//
			'is_active' => $this->get('isActive') == 'true'? 1 : 0,
			'is_all_star' => $this->get('isAllStar') == 'true'? 1 : 0,

			// venue attributes
			//
			'venue_name' => $venue? ($venue->fullName ?? NULL) : NULL,
			'venue_city' => $address? ($address->city ?? NULL) : NULL,
			'venue_state' => $address? ($address->state ?? NULL) : NULL,
			'venue_zip_code' => $address? ($address->zipCode ?? NULL) : NULL,
			'venue_capacity' => $venue? ($venue->capacity ?? NULL) : NULL,
			'venue_grass' => $venue? ($venue->grass == 'true'? 1 : 0) : 0,
			'venue_indoor' => $venue? ($venue->indoor == 'true'? 1 : 0) : 0
		];
	}
}