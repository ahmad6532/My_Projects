<?php

namespace App\Helpers\Nhs_LFPSE;

class Contained
{
	public string $resourceType;
	public string $id;
	/** @var Extension[] */
	public array $extension;

	/**
	 * @param Extension[] $extension
	 */
	public function __construct(
		string $resourceType,
		string $id,
		array $extension
	) {
		$this->resourceType = $resourceType;
		$this->id = $id;
		$this->extension = $extension;
	}
}