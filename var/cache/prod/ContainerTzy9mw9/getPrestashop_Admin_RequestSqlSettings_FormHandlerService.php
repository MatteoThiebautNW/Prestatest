<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'prestashop.admin.request_sql_settings.form_handler' shared service.

return $this->services['prestashop.admin.request_sql_settings.form_handler'] = new \PrestaShop\PrestaShop\Core\Form\FormHandler(${($_ = isset($this->services['form.factory']) ? $this->services['form.factory'] : $this->load('getForm_FactoryService.php')) && false ?: '_'}->createBuilder(), ${($_ = isset($this->services['prestashop.core.hook.dispatcher']) ? $this->services['prestashop.core.hook.dispatcher'] : $this->getPrestashop_Core_Hook_DispatcherService()) && false ?: '_'}, ${($_ = isset($this->services['prestashop.admin.request_sql_settings.form_data_provider']) ? $this->services['prestashop.admin.request_sql_settings.form_data_provider'] : $this->load('getPrestashop_Admin_RequestSqlSettings_FormDataProviderService.php')) && false ?: '_'}, ['settings' => 'PrestaShopBundle\\Form\\Admin\\Configure\\AdvancedParameters\\RequestSql\\SqlRequestSettingsType'], 'RequestSql');
