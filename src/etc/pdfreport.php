<?php
//call the FPDF library
require('//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/fpdf/fpdf.php');

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';
   
    session_start();

    if($_SESSION['user_email']==""){
        header('location:../view/index.php');           
    }

$control = new ControlLog();

if(isset($_GET['daterange'])){
    $dates = explode('-',$_GET['daterange']); // dates

    $logs = $control->FetchReportData($dates[0],$dates[1]);
}

//$select = $pdo->prepare("select * from tbl_invoice where invoice_id = $id");
//$select->execute();

//$row = $select->fetch(PDO::FETCH_OBJ);

//A4 width: 219mm
//default margin: 10mm each side
//writable horizontal: 219-10*2 = 199mm

//create pdf object
$pdf = new FPDF('P','mm','A4');
//string orientation (P or L) - portrait or landscape
// string unit (pt, mm, cm, and in) - measure unit
// mixed format - A3, A4, A5, Letter and Legal - format of pages

//add new page
$pdf->AddPage();
//$pdf->SetFillColor(123,255,234);
$pdf->SetFont('Arial','B',16); // I - italic U underline B bold
//$pdf->Cell(80,10,'Hello world',1,0,'C',true,'www.google.com');
$pdf->Cell(80,10,utf8_decode('JW Imobiliária'),0,0,'');

$pdf->SetFont('Arial','B',13);
$pdf->Cell(112,10,utf8_decode('Controle de Chaves de Imóveis'),0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(80,5,utf8_decode('Endereço: Marcílio Dias 1101, Bagé RS'),0,0,'');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(112,5,utf8_decode('Relatório de Movimentações'),0,1,'C');


$pdf->SetFont('Arial','',8);
$pdf->Cell(80,5,'Telefone: 3242-1647',0,0,'');

$pdf->SetFont('Arial','I',10);
$pdf->Cell(112,10,utf8_decode('Período: ').$dates[0] .' a '. $dates[1],0,1,'C');
//$pdf->Cell(112,5,'Date: '.$row->order_date,0,1,'C');

$pdf->SetFont('Arial','',8);
//$pdf->Cell(80,5,'Email Adress: jiovana@unipampa.edu.br',0,1,'');
//$pdf->Cell(80,5,'Website: unipampa.edu.br',0,0,'');

//line(x1,y1,x2,y2); coordinates
$pdf->Line(5,38,205,38); // prints a line in the page
$pdf->Line(5,39,205,39);

$pdf->Ln(14); //break the line


$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(208,208,208);
$pdf->Cell(25,7,'Data',1,0,'C',true);// true to background color
$pdf->Cell(25,7,utf8_decode('Usuário'),1,0,'C',true);
$pdf->Cell(25,7,utf8_decode('Chave'),1,0,'C',true);
$pdf->Cell(25,7,utf8_decode('Operação'),1,0,'C',true);
$pdf->Cell(90,7,utf8_decode('Descrição'),1,1,'C',true);

//////////////////////////////
foreach($logs as $log){
    $pdf->SetFont('Arial','',9);
    $row_date = date_create( $log['date'] );
    $row_date = date_format( $row_date, 'd/m/Y' );
    $pdf->Cell(25,12,$row_date,1,0,'C');
    $pdf->Cell(25,12,$log['nome'],1,0,'C');
    $pdf->Cell(25,12,$log['gancho'],1,0,'C');
    $pdf->Cell(25,12,utf8_decode($log['operation']),1,0,'C');
    if ($log['operation']=='Devolução'){
        $pdf->MultiCell(90,12,utf8_decode($log['description']),1,1,false); 
    } else {
         $pdf->MultiCell(90,6,utf8_decode($log['description']),1,1,false); 
    }
   
}





//output the result
$pdf->Output();


?>