<?php

namespace App\Filter\Modifier\Factory;

use App\Filter\Modifier\PriceModifierInterface;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

class PriceModifierFactory implements PriceModifierFactoryInterface
{

    /**
     * @throws ClassNotFoundException
     */
    public function create(string $modifierType): PriceModifierInterface
    {
        // Convert type (snake_case) tp ClassName (PascalCase)
        $modifierClassBasename = str_replace('_', '', ucwords($modifierType, '_'));

        $modifierName = self::PRICE_MODIFIER_NAMESPACE . $modifierClassBasename;

        if (!class_exists($modifierName)) {
            throw new ClassNotFoundException($modifierName);
        }

        return new $modifierName();
    }
}