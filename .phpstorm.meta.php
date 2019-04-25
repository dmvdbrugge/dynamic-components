<?php

namespace PHPSTORM_META
{
    override(\DynamicComponents\Menu::addAs(0), map(['' => '@']));

    registerArgumentsSet('entryTypes', \DynamicComponents\Controls\Entry::Normal, \DynamicComponents\Controls\Entry::Password, \DynamicComponents\Controls\Entry::Search);
    registerArgumentsSet('multilineEntryTypes', \DynamicComponents\Controls\MultilineEntry::Wrap, \DynamicComponents\Controls\MultilineEntry::NoWrap);

    expectedArguments(\DynamicComponents\Controls\Entry::__construct(), 0, argumentsSet('entryTypes'));
    expectedArguments(\DynamicComponents\Controls\MultilineEntry::__construct(), 0, argumentsSet('multilineEntryTypes'));

    expectedReturnValues(\DynamicComponents\Controls\Entry::getType(), argumentsSet('entryTypes'));
    expectedReturnValues(\DynamicComponents\Controls\MultilineEntry::getType(), argumentsSet('multilineEntryTypes'));
}
