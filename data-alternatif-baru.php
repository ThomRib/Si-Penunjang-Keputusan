<?php
include_once('includes/header.inc.php');
include_once('includes/Alternatif.inc.php');
$altObj = new Alternatif($db);

if($_POST){
	$altObj->id = $_POST["id_alternatif"];
	$altObj->nik = $_POST["nik"];
	$altObj->nama = $_POST["nama"];
	$altObj->tempat_lahir = $_POST["tempat_lahir"];
	$altObj->tanggal_lahir = $_POST["tanggal_lahir"];
	$altObj->kelamin = $_POST["kelamin"];
	$altObj->alamat = $_POST["alamat"];
	$altObj->jabatan = $_POST["jabatan"];
	$altObj->tanggal_masuk = $_POST["tanggal_masuk"];
	$altObj->status = $_POST["status"];
	$altObj->no_telp = $_POST["no_telp"];
	$altObj->pendidikan = $_POST["pendidikan"];

	if($altObj->insert()){ ?>
		<script type="text/javascript">
			window.onload=function(){
				showStickySuccessToast();
			};
		</script> <?php
	} else { ?>
		<script type="text/javascript">
			window.onload=function(){
				showStickyErrorToast();
			};
		</script> <?php
	}
}
?>
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12">
	  <ol class="breadcrumb">
		  <li><a href="index.php">Beranda</a></li>
		  <li><a href="data-alternatif.php">Data Nasabah</a></li>
		  <li class="active">Tambah Data</li>
		</ol>
  	<p style="margin-bottom:10px;">
  		<strong style="font-size:18pt;"><span class="fa fa-clone"></span> Tambah Nasabah</strong>
  	</p>
  	<div class="panel panel-default">
			<div class="panel-body">
				    <form method="post">
						  <div class="form-group" hidden>
						    <label for="id_alternatif">ID Nasabah</label>
						    <input type="text" class="form-control" id="id_alternatif" name="id_alternatif" required readonly="on" value="<?php echo $altObj->getNewID(); ?>">
						  </div>
							<div class="form-group">
									<label for="nik">NIK Nasabah</label>
									<input type="text" name="nik" id="nik" class="form-control" autofocus="on" required="on">
							</div>
							<div class="form-group">
									<label for="nama">Nama Lengkap</label>
									<input type="text" name="nama" id="nama" class="form-control" required="on">
							</div>
							<div class="form-group" hidden>
									<label for="tempat_lahir">Tempat Lahir</label>
									<input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" required="on" value="pontianak">
							</div>
							<div class="form-group" hidden>
									<label for="tanggal_lahir">Tanggal Lahir</label>
									<input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control datepicker" required="on" value="2022-09-10">
							</div>
							<div class="form-group" hidden>
									<label for="kelamin">Jenis Kelamin</label>
									<input type="text" name="kelamin" id="kelamin" class="form-control" autofocus="on" required="on" value="pria">
                                
							</div>
							<div class="form-group" hidden>
									<label for="alamat">Alamat</label>
									<input type="text" name="alamat" id="alamat" class="form-control" required="on" value="tes">
							</div>
							<div class="form-group">
									<label for="jabatan">Pekerjaan</label>
									<input type="text" name="jabatan" id="jabatan" class="form-control" required="on">
							</div>
							<div class="form-group">
									<!--<label for="tanggal_masuk"></label>-->
									<!--<input type="text" name="tanggal_masuk" id="tanggal_masuk" class="form-control datepicker" required="on">-->
									<label for="tanggal_masuk">Jaminan</label>

									<select class="form-control" name="tanggal_masuk" id="tanggal_masuk" required="on">
											<option value="">---</option>
											<option value="BPKB">BPKB</option>
											<option value="SHM">SHM</option>
									</select>
							</div>
							<div class="form-group" hidden>
								
                                                        
							<!--<label for="tanggal_masuk">Tanggal Masuk</label>-->
                                    <!--<input type="text" name="tanggal_masuk" id="tanggal_masuk" class="form-control datepicker" required="on" value="<?php echo $altObj->tanggal_masuk; ?>">-->
                                    <label for="status">Status</label>
									<input type="text" name="status" id="status" class="form-control" autofocus="on" required="on" value="belum kawin">
                                                                
                            </div>
       <!--                     <div class="form-group">-->
							<!--		<label for="nik">NIK Nasabah</label>-->
							<!--		<input type="text" name="nik" id="nik" class="form-control" autofocus="on" required="on">-->
							<!--</div>-->
                            <div class="form-group" hidden>
                                <label for="no_telp">No telp</label>
                                <input type="text" name="no_telp" id="no_telp" class="form-control" autofocus="on" required="on" value="08913245678">
                            </div>
							<div class="form-group">
									<label for="pendidikan">Penghasilan Nasabah</label>
									<input type="text" name="pendidikan" id="pendidikan" class="form-control" required="on">
							</div>
							<div class="btn-group">
							  <button type="submit" class="btn btn-dark">Simpan</button>
							  <button type="button" onclick="location.href='data-alternatif.php'" class="btn btn-default">Kembali</button>
							</div>
					</form>
			  </div>
		</div>
	</div>
</div>

<?php include_once('includes/footer.inc.php'); ?>
