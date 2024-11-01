AxiMyCalendarBundle
===================

This bundle provide ```axi/mycalendar``` library for Symfony

Installation
------------

```console
composer require axi/mycalendar-bundle
```


Usage
-----

Simply inject the CalendarService into your Controller


```php
use Axi\MyCalendar\Service\CalendarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyController extends AbstractController
{
    public function myAction(CalendarService $calendarService) {
        // ...
        $birthdate = new \DateTime("1984-01-12");
        $events = $calendarService->getEventsFromDate($birthdate);
        // ...
    }
}
```
