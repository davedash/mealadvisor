<?php 



function strip_namespace($name)
{
    if (($position=strpos($name, ":")) !== FALSE)
    {
        $name = substr($name, $position + 1);
    }
    return ($name);
}


function infocard_post_decrypt($tokenContent, &$token, $settings)
{

    $error = NULL;

do {

    $private_key_cipher = $settings['infocard_key'];
    $private_key_passphrase = $settings['infocard_key_passphrase'];
    if ($private_key_cipher == NULL)
    {
        $error = "Error obtaining ssl key material";
        break;
    }

    $tokenDoc = new DOMDocument();
    if ($tokenDoc->loadXML($tokenContent) == FALSE){
        $error = "Error loading encrypted token";
        break;
    }

    $rootElement = $tokenDoc->documentElement;
    if ("EncryptedData" != strip_namespace($rootElement->nodeName))
    {
        $error = "Badly formed security token: not EncryptedData";
        break;
    }

    $topNode = $rootElement->firstChild;
    if ("EncryptionMethod" != strip_namespace($topNode->nodeName)){
        $error = "Encryption method not specified";
        break;
    }

    if (!$blockAlgorithm=$topNode->getAttribute("Algorithm"))
    {
        $error = "Encryption Method blockAlgorithm missing";
        break;
    }

    switch ($blockAlgorithm)
    {
        case "http://www.w3.org/2001/04/xmlenc#aes256-cbc":
            $mcrypt_cipher = MCRYPT_RIJNDAEL_128;
            $mcrypt_mode = MCRYPT_MODE_CBC;
            $iv_length = 16;
            break;

        default:
            $error = "Unknown encryption blockAlgorithm: ".$blockAlgorithm."<br>";
            break;
    }

    if ($error)
        break;

    $topNode = $topNode->nextSibling;

    if ("KeyInfo" != strip_namespace($topNode->nodeName)){
        $error = "KeyInfo not specified";
        break;
    }

    $encryptionMethods = $topNode->getElementsByTagname("EncryptionMethod");
    $encryptionMethod = $encryptionMethods->item(0);
    $keyWrapAlgorithm = $encryptionMethod->getAttribute("Algorithm");
    switch ($keyWrapAlgorithm)
    {
        case "http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p":
            $ssl_padding = OPENSSL_PKCS1_OAEP_PADDING;
            break;

        default:
            $error = "Unrecognized keyWrapAlgorithm: ".$keyWrapAlgorithm."<br>";
            break;
    }
    if ($error)
        break;


    if (!$cipherValueNodes = $topNode->getElementsByTagname("CipherValue"))
    {
        $error = "no wrapping key cipher value found";
        break;
    }

    $cipherValueNode = $cipherValueNodes->item(0);
    $keyWrapCipher = $cipherValueNode->nodeValue;
    $keyWrapCipher = base64_decode($keyWrapCipher);   

    if (!$private_key=openssl_pkey_get_private(array($private_key_cipher, $private_key_passphrase)))
    {

        $error = "Unable to open and access private key";
        break;
    }
    
    if (openssl_private_decrypt($keyWrapCipher, $blockCipherKey, $private_key, $ssl_padding) != TRUE)
    {
        $error = "Unable to decrypt keyWrapCipher - most likely you are using the wrong private key";
        break;
    }

    openssl_free_key ($private_key);

    $topNode = $topNode->nextSibling;    

    if ("CipherData" != strip_namespace($topNode->nodeName)){
        $error = "cipherData not specified";
        break;
    }

    if (!$cipherValueNodes = $topNode->getElementsByTagname("CipherValue"))
    {
        $error = "no block cipher value found";
        break;
    }

    $cipherValueNode = $cipherValueNodes->item(0);
    $blockCipher = $cipherValueNode->nodeValue;
    $blockCipher = base64_decode($blockCipher);

    if ($iv_length > 0)
    {   
        $mcrypt_iv = substr($blockCipher, 0, $iv_length);
        $blockCipher = substr($blockCipher, $iv_length);
    }

 
    $token=mcrypt_decrypt ( $mcrypt_cipher, $blockCipherKey, $blockCipher, $mcrypt_mode, $mcrypt_iv);
    if (!$token)
    {
        $error = "block decryption failed";
        break;
    }

    if ($mcrypt_mode == MCRYPT_MODE_CBC)
    {
        $tokenLength = strlen($token);
        $paddingLength = substr($token, $tokenLength - 1, 1);
        $token = substr($token, 0, $tokenLength - ord($paddingLength));
    }

} while (0);


return ($error);

}

?>
