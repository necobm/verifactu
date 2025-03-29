<?php
namespace jdg\Verifactu;

class VeriFactuCertificateHelper
{
    /**
     */
    public static function stringP12toPEM(string $certificateP12, string $passphrase) :string {
        openssl_pkcs12_read($certificateP12, $certs, $passphrase);
        return $certs['cert'].$certs['pkey'];
    }

    public static function fileP12toPEM(string $certificateP12_filePath, string $passphrase, string|null $output = null) :string|bool {
        $certificateP12 = file_get_contents($certificateP12_filePath);
        if ($output==null) {
            $output = tempnam(sys_get_temp_dir(), 'cert_');
            if ($output === false) {
                return false;
            }
        }
        $cert = self::stringP12toPEM($certificateP12, $passphrase);
        if (file_put_contents($output, $cert) === false) {
            unlink($output);
            return false;
        }
        return $output;
    }


}
