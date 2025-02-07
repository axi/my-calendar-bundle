# My calendar bundle
A Symfony bundle for ```axi/mycalendar``` package.

## Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
composer require axi/mycalendar-bundle
```

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require axi/mycalendar-bundle
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Axi\MyCalendarBundle\AxiMyCalendarBundle::class => ['all' => true],
];
```

## Configuration
```yaml
axi_my_calendar:
    # Select only certain recipes, use a list of FQDN recipes class names
    only_recipes:
#        - Axi\MyCalendar\Recipe\Now
#        - Axi\MyCalendar\Recipe\MillionMinutes
#        - App\NowRecipe
    # Exclude certain recipes from the provided ones, use a list of FQDN recipes class names
    except_recipes:
#        - Axi\MyCalendar\Recipe\PlanetsRevolutions

```

## Usage
### Controller
Inject ```Axi\MyCalendar\Service\CalendarService``` in your controller action and use it:

```php
<?php
// src/Controller/MyController.php

namespace App;

use Axi\MyCalendar\Service\CalendarService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyController extends AbstractController {
    #[Route(
        path: /events
    )]
    public function eventsDate(
        CalendarService $calendarService
    ): Response {
        $birthdate = new DateTimeImmutable('1984-01-12');

        $events = $calendarService->getEventsFromDate($birthdate);

        dump($events);
        return new Response();
    }
}
```

### Add new recipes
Create a new class that extends ```Axi\MyCalendar\Recipe\Recipe``` or at least implements ```Axi\MyCalendar\Recipe\RecipeInterface```.  
It will be automaticaly added to ```CalendarService``` recipes.

```php
<?php

namespace App;

use Axi\MyCalendar\Recipe\Recipe;
use Axi\MyCalendar\Event;
use Symfony\Component\Translation\TranslatableMessage;

class NowRecipe extends Recipe
{
    public function getEvents(\DateTimeImmutable $basedOn): array
    {
        $event = new Event(
            new \DateTimeImmutable()
        );
        $event->setSummary(new TranslatableMessage('Now'));
        $event->setSourceRecipe(self::class);

        return [$event];
    }

    public function getSummary(...$vars): TranslatableMessage
    {
        return new TranslatableMessage('Now');
    }
}
```
but you're also welcome to propose a PR to add it to the main project: https://github.com/axi/my-calendar/pulls
