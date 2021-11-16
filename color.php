<?php
    
    class Color {
        
        private $color;
        
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
                return round( ( $val + $m ) * 255 );
            }, $rgb );
            
        }
        
        public function setRGB(
            int $r = 0,
            int $g = 0,
            int $b = 0
        ) {
            
            $this->color = array_map( function ( $val ) {
                return max( min( $val, 255 ), 0 );
            }, [ $r, $g, $b ] );
            
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
            
        }
        
        function setHSL(
            float $h = 0,
            float $s = 0,
            float $l = 0
        ) {
            
            $c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
            $m = $l - ( $c / 2 );
            
            $this->color = $this->hue2rgb( $h, $c, $m );
            
        }
        
        function setHSV(
            float $h = 0,
            float $s = 0,
            float $v = 0
        ) {
            
            $c = $v * $s;
            $m = $v - $c;
            
            $this->color = $this->hue2rgb( $h, $c, $m );
            
        }
        
        public function setCMYK(
            float $c = 0,
            float $m = 0,
            float $y = 0,
            float $k = 0
        ) {
            
            $this->color = array_map( function ( $val ) use ( $k ) {
                return round( 255 * ( 1 - $val ) * ( 1 - $k ) );
            }, [ $c, $m, $y ] );
            
        }
        
        public function toRGB(
            bool $assoc = true
        ) {
            
            return $this->isColor() ? (
                $assoc ? [
                    'r' => $this->color[0],
                    'g' => $this->color[1],
                    'b' => $this->color[2]
                ] : $this->color
            ) : null;
            
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
                    return str_pad( dechex( $val ), 2, '0', STR_PAD_LEFT );
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
        
        public function getColor() {
            
            return $this->color;
            
        }
        
    }
    
?>
