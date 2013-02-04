<?php

/* AcmeHelloBundle::base.html.twig */
class __TwigTemplate_340e92bc0347aea502b0abeb4a84395e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'header' => array($this, 'block_header'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        echo "
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <title>";
        // line 7
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
  </head>
  <body>
    <h1>";
        // line 10
        $this->displayBlock('header', $context, $blocks);
        echo "</h1>
    ";
        // line 11
        $this->displayBlock('body', $context, $blocks);
        // line 12
        echo "  </body>
</html>

";
    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
    }

    // line 10
    public function block_header($context, array $blocks = array())
    {
        echo "Default Header";
    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "AcmeHelloBundle::base.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  59 => 11,  53 => 10,  48 => 7,  41 => 12,  39 => 11,  35 => 10,  29 => 7,  22 => 2,);
    }
}
