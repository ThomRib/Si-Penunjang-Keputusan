<?php

include_once('includes/header.inc.php');
include_once('includes/bobot.inc.php');
include_once('includes/kriteria.inc.php');

$bobotObj = new Bobot($db);
$count = $bobotObj->countAll();

if(isset($_POST['submit'])){
	$kriteriaObj = new Kriteria($db);
	$kriteriaCount = $kriteriaObj->countAll();

	$r = [];
	$kriterias = $kriteriaObj->readAll();
	while ($row = $kriterias->fetch(PDO::FETCH_ASSOC)) {
		$kriteriass = $kriteriaObj->readSatu($row['id_kriteria']);
		while ($roww = $kriteriass->fetch(PDO::FETCH_ASSOC)) {
			$pcs = explode("C", $roww['id_kriteria']);
			$c = $kriteriaCount - $pcs[1];
		}
		if ($c>=1) {
			$r[$row['id_kriteria']] = $c;
		}
	}

	$no=1;
	foreach ($r as $k => $v) {
		for ($i=1; $i<=$v; $i++) {
			$pcs = explode("C", $k);
			$nid = "C".($pcs[1]+$i);

			if ($bobotObj->insert($_POST[$k.$no], $_POST['nl'.$no], $_POST[$nid.$no])) {
				// ...
			} else {
				$bobotObj->update($_POST[$k.$no], $_POST['nl'.$no], $_POST[$nid.$no]);
			}

			if ($bobotObj->insert($_POST[$nid.$no], 1/$_POST['nl'.$no], $_POST[$k.$no])) {
				// ...
			} else {
				$bobotObj->update($_POST[$nid.$no], 1/$_POST['nl'.$no], $_POST[$k.$no]);
			}
			$no++;
		}
	}
}

if (isset($_POST['hapus'])) {
	$bobotObj->delete();
	header("location: analisa-kriteria.php");
}
?>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<ol class="breadcrumb">
		  <li><a href="index.php">Beranda</a></li>
		  <li><a href="analisa-kriteria.php">Analisa Kriteria</a></li>
		  <li class="active">Tabel Analisa Kriteria</li>
		</ol>
		<div class="row">
			<div class="col-md-6 text-left">
				<strong style="font-size:18pt;"><span class="fa fa-table"></span> Perbandingan Kriteria</strong>
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
          <th>Antar Kriteria</th>
          <?php $bobots1 = $bobotObj->readAll2(); while ($row = $bobots1->fetch(PDO::FETCH_ASSOC)): ?>
            <th><?=$row['nama_kriteria'] ?></th>
          <?php endwhile; ?>
        </tr>
      </thead>
      <tbody>
				<?php $bobots2 = $bobotObj->readAll2(); while ($baris = $bobots2->fetch(PDO::FETCH_ASSOC)): ?>
          <tr>
            <th class="active"><?=$baris['nama_kriteria'] ?></th>
            <?php $bobots3 = $bobotObj->readAll2(); while ($kolom = $bobots3->fetch(PDO::FETCH_ASSOC)): ?>
              <td>
              	<?php
								if ($baris['id_kriteria'] == $kolom['id_kriteria']) {
              		echo '1';
              		if ($bobotObj->insert($baris['id_kriteria'], '1', $kolom['id_kriteria'])) {
										// ...
									} else {
							  		$bobotObj->update($baris['id_kriteria'], '1', $kolom['id_kriteria']);
							  	}
              	} else {
              		$bobotObj->readAll1($baris['id_kriteria'], $kolom['id_kriteria']);
              		echo number_format($bobotObj->kp, 2, '.', ',');
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
					<?php $stmt5 = $bobotObj->readAll2(); while ($row = $stmt5->fetch(PDO::FETCH_ASSOC)): ?>
						<th>
							<?php
								$bobotObj->readSum1($row['id_kriteria']);
								echo number_format($bobotObj->nak, 2, '.', ',');
								$bobotObj->insert3($bobotObj->nak, $row['id_kriteria']);
							?>
						</th>
					<?php endwhile; ?>
				</tr>
			</tfoot>
    </table>

		<table width="100%" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Perbandingan</th>
					<?php $bobots1x = $bobotObj->readAll2(); while ($row2x = $bobots1x->fetch(PDO::FETCH_ASSOC)): ?>
						<th><?=$row2x['nama_kriteria'] ?></th>
					<?php endwhile; ?>
					<th class="info">Jumlah</th>
					<th class="success">Prioritas</th>
				</tr>
			</thead>
			<tbody>
				<?php $bobots2x = $bobotObj->readAll2(); while ($baris = $bobots2x->fetch(PDO::FETCH_ASSOC)): ?>
					<tr>
						<th class="active"><?=$baris['nama_kriteria'] ?>
						</th>
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
				<tr class="info">
					<th>Jumlah jguguh</th>
					<?php $stmt5 = $bobotObj->readAll2(); 
					while ($row = $stmt5->fetch(PDO::FETCH_ASSOC)): ?>
						<th>
						1
						</th>
					<?php endwhile; ?>
						<th>
							<?php $hasilj = $count; 
							echo number_format($hasilj, 3, '.', ','); ?>	
						</th>
							<th>
						<?php $hasilj = "1"; 
							echo number_format($hasilj); ?>
				</tr>
			</tfoot>




		</table>

		<table width="30%" class="baru table-striped table-bordered">
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
		</table>

		<table width="100%" class="table table-striped table-bordered">
			<thead hidden>
				<tr>
					<th>Penjumlahan</th>
					<?php $bobots1y = $bobotObj->readAll2(); while ($row = $bobots1y->fetch(PDO::FETCH_ASSOC)): ?>
						<th><?=$row['nama_kriteria'] ?></th>
					<?php endwhile; ?>
					<th class="info">Jumlah</th>
				</tr>
			</thead>
			<tbody hidden>
				<?php $sumRow = []; $bobots2y = $bobotObj->readAll2(); while ($baris = $bobots2y->fetch(PDO::FETCH_ASSOC)): ?>
					<tr>
						<th class="active"><?=$baris['nama_kriteria'] ?></th>
						<?php $jumlah = 0; $bobots3y = $bobotObj->readAll2(); while ($kolom = $bobots3y->fetch(PDO::FETCH_ASSOC)): ?>
							<td>
							<?php
								if ($baris['id_kriteria'] == $kolom['id_kriteria']) {
									$c = $kolom['bobot_kriteria'] * 1;
									echo number_format($c, 3, '.', ',');
									$jumlah += $c;
								} else {
									$bobotObj->readAll1($baris['id_kriteria'], $kolom['id_kriteria']);
									$c = $kolom['bobot_kriteria'] * $bobotObj->kp;
									echo number_format($c, 3, '.', ',');
									$jumlah += $c;
								}
								?>
							</td>
						<?php endwhile; ?>
						<th class="info">
							<?php
							$sumRow[$baris['id_kriteria']] = $jumlah;
							echo number_format($jumlah, 3, '.', ',');
							?>
						</th>
					</tr>
				<?php endwhile;?>
			</tbody>
		</table>

		<table width="100%" class="table table-striped table-bordered">
			<thead hidden>
				<tr>
					<th>Rasio Konsistensi</th>
					<th class="info">Jumlah</th>
					<th class="success">Prioritas</th>
					<th class="warning">Hasil</th>
				</tr>
			</thead>
			<tbody hidden>
				<?php $total=0; $bobots1z = $bobotObj->readAll2(); while ($row1 = $bobots1z->fetch(PDO::FETCH_ASSOC)): ?>
					<tr>
						<th class="active"><?=$row1["nama_kriteria"]?></th>
						<th class="info"><?=number_format($sumRow[$row1["id_kriteria"]], 3, '.', ',')?></th>
						<th class="success"><?=number_format($row1["bobot_kriteria"], 3, '.', ',');?></th>
						<?php $jumlah = $sumRow[$row1["id_kriteria"]] + $row1["bobot_kriteria"]; ?>
						<th class="warning"><?=number_format($jumlah, 3, '.', ',');?></th>
						<?php $total += $jumlah; ?>
					</tr>
				<?php endwhile; ?>
			</tbody>
			<tfoot hidden>
				<tr class="danger">
					<th colspan="3">Rata-rata</th>
					<th><?php $rata = $total/$count; echo number_format($rata, 3, '.', ','); ?></th>
				</tr>
			</tfoot>
		</table>

		<table width="100%" class="table table-striped table-bordered">
			<tbody>
				<tr>
					<th>N (kriteria)</th>
					<td><?=$count?></td>
				</tr>
				<tr>
					<th>Hasil Akhir (X maks)</th>
					<td><?=number_format($hasilj, 3, '.', ',');?></td>
				</tr>
				<tr>
					<th>IR</th>
					<td><?php echo $ir = $bobotObj->getIr($count); ?></td>
				</tr>
				<tr>
					<th>CI</th>
					<td><?php $ci = ($hasilj-$count)/($count-1); 
					echo number_format($ci, 2, '.', ',');?></td>
				</tr>
				<tr>
					<th>CR</th>
					<td><?php $cr = $ci/$ir; echo number_format($cr, 2, '.', ',');?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<?php include_once('includes/footer.inc.php'); ?>
