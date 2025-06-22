<?php

class SponsorContact
{
  public string $uid;
  public string $fullName;
  public string $email;
  public string $phone;
  public string $street;
  public string $extension;
  public string $locality;
  public string $region;
  public string $postcode;

  public static function fromJson(string $json): self
  {
    $data = json_decode($json, true);
    $obj = new self();
    $obj->uid = $data["uid"] ?? "";
    $obj->fullName = $data["fullName"]["value"] ?? "";
    $obj->email = $data["emails"][0]["value"] ?? "";
    $obj->phone = $data["phones"][0]["value"] ?? "";
    $addr = $data["addresses"][0] ?? [];
    $obj->street = $addr["street"] ?? "";
    $obj->extension = $addr["extension"] ?? "";
    $obj->locality = $addr["locality"] ?? "";
    $obj->region = $addr["region"] ?? "";
    $obj->postcode = $addr["postcode"] ?? "";
    return $obj;
  }
}

class LetterMetaData
{
  public string $asn;
  public string $caseId;
  public string $externalStatusCode;
  public string $formattedLetter;
  public string $letterDate;
  public string $letterId;

  public static function fromArray(array $data): self
  {
    $obj = new self();

    $obj->asn = $data["asn"] ?? "";
    $obj->caseId = $data["caseId"] ?? "";
    $obj->externalStatusCode = $data["externalStatusCode"] ?? "";
    $obj->formattedLetter = $data["formattedLetter"] ?? "";
    $obj->letterDate = $data["letterDate"] ?? "";
    $obj->letterId = $data["letterId"] ?? "";
    return $obj;
  }
}

class StructureType
{
  public string $structureType;
  public string $label;
  public string $description;

  public static function fromArray(array $data): self
  {
    $obj = new self();
    $obj->structureType = $data["structureType"] ?? "";
    $obj->label = $data["label"] ?? "";
    $obj->description = $data["description"] ?? "";
    return $obj;
  }
}

class PointConverted
{
  public float $latitude;
  public float $longitude;
  public string $horizontalDatum;

  public static function fromArray(array $data): self
  {
    $obj = new self();
    $obj->latitude = $data["latitude"] ?? 0.0;
    $obj->longitude = $data["longitude"] ?? 0.0;
    $obj->horizontalDatum = $data["horizontalDatum"] ?? "";
    return $obj;
  }
}

class FrequencyBand
{
  /** @var float[] */
  public array $value;
  public string $uom;

  public static function fromArray(array $data): self
  {
    $obj = new self();
    $obj->value = $data["value"] ?? [];
    $obj->uom = $data["uom"] ?? "";
    return $obj;
  }
}

class FrequencyEirp
{
  public float $value;
  public string $uom;

  public static function fromArray(array $data): self
  {
    $obj = new self();
    $obj->value = $data["value"] ?? 0.0;
    $obj->uom = $data["uom"] ?? "";
    return $obj;
  }
}

class Frequency
{
  public FrequencyBand $band;
  public FrequencyEirp $erp;
  public int $standard;
  public string $purpose;

  public static function fromArray(array $data): self
  {
    $obj = new self();
    $obj->band = FrequencyBand::fromArray($data["band"] ?? []);
    $obj->erp = FrequencyEirp::fromArray($data["erp"] ?? []);
    $obj->standard = $data["standard"] ?? 0;
    $obj->purpose = $data["purpose"] ?? "";
    return $obj;
  }
}

class FaaResponse
{
  public int $createdOn;
  public int $updatedOn;
  public string $asn;

  public string $heightAglFoot;

  public string $elevationFoot;
  public SponsorContact $sponsorJsContact;
  public SponsorContact $formRepJsContact;
  public string $formRepOwnerDisplayName;
  public StructureType $structureType;
  public string $structureTypeLabel;
  public string $locationDescription;
  public string $proposalDescription;
  public PointConverted $pointConverted;
  public string $structureName;
  public string $caseStatus;
  public string $fccAsr;
  public string $countyLabel;
  public string $priorAsn;
  public string $durationCodeLabel;
  public string $durationCode_ended;

  /** @var Frequency[]|null */
  public ?array $frequencies = null;

  public ?array $letterMetaData;

  public static function fromArray(array $data): self
  {
    $obj = new self();
    $obj->createdOn = $data["createdOn"];
    $obj->updatedOn = $data["updatedOn"];
    $obj->asn = $data["asn"] ?? "";
    $obj->heightAglFoot = $data["heightAglFoot"];
    $obj->elevationFoot = $data["elevationFoot"];
    $obj->sponsorJsContact = SponsorContact::fromJson($data["sponsorJsContact"] ?? "{}");
    $obj->formRepJsContact = SponsorContact::fromJson($data["formRepJsContact"] ?? "{}");
    $obj->formRepOwnerDisplayName = $data["formRepOwnerDisplayName"] ?? "";
    $obj->structureType = StructureType::fromArray($data["structureType"] ?? []);
    $obj->structureTypeLabel = $data["structureTypeLabel"] ?? "";
    $obj->locationDescription = $data["locationDescription"] ?? "";
    $obj->proposalDescription = $data["proposalDescription"] ?? "";
    $obj->pointConverted = PointConverted::fromArray($data["pointConverted"] ?? []);
    $obj->structureName = $data["structureName"] ?? "";
    $obj->caseStatus = $data["caseStatus"] ?? "";
    $obj->fccAsr = $data["fccAsr"] ?? "N/A";
    $obj->countyLabel = $data["countyLabel"] ?? "";
    $obj->priorAsn = json_decode($data["priorAsn"], true)[0] ?? "";

    $obj->durationCodeLabel = $data["durationCodeLabel"] ?? "";
    $obj->durationCode_ended = $data["durationCode"]["ended"] ?? "";

    // Letter metadata add
    if (!empty($data["letterMetaData"])) {
      $obj->letterMetaData = array_map([LetterMetaData::class, "fromArray"], $data["letterMetaData"]);
    }

    // Frequencies add
    if (isset($data["frequencies"])) {
      $arr = json_decode($data["frequencies"], true)["frequencies"] ?? [];
      $obj->frequencies = array_map([Frequency::class, "fromArray"], $arr);
    }

    return $obj;
  }
}
