<?php

namespace {

from('Application')
-> import('Controller.Admin.Base')
-> import('Model.User');

}

namespace Application\Controller\Admin {

class Log extends Base {

  public function IndexAction ( ) {

    try {
        \Hoa\Session::start();

        if(true === \Hoa\Session::isNamespaceSet('user')) {
            $this->getKit('Redirector')->redirect('admin_posts');
            return;
        }
    }
    catch ( \Hoa\Core\Exception $e ) { }

    $this->view->addOverlay('hoa://Application/View/Admin/Log/Index.xyl');
    $this->view->render();

    return;
  }

  public function InAction ( ) {

    try {
        \Hoa\Session::start();

        if(true === \Hoa\Session::isNamespaceSet('user')) {
            $this->getKit('Redirector')->redirect('admin_posts');
            return;
        }
    }
    catch ( \Hoa\Core\Exception $e ) { }

    $fragment = new \Hoa\Xyl(
        new \Hoa\File\Read('hoa://Application/View/Admin/Log/Log.frag.xyl'),
        new \Hoa\Http\Response(),
        new \Hoa\Xyl\Interpreter\Html(),
        $this->router
    );
    $fragment->interprete();

    $form = $fragment->getElement('log');

    if(   true === $form->hasBeenSent()
       && true === $form->isValid()) {

      $data = $form->getData();
      $user = new \Application\Model\User();
      $user->name = $data['name'];

      try {
          $user->open();
      }
      catch ( \Hoathis\Model\Exception\NotFound $e ) {
          $this->getKit('Redirector')->redirect('log');
          return;
      }

      if(sha1($data['password']) !== $user->password) {
          $this->getKit('Redirector')->redirect('log');
          return;
      }

      $q = new \Hoa\Session\QNamespace('user');
      $q->name = $data['name'];

      $this->getKit('Redirector')->redirect('admin_posts');
      return;
    }
    else {

      $this->getKit('Redirector')->redirect('log');
      return;
    }
  }

  public function OutAction ( ) {

    try {
        \Hoa\Session::start();
    }
    catch ( \Hoa\Core\Exception $e ) { }

    \Hoa\Session::destroy();
    $this->getKit('Redirector')->redirect('log');
    return;
  }
}

}