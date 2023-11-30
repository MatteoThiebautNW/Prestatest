<?php
/* Smarty version 3.1.47, created on 2023-11-30 13:28:59
  from '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/_partials/helpers.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.47',
  'unifunc' => 'content_6568800b43c2c8_55448958',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '16273ce75c0f776438c6b0f67c47ea22c48e6959' => 
    array (
      0 => '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/_partials/helpers.tpl',
      1 => 1701347020,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6568800b43c2c8_55448958 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->smarty->ext->_tplFunction->registerTplFunctions($_smarty_tpl, array (
  'renderLogo' => 
  array (
    'compiled_filepath' => '/Applications/MAMP/htdocs/prestatest/var/cache/prod/smarty/compile/classiclayouts_layout_full_width_tpl/16/27/3c/16273ce75c0f776438c6b0f67c47ea22c48e6959_2.file.helpers.tpl.php',
    'uid' => '16273ce75c0f776438c6b0f67c47ea22c48e6959',
    'call_name' => 'smarty_template_function_renderLogo_10093062886568800b435b57_76063538',
  ),
));
?> 

<?php }
/* smarty_template_function_renderLogo_10093062886568800b435b57_76063538 */
if (!function_exists('smarty_template_function_renderLogo_10093062886568800b435b57_76063538')) {
function smarty_template_function_renderLogo_10093062886568800b435b57_76063538(Smarty_Internal_Template $_smarty_tpl,$params) {
foreach ($params as $key => $value) {
$_smarty_tpl->tpl_vars[$key] = new Smarty_Variable($value, $_smarty_tpl->isRenderingCache);
}
?>

  <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['pages']['index'], ENT_QUOTES, 'UTF-8');?>
">
    <img
      class="logo img-fluid"
      src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['logo_details']['src'], ENT_QUOTES, 'UTF-8');?>
"
      alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['name'], ENT_QUOTES, 'UTF-8');?>
"
      width="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['logo_details']['width'], ENT_QUOTES, 'UTF-8');?>
"
      height="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['logo_details']['height'], ENT_QUOTES, 'UTF-8');?>
">
  </a>
<?php
}}
/*/ smarty_template_function_renderLogo_10093062886568800b435b57_76063538 */
}
