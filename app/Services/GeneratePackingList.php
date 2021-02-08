<?php


namespace App\Services;

use App\Models\Order;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Element\Section;
use Carbon\Carbon;

class GeneratePackingList
{
    private $phpWord;

    private $order;


    public function __construct(\App\Http\Resources\Order $order)
    {
        $languageEnGb = new Language(Language::EN_GB);
        $this->phpWord = new PhpWord();
        $this->phpWord->getSettings()->setThemeFontLang($languageEnGb);
        $this->order = $order;
    }

    private function addHeader(): Section
    {
        $section = $this->phpWord->addSection(['pageSizeW'=>true]);
        $table = $section->addTable(['alignment' => JcTable::START,'width' => 50 * 50, 'unit' => 'pct']);
        $width = 8000;
        $table->addRow();
        $table
            ->addCell($width)
            ->addImage(
                "http://factory-dashboard-api.test/sas-address.png",
                [
                    'width' => 150,
                    'height' => 150,
                ]
            );

        $table
            ->addCell('4000')
            ->addImage("http://factory-dashboard-api.test/packing-list.png",
                [
                    'alignment' => JcTable::END
                ]);
        return $section;
    }

    private function addSubHeader($section)
    {
       // $section = $this->phpWord->addSection();
        $tableDefaultWidth = 12000;
        $cellDefaultWidth = 6000;

        $section->addLine();
        $section->addText("PACKING LIST",['bold' => true,'size' => 22],['alignment' => JcTable::CENTER]);
        $section->addLine();
        $table = $section->addTable(['alignment' => JcTable::START,'width' => $tableDefaultWidth]);
        $table->addRow();
        $table
            ->addCell($cellDefaultWidth)
            ->addText("Deliver to:",['bold' => true,'size' => 12]);

        $table
            ->addCell($cellDefaultWidth)
            ->addText("Order Number : ".$this->order->merch_order_id,[],['alignment' => JcTable::END]);

        $table->addRow();
        $table
            ->addCell($cellDefaultWidth)
            ->addText("6 Forbes McCammon Drive, Swanson, Auckland");

        $table
            ->addCell($cellDefaultWidth)
            ->addText("Delivery Date : ".Carbon::createFromFormat('Y-m-d H:i:s', $this->order->delivery_date)->format('dS F y'),[],['alignment' => JcTable::END]);

        $table->addRow();
        $table
            ->addCell($cellDefaultWidth);

        $table
            ->addCell($cellDefaultWidth)
            ->addText("Delivered Boxes : ".$this->order->no_of_boxes_delivered,[],['alignment' => JcTable::END]);


    }

    public function generateDoc(): PhpWord
    {
        $section = $this->addHeader();
        $this->addSubHeader($section);
        return $this->phpWord;
    }
}
