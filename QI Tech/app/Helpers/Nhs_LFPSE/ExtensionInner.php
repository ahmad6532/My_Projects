<?php

namespace App\Helpers\Nhs_LFPSE;

class ExtensionInner
{
	public string $url;
	// public ?string $valueCode;
	// public ?string $valueDate;
	// public ?bool $valueBoolean;
	// public ?string $valueString;
	// public ?int $valueInteger;

	public function __construct(
		string $url,
		?string $valueCode = null,
		?string $valueDate = null,
		?bool $valueBoolean = null,
		?string $valueString = null,
		?int $valueInteger = null,
		?string $valueTime = null
	) {
		$this->url = $url;
		if($valueCode)
			$this->valueCode = $valueCode;
		if($valueDate)
			$this->valueDate = $valueDate;
		if(isset($valueBoolean))
			$this->valueBoolean = $valueBoolean;
		if($valueString)
			$this->valueString = $valueString;
		if($valueInteger)
			$this->valueInteger = $valueInteger;
		if($valueTime)
			$this->valueTime = $valueTime;
	}
}