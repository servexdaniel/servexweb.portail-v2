<?php

namespace App\Servex\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


trait UsesColorsTrait {

    /**
     * Retourne la liste des couleurs
     */
    function getListColors()
    {
        $colors = [
                    //'#000000',
                    '#0C090A',
                    '#34282C',
                    '#3B3131',
                    '#3A3B3C',
                    '#454545',
                    '#413839',
                    '#3D3C3A',
                    '#463E3F',
                    '#4C4646',
                    '#504A4B',
                    '#565051',
                    '#52595D',
                    '#5C5858',
                    '#625D5D',
                    '#666362',
                    '#6D6968',
                    '#696969',
                    '#726E6D',
                    '#736F6E',
                    '#757575',
                    '#797979',
                    '#837E7C',
                    '#808080',
                    '#848482',
                    '#A9A9A9',
                    '#B6B6B4',
                    '#C0C0C0',
                    '#C9C0BB',
                    '#D1D0CE',
                    '#CECECE',
                    '#D3D3D3',
                    '#DCDCDC',
                    '#E5E4E2',
                    '#BCC6CC',
                    '#98AFC7',
                    '#838996',
                    '#778899',
                    '#708090',
                    '#6D7B8D',
                    '#657383',
                    '#616D7E',
                    '#646D7E',
                    '#566D7E',
                    '#737CA1',
                    '#728FCE',
                    '#4863A0',
                    '#2B547E',
                    '#36454F',
                    '#29465B',
                    '#2B3856',
                    '#123456',
                    '#151B54',
                    '#191970',
                    '#000080',
                    '#151B8D',
                    '#00008B',
                    '#15317E',
                    '#0000A0',
                    '#0000A5',
                    '#0020C2',
                    '#0000CD',
                    '#0041C2',
                    '#2916F5',
                    '#0000FF',
                    '#0909FF',
                    '#1F45FC',
                    '#2554C7',
                    '#1569C7',
                    '#1974D2',
                    '#2B60DE',
                    '#4169E1',
                    '#2B65EC',
                    '#306EFF',
                    '#157DEC',
                    '#1589FF',
                    '#1E90FF',
                    '#368BC1',
                    '#4682B4',
                    '#488AC7',
                    '#357EC7',
                    '#3090C7',
                    '#659EC7',
                    '#87AFC7',
                    '#95B9C7',
                    '#6495ED',
                    '#6698FF',
                    '#56A5EC',
                    '#38ACEC',
                    '#00BFFF',
                    '#3BB9FF',
                    '#5CB3FF',
                    '#79BAEC',
                    '#82CAFF',
                    '#87CEFA',
                    '#87CEEB',
                    '#A0CFEC',
                    '#B7CEEC',
                    '#B4CFEC',
                    '#ADDFFF',
                    '#C2DFFF',
                    '#C6DEFF',
                    '#BDEDFF',
                    '#B0E0E6',
                    '#AFDCEC',
                    '#ADD8E6',
                    '#B0CFDE',
                    '#C9DFEC',
                    '#D5D6EA',
                    '#E3E4FA',
                    '#E6E6FA',
                    '#EBF4FA',
                    '#F0F8FF',
                    '#F8F8FF',
                    '#F0FFFF',
                    '#E0FFFF',
                    '#CCFFFF',
                    '#9AFEFF',
                    '#7DFDFE',
                    '#57FEFF',
                    '#00FFFF',
                    '#0AFFFF',
                    '#50EBEC',
                    '#4EE2EC',
                    '#16E2F5',
                    '#8EEBEC',
                    '#AFEEEE',
                    '#CFECEC',
                    '#81D8D0',
                    '#77BFC7',
                    '#92C7C7',
                    '#78C7C7',
                    '#7BCCB5',
                    '#66CDAA',
                    '#AAF0D1',
                    '#7FFFD4',
                    '#93FFE8',
                    '#40E0D0',
                    '#48D1CC',
                    '#48CCCD',
                    '#46C7C7',
                    '#43C6DB',
                    '#00CED1',
                    '#43BFC7',
                    '#20B2AA',
                    '#3EA99F',
                    '#5F9EA0',
                    '#3B9C9C',
                    '#008B8B',
                    '#008080',
                    '#045F5F',
                    '#033E3E',
                    '#25383C',
                    '#2C3539',
                    '#3C565B',
                    '#4C787E',
                    '#5E7D7E',
                    '#307D7E',
                    '#348781',
                    '#438D80',
                    '#4E8975',
                    '#306754',
                    '#2E8B57',
                    '#31906E',
                    '#00A36C',
                    '#34A56F',
                    '#50C878',
                    '#3EB489',
                    '#3CB371',
                    '#78866B',
                    '#848B79',
                    '#617C58',
                    '#728C00',
                    '#6B8E23',
                    '#808000',
                    '#556B2F',
                    '#4B5320',
                    '#667C26',
                    '#4E9258',
                    '#387C44',
                    '#347235',
                    '#347C2C',
                    '#228B22',
                    '#008000',
                    '#006400',
                    '#046307',
                    '#254117',
                    '#437C17',
                    '#347C17',
                    '#6AA121',
                    '#4AA02C',
                    '#41A317',
                    '#12AD2B',
                    '#3EA055',
                    '#73A16C',
                    '#6CBB3C',
                    '#6CC417',
                    '#4CC417',
                    '#32CD32',
                    '#52D017',
                    '#4CC552',
                    '#54C571',
                    '#99C68E',
                    '#8FBC8F',
                    '#89C35C',
                    '#85BB65',
                    '#9CB071',
                    '#B0BF1A',
                    '#B2C248',
                    '#9DC209',
                    '#A1C935',
                    '#9ACD32',
                    '#77DD77',
                    '#7FE817',
                    '#59E817',
                    '#57E964',
                    '#16F529',
                    '#5EFB6E',
                    '#36F57F',
                    '#00FF7F',
                    '#00FA9A',
                    '#5FFB17',
                    '#00FF00',
                    '#7CFC00',
                    '#66FF00',
                    '#7FFF00',
                    '#87F717',
                    '#98F516',
                    '#B1FB17',
                    '#ADFF2F',
                    '#BDF516',
                    '#DAEE01',
                    '#E2F516',
                    '#CCFB5D',
                    '#BCE954',
                    '#64E986',
                    '#90EE90',
                    '#6AFB92',
                    '#98FB98',
                    '#98FF98',
                    '#B5EAAA',
                    '#E3F9A6',
                    '#C3FDB8',
                    '#DBF9DB',
                    '#F0FFF0',
                    '#F5FFFA',
                    '#FFFACD',
                    '#FFFFC2',
                    '#FFFFCC',
                    '#FAFAD2',
                    '#FFFFE0',
                    '#F5F5DC',
                    '#FFF8DC',
                    '#FBF6D9',
                    '#F7E7CE',
                    '#FAEBD7',
                    '#FFEFD5',
                    '#FFEBCD',
                    '#FFE4C4',
                    '#F5DEB3',
                    '#FFE4B5',
                    '#FFE5B4',
                    '#FED8B1',
                    '#FFDAB9',
                    '#FFDEAD',
                    '#FBE7A1',
                    '#F3E3C3',
                    '#F0E2B6',
                    '#F1E5AC',
                    '#F3E5AB',
                    '#ECE5B6',
                    '#EEE8AA',
                    '#F0E68C',
                    '#EDDA74',
                    '#EDE275',
                    '#FFE87C',
                    '#FFF380',
                    '#FAF884',
                    '#FFFF33',
                    '#FFFF00',
                    '#FFEF00',
                    '#F5E216',
                    '#FFDB58',
                    '#FFDF00',
                    '#F9DB24',
                    '#FFD801',
                    '#FFD700',
                    '#FDD017',
                    '#EAC117',
                    '#F6BE00',
                    '#F2BB66',
                    '#FBB917',
                    '#FBB117',
                    '#FFAE42',
                    '#FFA62F',
                    '#FFA500',
                    '#EE9A4D',
                    '#F4A460',
                    '#E2A76F',
                    '#C19A6B',
                    '#E6BF83',
                    '#DEB887',
                    '#D2B48C',
                    '#C8AD7F',
                    '#C2B280',
                    '#BCB88A',
                    '#C8B560',
                    '#C9BE62',
                    '#BDB76B',
                    '#BAB86C',
                    '#B5A642',
                    '#C7A317',
                    '#D4AF37',
                    '#E9AB17',
                    '#E8A317',
                    '#DAA520',
                    '#D4A017',
                    '#C68E17',
                    '#B8860B',
                    '#C58917',
                    '#CD853F',
                    '#CD7F32',
                    '#C88141',
                    '#B87333',
                    '#966F33',
                    '#806517',
                    '#665D1E',
                    '#8E7618',
                    '#8B8000',
                    '#827839',
                    '#AF9B60',
                    '#827B60',
                    '#786D5F',
                    '#483C32',
                    '#493D26',
                    '#513B1C',
                    '#3D3635',
                    '#3B2F2F',
                    '#43302E',
                    '#49413F',
                    '#5C3317',
                    '#654321',
                    '#704214',
                    '#6F4E37',
                    '#835C3B',
                    '#7F5217',
                    '#7F462C',
                    '#A0522D',
                    '#8B4513',
                    '#8A4117',
                    '#7E3817',
                    '#7E3517',
                    '#954535',
                    '#C34A2C',
                    '#C04000',
                    '#C35817',
                    '#B86500',
                    '#B5651D',
                    '#C36241',
                    '#CB6D51',
                    '#C47451',
                    '#D2691E',
                    '#CC6600',
                    '#E56717',
                    '#E66C2C',
                    '#FF6700',
                    '#FF5F1F',
                    '#F87217',
                    '#F88017',
                    '#FF8C00',
                    '#F87431',
                    '#FF7722',
                    '#E67451',
                    '#FF8040',
                    '#FF7F50',
                    '#F88158',
                    '#F9966B',
                    '#FFA07A',
                    '#E9967A',
                    '#E78A61',
                    '#DA8A67',
                    '#FA8072',
                    '#F08080',
                    '#F67280',
                    '#E77471',
                    '#F75D59',
                    '#E55451',
                    '#CD5C5C',
                    '#FF6347',
                    '#E55B3C',
                    '#FF4500',
                    '#FF0000',
                    '#FD1C03',
                    '#FF2400',
                    '#F62217',
                    '#F70D1A',
                    '#F62817',
                    '#E42217',
                    '#E41B17',
                    '#DC381F',
                    '#C24641',
                    '#C11B17',
                    '#B22222',
                    '#B21807',
                    '#A52A2A',
                    '#A70D2A',
                    '#9F000F',
                    '#931314',
                    '#990012',
                    '#8B0000',
                    '#800000',
                    '#8C001A',
                    '#800517',
                    '#660000',
                    '#551606',
                    '#3D0C02',
                    '#3F000F',
                    '#2B1B17',
                    '#550A35',
                    '#810541',
                    '#7D0541',
                    '#7D0552',
                    '#872657',
                    '#7E354D',
                    '#7F4E52',
                    '#7F525D',
                    '#7F5A58',
                    '#997070',
                    '#B38481',
                    '#BC8F8F',
                    '#C5908E',
                    '#C48189',
                    '#C48793',
                    '#E8ADAA',
                    '#C4AEAD',
                    '#ECC5C0',
                    '#FFCBA4',
                    '#F8B88B',
                    '#EDC9AF',
                    '#FFDDCA',
                    '#FDD7E4',
                    '#FFE6E8',
                    '#FFE4E1',
                    '#FFDFDD',
                    '#FFCCCB',
                    '#FBCFCD',
                    '#FBBBB9',
                    '#FFC0CB',
                    '#FFB6C1',
                    '#FAAFBE',
                    '#FAAFBA',
                    '#F9A7B0',
                    '#FEA3AA',
                    '#E7A1B0',
                    '#E799A3',
                    '#E38AAE',
                    '#F778A1',
                    '#E56E94',
                    '#DB7093',
                    '#D16587',
                    '#C25A7C',
                    '#C25283',
                    '#E75480',
                    '#F660AB',
                    '#FF69B4',
                    '#FC6C85',
                    '#F6358A',
                    '#F52887',
                    '#FF1493',
                    '#F535AA',
                    '#FD349C',
                    '#E45E9D',
                    '#E3319D',
                    '#E4287C',
                    '#E30B5D',
                    '#DC143C',
                    '#C32148',
                    '#C21E56',
                    '#C12869',
                    '#C12267',
                    '#CA226B',
                    '#C71585',
                    '#C12283',
                    '#B3446C',
                    '#B93B8F',
                    '#DA70D6',
                    '#DF73D4',
                    '#EE82EE',
                    '#F433FF',
                    '#FF00FF',
                    '#E238EC',
                    '#D462FF',
                    '#C45AEC',
                    '#BA55D3',
                    '#A74AC7',
                    '#B048B5',
                    '#D291BC',
                    '#915F6D',
                    '#7E587E',
                    '#614051',
                    '#583759',
                    '#5E5A80',
                    '#4E5180',
                    '#6A5ACD',
                    '#6960EC',
                    '#736AFF',
                    '#7B68EE',
                    '#7575CF',
                    '#6C2DC7',
                    '#6A0DAD',
                    '#5453A6',
                    '#483D8B',
                    '#4E387E',
                    '#571B7E',
                    '#4B0150',
                    '#36013F',
                    '#461B7E',
                    '#4B0082',
                    '#342D7E',
                    '#663399',
                    '#6A287E',
                    '#8B008B',
                    '#800080',
                    '#86608E',
                    '#9932CC',
                    '#9400D3',
                    '#8D38C9',
                    '#A23BEC',
                    '#B041FF',
                    '#842DCE',
                    '#8A2BE2',
                    '#7A5DC7',
                    '#7F38EC',
                    '#9D00FF',
                    '#8E35EF',
                    '#893BFF',
                    '#967BB6',
                    '#9370DB',
                    '#8467D7',
                    '#9172EC',
                    '#9E7BFF',
                    '#CCCCFF',
                    '#DCD0FF',
                    '#E0B0FF',
                    '#D891EF',
                    '#B666D2',
                    '#C38EC7',
                    '#C8A2C8',
                    '#DDA0DD',
                    '#E6A9EC',
                    '#F2A2E8',
                    '#F9B7FF',
                    '#C6AEC7',
                    '#D2B9D3',
                    '#D8BFD8',
                    '#E9CFEC',
                    '#FCDFFF',
                    '#EBDDE2',
                    '#E9E4D4',
                    '#EDE6D6',
                    '#FAF0DD',
                    '#F8F0E3',
                    '#FFF0F5',
                    '#FDEEF4',
                    '#FFF9E3',
                    '#FDF5E6',
                    '#FAF0E6',
                    '#FFF5EE',
                    '#FAF5EF',
                    '#FFFAF0',
                    '#FFFFF0',
                    '#FFFFF7',
                    '#F5F5F5',
                    '#FBFBF9',
                    '#FFFAFA',
                    '#FEFCFF',
                    //'#FFFFFF'
        ];

        $array = array();
        foreach($colors as $colorhex)
        {
            $rgbColor = $this->HTMLToRGB($colorhex);
            $hsl = $this->RGBToHSL($rgbColor);
            $temp = null;
            //Si la couleur est clair, le text sera en noir
            if($hsl->lightness > 200)
            {
                //Couleur du fond et couleur du text noir
                $temp['bgColor'] = $colorhex;
                $temp['textColor'] = "#786D5F";
            }else{
                //Couleur du fond et couleur du text en clair
                $temp['bgColor'] = $colorhex;
                $temp['textColor'] = "#ffffff";
            }
            array_push($array, $temp);
        }
        return $array;
    }

    function calculateColorAndText($colorhex)
    {
        $array = array();
        $rgbColor = $this->HTMLToRGB($colorhex);
        $hsl = $this->RGBToHSL($rgbColor);
        $temp = null;
        //Si la couleur est clair, le text sera en noir
        if($hsl->lightness > 200)
        {
            //Couleur du fond et couleur du text noir
            $temp['bgColor'] = $colorhex;
            $temp['textColor'] = "#786D5F";
        }else{
            //Couleur du fond et couleur du text en clair
            $temp['bgColor'] = $colorhex;
            $temp['textColor'] = "#ffffff";
        }
        array_push($array, $temp);
        return $array;
    }


    function calculateBringhtness($color)
    {
        $brightness = 0.5; // 50% brighter
        $colorServex = $color;
        $brightness = 0.08; // 8% brighter
        $colorServex50 = $this->colourBrightness($color, $brightness);
        $brightness = 0.2; // 20% brighter
        $colorServex100 = $this->colourBrightness($color, $brightness);
        $brightness = 0.8; // 80% brighter
        $colorServexLight = $this->colourBrightness($color, $brightness);
        $brightness = 0.6; // 60% brighter
        $colorServexLighter = $this->colourBrightness($color, $brightness);
        $brightness = -0.2; // -20% brighter
        $colorServexDark = $this->colourBrightness($color, $brightness);
        $brightness = -0.4; // -40% darker
        $colorServexDarker = $this->colourBrightness($color, $brightness);

        return [
            'colorServex'       => $colorServex,
            'colorServex50'     => $colorServex50,
            'colorServex100'    => $colorServex100,
            'colorServexLight'  => $colorServexLight,
            'colorServexLighter'=> $colorServexLighter,
            'colorServexDark'   => $colorServexDark,
            'colorServexDarker' => $colorServexDarker
        ];
    }

    /**
     * Décoder la couleur, CPColor renvoit un entier à convertir en hexadecimal(dechex).
     * Ensuite transformer le rgb en bgr (Inverser le blue et le red pour avoir la meme couleur que Servex) puis transformer en hexadeciaml
     * */
    function decodeColor($value)
    {
        if(!is_null($value) && strlen($value) > 0)
        {
            $rgb = $this->hex2RGB(dechex($value));
            if(!is_bool($rgb))
            {
                $colorhex = $this->RGB2Hex($rgb['red'], $rgb['green'], $rgb['blue']);
                $rgb2 = $this->HTMLToRGB($colorhex);
                $hsl = $this->RGBToHSL($rgb2);

                //Si la couleur est clair, le text sera en noir
                if($hsl->lightness > 200)
                {
                    $colorhex = $colorhex."þ"."#000000"; //Couleur du fond et couleur du text noir
                }else{
                    $colorhex = $colorhex."þ"."#ffffff";  //Couleur du fond et couleur du text en clair
                }
            }else{
                $colorhex = "";
            }
            return $colorhex;
        } else{
            return "";
        }
    }

    /**
     *  Convertit la couleur Hexadecimal en RGB
     */
    function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
    {
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = array();
        if (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else { //If a proper hex code
            $colorVal = hexdec($hexStr);
            $rgbArray['blue'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['red'] = 0xFF & $colorVal;
        }
        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
    }

    /**
     *  Convertit la couleur RGB en Hexadecimal
     */
    function RGB2Hex($r, $g, $b)
    {
        $red = dechex($r);
        if (strlen($red)<2)
            $red = '0'.$red;

        $green = dechex($g);
        if (strlen($green)<2)
            $green = '0'.$green;

        $blue = dechex($b);
        if (strlen($blue)<2)
            $blue= '0'.$blue;

        return '#' . $red . $green . $blue;
    }

    function HTMLToRGB($htmlCode)
    {
      if($htmlCode[0] == '#')
        $htmlCode = substr($htmlCode, 1);

      if (strlen($htmlCode) == 3)
      {
        $htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
      }

      $r = hexdec($htmlCode[0] . $htmlCode[1]);
      $g = hexdec($htmlCode[2] . $htmlCode[3]);
      $b = hexdec($htmlCode[4] . $htmlCode[5]);

      return $b + ($g << 0x8) + ($r << 0x10);
    }

    /**
     *  Convertit la couleur RGB en HSL
     */
    function RGBToHSL($RGB)
    {
        $r = 0xFF & ($RGB >> 0x10);
        $g = 0xFF & ($RGB >> 0x8);
        $b = 0xFF & $RGB;

        $r = ((float)$r) / 255.0;
        $g = ((float)$g) / 255.0;
        $b = ((float)$b) / 255.0;

        $maxC = max($r, $g, $b);
        $minC = min($r, $g, $b);

        $l = ($maxC + $minC) / 2.0;

        if($maxC == $minC)
        {
          $s = 0;
          $h = 0;
        }
        else
        {
          if($l < .5)
          {
            $s = ($maxC - $minC) / ($maxC + $minC);
          }
          else
          {
            $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
          }
          if($r == $maxC)
            $h = ($g - $b) / ($maxC - $minC);
          if($g == $maxC)
            $h = 2.0 + ($b - $r) / ($maxC - $minC);
          if($b == $maxC)
            $h = 4.0 + ($r - $g) / ($maxC - $minC);

          $h = $h / 6.0;
        }

        $h = (int)round(255.0 * $h);
        $s = (int)round(255.0 * $s);
        $l = (int)round(255.0 * $l);

        return (object) Array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
    }

    /**
     *  Convertit la couleur Hex en HSL
     */
    function hexToHsl($hex)
    {
        $hex = array($hex[0].$hex[1], $hex[2].$hex[3], $hex[4].$hex[5]);
        $rgb = array_map(function($part) {
            return hexdec($part) / 255;
        }, $hex);

        $max = max($rgb);
        $min = min($rgb);

        $l = ($max + $min) / 2;

        if ($max == $min) {
            $h = $s = 0;
        } else {
            $diff = $max - $min;
            $s = $l > 0.5 ? $diff / (2 - $max - $min) : $diff / ($max + $min);

            switch($max) {
                case $rgb[0]:
                    $h = ($rgb[1] - $rgb[2]) / $diff + ($rgb[1] < $rgb[2] ? 6 : 0);
                    break;
                case $rgb[1]:
                    $h = ($rgb[2] - $rgb[0]) / $diff + 2;
                    break;
                case $rgb[2]:
                    $h = ($rgb[0] - $rgb[1]) / $diff + 4;
                    break;
            }

            $h /= 6;
        }

        return array($h, $s, $l);
    }

    /**
     *  Gestion des dégradations de la couleur en fonction du pourcentage de clarité
     */
    private function colourBrightness($hex, $percent)
    {
        // Work out if hash given
        $hash = '';
        if (stristr($hex, '#')) {
            $hex = str_replace('#', '', $hex);
            $hash = '#';
        }
        /// HEX TO RGB
        $rgb = [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
        //// CALCULATE
        for ($i = 0; $i < 3; $i++) {
            // See if brighter or darker
            if ($percent > 0) {
                // Lighter
                $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1 - $percent));
            } else {
                // Darker
                $positivePercent = $percent - ($percent * 2);
                $rgb[$i] = round($rgb[$i] * (1 - $positivePercent)); // round($rgb[$i] * (1-$positivePercent));
            }
            // In case rounding up causes us to go to 256
            if ($rgb[$i] > 255) {
                $rgb[$i] = 255;
            }
        }
        //// RBG to Hex
        $hex = '';
        for ($i = 0; $i < 3; $i++) {
            // Convert the decimal digit to hex
            $hexDigit = dechex($rgb[$i]);
            // Add a leading zero if necessary
            if (strlen($hexDigit) == 1) {
                $hexDigit = "0" . $hexDigit;
            }
            // Append to the hex string
            $hex .= $hexDigit;
        }
        return $hash . $hex;
    }


    function getColors()
    {
        $colors = [];
        $client = getCurrentTenant();
        try{
            if(!is_null($client))
            {
                $colorsTemp = $client->getSetting('colors');
                if(!is_null($colorsTemp))
                {
                    $colors = explode("þ", $colorsTemp);
                    return json_encode([
                        'bannerColor' => $colors[0],
                        'textColor' => $colors[1],
                        'iconColor' => $colors[2],
                        'colorServex' => $colors[3],
                        'colorServex50' => $colors[4],
                        'colorServex100' => $colors[5],
                        'colorServexlight' => $colors[6],
                        'colorServexlighter' => $colors[7],
                        'colorServexdark' => $colors[8],
                        'colorServexdarker' => $colors[9]
                    ]);
                }else{
                    return json_encode([
                        'bannerColor' => '',
                        'textColor' => '',
                        'iconColor' => '',
                        'colorServex' => '',
                        'colorServex50' => '',
                        'colorServex100' => '',
                        'colorServexlight' => '',
                        'colorServexlighter' => '',
                        'colorServexdark' => '',
                        'colorServexdarker' => ''
                    ]);
                }
                $colors = explode("þ", $colorsTemp);
            }
        }
        catch(\Exception $e)
        {
            dd($e);
        }
    }

}
