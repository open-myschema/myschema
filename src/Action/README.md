## Action Module

Actions are units of functionality, business or technical. They correspond to the notion of 'events'.

Included is a basic implementation of [PSR-14](https://www.php-fig.org/psr/psr-14/):
- [ActionDispatch](/src/Application/ActionDispatch.php) implements `\Psr\EventDispatcher\EventDispatcherInterface`
- [LazyActionListener](/src/Application/LazyActionListener.php) implements `\Psr\EventDispatcher\ListenerProviderInterface`

Actions extend the abstract class [Action](/src/Application/Action.php) which defines two abstract methods:
1. `assertAuthorization` - assert whether a request has authorization to execute an action
2. `isValid` - check whether the request meets valid criteria for an action.
