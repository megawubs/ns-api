<?php


namespace Wubs\NS\Responses\Planner;


use Illuminate\Support\Collection;

class Step
{
    public $carrier;

    public $carrierType;

    public $tripNumber;

    public $status;

    public $tripDetails;

    public $plannedFailureId;

    public $unplannedFailureId;

    /**
     * @var Collection|WayPoint[]
     */
    public $wayPoints;

    public function __construct($travelPart)
    {
        $this->carrier = (string)$travelPart->Vervoerder;
        $this->carrierType = (string)$travelPart->VervoerType;
        $this->tripNumber = (string)$travelPart->RitNummer;
        $this->status = (string)$travelPart->Status;
        $this->tripDetails = (string)$travelPart->ReisDetails;
        $this->plannedFailureId = (string)$travelPart->GeplandeStoringId;
        $this->unplannedFailureId = (string)$travelPart->OngeplandeStoringId;
        $this->wayPoints = $this->toWayPoints($travelPart->ReisStop);
    }

    /**
     * @param $stopsXML
     * @return Collection|WayPoint[]
     */
    private function toWayPoints($stopsXML)
    {
        $stops = new Collection();

        foreach ($stopsXML as $stop) {
            $stops->push(new WayPoint($stop));
        }

        return $stops;
    }

}