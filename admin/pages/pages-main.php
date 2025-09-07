<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Dashboard <small>Pages</small> </h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
      <li>Pages</li>
      <li class="active">List</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <!-- /.box -->
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">Pages List</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="pages-table" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>S.N</th>
                  <th>Title</th>
                  <th>Sub Title</th>
                  <th>Updated On</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $result =  $mydb->select("tbl_about");
              $count = 1;
              if($mydb->totalRows > 0){
                foreach ($result as $row):
              ?>
                <tr>
                  <td class="text-center"><?=$count;?>.</td>
                  <td><b><?=$row['title'];?></b></td>
                  <td><i><?=$row['sub_title'];?></i></td>
                  <td class="text-center"><?=date("F d, Y", strtotime($row['updated']));?><br /><small style="font-style:italic;"><?php echo ($row['updated_by'] ? "by " .$row['updated_by'] : "" ); ?></small></td>
                  <td class="text-center">
                  <!--View-->
                  <a href="./?page=pages&id=<?=$row['id'];?>"><button type="button" class="btn btn-success"><i class="fa fa-eye"></i> View Page</button></a>&nbsp;&nbsp;
                  </td>
                </tr>
              <?php
                  $count++;
                  endforeach;
                  }
              ?>
              </tbody>
              <tfoot>
                <tr>
                  <th class="text-center" colspan="5"><?php echo C_NAME; ?> Pages Table</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
  </section>
</div>
<!-- DataTables -->
<script type="text/javascript">
  $(function () {
	$('#pages-table').DataTable({
	  "paging": true,
	  "lengthChange": false,
	  "searching": true,
	  "ordering": false,
	  "info": true,
	  "autoWidth": false
	});
  });
</script>
