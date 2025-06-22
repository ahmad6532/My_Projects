<?php

namespace App\Helpers\Nhs_LFPSE;

class Recorder
{
	public string $reference;

	public function __construct(string $reference)
	{
		$this->reference = $reference;
	}
}