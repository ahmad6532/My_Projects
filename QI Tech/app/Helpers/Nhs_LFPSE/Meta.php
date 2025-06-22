<?php

namespace App\Helpers\Nhs_LFPSE;

class Meta
{
	/** @var string[] */
	public array $profile;

	/**
	 * @param string[] $profile
	 */
	public function __construct(array $profile)
	{
		$this->profile = $profile;
	}
}