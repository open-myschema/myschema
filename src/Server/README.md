## Server Module

The server module handles receiving requests and returning responses. The main components are:
- [Runtimes](/Runtime) - abstract PHP runtimes
- [Middleware](/Middleware) - PSR-15 server middlewares

### Defined Actions
- [HttpRequestAction](/Action/HttpRequestAction.php) - fired when a HTTP request is received. Listened on by
[ServerActionsListener](/Action/ServerActionsListener.php) which composes a `\Laminas\Stratigility\MiddlewarePipe` instance to handle the request.

### Defined Commands
- [HelloWorldCommand](/Console/HelloWorldCommand.php) - Introductory command.
