<?php


namespace Webcomcafe\Templating;


use Webcomcafe\Templating\Exceptions\TemplateNotFoundException;

class Layout
{
    /**
     * @var string $name
     */
    private string $name;

    /**
     * @var array $config
     */
    private array $config;

    private array $contents = [
        'page' => '',
        'styles' => '',
        'scripts' => '',
    ];


    /**
     * Layout constructor.
     * @param string $name
     * @param array $config
     */
    public function __construct(string $name, array $config)
    {
        $this->name = $name;
        $this->config = $config;
    }

    private function getBaseDir(string $file)
    {
        //$path = implode(DS, $this->config['dir']).DS.$file;
        $path = $this->config['dir'].DS.$file;
        $file = $path.'.'.$this->config['ext'];
        return $file;
    }

    public function render()
    {
        $file = $this->getBaseDir('layouts'. DS . $this->name . DS. 'index');

        if( !file_exists($file) || !is_readable($file))
            throw new TemplateNotFoundException('Template ['.$this->name.'] not found');

        return $this->inc($file);
    }

    private function inc(string $file, array $vars = [])
    {
        ob_start();
        require $file;
        return ob_get_clean();
    }

    public function add(string $section, $contents)
    {
        $this->contents[$section] = $contents;
    }

    public function page()
    {
        return $this->get('page');
    }

    public function styles()
    {
        return $this->get('styles');
    }

    public function section(string $name)
    {
        $this->get($name);
    }

    public function scripts()
    {
        return $this->get('scripts');
    }

    public function js(string $resource)
    {
        return sprintf('<script src="%s" type="text/javascript"></script>', $this->asset($resource)).PHP_EOL;
    }

    public function css(string $resource)
    {
        return sprintf('<link rel="stylesheet" href="%s" type="text/css">', $this->asset($resource)).PHP_EOL;
    }

    public function partial(string $name, array $vars = [])
    {
        $file = $this->getBaseDir('_partials.'.$name);
        return $this->inc($file, $vars);
    }

    public function asset(string $resource)
    {
        $resource = $this->config['url'].'/'.$resource;
        return $resource;
    }

    private function get(string $section)
    {
        $contents = $this->contents[$section];
        unset($this->contents[$section]);
        return $contents;
    }

    private function var(string $name)
    {
        return $this->config['vars'][$name] ?? null;
    }

    public function __get($name)
    {
        return $this->var($name);
    }
}