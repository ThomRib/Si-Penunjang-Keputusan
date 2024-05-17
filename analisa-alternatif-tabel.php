<?php

include_once('includes/header.inc.php');
include_once('includes/skor.inc.php');
include_once('includes/alternatif.inc.php');
include_once('includes/bobot.inc.php');

$bobotObj = new Bobot($db);
$skoObj = new Skor($db);
$altObj = new Alternatif($db);
$altkriteria = isset($_POST['kriteria']) ? $_POST['kriteria'] : $_GET['kriteria'];

if (isset($altkriteria)) {
	$skoObj->readKri($altkriteria);
	$count = $skoObj->countAll();

	if (isset($_POST['submit'])) {
		$altCount = $altObj->countByFilter();

		$no=1; $r = []; $nid = [];
		$alt1 = $altObj->readByFilter();
		while ($row = $alt1->fetch(PDO::FETCH_ASSOC)){
			$alt2 = $altObj->readByFilter();
			while ($roww = $alt2->fetch(PDO::FETCH_ASSOC)) {
				$nid[$row['id_alternatif']][] = $roww['id_alternatif'];
			}
			$total = $altCount-$no;
			if ($total>=1) {
				$r[$row['id_alternatif']] = $total;
			}
			$no++;
		}

		$ni=1;
		foreach ($nid as $key => $value) {
			array_splice($nid[$key], 0, $ni++);
		}
		$ne = count($nid)-1;
		array_splice($nid, $ne, 1);

		// print_r($r);
		
		// print_r($nid);
		
		// die();

		$no=1; foreach ($r as $k => $v) {
			 $j=0; for ($i=1; $i<=$v; $i++) {
				// $rows = $altObj->readSatu($k); while ($row = $rows->fetch(PDO::FETCH_ASSOC)){
					if ($skoObj->insert($_POST[$k.$no], $_POST['nl'.$no], $_POST[$nid[$k][$j].$no], $altkriteria)) {
						// ...
					} else {
						$skoObj->update($_POST[$k.$no], $_POST['nl'.$no], $_POST[$nid[$k][$j].$no], $altkriteria);
					}

					if ($skoObj->insert($_POST[$nid[$k][$j].$no], 1/$_POST['nl'.$no], $_POST[$k.$no], $altkriteria)) {
						// ...
					} else {
						$skoObj->update($_POST[$nid[$k][$j].$no], 1/$_POST['nl'.$no], $_POST[$k.$no], $altkriteria);
					}
					$no++; $j++;
				// }
			}
		}
	}

	if (isset($_POST['hapus'])) {
		$skoObj->delete();
		echo "<script>location.href='analisa-alternatif.php'</script>";
		exit;
	}
	?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<ol class="breadcrumb">
			  <li><a href="index.php">Beranda</a></li>
			  <li><a href="analisa-alternatif.php">Analisa Nasabah</a></li>
			  <li class="active">Tabel Analisa Nasabah</li>
			</ol>
			<div class="row">
				<div class="col-md-6 text-left">
					<strong style="font-size:18pt;"><span class="fa fa-table"></span> Nasabah Menurut Kriteria</strong>
				</div>
				<div class="col-md-6 text-right">
					<form method="post">
	          <button name="hapus" class="btn btn-danger">Hapus Semua Data</button>
					</form>
				</div>
			</div>
			<br/>
			<table width="100%" class="table table-striped table-bordered">
        <thead>
					<tr>
						<th><?=$skoObj->kri?></th>
						<?php $alt1a = $altObj->readByFilter(); while ($row = $alt1a->fetch(PDO::FETCH_ASSOC)): ?>
							<th><?=$row['nama']?></th>
						<?php endwhile; ?>
					</tr>
        </thead>
				<tbody>
					<?php $alt2a = $altObj->readByFilter(); while ($baris = $alt2a->fetch(PDO::FETCH_ASSOC)): ?>
						<tr>
							<th class="active"><?=$baris['nama']?></th>
							<?php $alt3a = $altObj->readByFilter(); while ($kolom = $alt3a->fetch(PDO::FETCH_ASSOC)): ?>
								<td>
								<?php
									if ($baris['id_alternatif'] == $kolom['id_alternatif']) {
										echo '1';
										if (!$skoObj->insert($baris['id_alternatif'], '1', $kolom['id_alternatif'], $altkriteria)) {
											$skoObj->update($baris['id_alternatif'], '1', $kolom['id_alternatif'], $altkriteria);
										}
									} else {
										$skoObj->readAll1($baris['id_alternatif'], $kolom['id_alternatif'], $altkriteria);
										echo number_format($skoObj->kp, 2, '.', ',');
									}
								?>
								</td>
							<?php endwhile; ?>
						</tr>
					<?php endwhile; ?>
				</tbody>
				
				
				
				
				
				
        <tfoot>
         	<tr class="info">
						<th>Jumlah</th>
						<?php /*$jumlahBobot=[];*/ $alt4a = $altObj->readByFilter(); while ($row = $alt4a->fetch(PDO::FETCH_ASSOC)): ?>
						<th>
							<?php
							/*
								$bobotObj->readSum1($row['id_kriteria']);
								echo number_format($bobotObj->nak, 2, '.', ',');
								$bobotObj->insert3($bobotObj->nak, $row['id_kriteria']);
							*/
							
								$skoObj->readSum1($row['id_alternatif'], $altkriteria);
								echo number_format($skoObj->nak, 2, '.', ',');
								$skoObj->insert5($skoObj->nak, $row['id_alternatif'], $altkriteria);
							?>
						</th>
					<?php endwhile;?>
          </tr>
        </tfoot>
		 	</table>

			<table width="100%" class="table table-striped table-bordered">
	      <thead>
		      <tr>
	          <th>Perbandingan</th>
	          <?php $alt1b = $altObj->readByFilter(); while ($row = $alt1b->fetch(PDO::FETCH_ASSOC)): ?>
		          <th><?=$row['nama']?></th>
	          <?php endwhile; ?>
			  <th class="info">Jumlah gscsjcv</th>
	          <th class="success">Prioritas</th>
		      </tr>
	      </thead>
	      <tbody>
			
			<?php $bobots2x = $bobotObj->readAll2(); while ($baris = $bobots2x->fetch(PDO::FETCH_ASSOC)): ?>
					<tr>
					<th class="active"><?=$baris['nama'] ?>
						<?php $stmt4x = $bobotObj->readAll2(); while ($kolom = $stmt4x->fetch(PDO::FETCH_ASSOC)): ?>
							<td>
							<?php
								if ($baris['id_kriteria'] == $kolom['id_kriteria']) {
									$c = 1/$kolom['jumlah_kriteria'];
									$bobotObj->insert2($c, $baris['id_kriteria'], $kolom['id_kriteria']);
									echo number_format($c, 3, '.', ',');
								} else {
									$bobotObj->readAll1($baris['id_kriteria'], $kolom['id_kriteria']);
									$c = $bobotObj->kp/$kolom['jumlah_kriteria'];
									$bobotObj->insert2($c, $baris['id_kriteria'], $kolom['id_kriteria']);
									echo number_format($c, 3, '.', ',');
								}
								?>
							</td>
						<?php endwhile; ?>
						
						<th class="info">
							<?php
							$bobotObj->readSum2($baris['id_kriteria']);
							$j = $bobotObj->hak;
							echo number_format($j, 3, '.', ',');
							?>
						</th>
						<th class="success">
							<?php
							$bobotObj->readAvg($baris['id_kriteria']);
							$b = $bobotObj->hak;
							$bobotObj->insert4($b, $baris['id_kriteria']);
							echo number_format($b, 3, '.', ',');
							?>
						</th>
					</tr>
				<?php endwhile; ?>				
	      </tbody>

			

		  <tfoot>
				<!-- <tr class="info">
					<th>Jumlah</th>
				
						<th>
						1
						</th>
						<th>
						1
						</th>
						<th>
						1
						</th>
						<th>
						1
						</th>
						<th>
						1
						</th>
						<th>
							<?php $j = "5"; 
							echo number_format($j, 2, '.', ','); ?>	
						</th>
							<th>
						<?php $hasilj = "1"; 
							echo number_format($hasilj); ?>
				</tr> -->
				<tr class="info">
					<th>Jumlah</th>
					<?php $stmt5 = $bobotObj->readAll2(); 
					while ($row = $stmt5->fetch(PDO::FETCH_ASSOC)): ?>
						<th>
						1
						</th>
					<?php endwhile; ?>
						<th>
							<!-- <?php $hasilj = $count; 
							echo number_format($hasilj, 2, '.', ','); ?>	 -->
							5.000
						</th>
							<th>
						<?php $hasilj = "1"; 
							echo number_format($hasilj); ?>
				</tr>
			</tfoot>
		  </table>
		  
		  
<!--		<table width="30%" class="baru table-striped table-bordered">
			<thead>
			
				<th colspan="2">
				<center>Eigen Value</center>
				</th>
				<?php
				
					//bagian prioritas
					$p1 = "3.50";
					$p2 = "4.25";
					$p3 = "8.5";
					$p4 = "6.33";
					$p5 = "9.00";
					
					//bagian jumlah 5c
					$c1 = "0.284";
					$c2 = "0.277";
					$c3 = "0.140";
					$c4 = "0.174";
					$c5 = "0.125";			

					//htungan tabel h1
					$hasiln1 = $c1 * $p1;
				
					//htungan tabel h2
					$hasiln2 = $c2 * $p2;
					
					//htungan tabel h3
					$hasiln3 = $c3 * $p3;
					
					//htungan tabel h4
					$hasiln4 = $c4 * $p4;
					
					//htungan tabel h5
					$hasiln5 = $c5 * $p5;
					
				?>

			</thead>
			<tbody>
			<th>
				Character
			</th>
				<th>
							<?php
								echo number_format($hasiln1, 3, '.', ',');			
							?>								

				</th>
			</tbody>
			<tbody>	
			<th>
				Capacity
			</th>

				<th>
							<?php					
								echo number_format($hasiln2, 3, '.', ',');			
							?>								
				</th>
			</tbody>
			<tbody>		
			<th>
				Capital
			</th>
			
				<th>
							<?php					
								echo number_format($hasiln3, 3, '.', ',');			
							?>								
				</th>
			</tbody>
			<tbody>			
			<th>
				Collateral
			</th>

				<th>
							<?php					
								echo number_format($hasiln4, 3, '.', ',');			
							?>								
				</th>
			</tbody>
			<tbody>	
						<th>
				Condition
			</th>

				<th>
							<?php					
								echo number_format($hasiln5, 3, '.', ',');			
							?> 								
				</th>
			</tbody>
			<tbody>
			<th>Jumlah</th>
				<th>
						<?php $hasilj = $hasiln1 + $hasiln2 + $hasiln3 + $hasiln4 + $hasiln5 ; 
						echo number_format($hasilj, 3, '.', ','); ?>
				</th>
			</tbody>
		</table>-->

			<!-- <table width="100%" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Penjumlahan</th>
						<?php //$alt1y = $skoObj->readAll2(); while ($row = $alt1y->fetch(PDO::FETCH_ASSOC)): ?>
							<th><?//=$row['nama']?></th>
						<?php //endwhile; ?>
						<th class="info">Jumlah</th>
					</tr>
				</thead>
				<tbody>
					<?php //$sumRow = []; $alt2y = $skoObj->readAll2(); while ($baris = $alt2y->fetch(PDO::FETCH_ASSOC)): ?>
						<tr>
							<th class="active"><?//=$baris['nama'] ?></th>
							<?php //$jumlah = 0; $alt3y = $skoObj->readAll2(); while ($kolom = $alt3y->fetch(PDO::FETCH_ASSOC)): ?>
								<td>
								<?php
									// if ($baris['id_alternatif'] == $kolom['id_alternatif']) {
									// 	$c = $prioritas * 1;
									// 	echo number_format($c, 3, '.', ',');
									// 	$jumlah += $c;
									// } else {
									// 	$skoObj->readAll1($baris['id_alternatif'], $kolom['id_alternatif'], $altkriteria);
									// 	$c = $prioritas * $skoObj->kp;
									// 	echo number_format($c, 3, '.', ',');
									// 	$jumlah += $c;
									// }
									?>
								</td>
							<?php //endwhile; ?>
							<th class="info">
								<?php
								// $sumRow[$baris['id_alternatif']] = $jumlah;
								// echo number_format($jumlah, 3, '.', ',');
								?>
							</th>
						</tr>
					<?php //endwhile;?>
				</tbody>
			</table> -->

		</div>
	</div>
<?php } else {
	echo "<script>location.href='analisa-alternatif.php'</script>";
}
include_once('includes/footer.inc.php');
?>
