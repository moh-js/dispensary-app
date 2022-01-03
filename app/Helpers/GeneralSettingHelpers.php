<?php

use App\Settings\GeneralSettings;

function getAppName()
{
    return app(GeneralSettings::class)->app_name;
}


function getAppShortName()
{
    return app(GeneralSettings::class)->app_short_name;
}


function getAppCurrency()
{
    return app(GeneralSettings::class)->app_currency;
}

function getAppAddress()
{
    return app(GeneralSettings::class)->app_address;
}

function getAppPhone()
{
    return app(GeneralSettings::class)->app_phone;
}


