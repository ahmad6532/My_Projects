<?php

namespace App\Helpers\Nhs_LFPSE;

class Type
{
	/** @var Coding[] */
	public array $coding;

	/**
	 * @param Coding[] $coding
	 */
	public function __construct(array $coding)
	{
		$this->coding = $coding;
	}
}