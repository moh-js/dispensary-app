<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.app_name', 'Dispensary App');
        $this->migrator->add('general.app_short_name', 'DA');
        $this->migrator->add('general.app_currency', 'Tsh');
        $this->migrator->add('general.app_address', 'P.O Box 131, Mbeya');
        $this->migrator->add('general.app_phone', '+255 25 250 3016');
    }

    public function down(): void
    {
        $this->migrator->delete('general.app_name');
        $this->migrator->delete('general.app_short_name');
        $this->migrator->delete('general.app_currency');
        $this->migrator->delete('general.app_address');
        $this->migrator->delete('general.app_phone');
    }
}
