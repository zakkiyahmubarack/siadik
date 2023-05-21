<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Kategori Nomor HP

    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-primary">
        <div class="box-body">
            <a href="<?php echo base_url() . "/" . url_segment(1); ?>/add" class="btn btn-success" style="margin-bottom: 10px;"><span class="glyphicon glyphicon-plus"></span> Buat Baru</a>
            <?php
            //if(url_segment(2) == ""){echo js(base_url()."/".url_segment(1)."/home");}
            switch (url_segment(2)) {
                default:
            ?>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>NO.</td>
                            <th>Nama Kategori</td>
                            <th>Keterangan</td>
                            <th>
                                </td>
                        </tr>
                        <?php
                        $no = $posisi + 1;
                        $h2 = mysqli_query($koneksi, "select * from tbl_phone_category");
                        while ($d2 = mysqli_fetch_assoc($h2)) {
                            echo "<tr class='baris'>
										<td>" . $no++ . ".</td>
										<td>$d2[nama]</td>
										<td>$d2[keterangan]</td>
										<td>
											" . link_edit(base_url() . "/" . url_segment(1) . "/edit/$d2[id]") . "
											" . link_hapus(base_url() . "/" . url_segment(1) . "/hapus/$d2[id]") . "
										</td>
									  </tr>";
                        }
                        ?>
                    </table>
                <?php
                    break;
                case 'add':
                ?>
                    <form name="form1" method="post" action="<?php echo base_url() . "/" . url_segment(1) . "/simpan"; ?>" class="form">
                        <p>Nama Kategori:<br>
                            <input type="text" name="nama" class="form-control" required="true">
                        </p>
                        <p>Keterangan:<br>
                            <input type="text" name="keterangan" class="form-control" required="true">
                        </p>
                        <p>
                            <a href="<?php echo base_url() . "/" . url_segment(1); ?>" class="btn btn-warning">Kembali</a>
                            <input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
                        </p>
                    </form>
                <?php
                    break;
                case 'simpan':
                    $h3 = mysqli_query($koneksi, "insert into tbl_phone_category (nama,keterangan) 
											values ('$_POST[nama]','$_POST[keterangan]')");
                    if ($h3 == TRUE) {
                        echo js(base_url() . "/" . url_segment(1));
                    } else {
                        echo jsPesan("Erorr Query", base_url() . "/" . url_segment(1) . "/add");
                    }
                    break;
                case 'edit':
                    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "select * from tbl_phone_category  where id='" . url_segment(3) . "'"));
                ?>
                    <form name="form1" method="post" action="<?php echo base_url() . "/" . url_segment(1) . "/update"; ?>" class="form">
                        <p>Nama Kategori:<br>
                            <input type="text" name="nama" class="form-control" required="true" value="<?php echo $data['nama']; ?>">
                        </p>
                        <p>Keterangan:<br>
                            <input type="text" name="keterangan" class="form-control" required="true" value="<?php echo $data['keterangan']; ?>">
                        </p>
                        <p>
                            <a href="<?php echo base_url() . "/" . url_segment(1); ?>" class="btn btn-warning">Kembali</a>
                            <input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                        </p>
                    </form>
                <?php
                    break;
                case 'update':
                    $h3 = mysqli_query($koneksi, "UPDATE tbl_phone_category set
											nama='$_POST[nama]',
											keterangan='$_POST[keterangan]'
											where id='$_POST[id]'");
                    if ($h3 == TRUE) {
                        echo js(base_url() . "/" . url_segment(1));
                    } else {
                        echo jsPesan("Error Query", base_url() . "/" . url_segment(1) . "/edit/$_POST[id]");
                    }
                    break;
                case 'hapus':
                    $h3 = mysqli_query($koneksi, "DELETE FROM tbl_phone_category where id='" . url_segment(3) . "'");
                    if ($h3 == TRUE) {
                        echo js(base_url() . "/" . url_segment(1));
                    } else {
                        echo jsPesan('Error', base_url() . "/" . url_segment(1));
                    }
                    break;
                ?>
            <?php
            }
            ?>
        </div>
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->