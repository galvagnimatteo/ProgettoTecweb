<?php
require_once "mappaPosti.php";
function generateSVG($sala, $idproiez, $orario)
{


    $statoPosti = mappaPosti($sala, $idproiez, $orario);

    $SVG = "";

        $SVG .=
            '<svg class="mappaposti" version="1.1" width="95%" xmlns="http://www.w3.org/2000/svg" aria-label="Mappa della sala per la scelta dei posti">' .
            '<rect class="sfondosvg" width="100%" height="100%" />' .
            '<g id="scene">' .
            '<g class="center">';

        $r = 20;
        $cx = 30;
        $cy = 30;

        $tx = 23;
        $ty = 38;

        $lx1 = 10;
        $lx2 = 50;
        $ly1 = 10;
        $ly2 = 50;

        $lastRow = "a";
        $SVG .=
            '<text class="letteraFila" x="' .
            $tx -
            40 .
            '" y="' .
            $ty .
            '">A</text>';

        foreach ($statoPosti as $codice => $stato) {
            $fila = substr($codice, 0, 1);
            $count = intval(substr($codice, 1));

            if ($lastRow != strtolower($fila)) {
                $SVG .=
                    '<text class="letteraFila" x="' .
                    $tx -
                    8 .
                    '" y="' .
                    $ty .
                    '">' .
                    strtoupper($lastRow) .
                    "</text>";
                $cy += 60;
                $ty += 60;
                $ly1 += 60;
                $ly2 += 60;
                $cx = 30;
                $tx = 23;
                $lx1 = 10;
                $lx2 = 50;
                $SVG .=
                    '<text class="letteraFila" x="' .
                    $tx -
                    40 .
                    '" y="' .
                    $ty .
                    '">' .
                    strtoupper($fila) .
                    "</text>";
            }

            $lastRow = strtolower($fila);
            $mostraLinea = "";
            if (!$stato) {
                $mostraLinea = " nascondilinea";
            }
			
			$statoClass = $stato ? "occupato" : "libero";
			
            $SVG .=
                '<g data-codice="' .
                strtolower($fila) .
                $count .
                '" class="seat ' .
                $statoClass .
                '">' .
                '<circle cx="' .
                $cx .
                '" cy="' .
                $cy .
                '" r="' .
                $r .
                '" />' .
                '<text class="codicePosto" x="' .
                $tx .
                '" y="' .
                $ty .
                '">' .
                $count .
                "</text>" .
                '<line class="linex' .
                $mostraLinea .
                '" x1="' .
                $lx1 .
                '" y1="' .
                $ly1 .
                '" x2="' .
                $lx2 .
                '" y2="' .
                $ly2 .
                '" />' .
                "</g>";

            $lx1 += 50;
            $lx2 += 50;

            if ($count != 9) {
                $cx += 50;
                $tx += 50;
            } else {
                $cx += 50;
                $tx += 44;
            }
        }

        $SVG .=
            '<text class="letteraFila" x="' .
            $tx -
            8 .
            '" y="' .
            $ty .
            '">' .
            strtoupper($lastRow) .
            "</text>";
        $SVG .= "</g></g></svg>";
        unset($statoPosti);

    return $SVG;
}

?>
