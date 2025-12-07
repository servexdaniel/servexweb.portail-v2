<?php

namespace App\Servex\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

trait UsesTenantSettingsTrait
{

    // get setting value
    public function getSetting($name)
    {
        $settings = $this->getCache();
        $value = Arr::get($settings, $name);
        return ($value !== '') ? $value : NULL;
    }

    // create-update setting
    public function setSetting($name, $value)
    {
        //$this->removeKeyFromCache($name);
        $this->storeSetting($name, $value);
        $this->setCache();
    }

    // delete a setting
    public function deleteSetting($name)
    {
        $record = Setting::where(['customer_id' => $this->id, 'name' => $name])->first();
        if ($record) {
            $record->delete();
            $this->removeKeyFromCache($name);
        }
        $this->setCache();
    }

    // create-update multiple settings at once
    public function setSettings($data = [])
    {
        foreach ($data as $name => $value) {
            $this->storeSetting($name, $value);
        }
        $this->setCache();
    }

    private function storeSetting($name, $value)
    {
        $record = Setting::where(['customer_id' => $this->id, 'name' => $name])->first();
        if ($record) {
            $record->value = $value;
            $record->save();
        } else {
            $data = new Setting(['name' => $name, 'value' => $value]);
            $this->settings()->save($data);
        }
    }

    private function getCache()
    {
        if (Cache::has('tenant_settings_' . $this->id)) {
            return Cache::get('tenant_settings_' . $this->id);
        }
        return $this->setCache();
    }

    private function setCache()
    {
        if (Cache::has('tenant_settings_' . $this->id)) {
            Cache::forget('tenant_settings_' . $this->id);
        }
        $settings = $this->settings->pluck('value', 'name');
        Cache::forever('tenant_settings_' . $this->id, $settings);
        return $this->getCache();
    }

    public function removeKeyFromCache($key)
    {
        if (Cache::has('tenant_settings_' . $this->id)) {
            $settings = Cache::get('tenant_settings_' . $this->id);
            $value = Arr::get($settings, $key);

            if (!is_null($value)) {
                $settings = Cache::get('tenant_settings_' . $this->id);
                $settings->forget($key);

                Cache::forever('tenant_settings_' . $this->id, $settings);
            }
        }
    }
}
