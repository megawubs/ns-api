<?php


namespace Wubs\NS\Responses\Planner;


use Illuminate\Support\Collection;
use Wubs\NS\Contracts\Response;

class Step implements Response
{
    public $carrier;

    public $carrierType;

    public $tripNumber;

    public $status;

    public $tripDetails;

    public $plannedFailureId;

    public $unplannedFailureId;

    public $transitType;

    /**
     * @var Collection|WayPoint[]
     */
    public $wayPoints;

    /**
     * @param $carrier
     * @param $carrierType
     * @param $plannedFailureId
     * @param $status
     * @param $tripDetails
     * @param $tripNumber
     * @param $unplannedFailureId
     * @param $wayPoints
     * @param $transitType
     */
    private function __construct(
        $carrier,
        $carrierType,
        $plannedFailureId,
        $status,
        $tripDetails,
        $tripNumber,
        $unplannedFailureId,
        $wayPoints,
        $transitType
    ) {
        $this->carrier = $carrier;
        $this->carrierType = $carrierType;
        $this->plannedFailureId = $plannedFailureId;
        $this->status = $status;
        $this->tripDetails = $tripDetails;
        $this->tripNumber = $tripNumber;
        $this->unplannedFailureId = $unplannedFailureId;
        $this->wayPoints = $wayPoints;
        $this->transitType = $transitType;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return Step
     */
    public static function fromXML(\SimpleXMLElement $xml)
    {

        return new static(
            (string)$xml->Vervoerder,
            (string)$xml->VervoerType,
            (string)$xml->GeplandeStoringId,
            (string)$xml->Status,
            static::toTripDetails($xml->Reisdetails),
            (string)$xml->RitNummer,
            (string)$xml->OngeplandeStoringId,
            static::toWayPoints($xml->ReisStop),
            (string)$xml->attributes()->reisSoort
        );
    }


    /**
     * @param \SimpleXMLElement $wayPointsXML
     * @return Collection|WayPoint[]
     */
    private static function toWayPoints(\SimpleXMLElement $wayPointsXML)
    {
        $wayPoints = new Collection();

        foreach ($wayPointsXML as $wayPoint) {
            $wayPoints->push(WayPoint::fromXML($wayPoint));
        }

        return $wayPoints;
    }

    private static function toTripDetails(\SimpleXMLElement $tripDetailsXML)
    {
        $tripDetails = new Collection();
        if ($tripDetailsXML->Reisdetail instanceof \SimpleXMLElement) {
            foreach ($tripDetailsXML->Reisdetail as $detail) {
                $tripDetails->push((string)$detail);
            }
        }


        return $tripDetails;
    }
}