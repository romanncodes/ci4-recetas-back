<?php

namespace App\Controllers;
use App\Models\ProductModel;
use App\Models\SupplyModel;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\Pdf;

class Home extends Auth{
    protected $productModel;
    protected $supplyModel;
    use ResponseTrait;

    public function __construct(){
        $this->productModel = new ProductModel();
        $this->supplyModel = new SupplyModel();
    }

    public function index(){
       $list = $this->productModel->findAll(); 
        echo view('welcome_message',["list"=>$list]);
      //  $this->generarPDF();
       
    }

    public function generarPDF() {
        $pdf=$this->configObjectPdf();

        // Añadir una página
        // Este método tiene varias opciones, consulta la documentación para más información.
        $pdf->AddPage();
        //preparamos y maquetamos el contenido a crear
        $html = '';
        $html .= "<style type=text/css>";
		$html .= ".img{height:500px;}";
		$html .= ".logo{height:170px}";
		$html .= "div{border:1px solid black; text-align:center; width:100%;padding:50px}";
		$html .= "</style>";
		$logo=base_url()."/assets/panaderia.jpeg";

		$list = $this->productModel->findAll(); 

        foreach ($list as $index=>$item) {
			$html .= '<div>'; 
			$html .= '<br><img class="logo" src="'.$logo.'"/><br>';
			$html .= '<img class="img" src="'.$item['pr_img'].'"/>';
			$html .= '<h1>'.$item['pr_name'].'</h1>';
			$html .= '<h2>Precio: '.$item['pr_price'].'</h2>';
			$html .= '</div>';

            $pdf->writeHTML($html, true, false, false, false, '');
            $pdf->AddPage();
            $html = '';
			$html .= "<style type=text/css>";
			$html .= ".img{height:500px;}";
			$html .= ".logo{height:170px}";
			$html .= "div{border:1px solid black; text-align:center; width:100%;padding:50px}";
			$html .= "</style>";
        }
        
        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTML($html, true, false, false, false, '');
        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = "Productos.pdf";
        $pdf->Output("$nombre_archivo", 'I');// esta linea Muestra el PDF en el browser
        exit();
    }



    public function getRecipes($id){
        $db      = \Config\Database::connect();
        $builder = $db->table('recetas r');
        $builder->select('p.pr_name, i.in_name, r.re_quantity, i.in_unity, i.in_price, i.in_quantity');
        $builder->join('insumos i', "r.re_supply = i.in_id");
        $builder->join('productos p', "r.re_product = p.pr_id");
        $builder->where('p.pr_id', $id);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function generarPDFDetails(){
        $this->headersConfig();
        $pdf = $this->configObjectPdf();
        $products = $this->productModel->findAll(); 

        $logo=base_url()."/assets/panaderia.jpeg";
        
        foreach ($products as $index=>$item) {
            $recipes = $this->getRecipes($item["pr_id"]);
            $pdf->AddPage();
            $html = '';
            $html .= '<img  src="'.$logo.'"/>';
			$html .= '<h1>'.$item['pr_name'].'</h1>';
            $html .= '<table border="1">';
            $html .= '<tr align="center"><th>Insumo</th><th>Cantidad</th><th>Unidad</th><th>Costo</th></tr>';
            $sum=0;
            foreach ($recipes as $key=>$recipe) {
                $sum += intval($recipe['in_price']/$recipe['in_quantity']*$recipe['re_quantity']);
                $html .= '<tr>';
                $html .= '<td>'.$recipe['in_name'].'</td>';
                $html .= '<td align="center">'.$recipe['re_quantity'].'</td>';
                $html .= '<td align="center">'.$recipe['in_unity'].'</td>';
                $html .= '<td align="right">'.intval($recipe['in_price']/$recipe['in_quantity']*$recipe['re_quantity']).'</td>';
                $html .= '</tr>';
            }
            $html .= '<tr>';    
            $html .= '<td></td><td></td><td></td>';
            $html .= '<td align="right">'.$sum.'</td>';
            $html .= '</tr>';
            $html .= '</table>';
            $pdf->writeHTML($html, true, false, false, false, '');
        }
        
        $nombre_archivo = "Productos-Detalle.pdf";
        $pdf->Output("$nombre_archivo", 'I');// esta linea Muestra el PDF en el browser
        exit();
    }


    public function generarPDFSupply(){
        $this->headersConfig();
        $pdf = $this->configObjectPdf();
        $db      = \Config\Database::connect();
        $builder = $db->table('insumos');
        $builder->select('*');
        $query = $builder->get();
        $products = $query->getResultArray();

        $logo=base_url()."/assets/panaderia.jpeg";
        $pdf->AddPage();
        $html = '';
        $html .= '<img  src="'.$logo.'"/>';
        $html .= '<h3>Reporte de Insumos</h3> <br>';
        $html .= '<table border="1">';
        $html .= '<tr align="center"><th>Nombre</th><th>Unidad</th><th>Cantidad</th><th>Precio</th></tr>';
        foreach ($products as $index=>$item) {
            $html .= '<tr>';
            $html .= '<td>'.$item['in_name'].'</td>';
            $html .= '<td align="center">'.$item['in_unity'].'</td>';
            $html .= '<td align="center">'.$item['in_quantity'].'</td>';
            $html .= '<td align="right">'.$item['in_price'].'</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, false, false, '');
        $nombre_archivo = "Insumos.pdf";
        $pdf->Output("$nombre_archivo", 'I');// esta linea Muestra el PDF en el browser
        exit();
    }
   

     public function configObjectPdf(){
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        //$pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Roman Gajardo');
        $pdf->SetTitle('REPORTE');
        $pdf->SetSubject('Reporte');
        $pdf->SetKeywords('PDF Marval Store');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        // datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
        $pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));

        // datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        //relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // ---------------------------------------------------------
        // establecer el modo de fuente por defecto
        $pdf->setFontSubsetting(true);
        // Establecer el tipo de letra
        //Si tienes que imprimir carácteres ASCII estándar, puede utilizar las fuentes básicas como
        // Helvetica para reducir el tamaño del archivo.
        $pdf->SetFont('helvetica', '', 12, '', true);
        return $pdf;
    }

}
