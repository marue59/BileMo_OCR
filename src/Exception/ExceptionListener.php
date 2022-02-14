<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


class ExceptionListener implements EventSubscriberInterface {

	public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['onException', 255]]
        ];
    }

    public function onException(ExceptionEvent $event) {

    	$response = new JsonResponse();

        $exception = $event->getThrowable();//dd($exception);
        switch ($exception) {
            case $exception instanceof MethodNotAllowedHttpException:
                $response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
                $response->setData([
                	'code' => Response::HTTP_METHOD_NOT_ALLOWED,
                	'message' => 'Not allowed'
                ]); 
                break;
            case $exception instanceof NotFoundHttpException:
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                $response->setData([
                	'code' => Response::HTTP_NOT_FOUND,
                	'message' => 'Resource not found'
                ]); 
                break;
            case $exception instanceof AccessDeniedException:
                $response->setStatusCode(Response::HTTP_FORBIDDEN);
                $response->setData([
                	'code' => Response::HTTP_FORBIDDEN,
                	'message' => 'Forbiden'
                ]);
                break;
            case $exception instanceof InvalidArgumentException:
                $response->setStatusCode($exception->getCode());
                $response->setData($exception->getMessage());
                break;
            default:
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                $response->setData([
                	'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                	'message' => 'Server error'
                ]);
            break;
        }

        $event->setResponse($response);
    }
}