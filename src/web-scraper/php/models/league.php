<?php
/******************************************************************************\
|                                                                              |
|                                  league.php                                  |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This defines a class for fetching, storing, and saving leagues.       |
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

class League extends Model {

	//
	// querying methods
	//

	/**
	 * Get this league's url.
	 *
	 * @return string
	 */
	public static function baseUrl($name) {
		switch ($name) {
			case 'NFL':
				return ESPN_API_URL . '/sports/football/leagues/nfl';
			case 'NCAA':
				return ESPN_API_URL . '/sports/football/leagues/college-football';

		}
	}
}