<?php
//ob_start();
header("Content-type: text/html;charset=utf-8");

class Excels
{
	/**
	 * @export 导出 
	 */
	private $_ci;
	private $data = array();
	private $title = array();

	public function __construct()
    {
		
	 	$_ci =& get_instance();
		$_ci->load->library('phpexcel');
		$_ci->load->library('PHPExcel/iofactory');
	
    }
	// 接收 数据数组
	public function exports($data,$title)
	{
	$objPHPExcel = new PHPExcel();
		$this->data = $data;
		$this->title = $title;

		//设置属性
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                     ->setLastModifiedBy("Maarten Balliauw")
                                     ->setTitle("Office 2007 XLSX Test Document")
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");

									
								    $objPHPExcel->getActiveSheet()->fromArray($this->title, NULL, 'A1');

									/*---------------------对应栏目数据，取数据循环填充-----------------------*/

								   $objPHPExcel->getActiveSheet()->fromArray($this->data, NULL, 'A2');
									

								
									/*---------------------设置列的宽度-----------------------*/
				
									$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
									$objPHPExcel->getActiveSheet() ->getColumnDimension('B')->setWidth(16);
									$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
									$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
									$objPHPExcel->getActiveSheet()->getColumnDimension('e')->setWidth(16);
									$objPHPExcel->getActiveSheet()->getColumnDimension('f')->setWidth(15);
									$objPHPExcel->getActiveSheet()->getColumnDimension('g')->setWidth(16);
									$objPHPExcel->getActiveSheet()->getColumnDimension('h')->setWidth(16);
									$objPHPExcel->getActiveSheet()->getColumnDimension('i')->setWidth(16);
									$objPHPExcel->getActiveSheet()->getColumnDimension('j')->setWidth(16);
									$objPHPExcel->getActiveSheet()->getColumnDimension('k')->setWidth(16);
									

								    // 设置字体
									$objPHPExcel->getActiveSheet()->getStyle( 'A1:k1')->applyFromArray(
									 array(
										   'font'    => array ('bold' => true)
										)
									);
									// 字体大小
									$objPHPExcel->getActiveSheet()->getStyle( 'A1:k1')->getFont()->setSize(14);
									
									// 栏目背景颜色 
									$objPHPExcel->getActiveSheet()->getStyle( 'A1:k1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
									$objPHPExcel->getActiveSheet()->getStyle( 'A1:k1')->getFill()->getStartColor()->setARGB('rsssdff');
					
									// 文字居中
									$objStyleA1 = $objPHPExcel->getActiveSheet()->getStyle('A1:k100'); 							 
									$objAlignA1 = $objStyleA1->getAlignment();   
									$objAlignA1->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    //左右居中   
									$objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中  
								    $objPHPExcel->getActiveSheet()->setTitle('Simple');
 
		// 清除缓冲
		ob_clean();

        //操作句柄 输出文件
        $objPHPExcel->setActiveSheetIndex(0);
        $name = date("Y-m-d H:i");
        $name .= "order.xls";

		header('Content-Type: application/vnd.ms-excel; charset=utf-8');  
		header('Pragma:public');
		header('Content-Type:application/x-msexecl;name="'.$name.'"');
		header('Content-Disposition:inline;filename="'.$name.'"');
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
		exit;
	}

}
?>
　