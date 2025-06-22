<?php

namespace App\Helpers\Nhs_LFPSE;

class Location
{
	public string $reference;

	public function __construct(string $reference)
	{
		$this->reference = $reference;
	}
}