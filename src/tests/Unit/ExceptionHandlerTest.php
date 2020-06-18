<?php

namespace Tests\Unit;

use App\Exceptions\Handler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Json;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;
use Mockery;
class ExceptionHandlerTest extends TestCase
{

    /**
     * @return Request
     */
    private function getRequest(){
        $request = $this->partialMock(Request::class, function ($mock) {
            $mock->shouldReceive('wantsJson')->andReturn(true);
        });
        return $request;
    }
    /**
     * A basic unit test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function testExceptionHandlerReturnJsonResponseWithTheCorrectStatusAndMessage()
    {

        $request = $this->getRequest();
        $handler = new Handler($this->app);
        /**
         * @var JsonResponse $reponse
         */
       $reponse = $handler->render($request,new AuthenticationException("Unauthenticated"));

        $this->assertEquals(401,$reponse->getStatusCode());
        $this->assertTrue($reponse instanceof JsonResponse);
        $this->assertEquals($reponse->getData()->error,"Unauthenticated");
    }

    public function testReturnExpectedStatusCodeWhenIsHttpException(){
        $request = $this->getRequest();
        $handler = new Handler($this->app);
        /**
         * @var JsonResponse $reponse
         */
        $reponse = $handler->render($request,new HttpException(403));

        $this->assertEquals(403,$reponse->getStatusCode());
    }

    public function testExtraInformationInDebugMode(){
        $request = $this->getRequest();
        $handler = new Handler($this->app);
        /**
         * @var JsonResponse $reponse
         */

        $this->app['config']->set('app.debug', true);
        $reponse = $handler->render($request,new AuthenticationException("Unauthenticated"));
        $this->assertNotEmpty($reponse->getData()->trace);
        $this->assertEquals($reponse->getData()->message,"Unauthenticated");
        $this->assertEquals(AuthenticationException::class,$reponse->getData()->exception);
    }
}
