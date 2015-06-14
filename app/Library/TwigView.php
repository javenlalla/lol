<?php

namespace Library;

use Twig_Lexer;
use \Slim\Views\Twig;

class TwigView extends Twig
{
    CONST TWIG_EXTENSION = '.twig';
    
    /**
     * Overriding render function.
     *
     * This method will output the rendered template content
     *
     * @param string $template The path to the Twig template, relative to the Twig templates directory.
     * @param null $data
     * @return string
     */
    public function render($template, $data = null)
    {
        $templateFile = $template . self::TWIG_EXTENSION;

        return parent::render($templateFile, $data);
    }
    
    /**
     * Update the Twig syntax for block delimiters.
     */
    public function updateDelimiterSyntax()
    {
        $instance = $this->getEnvironment();

        $lexer = new Twig_Lexer($instance, array(
            'tag_comment'  => array('<%#', '%>'),
            'tag_block'    => array('<%', '%>'),
            'tag_variable' => array('<%=', '%>'),
        ));
        
        $instance->setLexer($lexer);
    }
    
}