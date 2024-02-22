<?php
include_once('includes/header.inc.php');

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

//alamat backand data nilai awal
include_once('includes/nilai-awal.inc.php');

$altObj = new NilaiAwal($db);
$altObj->id = $id;
$altObj->readOne();

//pengiriman dengan metode post ke database, untuk merubah data awal
if ($_POST) {
  	$altObj->id = $_POST["id_nilai_awal"];
  	$altObj->nilai = $_POST["nilai"];
  	$altObj->keterangan = $_POST["keterangan"];
  	$altObj->periode = $_POST["periode"];
    
	//untuk alert info berhasil dan gagal di update
	if($altObj->update()){ ?>
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
      <li><a href="nilai-awal.php">Nilai Awal</a></li>
      <li class="active">Edit Data</li>
    </ol>
    <p style="margin-bottom:10px;">
      <strong style="font-size:18pt;"><span class="fa fa-pencil"></span> Edit</strong>
    </p>
      <div class="panel panel-default">
        <div class="panel-body">
			<!--form kontrol pengiriman post untuk update data-->
          <form method="POST">
            <div class="form-group">
                <label for="id_nilai_awal">ID</label>
                <input type="text" name="id_nilai_awal" id="id_nilai_awal" class="form-control" autofocus="on" readonly="on" value="<?php echo $altObj->id; ?>">
            </div>
            <div class="form-group" hidden>
                <label for="nilai">Nilai</label>
                <input type="text" name="nilai" id="nilai" class="form-control" autofocus="on" required="on" value="<?php echo $altObj->nilai; ?>">
            </div>
            <div class="form-group" hidden>
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control" required="on" value="<?php echo $altObj->keterangan; ?>">
            </div>
            <div class="form-group">
                <label for="periode">Periode</label>
                <input type="text" name="periode" id="periode" class="form-control" required="on" value="<?php echo $altObj->periode; ?>">
            </div>            
            <div class="btn-group">
              <button type="submit" class="btn btn-dark">Ubah</button>
              <button type="button" onclick="location.href = 'nilai-awal.php'" class="btn btn-default">Kembali</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>

<?php include_once('includes/footer.inc.php'); ?>
