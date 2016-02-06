# RepeatingSegment
FZ2 Repeating Segment Router

Like the ZF2 Zend\Mvc\Router\Http\Segment route. However the segments can repeat. The results are returned as an array rather than a single value.

Unlike the Segment route the constraints are required for this to work.

```php
return [
    'router' => [
        'routes' => [
            'routName' => [
                'type' => 'Tohmua\RepeatingSegment\RepeatingSegment',
                'options' => [
                    'route'       => '/foo[:section]/bar',
                    'constraints' => [
                        'section' => '/[a-zA-Z][a-zA-Z0-9_-]+',
                    ],
                ],
            ],
        ],
    ],
];
```

####Example 1:
```
'/foo[:section]/bar'
```

will for example match:
http://www.mysite.co.uk/foo/test1/test2/test3/test4/bar

the match will return
```php
$section = ['/test1', '/test2', '/test3', '/test4'];
```

####Example 2:
```
'/foo[:section]/bar[:other_section]/baz'
```

will for example match
http://www.mysite.co.uk/foo/test1/test2/bar/test3/test4/baz

the match will return
```php
$section = ['/test1', '/test2'];
$other_section = ['/test3', '/test4'];
```
