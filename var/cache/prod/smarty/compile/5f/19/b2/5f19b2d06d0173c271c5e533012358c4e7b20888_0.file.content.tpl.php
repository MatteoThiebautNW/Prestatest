<?php
/* Smarty version 3.1.34-dev-7, created on 2023-11-30 12:02:24
  from '/Applications/MAMP/htdocs/prestatest/admin3908otfhk/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_65686bc07980c3_54499257',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5f19b2d06d0173c271c5e533012358c4e7b20888' => 
    array (
      0 => '/Applications/MAMP/htdocs/prestatest/admin3908otfhk/themes/default/template/content.tpl',
      1 => 1701340667,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65686bc07980c3_54499257 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>

<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}
