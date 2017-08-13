<?php

namespace SystemCtl\Template;

use SystemCtl\Exception\ConfigurationNotSupported;

class UnitTemplate implements RendererAwareInterface
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $description;
    /** @var string[] */
    protected $after;
    /** @var string[] */
    protected $required;
    /** @var string[] */
    protected $wants;
    /** @var string[] */
    protected $conflicts;
    /** @var string */
    protected $type;
    /** @var string */
    protected $execStart;
    /** @var string */
    protected $execStop;
    /** @var string */
    protected $execReload;
    /** @var string */
    protected $environmentFile;
    /** @var string */
    protected $PIDFile;
    /** @var string */
    protected $wantedBy = 'multi-user.target';

    /** @var RendererInterface */
    protected $renderer;
    /** @var string */
    protected $installPath;

    /**
     * Create a new template for any unit
     * @param string $name
     * @param string $installPath
     */
    public function __construct(string $name, string $installPath)
    {
        $this->name = $name;
        $this->installPath = $installPath;
    }

    /**
     * Install template onto system
     *
     * @return bool
     */
    public function install(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * A meaningful description of the unit.
     *
     * This text is displayed for example in the output of the systemctl status command.
     *
     * @param string $description
     * @return UnitTemplate
     */
    public function setDescription(string $description): UnitTemplate
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAfter(): array
    {
        return $this->after;
    }

    /**
     * Defines the order in which units are started.
     *
     * The unit starts only after the units specified in After are active.
     * Unlike Requires, After does not explicitly activate the specified units.
     * The Before option has the opposite functionality to After.
     *
     * @param string[] $after
     * @return UnitTemplate
     */
    public function setAfter(array $after): UnitTemplate
    {
        $this->after = $after;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRequired(): array
    {
        return $this->required;
    }

    /**
     * Configures dependencies on other units.
     *
     * The units listed in Requires are activated together with the unit.
     * If any of the required units fail to start, the unit is not activated.
     *
     * @param string[] $required
     * @return UnitTemplate
     */
    public function setRequired(array $required): UnitTemplate
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getWants(): array
    {
        return $this->wants;
    }

    /**
     * Configures weaker dependencies than Requires.
     *
     * If any of the listed units does not start successfully, it has no impact on the unit activation.
     * This is the recommended way to establish custom unit dependencies.
     *
     * @param string[] $wants
     * @return UnitTemplate
     */
    public function setWants(array $wants): UnitTemplate
    {
        $this->wants = $wants;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getConflicts(): array
    {
        return $this->conflicts;
    }

    /**
     * Configures negative dependencies, an opposite to Requires.
     *
     * @param string[] $conflicts
     * @return UnitTemplate
     */
    public function setConflicts(array $conflicts): UnitTemplate
    {
        $this->conflicts = $conflicts;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Configures the unit process startup type that affects the functionality of ExecStart and related options
     *
     * @see UnitType
     * @param string $type
     * @return UnitTemplate
     */
    public function setType(string $type): UnitTemplate
    {
        if (!\in_array($type, UnitType::TYPES)) {
            $types = implode(', ', UnitType::TYPES);
            throw new ConfigurationNotSupported("Given type '{$type}' not among {$types}");
        }

        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getExecStart(): string
    {
        return $this->execStart;
    }

    /**
     * @param string $execStart
     * @return UnitTemplate
     */
    public function setExecStart(string $execStart): UnitTemplate
    {
        $this->execStart = $execStart;
        return $this;
    }

    /**
     * @return string
     */
    public function getExecStop(): string
    {
        return $this->execStop;
    }

    /**
     * @param string $execStop
     * @return UnitTemplate
     */
    public function setExecStop(string $execStop): UnitTemplate
    {
        $this->execStop = $execStop;
        return $this;
    }

    /**
     * @return string
     */
    public function getExecReload(): string
    {
        return $this->execReload;
    }

    /**
     * @param string $execReload
     * @return UnitTemplate
     */
    public function setExecReload(string $execReload): UnitTemplate
    {
        $this->execReload = $execReload;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvironmentFile(): string
    {
        return $this->environmentFile;
    }

    /**
     * @param string $environmentFile
     * @return UnitTemplate
     */
    public function setEnvironmentFile(string $environmentFile): UnitTemplate
    {
        $this->environmentFile = $environmentFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getPIDFile(): string
    {
        return $this->PIDFile;
    }

    /**
     * @param string $PIDFile
     * @return UnitTemplate
     */
    public function setPIDFile(string $PIDFile): UnitTemplate
    {
        $this->PIDFile = $PIDFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getWantedBy(): string
    {
        return $this->wantedBy;
    }

    /**
     * @param string $wantedBy
     * @return UnitTemplate
     */
    public function setWantedBy(string $wantedBy): UnitTemplate
    {
        $this->wantedBy = $wantedBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setRenderer(RendererInterface $renderer): RendererAwareInterface
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }
}
