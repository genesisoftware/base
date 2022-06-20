<?php

declare(strict_types=1);

namespace Genesisoft\Base\Plugin\Magento\Framework;

use Magento\Framework\Escaper as Subject;

/**
 * Class Escaper
 * @package Jn2\Boostcommerce\Plugin\Magento\Framework
 */
class Escaper
{
    /**
     * Plugin para corrigir a issue 33346 que ocorre na função que monta URLs
     * só aceitar parâmetros como string.
     *
     * @param Subject $subject
     * @param $string
     * @return array
     * @see https://github.com/magento/magento2/issues/33346
     *
     * @todo Remover esse plugin quando o bug for corrigido no core
     */
    public function beforeEncodeUrlParam(Subject $subject, $string): array
    {
        return [(string)$string];
    }
}
