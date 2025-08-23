<?php
namespace jdgOpenCode\verifactu;
use DateTime;

class VeriFactuQr
{
    /**
     * Generates the QR code URL for invoice verification.
     * @param bool $productionMode True for production environment, false for pre-production.
     * @param string $nif The NIF of the issuer.
     * @param string $serienum The invoice series number.
     * @param DateTime $invoiceDate The date of the invoice.
     * @param float $amount The total amount of the invoice.
     * @return string The generated QR code URL.
     */
    public static function Url(bool $productionMode, string $nif, string $serienum, DateTime $invoiceDate, float $amount):string
    {
        $baseUrl = $productionMode ? 'https://www2.agenciatributaria.gob.es/wlpl/TIKE-CONT/ValidarQR' : 'https://prewww2.aeat.es/wlpl/TIKE-CONT/ValidarQR';
        $formattedDate = $invoiceDate->format('d-m-Y');
        $formattedAmount = number_format($amount, 2, '.', '');
        while (substr($formattedAmount, -1) === '0' && strpos($formattedAmount, '.') !== false) {
            $formattedAmount = substr($formattedAmount, 0, -1);
        } 
        $encodedSerienum = urlencode($serienum);
        return sprintf('%s?nif=%s&numserie=%s&fecha=%s&importe=%s', $baseUrl, $nif, $encodedSerienum, $formattedDate, $formattedAmount);
    }
}