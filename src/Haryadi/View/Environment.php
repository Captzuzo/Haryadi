<?php
namespace Haryadi\View;

class Environment
{
    protected array $sections = [];
    protected array $sectionStack = [];
    protected array $pushStacks = [];
    protected array $pushStackNames = [];
    protected ?string $parent = null;
    public ?string $title = null;

    public function startSection(string $name): void
    {
        $this->sectionStack[] = $name;
        ob_start();
    }

    public function stopSection(): void
    {
        $content = ob_get_clean();
        $name = array_pop($this->sectionStack);
        if ($name === null) return;
        if (!isset($this->sections[$name])) {
            $this->sections[$name] = $content;
        } else {
            // append
            $this->sections[$name] .= $content;
        }
    }

    public function yieldContent(string $name, $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }

    public function setParent(?string $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    // Push stack
    public function startPush(string $name): void
    {
        $this->pushStackNames[] = $name;
        ob_start();
    }

    public function stopPush(): void
    {
        $content = ob_get_clean();
        $name = array_pop($this->pushStackNames);
        if ($name === null) return;
        if (!isset($this->pushStacks[$name])) $this->pushStacks[$name] = '';
        $this->pushStacks[$name] .= $content;
    }

    public function yieldPush(string $name): string
    {
        return $this->pushStacks[$name] ?? '';
    }

    // Fragments (alias to sections)
    public function startFragment(string $name): void
    {
        $this->startSection($name);
    }

    public function stopFragment(): void
    {
        $this->stopSection();
    }
}
