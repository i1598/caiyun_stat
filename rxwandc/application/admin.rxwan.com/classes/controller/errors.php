<?php
defined( 'SYSPATH' )or die( 'No direct script access.' );

class Controller_Errors    extends    Controller_Smarty    {


    public function action_404(){
        $this->response->body($this->view->fetch( 'errors/404.html' ));
    }


}