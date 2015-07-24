<a href="<?php echo site_url('_cpanel/admin/resources/data/');?>" class="general-button"><?php echo glyphicon('glyphicon-plus');?>Data resources</a>
<br /><br />
<?php echo (isset($msg))?$msg:""; ?>
<div id="peaBoxContent">
<form id="peaForm-add" name="add" enctype="multipart/form-data" action="" method="post">
    <table cellspacing="0" cellpadding="0" border="0" class="formTbl">
        <tbody>
            <tr class="title">
                <td id="formTblHead" colspan="2"><strong>Tambah resources</strong></td>
            </tr>
        	<tr bgcolor="#ffffff" class="row1">			
                <td width="20%" valign="top" align="left">Parent</td>			
                <td valign="top" align="left">
                	<select name="parent_id">
                    <?php echo dropdown_resources($resources,0,$edit['parent_id']);?>
                    </select>
                </td>
            </tr>
            <tr bgcolor="#ffffff" class="row1">			
                <td width="20%" valign="top" align="left">Nama resources</td>			
                <td valign="top" align="left"><?php echo $edit['name'];?></td>
            </tr>
        </tbody>
        
        <tfoot>
            <tr class="title">
                <td valign="top">&nbsp;</td>
                <td valign="top">
                    <input type="submit" class="pea-button-save" value="Simpan" name="save_resources">
                </td>
            </tr>
        </tfoot>
    </table>
</form>
</div>