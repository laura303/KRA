<?php
function createPDF($licence, $payment){
	global $l, $BASE_URL;
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor($BASE_URL);
	$pdf->SetTitle('Reports');
	
	// set default header data
	$pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, '');
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	
	// ---------------------------------------------------------
	
	// set font
	$pdf->SetFont('dejavusans', '', 10);
	
	// add a page
	$pdf->AddPage();
	
	$html = '
	<table border="0" cellspacing="3" cellpadding="4">
		<thead>
		</thead>
		<tr>
			<th colspan="4">KRA Reports</th>
		</tr>
		<tr>
			<td colspan="4"><center><H1>Payment E-Slip</H1></center></td>
		</tr>
		<tr>
			<td colspan="4"><H3>'.$licence->user->username.': Email: '.$licence->user->email.'</H3></td>
		</tr>
		<tr>
			<td colspan="1">Licence Number:</td>
			<td colspan="3">'.$licence->licence_number.'</td>
		</tr>
		<tr>
			<td colspan="1">Licence Type:</td>
			<td colspan="3">'.$licence->type.'</td>
		</tr>
		<tr>
			<td colspan="1">Mpesa Code:</td>
			<td colspan="3">'.$payment->mpesa_code.'</td>
		</tr>
		<tr>
			<td colspan="1">Amount Received:</td>
			<td colspan="3">'.$payment->amount_paid.'</td>
		</tr>
		<tr>
			<td colspan="1">Time Received:</td>
			<td colspan="3">'.date('d/m/Y H:i:s', $payment->time_of_transaction).'</td>
		</tr>
		<tr>
			<td colspan="1">Expiry Date:</td>
			<td colspan="3">'.date('d/m/Y H:i:s', $payment->expiry_date).'</td>
		</tr>
		<tr>
			<td colspan="4"><center>Generated E-slip</center></td>
		</tr>
	</table>';
	
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->lastPage();
	$pdf->Output("$licence->user->username-$licence->licence_number-E-slip".date('d/m/Y H:i:s').'.pdf', 'I');
}
