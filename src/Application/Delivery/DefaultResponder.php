<?php
declare(strict_types=1);

namespace Application\Delivery;

use Cadre\DomainSession\Session;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Radar\Adr\Responder\ResponderAcceptsInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Twig_Environment;

class DefaultResponder implements ResponderAcceptsInterface
{
    protected $request;
    protected $response;
    protected $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
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
        if (isset($payload['session']) && $payload['session'] instanceof Session) {
            if ($payload['session']->getId()->hasUpdatedValue()) {
                $this->response = FigResponseCookies::set(
                    $this->response,
                    SetCookie::create('SESSION_ID')
                        ->withValue($payload['session']->getId()->value())
                );
            }
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
