<?php


namespace Wubs\NS\Responses\Planner;


use Illuminate\Support\Collection;
use Wubs\NS\Contracts\Response;

class Advise implements Response
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
     * @var Collection|Step[]
     */
    public $steps;

    function __construct(
        $actualArrivalTime,
        $actualDepartureTime,
        $actualTravelDuration,
        $arrivalDelay,
        $departureDelay,
        $notifications,
        $numberOfSwitches,
        $optimal,
        $plannedArrivalTime,
        $plannedDepartureTime,
        $plannedTravelDuration,
        $state,
        $steps
    ) {
        $this->actualArrivalTime = $actualArrivalTime;
        $this->actualDepartureTime = $actualDepartureTime;
        $this->actualTravelDuration = $actualTravelDuration;
        $this->arrivalDelay = $arrivalDelay;
        $this->departureDelay = $departureDelay;
        $this->notifications = $notifications;
        $this->numberOfSwitches = $numberOfSwitches;
        $this->optimal = $optimal;
        $this->plannedArrivalTime = $plannedArrivalTime;
        $this->plannedDepartureTime = $plannedDepartureTime;
        $this->plannedTravelDuration = $plannedTravelDuration;
        $this->state = $state;
        $this->steps = $steps;
    }

    public static function fromXML(\SimpleXMLElement $xml)
    {
        return new static(
            (string)$xml->ActueleAankomstTijd,
            (string)$xml->ActueleVertrekTijd,
            (string)$xml->ActueleReisTijd,
            (string)$xml->AankomstVertraging,
            (string)$xml->VertrekVertraging,
            static::toNotifications($xml->Melding),
            (string)$xml->AantalOverstappen,
            (string)$xml->Optimaal,
            (string)$xml->GeplandeAankomstTijd,
            (string)$xml->GeplandeVertrekTijd,
            (string)$xml->ActueleReisTijd,
            (string)$xml->Status,
            static::toSteps($xml->ReisDeel)
        );
    }

    /**
     * @param $steps
     * @return Collection|Step[]
     */
    private static function toSteps($steps)
    {
        $parts = new Collection();
        foreach ($steps as $step) {
            $parts->push(Step::fromXML($step));
        }

        return $parts;
    }

    public static function toNotifications($notificationsXML)
    {
        $notifications = new Collection();

        foreach ($notificationsXML as $notification) {
            $notifications->push(Notification::fromXML($notification));
        }

        return $notifications;
    }
}