<!-- Bootstrap core JavaScript
     ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- jQuery 2.1.3 -->
<script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>    
<!-- DATA TABES SCRIPT -->
<script src="plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<!--<script src="validation/jquery.validate.js" type="text/javascript"></script>-->
<script>
    $(document).ready(function() {

        var page_name = document.getElementById("page_name").value;
        $("#" + page_name).addClass("active");
        
         $('#tbtable').dataTable();  
       
    });

</script>
