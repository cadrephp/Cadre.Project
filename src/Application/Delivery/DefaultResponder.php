<?php
namespace Application\Delivery;

use Radar\Adr\Responder\ResponderAcceptsInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Twig_Environment;

class DefaultResponder implements ResponderAcceptsInterface
{
    protected $request;
    protected $response;
    protected $twig;
    protected $debugbar;

    public function __construct(Twig_Environment $twig, DebugBar $debugbar = null)
    {
        $this->twig = $twig;
        $this->debugbar = $debugbar;
    }

    public static function accepts()
    {
        return ['text/html', 'application/json'];
    }

    public function __invoke(
        Request $request,
        Response $response,
        array $payload
    ) {
        $this->request = $request;
        $this->response = $response;
        if (isset($payload['success']) && true === $payload['success']) {
            $this->success($payload);
        } else {
            $this->error($payload);
        }
        return $this->response;
    }

    protected function jsonBody(array $data)
    {
        $this->response = $this->response->withHeader('Content-Type', 'application/json');
        $this->response->getBody()->write(json_encode($data));
    }

    protected function htmlBody(array $data)
    {
        $view = $this->request->getAttribute('_view', 'index.html.twig');
        $body = $this->twig->render($view, $data);

        if (isset($this->debugbar)) {
            $debugbarRenderer = $this->debugbar->getJavascriptRenderer();
            $body = str_replace(
                '<!-- DebugBar::renderHead -->',
                str_replace(
                    '/vendor/maximebf/debugbar/src/DebugBar/Resources',
                    '/debugbar',
                    $debugbarRenderer->renderHead()
                ),
                $body
            );
            $body = str_replace('<!-- DebugBar::render -->', $debugbarRenderer->render(), $body);
        }

        $this->response = $this->response->withHeader('Content-Type', 'text/html');
        $this->response->getBody()->write($body);
    }

    protected function success($payload)
    {
        $this->response = $this->response->withStatus(200);
        if ('application/json' === $this->request->getHeaderLine('Accept')) {
            $this->jsonBody($payload);
        } else {
            $this->htmlBody($payload);
        }
    }

    protected function error($payload)
    {
        $this->response = $this->response->withStatus(500);
        $this->request = $this->request->withAttribute('_view', 'error.html.twig');
        $this->htmlBody($payload);
    }
}
