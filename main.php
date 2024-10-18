function main($MyNumber) {
    $MyNumber = preg_replace("/[^0-9]/", "", $MyNumber );
    $VND = "";
    $Place = array("", "", " Ngàn ", " Triệu ", " Tỷ ", " Ngàn Tỷ ");
    
    // Convert MyNumber to a string, trimming extra spaces
    $MyNumber = trim(strval($MyNumber));
    
    // Find decimal place
    $DecimalPlace = strpos($MyNumber, ".");
    
    // If we find a decimal place
    if ($DecimalPlace > 0) {
        // Convert cents
        $Temp = substr(str_pad(substr($MyNumber, $DecimalPlace + 1), 2, '0'), 0, 2);
        $Cents = ConvertTens($Temp);
        // Strip off cents from remainder to convert
        $MyNumber = trim(substr($MyNumber, 0, $DecimalPlace));
    }

    $Count = 1;
    while ($MyNumber != "") {
        // Convert last 3 digits of MyNumber to VND
        $Temp = ConvertHundreds(substr($MyNumber, -3));
        if ($Temp != "") {
            $VND = $Temp . $Place[$Count] . $VND;
        }
        if (strlen($MyNumber) > 3) {
            // Remove last 3 converted digits from MyNumber
            $MyNumber = substr($MyNumber, 0, -3);
        } else {
            $MyNumber = "";
        }
        $Count++;
    }
    
    // Clean up VND
    switch (trim($VND)) {
        case "":
            $VND = "Không Ngàn";
            break;
        case "Một":
            $VND = "Một Ngàn";
            break;
        default:
            $VND = trim($VND) . " Đồng";
            break;
    }

    return $VND;
}

function ConvertHundreds($MyNumber) {
    $Result = "";

    // Exit if there's nothing to convert
    if (intval($MyNumber) == 0) {
        return "";
    }

    // Append leading zeros to number
    $MyNumber = str_pad($MyNumber, 3, '0', STR_PAD_LEFT);

    // Convert hundreds place digit
    if ($MyNumber[0] != "0") {
        $Result = ConvertDigit($MyNumber[0]) . " Trăm ";
    }

    // Convert tens place digit
    if ($MyNumber[1] != "0") {
        $Result .= ConvertTens(substr($MyNumber, 1));
    } else {
        // Convert ones place digit
        $Result .= ConvertDigit($MyNumber[2]);
    }

    return trim($Result);
}

function ConvertTens($MyTens) {
    $Result = "";

    // Is the value between 10 and 19?
    if (intval($MyTens[0]) == 1) {
        switch (intval($MyTens)) {
            case 10: $Result = "Mười"; break;
            case 11: $Result = "Mười Một"; break;
            case 12: $Result = "Mười Hai"; break;
            case 13: $Result = "Mười Ba"; break;
            case 14: $Result = "Mười Bốn"; break;
            case 15: $Result = "Mười Lam"; break;
            case 16: $Result = "Mười Sáu"; break;
            case 17: $Result = "Mười Bảy"; break;
            case 18: $Result = "Mười Tám"; break;
            case 19: $Result = "Mười Chín"; break;
        }
    } else {
        // Otherwise, it's between 20 and 99
        switch (intval($MyTens[0])) {
            case 2: $Result = "Hai Mươi "; break;
            case 3: $Result = "Ba Mươi "; break;
            case 4: $Result = "Bốn Mươi "; break;
            case 5: $Result = "Năm Mươi "; break;
            case 6: $Result = "Sáu Mươi "; break;
            case 7: $Result = "Bảy Mươi "; break;
            case 8: $Result = "Tám Mươi "; break;
            case 9: $Result = "Chín Mươi "; break;
        }
        // Convert ones place digit
        $Result .= ConvertDigit($MyTens[1]);
    }

    return $Result;
}

function ConvertDigit($MyDigit) {
    switch (intval($MyDigit)) {
        case 1: return "Một";
        case 2: return "Hai";
        case 3: return "Ba";
        case 4: return "Bốn";
        case 5: return "Năm";
        case 6: return "Sáu";
        case 7: return "Bảy";
        case 8: return "Tám";
        case 9: return "Chín";
        default: return "";
    }
}
