<?php

namespace App\Helpers\Nhs_LFPSE;

class Root
{
	private string $api_version = "6";
	public string $resourceType;
	public Meta $meta;
	/** @var Contained[] */
	public array $contained;
	/** @var Extension[] */
	public array $extension;
	public string $category;
	public Type $event;
	//public Subject $subject; // This is optional
	public string | null $date;
	public Location $location;
	public Recorder $recorder;
	public string $description;
	public array $suspectEntity;

	/**
	 * @param Contained[] $contained
	 * @param Extension[] $extension
	 */
	public function __construct(
		// Meta $meta,
		array $contained,
		array $extension,
		// string $category,
		string $type,
		$subject,
		string | null $date,
		Location $location,
		Recorder $recorder,
		string $description,
		array $suspectEntity = []
	) {
		$this->resourceType = "AdverseEvent";
		// $this->meta = $meta;
		if($this->api_version == "6"){
			$this->meta = new Meta(["https://developer.learn-from-patient-safety-events.nhs.uk/taxonomy/fhir/StructureDefinition/patient-safety-adverse-event-" . $this->api_version]);
			
		}else{
			$this->meta = new Meta(["https://psims-uat.azure-api.net/taxonomy/fhir/StructureDefinition/patient-safety-adverse-event-" . $this->api_version]);
		}

		$this->contained = $contained;
		$this->extension = $extension;
		// $this->category = $category;
		// $this->category = "AE"; => v5

		$this->event = new Type([new Coding($type)]);
		if($subject)
			$this->subject = $subject;
		if($date)
			$this->date = $date;
		$this->location = $location;
		$this->recorder = $recorder;
		$this->description = $description;
		$this->actuality = "actual";
		$this->suspectEntity = [];
        foreach ($suspectEntity as $entity) {
            $this->suspectEntity[] = [
                "instance" => [
                    "reference" => "#" . $entity
                ]
            ];
        }
	}
}