<?php


namespace App\Services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\SimpleType\JcTable;
use Carbon\Carbon;

class GeneratePackingList
{
    private $phpWord;

    private $order;

    private $defaultTableStyle = [];

    private $section;

    public function __construct($order = [])
    {
        $languageEnGb = new Language(Language::EN_GB);
        $this->phpWord = new PhpWord();
        $this->phpWord->getSettings()->setThemeFontLang($languageEnGb);
        $this->order = $order;
        $this->defaultTableStyle = [
            'borderSize' => 6,
            'borderColor' => '000e14',
            'cellMargin' => 80,
            'alignment' => JcTable::CENTER,
            'cellSpacing' => 50
        ];
    }

    private function addHeaderSection()
    {
        $this->section = $this->phpWord->addSection(['pageSizeW' => true]);
        $table = $this->section
            ->addTable(
                [
                    'alignment' => JcTable::START,
                    'width' => 50 * 50,
                    'unit' => 'pct'
                ]);
        $width = 8000;
        $table->addRow();
        $table
            ->addCell($width)
            ->addImage(
                "https://backend.sas.co.nz/sas-address.png",
                [
                    'width' => 150,
                    'height' => 150,
                ]
            );

        $table
            ->addCell('4000')
            ->addImage("https://backend.sas.co.nz/packing-list.png",
                [
                    'alignment' => JcTable::END
                ]);
    }

    private function addSubHeaderSection()
    {
        $tableDefaultWidth = 12000;
        $cellDefaultWidth = 6000;

        //$this->section->addLine();
        $this->section->addText("PACKING LIST", ['bold' => true, 'size' => 22], ['alignment' => JcTable::CENTER]);
        //$this->section->addLine();
        $table = $this->section->addTable(['alignment' => JcTable::START, 'width' => $tableDefaultWidth]);
        $table->addRow();
        $table
            ->addCell($cellDefaultWidth)
            ->addText("Deliver to:", ['bold' => true, 'size' => 12]);

        $table
            ->addCell($cellDefaultWidth)
            ->addText("Order Number : " . $this->order['merch_order_id'], ['bold' => true, 'size' => 12], ['alignment' => JcTable::END]);

        $table->addRow();
        $table
            ->addCell($cellDefaultWidth)
            ->addText($this->order['customer_name'] . "\n" . $this->order['shipping_address']);

        $table
            ->addCell($cellDefaultWidth)
            ->addText(
                "Delivery Date : " . Carbon::createFromFormat('Y-m-d H:i:s', $this->order['delivery_date'])->format('dS F Y')
                . "\n"
                . "Delivered Boxes : " . $this->order['no_of_boxes_delivered'],
                [],
                ['alignment' => JcTable::END]
            );

    }

    private function addItemHeaderSection()
    {
        $tableHeaderStyle = ['size' => 11, 'bold' => true,];
        $this->section->addLine();

        $table = $this->section->addTable($this->defaultTableStyle);
        $table->addRow();
        $table
            ->addCell(4500)
            ->addText("Product Code", $tableHeaderStyle);

        $table
            ->addCell(4000)
            ->addText("Product Name", $tableHeaderStyle);

        $table
            ->addCell(1300)
            ->addText("QTY", $tableHeaderStyle);

        $table
            ->addCell(2300)
            ->addText("Delivered QTY", $tableHeaderStyle);

        return $table;
    }

    private function addItemBodySection($table)
    {
        $orderItems = $this->order['items'];
        if (isset($orderItems)) {
            foreach ($orderItems as $orderItem) {
                $table->addRow();
                $table->addCell(4500)->addText($orderItem['product_code']);
                $table->addCell(4000)->addText($orderItem['product_title']);
                $table->addCell(1300)->addText($orderItem['quantity']);
                $table->addCell(2300)->addText($orderItem['delivered_qty']);
                $table->addRow();

                if (isset($orderItem['order_item_variations'])) {
                    $variation_str = "";
                    foreach ($orderItem['order_item_variations'] as $index => $variations) {
                        if ($index > 0) {
                            $variation_str .= "\n";
                        }
                        if (isset($variations['attribute_name'])) {
                            $variation_str .= $variations['attribute_name'] . " : ";
                            $variation_str .= $variations['attribute_value_name'];

                            if (isset($variations['other_attributes'])) {
                                $variation_str .= " / ";
                                foreach ($variations['other_attributes'] as $key => $values) {
                                    $variation_str .= ucwords(str_replace("_", " ", $key)) . " : ";
                                    $variation_str .= implode(",", $values);
                                }

                            }
                            $variation_str .= " / Qty : " . $variations['qty'];
                            $variation_str .= " / Delivered Qty : " . $variations['delivered_qty'];
                        }
                    }

                    $table->addCell(null, ['gridSpan' => 4])
                        ->addText($variation_str);
                }
            }
        }


    }

    public function generateDoc(): PhpWord
    {
        Settings::setZipClass(Settings::PCLZIP);
        $section = $this->addHeaderSection();
        $section = $this->addSubHeaderSection($section);
        $table = $this->addItemHeaderSection($section);
        $this->addItemBodySection($table);
        return $this->phpWord;
    }
}
