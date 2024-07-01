
# Learn Arabic Unit/Lesson System

New routes have been added to reduce the performance burden from loading lessons. Previously all data stored in the database was loaded on each page load/request which, as the amount of terms added to the system increased, was starting to take a toll on performance. To fix this issue, as well as add some structure to lesson plans, a new system called "Units and Lessons" has been implemented.


## Features

Two new features have been added, Unit and Lesson.

### Unit

A unit will hold lessons related to it and any terms related to the unit's home page. To see how a unit works see the Documentation section as well as Usage/Examples.

### Lesson

A lesson holds all the terms that will be taught in the lesson. To see how a lesson works see the Documentation section as well as Usage/Examples. By introducing lessons we can define what terms we will need for just a single lesson rather than getting all the terms in a unit or worse all the terms in the database.


## Optimizations

Before implementing the unit system, each page was loading not only each Term, as well as each term relation,
we were actually loading each of these twice. To fix this, you must now specify all terms that will be used
in a given lesson within the $terms property of the lesson class. This will load all terms and their relations
and nothing more.

This new system has allowed for pretty impressive performance improvements. Unit 1 previously required roughly
700 database queries to load. Now it requires 10.


## Route Reference

#### Displays the curriculum home page with all units on it, calls index()

```http
    /units
```

#### Display a units home page and the lessons that you can access from it, calls showUnit($unit)

```http
  /units/{unit}
```

| Parameter | Type     | Description                                                     |
|:----------|:---------|:----------------------------------------------------------------|
| `unit`    | `string` | **Required**. Unit to fetch. Takes form uX where X is unit num. |

#### Display a lesson under the current unit, calls showLesson($unit, $lesson)

```http
  /units/{unit}/lessons/{lesson}
```

| Parameter | Type     | Description                                                         |
|:----------|:---------|:--------------------------------------------------------------------|
| `unit`    | `string` | **Required**. Current unit. Takes form uX where X is unit num.      |
| `lesson`  | `string` | **Required**. Lesson to fetch. Takes form lX where X is lesson num. |

### These are implemented in the web.php file

```php
Route::prefix("/units")
        ->controller(UnitController::class)
        ->group(function () {
            // Displays a list of all lessons
            Route::get('/', "index")->name('ls');

            // Renders an individual lesson
            Route::get('/{unit}', "showUnit")->name('ls.ls');

            Route::get('/{unit}/lessons/{lesson}', 'showLesson');
        });
```


## Documentation

Three new classes have been implemented: Unit, Lesson, and UnitController.

Unit is designed to be extended by classes like Unit01, Unit02, and Unit03. When a new Unit is needed another subclass needs to be made. The same applies to lessons under each unit. Below is the directory structure of units and lessons as they are subclassed:

```
--app
    --Units
        --Unit01
            --Lessons
                --LessonOne.php
                --LessonTwo.php
                --LessonThree.php
            --Unit01.php
        --Unit02
            --Lessons
                --LessonOne.php
                --LessonTwo.php
                --LessonThree.php
            --Unit02.php
        --Unit03
            --Lessons
                --LessonOne.php
                --LessonTwo.php
                --LessonThree.php
            --Unit03.php
        --...
        
        --Lesson.php  // Base Lesson Class
        --Unit.php    // Base Unit Class
```

### Unit

Unit has three properties:

- **$view** : the 'view' for home page of the unit
- **$terms** : an array of term slugs that are found on the unit home page
- **$lessons** : an associative array with key's lX from route, and value of key's respective class

Note: All these must be defined in subclasses

#### Methods

Unit has three methods:

- **render()** : returns the view in the $unit property along with its terms
- **loadTerms()** : returns associative array with key as term slug and value of arabic term
- **getLesson($lesson)** : calls render on matching lesson class from the given slug in the $lessons property

### Lesson

Lesson has two properties:

- **$view** : the 'view' for the lesson
- **$terms** : an array of terms slugs found in the lesson

Note: All these must be defined in subclasses

#### Methods

Lesson has two methods:

- **render()** : returns the view in $view property along with its terms
- **loadTerms()** : returns associative array with key as term slug and value of arabic term

### UnitController

UnitController has one defined property:

- **$units** : an associative array with key's uX from route, and value of key's respective class

Note: When a new unit is created it must gain an entry in this array.

#### Methods

UnitController has three methods:

- **index()** : returns the curriculum page to choose units
- **showUnit($unit)** : renders the given unit's home page
- **showLesson($unit, $lesson)** : renders the given lesson in the given unit

### Lesson/Unit Views

Unit home pages are still found in the lessons directory of views, in new routes "tab divs" that show lesson components can be removed.

A new view directory has been added, lesson. In it is subdirectories for each unit and the lesson views for them. These lesson views use the lesson components in the component directories.

```
--views
    --lesson
        --u1
            --lesson-01.blade.php
            --lesson-02.blade.php
            --lesson-03.blade.php
        --u2
            ...
        ...
```

101, 102, 103, and all similar lesson components still exist and are still under components in the lesson directory, they are broken down still by unit but also by lesson inside each unit.

```
--components
    --lesson
        --u1
            --l1
                --101.blade.php
                --102.blade.php
                --103.blade.php
            --l2
            ...
        --u2
        ...
```


## Usage/Examples

### Example Unit Subclass

```php
class Unit01 extends Unit
{
    protected string $unit = 'lessons.u1';
    protected array $lessons = [
        'l1' => LessonOne::class,
        'l2' => LessonTwo::class,
        'l3' => LessonThree::class
    ];
    protected array $terms = [
        'ṭabax',
        'ḡasal',
        'maṭbax',
        'maḡsale',
        'ṭabbāx',
        'ḡassāle'
    ];
}
```

### Example Lesson Subclass

```php
class LessonOne extends Lesson
{
    protected string $view = 'lesson.u1.lesson-01';
    protected array $terms = [
        'ʔana',
        'min',
        'falasṭīn',
        'ʔiħna',
        'ʔinta',
        'ʔinti',
        'ʔintu',
        ...,
        'ʔahlan'
    ];
}
```

### UnitController units array

```php
class UnitController extends Controller
{
    use RedirectsToSubscribe;

    protected array $units = [
        "u1" => Unit01::class,
        "u2" => Unit02::class,
        "u3" => Unit03::class
    ];
    
    ...
}
```

