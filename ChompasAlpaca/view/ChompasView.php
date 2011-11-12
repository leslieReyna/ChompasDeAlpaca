<?php
require_once '/../control/ChompasControl.php';
require_once '/../control/PedidosControl.php';
class ChompasView {

    public function ejecutar(){
        $miControlChompa = new ChompasControl();
        $miControlPedido = new PedidosControl();
        if(!$_POST['venta']){
            $listaChompa = $miControlChompa->getAll();
            $this->_mostrarFormularioVentas($listaChompa);
        }
        else{
           $this->_calcularCantidad();
           $listaChompas = $miControlChompa->getAll();
           $stockPedido = $this->_stockMinimo();
           $this->_mostrarResultadoVenta($listaChompas, $stockPedido);
        }
        if($stockPedido!= null){            
            foreach ($stockPedido as $obj) {               
                $fecha =date("d/m/Y");
                $chompaID=$obj->getID();
                $insumo = $obj->getInsumo();
                $cantidad = $obj->getCantidadxPedido();
                $estado = 'pendiente';
                $miControlPedido->insertar($fecha, $chompaID, $insumo, $cantidad, $estado);
            }
        }
        if($_GET['opcion']){
            $opcion = $_GET['opcion'];
            switch ($opcion) {
                case 2:
                    $listado = $pedidoControl->getAllPedidos();
                    $this->_mostrarListaPedidos($listado);
                    break;
                case 3:
                    $listadoChompa=$chompaControl->getAll();
                    $this->_mostrarStock($listadoChompa);
                    break;
                case 4:
                    $listaChompa=$chompaControl->getAll();
                    $this->_mostrarFormularioVentas($listaChompa);
                    break;

                default:
                    break;
            }
        }
    }

    private function _stockMinimo(){
        $miControl= new ChompasControl();
        $lista = $miControl->getAll();
        foreach ($lista as $obj) {
            if($obj->getCantidadActual()<= $obj->getStockMinimo()){
                $array[]=$obj;
            }
        }
        return $array;
    }

    private function _calcularCantidad(){
         $miControlChompa = new ChompasControl();
         $chompa = new chompa();
           $lista = $miControlChompa->getAll();
           $id =1;
           while($id <= count($lista)) {
               foreach($lista as $chompita){
              $cantComprada = $_POST["cantidad$id"];
              $cantidad =$chompita->getCantidadActual()-$cantComprada;
              $miControlChompa->modificar($id, $cantidad);
              $id++;
              $cantidad =0;
               }
           }
           
    }

    private function _mostrarFormularioVentas($listaChompa){
        require_once 'InicioVenta.php';
    }

    private function _mostrarResultadoVenta($listaChompas, $stockPedido){
        require_once 'ResultadoVentas.php';
    }

     private function _mostrarListaPedidos($lista){
        require_once 'ListaPedidos.php';
    }
    private function _mostrarStock($lista){
        require_once 'ListaStock.php';
    }
}

$miView = new ChompasView();
$miView->ejecutar();
?>
