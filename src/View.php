<?php

namespace Webcomcafe\Templating;

use Webcomcafe\Templating\Exceptions\TemplateException;

class View
{
    /**
     * @var array $config
     */
    private array $config = [];

    /**
     * @var Layout $layout
     */
    private ?Layout $layout = null;

    private array $sections = [];

    /**
     * View constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config += $config;
    }


    /**
     * @param string $tpl
     * @param array $vars
     * @return int
     * @throws TemplateException
     */
    public function render(string $tpl, array $vars = [])
    {
        $path = $this->getTplPath($tpl);

        if( !$this->hash($path) ) {
            throw new TemplateException('view ['.$tpl.'] not found');
        }

        $content = $this->inc($path, $vars);

        if( null != $this->layout ) {
            $this->layout->add('page', $content);
            $content = $this->layout->render();
        }

        print $content;
        return strlen($content);
    }

    public function begin(string $name)
    {
        $this->sections[$name] = ob_start();
    }

    public function end(string $name)
    {
        if( !array_key_exists($name, $this->sections) )
            throw new TemplateException('section ['.$name.'] not exists');

        $this->layout->add($name, ob_get_clean());
    }

    private function inc(string $path, array $vars = [])
    {
        ob_start();
        extract($vars);
        require $path;
        ob_end_flush();
        return ob_get_clean();
    }

    private function hash(string $path)
    {
        return file_exists($path) && is_readable($path);
    }

    private function getTplPath(string $tpl)
    {
        //$this->config['dir'][1] = str_replace('.', DS, $this->config['dir'][1]);
        $tpl = str_replace('.', DS, $tpl);
        // $path = implode(DS, $this->config['dir']);
        $dir = rtrim($this->config['dir'],'/');
        $ext = $this->config['ext'];
        return $dir.DS.$tpl.'.'.$ext;
    }

    /**
     * @param string $name
     */
    public function layout(string $name) : void
    {
        $this->layout = new Layout($name, $this->config);
    }
}