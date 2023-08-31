<?php

namespace App\Core\Response;

class Response
{
    protected int $statusCode = 200;
    protected array $headers = [];
    protected $content;

    public function __construct(protected $templateInterface)
    {
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function header(string $key): ?string
    {
        return $this->headers[$key] ?? null;
    }

    public function redirect(string $url): self
    {
        $this->setHeader('Location', $url);
        $this->setStatusCode(302);

        $this->send();
        return $this;
    }

    public function view(string $template, array $data = []): self
    {
        $this->content = $this->templateInterface->render("$template.twig", $data);

        $this->send();
        echo $this->content;
        return $this;
    }

    public function json(array $data): self
    {
        $this->content = json_encode($data);
        $this->setHeader('Content-Type', 'application/json');
        $this->send();

        return $this;
    }

    public function send()
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        return $this->content;
    }
}
