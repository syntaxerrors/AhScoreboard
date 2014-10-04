<?php
 
class BaseModel extends Core_BaseModel {
 
    /********************************************************************
     * Core
     *******************************************************************/
    const ROLE_DEVELOPER   = 2;
    const ROLE_GUEST       = 3;
    const ROLE_FORUM_ADMIN = 6;
    const ROLE_SITE_ADMIN  = 1;

	public function checkType($types, $matchAll = false)
	{
		if (!is_array($types)) {
			$types = array($types);
		}

		$matchedType = 0;

		if ($this->types && $this->types->count() > 0) {
			$objectTypes = $this->types->keyName->toArray();

			foreach ($types as $type) {
				if (in_array($type, $objectTypes)) {
					if (!$matchAll) {
						return true;
					}

					$matchedType++;
				}
			}

			if ($matchedType) {
				if (count($types) == $matchedType) return true;
			}
		}

		return false;
	}
}