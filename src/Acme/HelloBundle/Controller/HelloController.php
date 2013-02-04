<?php
// src/Acme/HelloBundle/Controller/HelloController.php
namespace Acme\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloController extends Controller {
  public function indexAction($name) {
    return $this->render(
      'AcmeHelloBundle:Hello:index.html.twig',
      array('name' => $name)
    );
  }

  public function goodbyeAction() {

    $request = $this->getRequest();

    return $this->render(
      'AcmeHelloBundle:Hello:goodbye.html.twig',
      array('name' => $request->query->get('name'))
      );
  }
}
