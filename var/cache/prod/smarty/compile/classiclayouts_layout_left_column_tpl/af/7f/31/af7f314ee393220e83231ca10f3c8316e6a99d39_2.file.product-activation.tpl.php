<?php
/* Smarty version 3.1.34-dev-7, created on 2023-11-30 11:54:46
  from '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/catalog/_partials/product-activation.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_656869f613f758_25253563',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'af7f314ee393220e83231ca10f3c8316e6a99d39' => 
    array (
      0 => '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/catalog/_partials/product-activation.tpl',
      1 => 1701340511,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_656869f613f758_25253563 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['page']->value['admin_notifications']) {?>
  <div class="alert alert-warning row" role="alert">
    <div class="container">
      <div class="row">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['page']->value['admin_notifications'], 'notif');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['notif']->value) {
?>
          <div class="col-sm-12">
            <i class="material-icons float-xs-left">&#xE001;</i>
            <p class="alert-text"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['notif']->value['message'], ENT_QUOTES, 'UTF-8');?>
</p>
          </div>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
      </div>
    </div>
  </div>
<?php }
}
}
