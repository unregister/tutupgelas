
<a href="<?php echo site_url('_cpanel/admin/resources/add/');?>" class="general-button"><?php echo glyphicon('glyphicon-plus');?>Tambah resources</a>
<br /><br />
<div id="peaBoxContent">
<table cellspacing="0" cellpadding="0" border="0" class="formTbl">
    <thead>
        <tr class="title">
            <td id="formTblHead" colspan="2"><strong>Tambah resources</strong></td>
        </tr>
     <tbody
        <?php echo table_resources($resources);?>
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
</div>