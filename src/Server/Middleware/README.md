## Server Middlewares

#### [ErrorHandlerMiddleware](/src/Server/Middleware/ErrorHandlerMiddleware.php)
A middleware that is placed at the beginning of the middleware pipeline to catch all uncaught errors and exceptions thrown while the request is passed down the pipeline.

#### [FinalResponseMiddleware](/src/Server/Middleware/FinalResponseMiddleware.php)
A middleware that is placed at the end of the middleware pipeline to ensure that the pipeline always returns a response (usually a 404 response).

#### [LazyLoadingMiddleware](/src/Server/Middleware/LazyLoadingMiddleware.php)
A special type of middleware that all ensures that all middlewares piped to the middleware pipeline are done so in a lazy fashion, i.e encapsulates other middlewares so that they are not instantiated until required.

Takes care of resolving `string`, `array`, `callable`, `\Psr\Http\Server\RequestHandlerInterface` instances into `\Psr\Http\Server\MiddlewareInterface` instances for processing.
