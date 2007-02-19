<?php

define("INFO_IGNORE",          0);
define("INFO_ASSERTION",       1);
define("INFO_SIGNEDINFO",      2);

// some globals
$elementNames = array();
$elementBufferIndex = array();
$activeBuffer = 0;
$canonicalTokenBuffer= "";
$canonicalSignedInfoBuffer = "";
$depth = 0;

// XXX hard coded for now
function infocard_assertion_id_fails_replay ($assertionID, $notBefore, $notOnOrAfter)
{
    return (FALSE);
}

function infocard_post_get_claims($token, &$claimsReturned)
{
    global $elementNames;
    global $elementBufferIndex;
    global $activeBuffer;
    global $canonicalTokenBuffer;
    global $canonicalSignedInfoBuffer;
    global $depth;

    // reset globals so we can run a few times

    $elementNames = array();
    $elementBufferIndex[0] = 0;
    $activeBuffer = 0;
    $canonicalTokenBuffer = "";
    $canonicalSignedInfoBuffer  = "";
    $depth = 0;

    // define some local variables
    $error = "";
    $publicKeyHandle = NULL;
    $AssertionID = "";
    $IssueInstant = "";
    $NotBefore = "";
    $NotOnOrAfter = "";
    $locallyCalculatedDigest = "";
    $modulus = "";
    $exponent = "";
    $X509Certificate = "";
    $SignatureValue = "";

    // this sets up the XML parser in a sufficiently raw mode
    // that we can do basic canonicalization

    $xml_parser = xml_parser_create();
    xml_set_element_handler($xml_parser, "startElement", "endElement");
    xml_set_character_data_handler($xml_parser, "characterData");
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);

    // and now run the parser to produced the canonical encodings
    if (!xml_parse($xml_parser, $token, FALSE)) {
           die(sprintf("XML error: %s at line %d",
                   xml_error_string(xml_get_error_code($xml_parser)),
                   xml_get_current_line_number($xml_parser)));
    }
    xml_parser_free($xml_parser);


    // now comes the main part of the routine - lots of steps
    // but nothing complicated
    do {

        $tokenDoc = new DOMDocument();
        if ($tokenDoc->loadXML($token) == FALSE){
            $error = "Error loading signed token";
            break;
        }

        $currentElement = $tokenDoc->documentElement;
        if ("Assertion" != strip_namespace($currentElement->nodeName))
        {
            $error = "Badly formed SAML token: not Assertion";
            break;
        }

        if (!$AssertionID=$currentElement->getAttribute("AssertionID"))
        {
            $error = "AssertionID not found";
            break;
        }

        if (!$IssueInstant=$currentElement->getAttribute("IssueInstant"))
        {
            $error = "IssueInstant not found";
            break;
        }

        $currentElement = $currentElement->firstChild;
        if ("Conditions" != strip_namespace($currentElement->nodeName))
        {
            $error = "Badly formed SAML token: no Conditions";
            break;
        }

        if (!$NotBefore=$currentElement->getAttribute("NotBefore"))
        {
            $error = "NotBefore missing";
            break;
        }

        if (!$NotOnOrAfter=$currentElement->getAttribute("NotOnOrAfter"))
        {
            $error = "NotOnOrAfter missing";
            break;
        }
     
        // Pause for debugging and pedagogy
        if (array_key_exists("parsedVariables", $_POST)){
            print "AssertionID: $AssertionID <br/>";
            print "IssueInstant: $IssueInstant <br/>";
            print "NotBefore: $NotBefore <br/>";
            print "NotOnOrAfter: $NotOnOrAfter <br/><br/>";
        }
        
        // Verify that the token is being processed within the time window
        // specified by the Identity Provider (we could further restrict this)
        $currentTime = mktime() + 3600;
        $adjustedTimeText = gmdate("Y-m-d",$currentTime)."T".gmdate("H:i:s",$currentTime)."Z";
        if ($adjustedTimeText < $NotBefore){
            $error = "Adjusted current time ($adjustedTimeText) is prior
to start of validity window ($NotBefore)";
            break;
        }

        $currentTime = mktime() - 900;
        $adjustedTimeText = gmdate("Y-m-d",$currentTime)."T".gmdate("H:i:s",$currentTime)."Z";
        if ($adjustedTimeText > $NotOnOrAfter){
            $error = "Adjusted current time ($adjustedTimeText) is after
the end of validity window ($NotOnOrAfter)";
            break;
        }

        // Make sure assertion id has not been used before within the window
        if (infocard_assertion_id_fails_replay($AssertionID, $NotBefore, $NotOnOrAfter) == TRUE)
        {
            $error = "AssertionID fails replay";
            break;
        }

        // Get the AttributeStatement and put it aside
        // until after verification
        $AttributeStatement = $currentElement->nextSibling;
        if ("AttributeStatement" != strip_namespace($AttributeStatement->nodeName))
        {
            $error = "Badly formed SAML token: AttributeStatement missing";
            break;
        }

        // Get the signature component of document
        $SignatureElement = $AttributeStatement->nextSibling;
        if ("Signature" != strip_namespace($SignatureElement->nodeName))
        {
            $error = "Badly formed SAML token: Signature missing";
            break;
        }

        // Zero in on SignedInfo
        $SignedInfo = $SignatureElement->firstChild;
        if ("SignedInfo" != strip_namespace($SignedInfo->nodeName))
        {
            $error = "Badly formed SAML token: SignedInfo missing";
            break;
        }

        // Verify Canonicalization Method
        $currentElement = $SignedInfo->firstChild;
        if ("CanonicalizationMethod" != strip_namespace($currentElement->nodeName))
        {
            $error = "Badly formed SAML token: CanonicalizationMethod";
            break;
        }
        if (!$Algorithm=$currentElement->getAttribute("Algorithm"))
        {
            $error = "CanonicalizationMethod algorithm missing";
            break;
        }
        if ($Algorithm != "http://www.w3.org/2001/10/xml-exc-c14n#")
        {
            $error = "Wrong canonicalizationMethod algorithm";
            break;
        }
  
        // Make sure we can handle signature algorithm
        $currentElement = $currentElement->nextSibling;
        if ("SignatureMethod" != strip_namespace($currentElement->nodeName))
        {
            $error = "Badly formed SAML token: SignatureMethod";
            break;
        }
        if (!$Algorithm=$currentElement->getAttribute("Algorithm"))
        {
            $error = "Signature algorithm missing";
            break;
        }
        if ($Algorithm != "http://www.w3.org/2000/09/xmldsig#rsa-sha1")
        {
            $error = "Wrong canonicalizationMethod algorithm";
            break;
        }

        // Verify the reference is to the current token
        $Reference = $currentElement->nextSibling;
        if ("Reference" != strip_namespace($Reference->nodeName))
        {
            $error = "Badly formed SAML token: Reference";
            break;
        }
        if (!$URI=$Reference->getAttribute("URI"))
        {
            $error = "Reference URI missing";
            break;
        }
        if (substr($URI, 0, 1) == "#")
        {
            $URI = substr ($URI, 1);
        }
        if ($URI != $AssertionID)
        {
            $error = "Wrong reference ($URI rather than $AssertionID)";
            break;
        }

        // Check Transforms too, one day
        $currentElement = $Reference->firstChild;
        if ("Transforms" != strip_namespace($currentElement->nodeName))
        {
            $error = "Badly formed SAML token: Transforms";
            break;
        }

        // Verify the DigestMethod
        $currentElement = $currentElement->nextSibling;
        if ("DigestMethod" != strip_namespace($currentElement->nodeName))
        {
            $error = "Badly formed SAML token: DigestMethod";
            break;
        }
        if (!$Algorithm=$currentElement->getAttribute("Algorithm"))
        {
            $error = "DigestMethod Algorithm missing";
            break;
        }
        if ($Algorithm != "http://www.w3.org/2000/09/xmldsig#sha1")
        {
            $error = "Wrong Digest Algorithm";
            break;
        }

        // At last, get the digest value
        $currentElement = $currentElement->nextSibling;
        if ("DigestValue" != strip_namespace($currentElement->nodeName))
        {
            $error = "Badly formed SAML token: DigestValue";
            break;
        }
        $digestValue = $currentElement->nodeValue;

        // Now we do our OWN calculation of the digest value
        // using our canonicalized buffer
        if (array_key_exists("canonicalToken", $_POST)){
            print_binary ("Canonical representation of token", 
                $canonicalTokenBuffer);
        }
        $myHash = sha1($canonicalTokenBuffer);
        $binaryHash = pack("H*", $myHash); 
        $locallyCalculatedDigest = base64_encode($binaryHash);

        // This needs to agree with the value present 
        // as a field of SignedInfo (and extracted above)
         if (strcmp($locallyCalculatedDigest, $digestValue) != 0)
        {
            $error = "SignedInfo digest doesn't match calculated digest";
            break;
        }

        // So our token produced the hash that is contained in the
        // SignedInfo.  So now we need to check the signature 
        // over SignedInfo.  Let's start by extracting the SignatureValue
        $currentElement = $SignedInfo->nextSibling;
        if ("SignatureValue" != strip_namespace($currentElement->nodeName))
        {
            $error = "Badly formed SAML token: SignatureValue";
            break;
        }
        $SignatureValue = $currentElement->nodeValue;

        // Now we'll look in the KeyInfo to find out about
        // the public key of the Identity Provider
        $currentElement = $currentElement->nextSibling;
        if ("KeyInfo" != strip_namespace($currentElement->nodeName))
        {
            $error = "Badly formed SAML token: KeyInfo for signature";
            break;
        }

        // We support either raw RSA keys or X509 certificates
        if ($RSAKeyValues = $currentElement->getElementsByTagname("RSAKeyValue"))
        {
            $localNode = $RSAKeyValues->item(0);
            $localNode = $localNode->firstChild;

            $modulus = $localNode->nodeValue;
            $exponent = $localNode->nextSibling->nodeValue;

            $publicKeyHandle = kimssl_pkey_get_public ($modulus, $exponent);
            if ($publicKeyHandle == NULL){
                $error = "Could not construct a public key from modulus and exponent.";
                break;
            }
        }
        else if ($X509Certificates = $currentElement->getElementsByTagname("X509Certificate"))
        {
            $localNode = $X509Certificates->item(0);
            $X509Certificate = $localNode->nodeValue;

            // We need PEM encoding to satisfy openssl
            $encoding = "-----BEGIN CERTIFICATE-----\n";
            $offset = 0;
            while ($segment=substr($X509Certificate, $offset, 64)){
                $encoding = $encoding.$segment."\n";
                $offset += 64;
            }
            $encoding = $encoding."-----END CERTIFICATE-----\n";

            $publicKeyHandle = openssl_pkey_get_public ($encoding);
            if ($publicKeyHandle == NULL){
                $error = "Could not extract public key from certificate.";
                break;
            }
        }
        else {
            $error = "No signature validation mechanism";
            break;
        }

        // Pause for more debugging and pedagogy
        if (array_key_exists("parsedVariables", $_POST)){
            print "CalculatedDigest: $locallyCalculatedDigest <br/>";
            print "Modulus: $modulus <br/>";
            print "Exponent: $exponent <br/>";
            print "Certificate: $X509Certificate <br/>";
            print "Signature: $SignatureValue <br/><br/>";
        }
        if (array_key_exists("canonicalSignedInfo", $_POST)){
            print_binary("Canonical representation of SignedInfo", $canonicalSignedInfoBuffer );
        }

        // Everything is now ready for signature validation
        $SignatureValue = base64_decode($SignatureValue);
  
        if (openssl_verify ( $canonicalSignedInfoBuffer , 
                             $SignatureValue, $publicKeyHandle))
        {
            $claims["modulusHash"] = MD5(base64_decode($modulus));
        }
        else
        {
            $error = "Claims do not verify cryptographically.";
            break;
        }

        // Everything is kosher so we just extract the claims  
        // We return to the AttributeStatement we set aside above 
        $Attributes = $AttributeStatement->getElementsByTagname("Attribute");
        $offset = 0;
        while ($Attribute=$Attributes->item($offset))
        {        
            if (!$AttributeName=$Attribute->getAttribute("AttributeName"))
            {
                $error = "Attribute with no name";
                break;
            }
            $AttributeValue = $Attribute->firstChild;
            $claims[$AttributeName] = $AttributeValue->nodeValue;
            $offset++;
        }
        if ($error != "")
            break;

        // all is now well
        $claimsReturned = $claims;
  

    } while (0);

    if ($publicKeyHandle)
        openssl_free_key($publicKeyHandle);

    return ($error);
}


function startElement($parser, $name, $attrs) 
{
    global $elementNames;
    global $elementBufferIndex;
    global $activeBuffer;
    global $depth;
    global $claims;
    global $activeClaimName;

    $depth++;
    $localBuffer = "";

    // requirement not met by strip_namespace
    $nameSeparator = strpos($name, ":");
    if ($nameSeparator > 0)
        $relativeName = substr($name, $nameSeparator + 1);
    else
        $relativeName = $name;



    switch ($relativeName) {
      case "Signature":
         $activeBuffer = INFO_IGNORE;
         break;
      case "Assertion":  // saml:Assertion
         $activeBuffer = INFO_ASSERTION;
         break;
      case "SignedInfo":
         $activeBuffer = INFO_SIGNEDINFO;
         break;

      default:
         break;
    }

    switch ($activeBuffer){
        case INFO_ASSERTION:
        case INFO_SIGNEDINFO:
            $elementNames[$depth] = $name;
        
            $sortedAttributes = array();

            $localBuffer = "<".$name; 

            foreach ($attrs as $attName => $value){
                $sortedAttributes[$attName] = $value;
            }

            if ($activeBuffer == INFO_SIGNEDINFO){
                if ($relativeName == "SignedInfo" && 
                       !array_key_exists("xmlns", $sortedAttributes))
                {
                    $namespace = "xmlns";
                    if ($nameSeparator > 0){
                        $namespace .= ":".substr($name, 0, $nameSeparator);
                    }
                        
                    $sortedAttributes[$namespace] = 
                        "http://www.w3.org/2000/09/xmldsig#";
                }
            }

            uksort($sortedAttributes, "compareAttributes");
  

            foreach ($sortedAttributes as $attName => $value){
                $localBuffer = $localBuffer." $attName=\"$value\"";
            }
            $localBuffer = $localBuffer.">";

            addToBuffer($localBuffer, $activeBuffer);
            break;

        default:
            break;
    }

    $elementBufferIndex[$depth] = $activeBuffer;
}


// depending on the state of the XML parser, this function
// adds what is running past us to the right buffer for upcoming
// crypto operations

function addToBuffer ($valueBuffer, $bufferId)
{
    global $canonicalTokenBuffer;
    global $canonicalSignedInfoBuffer ;

    switch ($bufferId){
        case INFO_ASSERTION:
            $canonicalTokenBuffer.= $valueBuffer;
            break;

        case INFO_SIGNEDINFO:
            $canonicalSignedInfoBuffer  .= $valueBuffer;
            break;

        default:
            break;
    }
}

function characterData($parser, $data) 
{
    global $activeBuffer;
    global $elementBufferIndex;
    global $depth;

    $activeBuffer = $elementBufferIndex[$depth];

    addToBuffer (htmlentities($data, ENT_NOQUOTES), $activeBuffer);
}


function endElement($parser, $name) 
{
    global $elementNames;
    global $elementBufferIndex;
    global $activeBuffer;
    global $depth;

    $activeBuffer = $elementBufferIndex[$depth];

    switch ($activeBuffer){
        case INFO_ASSERTION:
        case INFO_SIGNEDINFO:
            $element = $elementNames[$depth];
            $localBuffer = "</$element>";   
            addToBuffer ($localBuffer, $activeBuffer);
            break;

        default:
            break;
    }

    $activeBuffer = $elementBufferIndex[--$depth];
}


// This is a basic implementation of a function to
// lexographically order attributes and namespaces as per XMLDSIG
// requirements.  It is not a complete implementation but handles 
// cases where namespaces are not mixed.  

function compareAttributes($a, $b)
{
   $a_ns = preg_match("/^xmlns:/", $a);
   $b_ns = preg_match("/^xmlns:/", $b);
   if ($a_ns != $b_ns){
       return ($a_ns == 1) ? -1 : 1;
   } 
    
   return strcmp($a, $b);
}

?>
