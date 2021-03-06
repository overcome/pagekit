<?php

namespace Pagekit\View\Helper;

use Pagekit\View\ViewInterface;

class SectionHelper implements HelperInterface
{
    /**
     * @var ViewInterface
     */
    protected $view;

    /**
     * @var array
     */
    protected $sections = [];

    /**
     * @var array
     */
    protected $openSections = [];

    /**
     * Constructor.
     *
     * @param ViewInterface $view
     */
    public function __construct(ViewInterface $view)
    {
        $this->view = $view;
        $this->view->on('render', function ($event) {
            if ($this->exists($template = $event->getTemplate())) {
                $event->setResult($this->get($template));
            }
        }, 10);
    }

    /**
     * Gets a section.
     *
     * @param  string $name
     * @return string
     */
    public function get($name)
    {
        return isset($this->sections[$name]) ? $this->sections[$name] : '';
    }

    /**
     * Sets a section value.
     *
     * @param string $name
     * @param mixed  $content
     */
    public function set($name, $content)
    {
        $this->sections[$name] = $content;
    }

    /**
     * Checks if the section exists.
     *
     * @param  string $name
     * @return bool
     */
    public function exists($name)
    {
        return isset($this->sections[$name]);
    }

    /**
     * Starts a new section.
     *
     * @param  string $name
     * @throws \InvalidArgumentException
     */
    public function start($name)
    {
        if (in_array($name, $this->openSections)) {
            throw new \InvalidArgumentException(sprintf('A section named "%s" is already started.', $name));
        }

        $this->openSections[] = $name;
        $this->sections[$name] = '';

        ob_start();
        ob_implicit_flush(0);
    }

    /**
     * Stops a section.
     *
     * @param  bool $show
     * @throws \LogicException
     */
    public function stop($show = false)
    {
        if (!$this->openSections) {
            throw new \LogicException('No section started.');
        }

        $name = array_pop($this->openSections);

        $this->sections[$name] = ob_get_clean();

        if ($show) {
            echo $this->view->render($name);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'section';
    }
}
