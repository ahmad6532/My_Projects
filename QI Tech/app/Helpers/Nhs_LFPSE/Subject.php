<?php

namespace App\Helpers\Nhs_LFPSE;

class Subject
{
	public string $reference;

	public function __construct(string $reference)
	{
		$this->reference = $reference;
	}
}