<?php


namespace Wubs\NS\Responses\Planner;


use Illuminate\Support\Collection;

class Option
{
    public $notifications;

    public $numberOfSwitches;

    public $plannedTravelDuration;

    public $actualTravelDuration;

    public $departureDelay;

    public $arrivalDelay;

    public $optimal;

    public $plannedDepartureTime;

    public $actualDepartureTime;

    public $plannedArrivalTime;

    public $actualArrivalTime;

    public $state;

    /**
     * @var Step[]
     */
    public $steps;

    /**
     * @param $xml
     */
    private function __construct($xml)
    {
        $this->notifications = $this->toNotifications($xml->Melding);
        $this->numberOfSwitches = (string)$xml->AantalOverstappen;
        $this->plannedTravelDuration = (string)$xml->GeplandeReisTijd;
        $this->actualTravelDuration = (string)$xml->ActueleReisTijd;
        $this->departureDelay = (string)$xml->VertrekVertraging;
        $this->arrivalDelay = (string)$xml->AankomstVertraging;
        $this->optimal = (string)$xml->optimaal;
        $this->plannedDepartureTime = (string)$xml->GeplandeVertrekTijd;
        $this->actualDepartureTime = (string)$xml->ActueleVertrekTijd;
        $this->plannedArrivalTime = (string)$xml->GeplandeAankomstTijd;
        $this->actualArrivalTime = (string)$xml->ActueleAankomstTijd;
        $this->state = (string)$xml->status;
        $this->steps = $this->toSteps($xml->ReisDeel);

    }

    public static function create($xml)
    {
        return new static($xml);
    }

    /**
     * @param $steps
     * @return Collection|Step[]
     */
    private function toSteps($steps)
    {
        $parts = new Collection();
        foreach ($steps as $step) {
            $parts->push(new Step($step));
        }

        return $parts;
    }

    public function toNotifications($notificationsXML)
    {
        $notifications = new Collection();

        foreach ($notificationsXML as $notification) {
            $notifications->push(new Notification($notification));
        }

        return $notifications;
    }
}