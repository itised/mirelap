<?php namespace Mirelap\Exceptions;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use PHPUnit_Framework_TestCase;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HandlerTest extends PHPUnit_Framework_TestCase
{
    /** @var ExceptionHandler */
    protected $parentHandler;

    /** @var Handler */
    protected $handler;

    /** @var Request */
    protected $request;

    public function setUp()
    {
        $this->request = Mockery::mock(Request::class);
        $this->parentHandler = Mockery::mock(ExceptionHandler::class);
        $this->handler = new Handler($this->parentHandler, false);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    protected function checkErrorResponse(Response $response, $expectedCode)
    {
        $json = json_decode($response->getContent(), true);

        $this->assertEquals($expectedCode, $response->getStatusCode());
        $this->assertArrayHasKey('status_code', $json);
        $this->assertArrayHasKey('message', $json);
        $this->assertEquals($expectedCode, $json['status_code']);

        return $json;
    }

    public function testNonHttpException()
    {
        $message = 'BOOM!';
        $exception = new RuntimeException($message);
        $response = $this->handler->render($this->request, $exception);

        $json = $this->checkErrorResponse($response, 500);
        $this->assertEquals($message, $json['message']);
    }

    public function testGenericHttpException()
    {
        $code = 404;
        $message = 'test message';
        $exception = new HttpException($code, $message);
        $response = $this->handler->render($this->request, $exception);

        $json = $this->checkErrorResponse($response, $code);
        $this->assertEquals($message, $json['message']);
    }

    public function testValidationHttpException()
    {
        $exception = new ValidationHttpException();
        $response = $this->handler->render($this->request, $exception);

        $this->checkErrorResponse($response, 422);
    }

    public function testDeleteResourceFailedException()
    {
        $exception = new DeleteResourceFailedException();
        $response = $this->handler->render($this->request, $exception);

        $this->checkErrorResponse($response, 422);
    }

    public function testStoreResourceFailedException()
    {
        $exception = new StoreResourceFailedException();
        $response = $this->handler->render($this->request, $exception);

        $this->checkErrorResponse($response, 422);
    }

    public function testUpdateResourceFailedException()
    {
        $exception = new UpdateResourceFailedException();
        $response = $this->handler->render($this->request, $exception);

        $this->checkErrorResponse($response, 422);
    }

    public function testUnknownVersionException()
    {
        $exception = new UnknownVersionException();
        $response = $this->handler->render($this->request, $exception);

        $this->checkErrorResponse($response, 400);
    }

    public function testResourceExceptionErrorsAreIncludedInJson()
    {
        $message = 'BOOM!';
        $errors = ['field' => ['error message']];
        $code = 99;
        $exception = new ResourceException($message, $errors, null, [], $code);
        $response = $this->handler->render($this->request, $exception);

        $json = $this->checkErrorResponse($response, 422);
        $this->assertEquals($message, $json['message']);
        $this->assertEquals($errors, $json['errors']);
        $this->assertEquals($code, $json['code']);
    }

    public function testHttpExceptionsWithNoMessageUseStatusCodeMessage()
    {
        $statusCode = 404;
        $exception = new HttpException($statusCode);
        $response = $this->handler->render($this->request, $exception);

        $json = $this->checkErrorResponse($response, $statusCode);
        $this->assertEquals(sprintf('%d %s', $statusCode, Response::$statusTexts[$statusCode]), $json['message']);
    }

    public function testResourceConflictException()
    {
        $submitted = ['id' => 1, 'name' => 'first', 'updated_at' => '2000-01-01 00:00:00'];
        $current = ['id' => 1, 'name' => 'second', 'updated_at' => '2000-01-01 00:00:01'];

        $exception = new ResourceConflictException($submitted, $current);
        $response = $this->handler->render($this->request, $exception);

        $json = $this->checkErrorResponse($response, 409);
        $this->assertEquals($submitted, $json['submitted']);
        $this->assertEquals($current, $json['current']);
    }
}