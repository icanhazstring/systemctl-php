<?php

namespace SystemCtl\Template\Section;

/**
 * UnitSection
 *
 * @method UnitSection setDescription(string $description)      A meaningful description of the unit.
 * @method UnitSection setDocumentation(string $documentation)  A list of URIs referencing documentation for the unit.
 * @method UnitSection setAfter(array $after)                   Defines the order in which units are started.
 * @method UnitSection setRequires(array $requires)             Configures dependencies on other units.
 * @method UnitSection setWants(array $wants)                   Configures weaker dependencies than Requires.
 * @method UnitSection setConflicts(array $conflicts)           Configures negative dependencies, opposite to Requires.
 *
 * @method string getDescription()
 * @method string getDocumentation()
 * @method array getAfter()
 * @method array getRequires()
 * @method array getWants()
 * @method array getConflicts()
 *
 * @package SystemCtl\Template\Section
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class UnitSection extends AbstractSection
{
    protected const PROPERTIES = [
        'Description',
        'Documentation',
        'After',
        'Requires',
        'Wants',
        'Conflicts'
    ];
}
