<script src="<?=_URL?>assets/grocery_crud/texteditor/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="<?=_URL?>assets/grocery_crud/texteditor/ckeditor/adapters/jquery.js" type="text/javascript"></script>
<script src="<?=_URL?>assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js" type="text/javascript"></script>
<form method="post" action="<?=_URL?>contact/admin/contact/reply_contact">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="email" value="<?=$contact->email?>">
<input type="hidden" name="subject" value="<?=$contact->subject?>">

<div class="box box-info">
	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-pencil-square-o"></i> Balas contact</h3>
	</div>

	<div class="box-body">
		<?=get_msg()?>
		<table class="table table-bordered tablesorter table-striped">
			<tr>
				<td width="20%" align="right"><strong>Nama pengirim :</strong></td>
				<td width="80%"><?=$contact->name?></td>
			</tr>

			<tr>
				<td align="right"><strong>Tanggal :</strong></td>
				<td><?=mysql_date($contact->created)?></td>
			</tr>

			<tr>
				<td align="right"><strong>Email :</strong></td>
				<td><?=$contact->email?></td>
			</tr>

			<tr>
				<td align="right"><strong>Subject :</strong></td>
				<td><?=$contact->subject?></td>
			</tr>

			<tr>
				<td align="right"><strong>Balasan :</strong></td>
				<td><textarea name="balasan" id="balasan" class="texteditor"></textarea></td>
			</tr>

        </table>

	</div>

	<div class="box-footer text-right">
		<button class="btn btn-large btn-primary submit-form" type="submit" name="submit">
	    	<i class="fa fa-reply"></i> KIRIM
	    </button>
    </div>

</div>
</form>