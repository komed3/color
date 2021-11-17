<?php
    
    class Color {
        
        public $color;
        
        # --- core functions -------------------------------------------
        
        private function isColor() {
            
            return is_array( $this->color ) && count( $this->color ) == 3;
            
        }
        
        private function rgb2hue(
            $r, $g, $b,
            $delta
        ) {
            
            if( $delta == 0 )
                return 0;
            
            else switch( max( $r, $g, $b ) ) {
                
                case $r:
                    return 60 * fmod( ( $g - $b ) / $delta, 6 ) + ( $b > $g ? 360 : 0 );
                
                case $g: 
                    return 60 * ( ( $b - $r ) / $delta + 2 );
                
                case $b: 
                    return 60 * ( ( $r - $g ) / $delta + 4 );
                
            }
            
        }
        
        private function hue2rgb(
            $h, $c, $m
        ) {
            
            $x = $c * ( 1 - abs( fmod( $h / 60, 2 ) - 1 ) );
            
            if( $h < 60 )
                $rgb = [ $c, $x, 0 ];
            
            else if( $h < 120 )
                $rgb = [ $x, $c, 0 ];
            
            else if( $h < 180 )
                $rgb = [ 0, $c, $x ];
            
            else if( $h < 240 )
                $rgb = [ 0, $x, $c ];
            
            else if( $h < 300 )
                $rgb = [ $x, 0, $c ];
            
            else
                $rgb = [ $c, 0, $x ];
            
            return array_map( function ( $val ) use ( $m ) {
                return ( $val + $m ) * 255;
            }, $rgb );
            
        }
        
        # --- set color functions --------------------------------------
        
        public function setRGB(
            int $r = 0,
            int $g = 0,
            int $b = 0
        ) {
            
            $this->color = array_map( function ( $val ) {
                return max( min( $val, 255 ), 0 );
            }, [ $r, $g, $b ] );
            
            return $this;
            
        }
        
        public function setRYB(
            int $r = 0,
            int $y = 0,
            int $b = 0
        ) {
            
            $w = min( $r, $y, $b );
            
            list( $r, $y, $b ) = array_map( function ( $val ) use ( $w ) {
                return $val - $w;
            }, [ $r, $y, $b ] );
            
            $my = max( $r, $y, $b );
            
            $g = min( $y, $b );
            
            $y -= $g;
            $b -= $g;
            
            if( $b && $g ) {
                
                $b *= 2.0;
                $g *= 2.0;
                
            }
            
            $r += $y;
            $g += $y;
            
            $mg = max( $r, $g, $b );
            $n = $my / $mg;
            
            list( $r, $g, $b ) = array_map( function ( $val ) use ( $w, $mg, $n ) {
                return ( $mg ? $n : 1 ) * $val + $w;
            }, [ $r, $g, $b ] );
            
            $this->color = [ $r, $g, $b ];
            
            return $this;
            
        }
        
        public function setHEX(
            string $color
        ) {
            
            $color = str_replace( '#', '', $color );
            $spl = strlen( $color ) / 3;
            $rep = 3 - $spl;
            
            $this->color = array_map( function ( $val ) use ( $rep ) {
                return hexdec( str_repeat( $val, $rep ) );
            }, str_split( $color, $spl ) );
            
            return $this;
            
        }
        
        function setHSL(
            float $h = 0,
            float $s = 0,
            float $l = 0
        ) {
            
            $c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
            $m = $l - ( $c / 2 );
            
            $this->color = $this->hue2rgb( $h, $c, $m );
            
            return $this;
            
        }
        
        function setHSV(
            float $h = 0,
            float $s = 0,
            float $v = 0
        ) {
            
            $c = $v * $s;
            $m = $v - $c;
            
            $this->color = $this->hue2rgb( $h, $c, $m );
            
            return $this;
            
        }
        
        public function setCMYK(
            float $c = 0,
            float $m = 0,
            float $y = 0,
            float $k = 0
        ) {
            
            $this->color = array_map( function ( $val ) use ( $k ) {
                return 255 * ( 1 - $val ) * ( 1 - $k );
            }, [ $c, $m, $y ] );
            
            return $this;
            
        }
        
        public function setYUV(
            float $y = 0,
            float $u = 0,
            float $v = 0
        ) {
            
            $this->color = [
                $y + ( 1 / 0.877 * $v ),
                $y - ( 0.114 / 0.289391 * $u ) - ( 0.299 / 0.514799 * $v ),
                $y + ( 1 / 0.493 * $u )
            ];
            
            return $this;
            
        }
        
        # --- output functions -----------------------------------------
        
        public function toRGB(
            bool $assoc = true
        ) {
            
            return $this->isColor() ? array_combine(
                $assoc ? [ 'r', 'g', 'b' ] : [ 0, 1, 2 ],
                array_map( 'round', $this->color )
            ) : null;
            
        }
        
        public function toRYB() {
            
            if( !$this->isColor() )
                return null;
            
            $w = min( $this->color );
            
            list( $r, $g, $b ) = array_map( function ( $val ) use ( $w ) {
                return $val - $w;
            }, $this->color );
            
            $mg = max( $r, $g, $b );
            
            $y = min( $r, $g );
            
            $r -= $y;
            $g -= $y;
            
            if( $b && $g ) {
                
                $b /= 2.0;
                $g /= 2.0;
                
            }
            
            $y += $g;
            $b += $g;
            
            $my = max( $r, $y, $b );
            $n = $mg / $my;
            
            list( $r, $y, $b ) = array_map( function ( $val ) use ( $w, $my, $n ) {
                return ( $my ? $n : 1 ) * $val + $w;
            }, [ $r, $y, $b ] );
            
            return [
                'r' => $r,
                'y' => $y,
                'b' => $b
            ];
            
        }
        
        public function toCMYK() {
            
            if( !$this->isColor() )
                return null;
            
            $k = 1 - max( $this->color ) / 255;
            
            list( $c, $m, $y ) = array_map( function ( $val ) use ( $k ) {
                return ( 1 - $k - $val / 255 ) / ( 1 - $k );
            }, $this->color );
            
            return [
                'c' => $c,
                'm' => $m,
                'y' => $y,
                'k' => $k
            ];
            
        }
        
        public function toHEX(
            bool $hash = true
        ) {
            
            return $this->isColor() ? ( $hash ? '#' : '' ) . implode( '',
                array_map( function ( $val ) {
                    return str_pad( dechex( round( $val ) ), 2, '0', STR_PAD_LEFT );
                }, $this->color )
            ) : null;
            
        }
        
        public function toHSL() {
            
            if( !$this->isColor() )
                return null;
            
            list( $r, $g, $b ) = array_map( function ( $val ) {
                return $val / 255;
            }, $this->color );
            
            $max = max( $r, $g, $b );
            $min = min( $r, $g, $b );
            
            $delta = $max - $min;
            
            $lightness = ( $max + $min ) / 2;
            
            return [
                'h' => $this->rgb2hue( $r, $g, $b, $delta ),
                's' => $delta == 0 ? 0 : $delta / ( 1 - abs( 2 * $lightness - 1 ) ),
                'l' => $lightness
            ];
            
        }
        
        public function toHSV() {
            
            if( !$this->isColor() )
                return null;
            
            list( $r, $g, $b ) = array_map( function ( $val ) {
                return $val / 255;
            }, $this->color );
            
            $max = max( $r, $g, $b );
            $min = min( $r, $g, $b );
            
            $delta = $max - $min;
            
            return [
                'h' => $this->rgb2hue( $r, $g, $b, $delta ),
                's' => $max == 0 ? 0 : $delta / $max,
                'v' => $max
            ];
            
        }
        
        public function toXYZ() {
            
            if( !$this->isColor() )
                return null;
            
            list( $r, $g, $b ) = array_map( function ( $val ) {
                return ( ( $val /= 255 ) <= 0.04045
                    ? $val / 12.92
                    : pow( ( $val + 0.055 ) / 1.055, 2.4 )
                ) * 100;
            }, $this->color );
            
            return [
                'x' => $r * 0.412453 + $g * 0.357580 + $b * 0.180423,
                'y' => $r * 0.212671 + $g * 0.715160 + $b * 0.072169,
                'z' => $r * 0.019334 + $g * 0.119193 + $b * 0.950227
            ];
            
        }
        
        public function toYxy() {
            
            if( !$this->isColor() )
                return null;
            
            list( $x, $y, $z ) = array_values( $this->toXYZ() );
            
            $cero = max( $x, $y, $z ) == 0;
            
            return [
                'Y' => $y,
                'x' => $cero ? 0 : $x / ( $x + $y + $z ),
                'y' => $cero ? 0 : $y / ( $x + $y + $z )
            ];
            
        }
        
        public function toLAB() {
            
            if( !$this->isColor() )
                return null;
            
            $xyz = $this->toXYZ();
            
            $x = $xyz['x'] / 95.047;
            $y = $xyz['y'] / 100;
            $z = $xyz['z'] / 108.883;
            
            list( $x, $y, $z ) = array_map( function ( $val ) {
                return $val > 0.008856
                    ? pow( $val, 1 / 3 )
                    : $val * 7.787 + 16 / 116;
            }, [ $x, $y, $z ] );
            
            return [
                'l' => $y * 116 - 16,
                'a' => ( $x - $y ) * 500,
                'b' => ( $y - $z ) * 200

            ];
            
        }
        
        public function toYUV() {
            
            if( !$this->isColor() )
                return null;
            
            list( $r, $g, $b ) = $this->color;
            
            $y = 0.299 * $r + 0.587 * $g + 0.114 * $b;
            
            return [
                'y' => $y,
                'u' => 0.493 * ( $b - $y ),
                'v' => 0.877 * ( $r - $y )
            ];
            
        }
        
        public function toYIQ() {
            
            if( !$this->isColor() )
                return null;
            
            list( $r, $g, $b ) = $this->color;
            
            return [
                'y' => 0.299900 * $r + 0.587000 * $g + 0.114000 * $b,
                'i' => 0.595716 * $r - 0.274453 * $g - 0.321264 * $b,
                'q' => 0.211456 * $r - 0.522591 * $g + 0.311350 * $b
            ];
            
        }
        
        public function toYCbCr() {
            
            if( !$this->isColor() )
                return null;
            
            list( $r, $g, $b ) = $this->color;
            
            return [
                'y'  => 16 + ( 65.738 * $r / 256 ) + ( 129.057 * $g / 256 ) + ( 25.064 * $b / 256 ),
                'Cb' => 128 - ( 37.945 * $r / 256 ) - ( 74.494 * $g / 256 ) + ( 112.439 * $b / 256 ),
                'Cr' => 128 + ( 112.439 * $r / 256 ) - ( 94.154 * $g / 256 ) - ( 18.285 * $b / 256 )
            ];
            
        }
        
        public function toYPbPr() {
            
            if( !$this->isColor() )
                return null;
            
            list( $r, $g, $b ) = $this->gamma();
            
            $y = 0.299 * $r + 0.587 * $g + 0.114 * $b;
            
            return [
                'y'  => $y,
                'Pb' => ( $b - $y ) / 1.772,
                'Pr' => ( $r - $y ) / 1.402
            ];
            
        }
        
        # --- calculations ---------------------------------------------
        
        public function gamma() {
            
            return $this->isColor() ? array_map( function ( $val ) {
                return 255 * pow( $val / 256, 0.45 );
            }, $this->color ) : null;
            
        }
        
        public function deltaE(
            Color $compare
        ) {
            
            if( !$this->isColor() || !$compare->isColor() )
                return null;
            
            list( $l1, $a1, $b1 ) = array_values( $this->toLAB() );
            list( $l2, $a2, $b2 ) = array_values( $compare->toLAB() );
            
            return sqrt(
                pow( $l1 - $l2, 2 ) +
                pow( $a1 - $a2, 2 ) +
                pow( $b1 - $b2, 2 )
            );
            
        }
        
        public function diff(
            Color $compare
        ) {
            
            if( !$this->isColor() || !$compare->isColor() )
                return null;
            
            return sqrt(
                pow( $compare->color[0] - $this->color[0], 2 ) +
                pow( $compare->color[1] - $this->color[1], 2 ) +
                pow( $compare->color[2] - $this->color[2], 2 )
            );
            
        }
        
        public function match(
            Color $compare
        ) {
            
            if( !$this->isColor() || !$compare->isColor() )
                return null;
            
            return 100 - $this->deltaE( $compare ) / sqrt( 195075 );
            
        }
        
        public function invert() {
            
            return $this->complementary();
            
        }
        
        public function complementary() {
            
            if( !$this->isColor() )
                return null;
            
            $complementary = new Color();
            
            $complementary->setRGB(
                255 - $this->color[0],
                255 - $this->color[1],
                255 - $this->color[2]
            );
            
            return $complementary;
            
        }
        
        public function triplet() {
            
            if( !$this->isColor() )
                return null;
            
            $c1 = new Color();
            $c1->setRGB(
                $this->color[1],
                $this->color[2],
                $this->color[0]
            );
            
            $c2 = new Color();
            $c2->setRGB(
                $this->color[2],
                $this->color[0],
                $this->color[1]
            );
            
            return [ $this, $c1, $c2 ];
            
        }
        
        public function contrast() {
            
            if( !$this->isColor() )
                return null;
            
            return (
                $this->color[0] * 299 +
                $this->color[1] * 587 +
                $this->color[2] * 114
            ) / 1000 >= 128 ? 0 : 1;
            
        }
        
    }
    
?>
