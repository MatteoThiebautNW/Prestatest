<?php
/* Smarty version 3.1.34-dev-7, created on 2023-11-30 13:15:53
  from '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/catalog/_partials/product-additional-info.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_65687cf96420c2_05997414',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '769d1e964784783b3a6cfcd5fa34b2410fa654b5' => 
    array (
      0 => '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/catalog/_partials/product-additional-info.tpl',
      1 => 1701340667,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65687cf96420c2_05997414 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="product-additional-info">
  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductAdditionalInfo','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

</div>
<?php }
}
