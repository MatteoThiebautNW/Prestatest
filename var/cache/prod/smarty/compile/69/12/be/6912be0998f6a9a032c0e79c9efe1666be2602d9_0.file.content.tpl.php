<?php
/* Smarty version 3.1.47, created on 2023-11-30 13:35:27
  from '/Applications/MAMP/htdocs/prestatest/admin155snuikz/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.47',
  'unifunc' => 'content_6568818fdc0988_44071817',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6912be0998f6a9a032c0e79c9efe1666be2602d9' => 
    array (
      0 => '/Applications/MAMP/htdocs/prestatest/admin155snuikz/themes/default/template/content.tpl',
      1 => 1701347019,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6568818fdc0988_44071817 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>

<div class="row">
	<div class="col-lg-12">
		<?php if ((isset($_smarty_tpl->tpl_vars['content']->value))) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}
