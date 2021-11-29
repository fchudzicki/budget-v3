<?php
require_once "include/connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);



		$teraz = date("Ymd");
		$terazstr = strtotime( $teraz );
		$sql = "SELECT platnosc.id, platnosc.kwota, platnosc.data, nazwa_oplaty.firma, nazwa_oplaty.firma_id, platnosc.status FROM platnosc, nazwa_oplaty WHERE nazwa_oplaty.firma_id = platnosc.firma_id ORDER BY platnosc.data DESC ";
		$result = $polaczenie->query($sql);

		
		$sqla = "SELECT aktual.datzgl FROM aktual ORDER BY datzgl DESC LIMIT 1";
		$resulta = $polaczenie->query($sqla);
	


?>
<html lang="pl">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>Refresh</title>
</head>
<body>
<?php
$flag = false;
	if ($resulta->num_rows > 0) 
	{
		while($row = $resulta->fetch_assoc()) 
		{
		$dataa = $row["datzgl"];
		$dataastr = strtotime( $dataa );
		//echo $dataa;
		}
	}

	?>

			<table width="500" border="1">
  				<tbody>
    				<tr>
<?php

	
				if ($result->num_rows > 0) 
				{
					
					echo<<<END
	
				<th width="100" align="center" >kwota</th>
				<th width="100" align="center" >termin</th>
				<th width="100" align="center" >firma</th>

				</tr><tr>
END;
					
				// output data of each row
					while($row = $result->fetch_assoc()) 
					{
						
					$bgkolor = "";
					$id =  $row["id"];
					$koszt = $row["kwota"];
					$deadline = $row["data"];
					$deadlinestr = strtotime( $deadline );
					$firma = $row["firma"];
					$status = $row["status"];

					$pozostalo = (($deadlinestr - $terazstr )/86400);
						if(($pozostalo <=4) && ($status == 0))
						{
							echo<<<END

							<td width="100" align="center" bgcolor=$bgkolor>$koszt zł</td>
							<td width="100" align="center" bgcolor=$bgkolor> $deadline  <br />$pozostalo dni </td>
							<td width="100" align="center" bgcolor=$bgkolor>$firma</td>
			

							</tr><tr>
END;
							$flag = true;
						}
				
					}
				} else 
				{
				echo "0 results";
				}
	
if (($flag == true) && ($dataastr < $terazstr ))
{
	$email = "filip.chudzicki@as4you.pl";
	$imie = "Filipo";
	$nazwisko = "Chudzior";
	$mailtopic = "Opłaty";
	$tresc = "<b> Masz niezapłacone rachunki!!!</b><br /> Więcej szczegółów znajdziesz <a href='http://as4you-wroclaw.16mb.com'>TUTAJ</a>";
	include "mail/mail.php";
	$polaczenie->query("INSERT INTO aktual VALUES (NULL, '$teraz')");
$polaczenie->close();
}
if ($flag == false	)
{ echo "<h3>BRAK ZALEGŁOŚCI</h3>";

}


?>
 		</tr>
			
				</tbody>
			</table>
		</div>
		
</body>
</html>
