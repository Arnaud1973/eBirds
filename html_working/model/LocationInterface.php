<?php

class LocationInterface extends ModelInterface {
// interface between view and location table

private $dbMngSettings;
public $allLocationArray = [];


public function __construct($inputArray = NULL) {

	$this->dbMngSettings = new DbMngSettings('location') ;
	$this->allLocationArray =  $this -> sortArray($this->dbMngSettings -> getAll());

	if (is_null($inputArray) || $inputArray == "") {
		foreach ($this->allLocationArray as $key => $row) {
			$inputArray[$key] = $row[0];
		}
	}

	foreach ($inputArray as $fieldLocation => $value) {
		// if motion setting
		if (array_key_exists($fieldLocation, $this->allLocationArray)) {
			// if value altered, check validity and store in new table
			if ( $value != $this->allLocationArray[$fieldLocation][0] && $value != "") {
				$this -> validateSettings($fieldLocation, $value);
			}
			$this -> setHydrate ($fieldLocation, $value);
		}
	}
}


public function updateValues()  {
	//
	$this->dbMngSettings -> updateValues($this -> _properties);
}


private function sortArray($unformatedList) {
	//

	foreach ($unformatedList as $row) {
		$formatedList[$row[0]] = [$row[1], $row[2], $row[3]];
	}

	uasort($formatedList, 'cmp');

	return $formatedList;
}

// TODO : Harmoniser la fonction de mise en forme avec SettingsInterface


private function cmp($a, $b) {

	if ($a[1] == $b[1]) {
		return 0;
	}
	return ($a[1] < $b[1]) ? -1 : 1;
}


// ##################################################################
	private function validateSettings ($fieldLocation, $value) {
	 	// validate input data before updating DB
		//
		$valueType = $this -> allLocationArray[$fieldLocation][2];
		// query table initialisation -> to get range values for settings
		if ($valueType == 'text')  {
		    if (!is_string($value) && !$value == "") {
					throw new Exception('Paramètre non valide : '. $fieldLocation .' - valeur : '.$value);
				}
		}

		elseif ($valueType == 'integer') {
			if (!is_int(intval($value)) && !$value == "") {
				throw new Exception('Paramètre non valide : '. $fieldLocation .' - valeur : '.$value);
			}
		}

		elseif ($valueType == 'long') {
			if (!is_float(floatval($value)) && !$value == "") {
				throw new Exception('Paramètre non valide : '. $fieldLocation .' - valeur : '.$value);
			}
		}

		else {
			throw new Exception('Paramètre non valide : '. $setting .' - valeur : '.$value);
		}
	}
}
