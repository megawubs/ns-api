# ns-api
Laravel ns-api wrapper

## Installation

In your project root, type:
```BASH
$ composer require wubs/ns-api
```

After it is installed, add `'Wubs\NS\NSServiceProvider',` to `app/config.php` in the providers array and add `'NS'
 => 'Wubs\NS\Facades\NS',` to the aliases array, also in `app/config.php`.

## Laravel usage

### Train Stations
Receive a collection of all the train stations:

```PHP
<?php
$stations = NS::stations();
```
This returns a Collection of Station objects a Station object has the following properties:
```PHP
<?php
$station = $stations->first();
$station->name;
$station->code;
$station->country;
$station->lat;
$station->long;
$station->alias;
```

### Failures
To receive a list of planned and unplanned failures:
 
```PHP
<?php
$failures = NS::failures(ut'); //full name or code from a station
$planned = $failures->planned; //Collection of planned failures
$unplanned = $failures->unplanned; //Collection of unplanned failures
```

A `Failure` object has the following properties:

```PHP
<?php
$failure->id;
$failure->route;
$failure->reason;
$failure->message;
$failure->date; //Carbon date object
```

### Travel Advise

A travel advise exists of multiple objects. One `Advise` Object has one ore more `Notification` objects in a 
`Collection` alo, it has muliple `Step` objects. Each `Step` object has multiple `WayPoint` objects in a `Collection`
WayPoints are the train stations that are part of your travel advise, but you don't stop there. The first and the
last items in this Collection are the start and end station for that part of the travel advise.

```PHP
<?php
$advises = NS::advise('Aalten', "Zurich"); //Returns a Collection with Advise objects
$advise = $advises->first();
$advise->notifications; //Collection|Notification[]
$advise->numberOfSwitches;
$advise->plannedTravelDuration;
$advise->actualTravelDuration;
$advise->departureDelay;
$advise->arrivalDelay;
$advise->optimal;
$advise->plannedDepartureTime; //Carbon
$advise->actualDepartureTime; //Carbon
$advise->plannedArrivalTime; //Carbon
$advise->actualArrivalTime; //Carbon
$advise->state;
$advise->steps; //Collection|Steps[]

$notification = $advise->notifications->first();
$notification->id;
$notification->serious;
$notification->text;

$step = $advise->steps->first();
$step->carrier;
$step->carrierType;
$step->tripNumber;
$step->status;
$step->tripDetails;
$step->plannedFailureId;
$step->unplannedFailureId;
$step->transitType;
$step->wayPoints; //Collection|WayPoint[]

$wayPoint = $step->wayPoints->first();
$wayPoint->name;
$wayPoint->time; //Carbon
$wayPoint->departureDelay;
$wayPoint->track;
```