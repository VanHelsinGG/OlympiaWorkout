<?php
namespace OlympiaWorkout\bootstrap\Router;

class Request
{
    protected array $request;

    public function __construct()
    {
        $this->request = [
            'uri' => $_SERVER['REQUEST_URI'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD'] ?? '',
        ];
    }

    public function uri(): string
    {
        return trim(parse_url($this->request['uri'], PHP_URL_PATH) ?? '', '/');
    }

    public function method(): string
    {
        return strtoupper($this->request['method']);
    }

    public function data(): array
    {
        return $_REQUEST;
    }

    public function body()
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? ($input ?: null);
    }

    public function headers(): array
    {
        return getallheaders() ?: [];
    }

    public function response(int $status, string $message): void
    {
        http_response_code($status);
        echo $message;
        exit;
    }
}