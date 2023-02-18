<?php
/**
 * Class syntax_plugin_extranet
 */
class syntax_plugin_extranet extends DokuWiki_Syntax_Plugin
{
    /**
     * What kind of syntax are we?
     */
    public function getType() {
        return 'substition';
    }

    /**
     * Where to sort in?
     */
    public function getSort() {
        return 200;
    }

    /**
     * Connect pattern to lexer
     * @param string $mode
     */
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~NOEXTRANET~~', $mode, 'plugin_changes');
    }

    /**
     * Handler to prepare matched data for the rendering process
     *
     * @param   string       $match   The text matched by the patterns
     * @param   int          $state   The lexer state for the match
     * @param   int          $pos     The character position of the matched text
     * @param   Doku_Handler $handler The Doku_Handler object
     * @return  array Return an array with all data you want to use in render
     */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        return substr($match, 2, -2);
    }

    /**
     * Handles the actual output creation.
     *
     * @param string $mode output format being rendered
     * @param Doku_Renderer $renderer the current renderer object
     * @param array $data data created by handler()
     * @return  boolean                 rendered correctly?
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        $renderer->doc .= "<!-- NOEXTRANET -->";
		
		return true;
    }
}
