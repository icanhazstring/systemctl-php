<?php
declare(strict_types=1);

namespace SystemCtl\Template\Section;

/**
 * InstallSection
 *
 * @method InstallSection setAlias(array $alias)     A space-separated list of additional names for this unit
 * @method InstallSection setRequiredBy(array $req)  Set up unit requirements for this unit
 * @method InstallSection setWantedBy(array $wanted) Set up unit requirements for this unit
 * @method InstallSection setAlso(array $also)       Additional units to install/deinstall when installed/deinstalled
 *
 * @method array getAlias()
 * @method array getRequiredBy()
 * @method array getWantedBy()
 * @method array getAlso()
 *
 * @package SystemCtl\Template\Section
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class InstallSection extends AbstractSection
{
    protected const PROPERTIES = [
        'Alias',
        'RequiredBy',
        'WantedBy',
        'Also'
    ];
}
