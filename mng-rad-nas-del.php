<?php

    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	$nashost = "";
        $nashost = $_REQUEST['nashost'];


        if (isset($_POST['submit'])) {
                if (trim($nashost) != "") {
                        
                        include 'library/opendb.php';

                        // delete all attributes associated with a username
                        $sql = "DELETE FROM nas WHERE nasname='$nashost'";
                        $res = mysql_query($sql) or die('<font color="#FF0000"> Query failed: ' . mysql_error() . "</font>");

                        echo "<font color='#0000FF'>success<br/></font>";
                        include 'library/closedb.php';

                }  else {
                        echo "<font color='#FF0000'>error: no nashost was entered, please specify a nas ip/host to remove from database<br/></font>";
                        echo "
                                <script language='JavaScript'>
                                <!--
                                alert('No nas ip/host was entered, please specify a nas ip/host to remove from database');
                                -->
                                </script>
                                ";
                }
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>

<SCRIPT TYPE="text/javascript">
<!--
function toggleShowDiv(pass) {

        var divs = document.getElementsByTagName('div');
        for(i=0;i<divs.length;i++) {
                if (divs[i].id.match(pass)) {
                        if (document.getElementById) {                                         
                                if (divs[i].style.display=="inline")
                                        divs[i].style.display="none";
                                else
                                        divs[i].style.display="inline";
                        } else if (document.layers) {                                           
                                if (document.layers[divs[i]].display=='visible')
                                        document.layers[divs[i]].display = 'hidden';
                                else
                                        document.layers[divs[i]].display = 'visible';
                        } else {
                                if (document.all.hideShow.divs[i].visibility=='visible')     
                                        document.all.hideShow.divs[i].visibility = 'hidden';
                                else
                                        document.all.hideShow.divs[i].visibility = 'visible';
                        }
                }
        }
}



// -->
</script>


<title>daloRADIUS</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />

</head>
 
 
<?php
	include ("menu-mng-rad-nas.php");
?>
		
		
		<div id="contentnorightbar">
		
				<h2 id="Intro"><a href="#"><?php echo $l[Intro][mngradnasdel.php] ?></a></h2>
				
                                <p>
                                <?php echo $l[captions][mngradnasdel] ?>
                                <br/><br/>
                                </p>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border='2' class='table1'>
<tr><td>
                                                <?php if (trim($nashost) == "") { echo "<font color='#FF0000'>";  }?>
                                                <b><?php echo $l[FormField][mngradnasnew.php][NasIPHost] ?></b>
</td><td>												
                                                <input value="<?php echo $nashost ?>" name="nashost"/><br/>
                                                </font>
</td></tr>
</table>
                                                <br/><br/>
<center>
                                                <input type="submit" name="submit" value="<?php echo $l[buttons][apply] ?>"/>
</center>
                                </form>
				
		</div>
		
		<div id="footer">
		
								<?php
        include 'page-footer.php';
?>

		
		</div>
		
</div>
</div>


</body>
</html>
