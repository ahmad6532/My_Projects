<?php

namespace App\Helpers\Nhs_LFPSE;

class Coding
{
	public string $code;

	public function __construct(string $code)
	{
		$this->code = $code;
	}
}