<?php
 class ErrorController extends Zend_Controller_Action{

 	public function errorAction() {
        $this->view->headTitle()->prepend('Error 404');
        $errors = $this->_getParam('error_handler');
        $this->getResponse()->setHttpResponseCode(404);
        $this->view->message = 'Application error';

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $this->view->message = 'Page not found';
                break;
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
                $this->view->message = $errors->exception->getMessage();
                break;
        }
        if ($errors->exception instanceof Exception_Baillyweb) {
            $tit = $errors->exception->getMessage();
            $msg = '';
        } else {
            $msg = 'La Pagina solicitada puede haber sido movida o ya no existe.';
            $tit = 'Pagina no encontrada';
        }

        $this->view->assign('mensaje_exc', $msg);
        $this->view->assign('titulo_exc', $tit);
        $this->view->exception = $errors->exception;
        $this->view->request = $errors->request;
    }


 }