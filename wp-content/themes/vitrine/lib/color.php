<?php

class epico_Color
{
    public static function epico_Desaturate($value, $amount, $min=0)
    {
        //Only support hex for now
        if(!self::epico_IsHex($value))
            return '';

        $color = self::epico_HexToHsv($value);

        $color[1] = max($color[1] - $amount, $min);

        $color = self::epico_HsvToHex($color[0], $color[1], $color[2]);
        return $color;
    }

    public static function epico_IsHex($value)
    {
        return preg_match("/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/", $value) === 1;
    }

    public static function epico_IsColor($value)
    {
        $value = trim($value);
        if(self::epico_IsHex($value))
            return true;

        if(self::epico_NamedColorToHex($value))
            return true;

        return false;
    }

    public static function epico_HexToHsv($value)
    {
        $rgb = self::epico_HexToRgb($value);

        return self::epico_RgbToHsv($rgb[0], $rgb[1], $rgb[2]);
    }

    public static function epico_RgbToHsv ($R, $G, $B)  // RGB Values:Number 0-255
    {                                             // HSV values:    0-360, 0-100, 0-100
        // Convert the RGB byte-values to percentages
        $R = ($R / 255);
        $G = ($G / 255);
        $B = ($B / 255);

        // Calculate a few basic values, the maximum value of R,G,B, the
        //   minimum value, and the difference of the two (chroma).
        $maxRGB = max($R, $G, $B);
        $minRGB = min($R, $G, $B);
        $chroma = $maxRGB - $minRGB;

        // Value (also called Brightness) is the easiest component to calculate,
        //   and is simply the highest value among the R,G,B components.
        // We multiply by 100 to turn the decimal into a readable percent value.
        $computedV = 100 * $maxRGB;

        // Special case if hueless (equal parts RGB make black, white, or grays)
        // Note that Hue is technically undefined when chroma is zero, as
        //   attempting to calculate it would cause division by zero (see
        //   below), so most applications simply substitute a Hue of zero.
        // Saturation will always be zero in this case, see below for details.
        if ($chroma == 0)
            return array(0, 0, $computedV);

        // Saturation is also simple to compute, and is simply the chroma
        //   over the Value (or Brightness)
        // Again, multiplied by 100 to get a percentage.
        $computedS = 100 * ($chroma / $maxRGB);

        // Calculate Hue component
        // Hue is calculated on the "chromacity plane", which is represented
        //   as a 2D hexagon, divided into six 60-degree sectors. We calculate
        //   the bisecting angle as a value 0 <= x < 6, that represents which
        //   portion of which sector the line falls on.
        if ($R == $minRGB)
            $h = 3 - (($G - $B) / $chroma);
        elseif ($B == $minRGB)
            $h = 1 - (($R - $G) / $chroma);
        else // $G == $minRGB
            $h = 5 - (($B - $R) / $chroma);

        // After we have the sector position, we multiply it by the size of
        //   each sector's arc (60 degrees) to obtain the angle in degrees.
        $computedH = 60 * $h;

        return array($computedH, $computedS, $computedV);
    }

    public static function epico_HsvToRgb($iH, $iS, $iV)
    {
        $iH = min(max($iH, 0), 360);// Hue
        $iS = min(max($iS, 0), 100);// Saturation
        $iV = min(max($iV, 0), 100);// Lightness

        $dS = $iS/100.0; // Saturation: 0.0-1.0
        $dV = $iV/100.0; // Lightness:  0.0-1.0
        $dC = $dV*$dS;   // Chroma:     0.0-1.0
        $dH = $iH/60.0;  // H-Prime:    0.0-6.0
        $dT = $dH;       // Temp variable

        while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
        $dX = $dC*(1-abs($dT-1));     // as used in the Wikipedia link

        switch($dH) {
            case($dH >= 0.0 && $dH < 1.0):
                $dR = $dC; $dG = $dX; $dB = 0.0; break;
            case($dH >= 1.0 && $dH < 2.0):
                $dR = $dX; $dG = $dC; $dB = 0.0; break;
            case($dH >= 2.0 && $dH < 3.0):
                $dR = 0.0; $dG = $dC; $dB = $dX; break;
            case($dH >= 3.0 && $dH < 4.0):
                $dR = 0.0; $dG = $dX; $dB = $dC; break;
            case($dH >= 4.0 && $dH < 5.0):
                $dR = $dX; $dG = 0.0; $dB = $dC; break;
            case($dH >= 5.0 && $dH < 6.0):
                $dR = $dC; $dG = 0.0; $dB = $dX; break;
            default:
                $dR = 0.0; $dG = 0.0; $dB = 0.0; break;
        }

        $dM  = $dV - $dC;
        $dR += $dM; $dG += $dM; $dB += $dM;
        $dR *= 255; $dG *= 255; $dB *= 255;

        return array(round($dR), round($dG), round($dB));
    }

    public static function epico_HsvToHex($iH, $iS, $iV)
    {
        $rgb = self::epico_HsvToRgb($iH, $iS, $iV);
        return self::epico_RgbToHex($rgb[0], $rgb[1], $rgb[2]);
    }

    //Converts hex color value to array of RGB
    public static function epico_HexToRgb($color)
    {
        //Modified version of:
        //http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/

        //Validate the input
        if(!self::epico_IsHex($color))
            return array(0,0,0);

        $color = str_replace("#", "", $color);

        if(3 == strlen($color)) {
            $r = hexdec(substr($color,0,1).substr($color,0,1));
            $g = hexdec(substr($color,1,1).substr($color,1,1));
            $b = hexdec(substr($color,2,1).substr($color,2,1));
        } else {
            $r = hexdec(substr($color,0,2));
            $g = hexdec(substr($color,2,2));
            $b = hexdec(substr($color,4,2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    public static function epico_RgbToHex($r, $g, $b) {
        //String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
        $hex = "#";
        $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

        return $hex;
    }

    //Returns hex value of known color name
    public static function epico_NamedColorToHex($name, $retArray=false)
    {
        $colors = array(
            //  Colors  as  they  are  defined  in  HTML  3.2
            "black"=>array( "red"=>0x00,  "green"=>0x00,  "blue"=>0x00),
            "maroon"=>array( "red"=>0x80,  "green"=>0x00,  "blue"=>0x00),
            "green"=>array( "red"=>0x00,  "green"=>0x80,  "blue"=>0x00),
            "olive"=>array( "red"=>0x80,  "green"=>0x80,  "blue"=>0x00),
            "navy"=>array( "red"=>0x00,  "green"=>0x00,  "blue"=>0x80),
            "purple"=>array( "red"=>0x80,  "green"=>0x00,  "blue"=>0x80),
            "teal"=>array( "red"=>0x00,  "green"=>0x80,  "blue"=>0x80),
            "gray"=>array( "red"=>0x80,  "green"=>0x80,  "blue"=>0x80),
            "silver"=>array( "red"=>0xC0,  "green"=>0xC0,  "blue"=>0xC0),
            "red"=>array( "red"=>0xFF,  "green"=>0x00,  "blue"=>0x00),
            "lime"=>array( "red"=>0x00,  "green"=>0xFF,  "blue"=>0x00),
            "yellow"=>array( "red"=>0xFF,  "green"=>0xFF,  "blue"=>0x00),
            "blue"=>array( "red"=>0x00,  "green"=>0x00,  "blue"=>0xFF),
            "fuchsia"=>array( "red"=>0xFF,  "green"=>0x00,  "blue"=>0xFF),
            "aqua"=>array( "red"=>0x00,  "green"=>0xFF,  "blue"=>0xFF),
            "white"=>array( "red"=>0xFF,  "green"=>0xFF,  "blue"=>0xFF),

            //  Additional  colors  as  they  are  used  by  Netscape  and  IE
            "aliceblue"=>array( "red"=>0xF0,  "green"=>0xF8,  "blue"=>0xFF),
            "antiquewhite"=>array( "red"=>0xFA,  "green"=>0xEB,  "blue"=>0xD7),
            "aquamarine"=>array( "red"=>0x7F,  "green"=>0xFF,  "blue"=>0xD4),
            "azure"=>array( "red"=>0xF0,  "green"=>0xFF,  "blue"=>0xFF),
            "beige"=>array( "red"=>0xF5,  "green"=>0xF5,  "blue"=>0xDC),
            "blueviolet"=>array( "red"=>0x8A,  "green"=>0x2B,  "blue"=>0xE2),
            "brown"=>array( "red"=>0xA5,  "green"=>0x2A,  "blue"=>0x2A),
            "burlywood"=>array( "red"=>0xDE,  "green"=>0xB8,  "blue"=>0x87),
            "cadetblue"=>array( "red"=>0x5F,  "green"=>0x9E,  "blue"=>0xA0),
            "chartreuse"=>array( "red"=>0x7F,  "green"=>0xFF,  "blue"=>0x00),
            "chocolate"=>array( "red"=>0xD2,  "green"=>0x69,  "blue"=>0x1E),
            "coral"=>array( "red"=>0xFF,  "green"=>0x7F,  "blue"=>0x50),
            "cornflowerblue"=>array( "red"=>0x64,  "green"=>0x95,  "blue"=>0xED),
            "cornsilk"=>array( "red"=>0xFF,  "green"=>0xF8,  "blue"=>0xDC),
            "crimson"=>array( "red"=>0xDC,  "green"=>0x14,  "blue"=>0x3C),
            "darkblue"=>array( "red"=>0x00,  "green"=>0x00,  "blue"=>0x8B),
            "darkcyan"=>array( "red"=>0x00,  "green"=>0x8B,  "blue"=>0x8B),
            "darkgoldenrod"=>array( "red"=>0xB8,  "green"=>0x86,  "blue"=>0x0B),
            "darkgray"=>array( "red"=>0xA9,  "green"=>0xA9,  "blue"=>0xA9),
            "darkgreen"=>array( "red"=>0x00,  "green"=>0x64,  "blue"=>0x00),
            "darkkhaki"=>array( "red"=>0xBD,  "green"=>0xB7,  "blue"=>0x6B),
            "darkmagenta"=>array( "red"=>0x8B,  "green"=>0x00,  "blue"=>0x8B),
            "darkolivegreen"=>array( "red"=>0x55,  "green"=>0x6B,  "blue"=>0x2F),
            "darkorange"=>array( "red"=>0xFF,  "green"=>0x8C,  "blue"=>0x00),
            "darkorchid"=>array( "red"=>0x99,  "green"=>0x32,  "blue"=>0xCC),
            "darkred"=>array( "red"=>0x8B,  "green"=>0x00,  "blue"=>0x00),
            "darksalmon"=>array( "red"=>0xE9,  "green"=>0x96,  "blue"=>0x7A),
            "darkseagreen"=>array( "red"=>0x8F,  "green"=>0xBC,  "blue"=>0x8F),
            "darkslateblue"=>array( "red"=>0x48,  "green"=>0x3D,  "blue"=>0x8B),
            "darkslategray"=>array( "red"=>0x2F,  "green"=>0x4F,  "blue"=>0x4F),
            "darkturquoise"=>array( "red"=>0x00,  "green"=>0xCE,  "blue"=>0xD1),
            "darkviolet"=>array( "red"=>0x94,  "green"=>0x00,  "blue"=>0xD3),
            "deeppink"=>array( "red"=>0xFF,  "green"=>0x14,  "blue"=>0x93),
            "deepskyblue"=>array( "red"=>0x00,  "green"=>0xBF,  "blue"=>0xFF),
            "dimgray"=>array( "red"=>0x69,  "green"=>0x69,  "blue"=>0x69),
            "dodgerblue"=>array( "red"=>0x1E,  "green"=>0x90,  "blue"=>0xFF),
            "firebrick"=>array( "red"=>0xB2,  "green"=>0x22,  "blue"=>0x22),
            "floralwhite"=>array( "red"=>0xFF,  "green"=>0xFA,  "blue"=>0xF0),
            "forestgreen"=>array( "red"=>0x22,  "green"=>0x8B,  "blue"=>0x22),
            "gainsboro"=>array( "red"=>0xDC,  "green"=>0xDC,  "blue"=>0xDC),
            "ghostwhite"=>array( "red"=>0xF8,  "green"=>0xF8,  "blue"=>0xFF),
            "gold"=>array( "red"=>0xFF,  "green"=>0xD7,  "blue"=>0x00),
            "goldenrod"=>array( "red"=>0xDA,  "green"=>0xA5,  "blue"=>0x20),
            "greenyellow"=>array( "red"=>0xAD,  "green"=>0xFF,  "blue"=>0x2F),
            "honeydew"=>array( "red"=>0xF0,  "green"=>0xFF,  "blue"=>0xF0),
            "hotpink"=>array( "red"=>0xFF,  "green"=>0x69,  "blue"=>0xB4),
            "indianred"=>array( "red"=>0xCD,  "green"=>0x5C,  "blue"=>0x5C),
            "indigo"=>array( "red"=>0x4B,  "green"=>0x00,  "blue"=>0x82),
            "ivory"=>array( "red"=>0xFF,  "green"=>0xFF,  "blue"=>0xF0),
            "khaki"=>array( "red"=>0xF0,  "green"=>0xE6,  "blue"=>0x8C),
            "lavender"=>array( "red"=>0xE6,  "green"=>0xE6,  "blue"=>0xFA),
            "lavenderblush"=>array( "red"=>0xFF,  "green"=>0xF0,  "blue"=>0xF5),
            "lawngreen"=>array( "red"=>0x7C,  "green"=>0xFC,  "blue"=>0x00),
            "lemonchiffon"=>array( "red"=>0xFF,  "green"=>0xFA,  "blue"=>0xCD),
            "lightblue"=>array( "red"=>0xAD,  "green"=>0xD8,  "blue"=>0xE6),
            "lightcoral"=>array( "red"=>0xF0,  "green"=>0x80,  "blue"=>0x80),
            "lightcyan"=>array( "red"=>0xE0,  "green"=>0xFF,  "blue"=>0xFF),
            "lightgoldenrodyellow"=>array( "red"=>0xFA,  "green"=>0xFA,  "blue"=>0xD2),
            "lightgreen"=>array( "red"=>0x90,  "green"=>0xEE,  "blue"=>0x90),
            "lightgrey"=>array( "red"=>0xD3,  "green"=>0xD3,  "blue"=>0xD3),
            "lightpink"=>array( "red"=>0xFF,  "green"=>0xB6,  "blue"=>0xC1),
            "lightsalmon"=>array( "red"=>0xFF,  "green"=>0xA0,  "blue"=>0x7A),
            "lightseagreen"=>array( "red"=>0x20,  "green"=>0xB2,  "blue"=>0xAA),
            "lightskyblue"=>array( "red"=>0x87,  "green"=>0xCE,  "blue"=>0xFA),
            "lightslategray"=>array( "red"=>0x77,  "green"=>0x88,  "blue"=>0x99),
            "lightsteelblue"=>array( "red"=>0xB0,  "green"=>0xC4,  "blue"=>0xDE),
            "lightyellow"=>array( "red"=>0xFF,  "green"=>0xFF,  "blue"=>0xE0),
            "limegreen"=>array( "red"=>0x32,  "green"=>0xCD,  "blue"=>0x32),
            "linen"=>array( "red"=>0xFA,  "green"=>0xF0,  "blue"=>0xE6),
            "mediumaquamarine"=>array( "red"=>0x66,  "green"=>0xCD,  "blue"=>0xAA),
            "mediumblue"=>array( "red"=>0x00,  "green"=>0x00,  "blue"=>0xCD),
            "mediumorchid"=>array( "red"=>0xBA,  "green"=>0x55,  "blue"=>0xD3),
            "mediumpurple"=>array( "red"=>0x93,  "green"=>0x70,  "blue"=>0xD0),
            "mediumseagreen"=>array( "red"=>0x3C,  "green"=>0xB3,  "blue"=>0x71),
            "mediumslateblue"=>array( "red"=>0x7B,  "green"=>0x68,  "blue"=>0xEE),
            "mediumspringgreen"=>array( "red"=>0x00,  "green"=>0xFA,  "blue"=>0x9A),
            "mediumturquoise"=>array( "red"=>0x48,  "green"=>0xD1,  "blue"=>0xCC),
            "mediumvioletred"=>array( "red"=>0xC7,  "green"=>0x15,  "blue"=>0x85),
            "midnightblue"=>array( "red"=>0x19,  "green"=>0x19,  "blue"=>0x70),
            "mintcream"=>array( "red"=>0xF5,  "green"=>0xFF,  "blue"=>0xFA),
            "mistyrose"=>array( "red"=>0xFF,  "green"=>0xE4,  "blue"=>0xE1),
            "moccasin"=>array( "red"=>0xFF,  "green"=>0xE4,  "blue"=>0xB5),
            "navajowhite"=>array( "red"=>0xFF,  "green"=>0xDE,  "blue"=>0xAD),
            "oldlace"=>array( "red"=>0xFD,  "green"=>0xF5,  "blue"=>0xE6),
            "olivedrab"=>array( "red"=>0x6B,  "green"=>0x8E,  "blue"=>0x23),
            "orange"=>array( "red"=>0xFF,  "green"=>0xA5,  "blue"=>0x00),
            "orangered"=>array( "red"=>0xFF,  "green"=>0x45,  "blue"=>0x00),
            "orchid"=>array( "red"=>0xDA,  "green"=>0x70,  "blue"=>0xD6),
            "palegoldenrod"=>array( "red"=>0xEE,  "green"=>0xE8,  "blue"=>0xAA),
            "palegreen"=>array( "red"=>0x98,  "green"=>0xFB,  "blue"=>0x98),
            "paleturquoise"=>array( "red"=>0xAF,  "green"=>0xEE,  "blue"=>0xEE),
            "palevioletred"=>array( "red"=>0xDB,  "green"=>0x70,  "blue"=>0x93),
            "papayawhip"=>array( "red"=>0xFF,  "green"=>0xEF,  "blue"=>0xD5),
            "peachpuff"=>array( "red"=>0xFF,  "green"=>0xDA,  "blue"=>0xB9),
            "peru"=>array( "red"=>0xCD,  "green"=>0x85,  "blue"=>0x3F),
            "pink"=>array( "red"=>0xFF,  "green"=>0xC0,  "blue"=>0xCB),
            "plum"=>array( "red"=>0xDD,  "green"=>0xA0,  "blue"=>0xDD),
            "powderblue"=>array( "red"=>0xB0,  "green"=>0xE0,  "blue"=>0xE6),
            "rosybrown"=>array( "red"=>0xBC,  "green"=>0x8F,  "blue"=>0x8F),
            "royalblue"=>array( "red"=>0x41,  "green"=>0x69,  "blue"=>0xE1),
            "saddlebrown"=>array( "red"=>0x8B,  "green"=>0x45,  "blue"=>0x13),
            "salmon"=>array( "red"=>0xFA,  "green"=>0x80,  "blue"=>0x72),
            "sandybrown"=>array( "red"=>0xF4,  "green"=>0xA4,  "blue"=>0x60),
            "seagreen"=>array( "red"=>0x2E,  "green"=>0x8B,  "blue"=>0x57),
            "seashell"=>array( "red"=>0xFF,  "green"=>0xF5,  "blue"=>0xEE),
            "sienna"=>array( "red"=>0xA0,  "green"=>0x52,  "blue"=>0x2D),
            "skyblue"=>array( "red"=>0x87,  "green"=>0xCE,  "blue"=>0xEB),
            "slateblue"=>array( "red"=>0x6A,  "green"=>0x5A,  "blue"=>0xCD),
            "slategray"=>array( "red"=>0x70,  "green"=>0x80,  "blue"=>0x90),
            "snow"=>array( "red"=>0xFF,  "green"=>0xFA,  "blue"=>0xFA),
            "springgreen"=>array( "red"=>0x00,  "green"=>0xFF,  "blue"=>0x7F),
            "steelblue"=>array( "red"=>0x46,  "green"=>0x82,  "blue"=>0xB4),
            "tan"=>array( "red"=>0xD2,  "green"=>0xB4,  "blue"=>0x8C),
            "thistle"=>array( "red"=>0xD8,  "green"=>0xBF,  "blue"=>0xD8),
            "tomato"=>array( "red"=>0xFF,  "green"=>0x63,  "blue"=>0x47),
            "turquoise"=>array( "red"=>0x40,  "green"=>0xE0,  "blue"=>0xD0),
            "violet"=>array( "red"=>0xEE,  "green"=>0x82,  "blue"=>0xEE),
            "wheat"=>array( "red"=>0xF5,  "green"=>0xDE,  "blue"=>0xB3),
            "whitesmoke"=>array( "red"=>0xF5,  "green"=>0xF5,  "blue"=>0xF5),
            "yellowgreen"=>array( "red"=>0x9A,  "green"=>0xCD,  "blue"=>0x32),
        );

        if(array_key_exists($name, $colors))
        {
            $val = $colors[$name];
            if($retArray)
                return $val;
            else
                return "#".$val['red'].$val['green'].$val['blue'];
        }

        return null;
    }
}