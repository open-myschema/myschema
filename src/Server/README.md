## Server Module

The server module handles receiving requests and returning responses. The main components are:
- [Runtimes](/src/Server/Runtime) - abstract PHP runtimes
- [Middleware](/src/Server/Middleware) - PSR-15 server middlewares

### Defined Actions
- [HttpRequestAction](/src/Server/Action/HttpRequestAction.php) - fired when a HTTP request is received. Listened on by
[ServerActionsListener](/src/Server/Action/ServerActionsListener.php) which composes a `\Laminas\Stratigility\MiddlewarePipe` instance to handle the request.

### Defined Commands
- [HelloWorldCommand](/src/Server/Console/HelloWorldCommand.php) - Introductory command.
