<form method="post" action="" name="edit" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="0" class="formTbl" >
<thead>
    <tr class="title">
        <td id="formTblHead"><strong>Ubah permission</strong></td>
    </tr>
</thead>
<tbody>
<?php
foreach((array)$arrmenu as $menu)
{
	$cek1 = $this->adodb->GetOne("SELECT COUNT(*) FROM sys_group_menu WHERE group_id = $group_id AND menu_id = ".$menu['id']);
	$checked1 = ($cek1 > 0)?' checked="checked"':'';
?>
    <tr bgcolor="#ffffff" class="row1">
        <td valign="top" align="left">
        <input type="checkbox" name="permission[]" value="<?php echo $menu['id'];?>" id="menu<?php echo $menu['id'];?>" <?php echo $checked1?> />
        <label for="menu<?php echo $menu['id'];?>"><?php echo $menu['title'];?></label>
        </td>
    </tr>
    <?php
	if( count($menu['child']) > 0 )
	{
		foreach((array)$menu['child'] as $child)
		{
			$cek2 = $this->adodb->GetOne("SELECT COUNT(*) FROM sys_group_menu WHERE group_id = $group_id AND menu_id = ".$child['id']);
			$checked2 = ($cek2 > 0)?' checked="checked"':'';
	?>
    		<tr bgcolor="#ffffff" class="row1">
                <td valign="top" align="left" style="padding-left:40px;">
                <input type="checkbox" name="permission[]" value="<?php echo $child['id'];?>" id="menu<?php echo $child['id'];?>"<?php echo $checked1?> />
                <label for="menu<?php echo $child['id'];?>"><?php echo $child['title'];?></label>
                </td>
            </tr>
    <?php
		}
	}
	?>
<?php
}
?>
</tbody>
<tfoot>
    <tr class="title">
        <td valign="top">
            <input name="change_permission" type="submit" value="&nbsp;SAVE&nbsp;"  default="true" class="pea-button-save" >
        </td>
    </tr>
</tfoot>

</table>
</form>