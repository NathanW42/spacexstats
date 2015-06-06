<?php

class Statistic extends Eloquent {

	protected $table = 'statistics';
	protected $primaryKey = 'statistic_id';

	public $timestamps = false;
	protected $appends = array('result', 'fullTitle');

	// Attribute Accessors
	public function getResultAttribute() {
		$type = camel_case($this->type);

		if (!empty($this->name)) {
			return StatisticResultBuilder::$type($this->name);
		} else {
			return StatisticResultBuilder::$type();
		}
	}

    public function getFullTitleAttribute() {
        if ($this->type === $this->name) {
            return $this->type;
        } else {
            return $this->type . ' - ' . $this->name;
        }
    }

    public function getDescriptionAttribute() {
        $type = camel_case($this->type);
        $description = $this->attributes['description'];

        $dynamicData = array();
        preg_match_all("/\{\{ (.*)\ }\}/U", $description, $dynamicData);

        foreach ($dynamicData[0] as $match => $dynamicString) {
            $this->attributes['description'] = substr_replace($dynamicString, StatisticDescriptionBuilder::$type($this->name, $dynamicData[1][$match]), 0);
        }

        return $this->attributes['description'];
    }
}
