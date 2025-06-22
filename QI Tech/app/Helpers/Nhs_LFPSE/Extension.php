<?php

namespace App\Helpers\Nhs_LFPSE;

class Extension
{
	/** @var Extension[] */
	public array $extension;
	public string $url;

	/**
	 * @param Extension[] $extension
	 */
	public function __construct(array $extension, string $url)
	{
		$this->extension = $extension;
		$this->url = $url;
	}
}