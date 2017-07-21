Working day
===========

[![Build Status](https://travis-ci.org/robier/working-day.svg?branch=master)](https://travis-ci.org/robier/working-day)
[![Test Coverage](https://codeclimate.com/github/robier/working-day/badges/coverage.svg)](https://codeclimate.com/github/robier/working-day/coverage)

--------------

Working day project is real OOP implementation of working hours concept. You can really easy check if establishment is
open/closed or it's on pause at given time. Or you can really easy implement your own working logic (ie. on odd dates we
are working in the morning, and on even dates we are working afternoon).

--------------

Features:
- 24 working days concept
- multiple shifts (as many as you need)
- getting days by date, not by day in he week
- does not implement any rendering logic
- you can really easily implement your own logic for providing data
- you can chain multiple providers (ie. if first provider does not provide any data, second registrated provider will be asked and so on...)
- few predefined providers

-------------

### Concept
##### Time
- Contains only time component of DateTime object.
- It uses 24h standard.

##### Day
- Representing only one day in the year, working and non working days
- Has metadata, you can store whatever data you want, data that describes day little bit more
- Product of **Provider**s calculation
- Day is "just" a holder for Date, Shifts and Meta objects.
- Have a lot of handy methods.
- One day starts at `00:00` and ends on `23:59:59`. We do not allow spilling hours from next day. Establishment can not 
be opened from `17:00-03:00` on Monday as after `23:59` it's not Monday any more.
- If day only have one shift with working time `00:00-23:59` we conciser that day opened **24h**.

##### Shifts
- Shifts is collection of **Shift** objects.
- Containing all logic for checking if establishment is opened/closed/on pause and so on... 

##### Shift
- Shift is holder for start and end time of a shift.
- It makes sure that provided start time is not greater than end time and that start/end is inside 23:59 span

##### Provider
- Only has one method (**get(Date $date): ?Day** that takes only date as parameter and returns **Day** or `null`)
- Provider knows how to generate **Day** object.
- Predefined providers: 
    - Week (only can take 7 specifications, and returns one of the days, by **Date**)
    - OddWeekDates (same as week but only returns **Day** if provided **Date** is odd)
    - EvenWeekDates (same as week but only returns **Day** if provided **Date** is even)
    - Multiple (takes multiple Providers, tries one by one until one of them returns **Day**, will return `null` if all providers failed)

##### Calculator
- Calculator is here to provide day from provider, if provider returned NULL, calculator will return new empty instance of **Day**
- In any case calculator will provide **Day** for asked date.

-------


### Sample:

Lets say you have a establishment that is open from Monday to Friday in 2 shifts 10:00-12:00 13:00-15:00 and during the 
weekend there is only one shift 11:00-13:00. 

```php

use Robier\WorkingDay\Calculator;
use Robier\WorkingDay\Date;
use Robier\WorkingDay\Day\Type;
use Robier\WorkingDay\Provider\Data\ShiftsCollection;
use Robier\WorkingDay\Provider\Week;
use Robier\WorkingDay\Shifts;

$shifts = new ShiftsCollection();
$shifts->set(Shifts::string('10:00-12:00 13:00-15:00'), Type::monday(), Type::tuesday(), Type::wednesday(), Type::thursday(), Type::friday());
$shifts->set(Shifts::string('11:00-13:00'), Type::saturday(), Type::sunday());

$week = new Week($shifts);

$calculator = new Calculator($week);

$calculator->get(Date::string('2017-07-24')); // will return Day object that contains shifts data for Monday as 2017-07-24 is actually a Monday 
$calculator->range(Date::string('2017-07-24'), Date::string('2017-07-28')) // will yeild 5 days, all with same shifts
$calculator->days(Date::string('2017-07-24'), 3); // will yeild 3 days in total starting with 2017-07-24
$calculator->week(Date::string('2017-07-24')); // will yeild whole week, and starting point is provided date 

```
