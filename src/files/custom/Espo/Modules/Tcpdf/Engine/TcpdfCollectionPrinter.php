<?php
/************************************************************************
 * This file is part of EspoCRM.
 *
 * EspoCRM - Open Source CRM application.
 * Copyright (C) 2014-2023 Yurii Kuznietsov, Taras Machyshyn, Oleksii Avramenko
 * Website: https://www.espocrm.com
 *
 * EspoCRM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * EspoCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with EspoCRM. If not, see http://www.gnu.org/licenses/.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "EspoCRM" word.
 ************************************************************************/

namespace Espo\Modules\Tcpdf\Engine;

use Espo\ORM\Collection;
use Espo\Tools\Pdf\CollectionPrinter;
use Espo\Tools\Pdf\Template;
use Espo\Tools\Pdf\Contents;
use Espo\Tools\Pdf\Data;
use Espo\Tools\Pdf\IdDataMap;
use Espo\Tools\Pdf\Params;
use Espo\Modules\Tcpdf\Engine\Tcpdf;

class TcpdfCollectionPrinter implements CollectionPrinter
{
    private EntityProcessor $entityProcessor;

    public function __construct(EntityProcessor $entityProcessor)
    {
        $this->entityProcessor = $entityProcessor;
    }

    /**
     * @param Collection<\Espo\ORM\Entity> $collection
     */
    public function print(Template $template, Collection $collection, Params $params, IdDataMap $dataMap): Contents
    {
        $pdf = new Tcpdf();

        $pdf->setUseGroupNumbers(true);

        foreach ($collection as $entity) {
            $pdf->startPageGroup();

            $data = $dataMap->get($entity->getId()) ?? Data::create();

            $this->entityProcessor->process($pdf, $template, $entity, $params, $data);
        }

        return new TcpdfContents($pdf);
    }
}
